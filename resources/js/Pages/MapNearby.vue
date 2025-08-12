<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { loadGoogleMaps } from '@/composables/useGoogleMaps'

const mapEl = ref(null)
let map
let infoWindow
let selfMarker = null
const markersById = new Map() // userId -> AdvancedMarkerElement

// --- 画像URLの組み立て（空ならダミー） ---
const avatarUrl = (u) => {
  if (u.avatar_url) return u.avatar_url       // ← サーバが作ったURL
  if (u.profile_photo_path) return `/storage/${u.profile_photo_path}` // 保険
  return '/images/avatar-default.png'
}

// --- 安定ジッター（重なり回避・IDごとに同じズレ） ---
const hash01 = (str) => {
  let h = 2166136261, s = String(str)
  for (let i = 0; i < s.length; i++) { h ^= s.charCodeAt(i); h = Math.imul(h, 16777619) }
  return ((h >>> 0) % 10000) / 10000
}
const jitterPos = (lat, lng, key) => {
  const d = 0.000135 // ≒15m
  const dLng = d * Math.cos((lat * Math.PI) / 180)
  return {
    lat: lat + (hash01(`${key}-lat`) - 0.5) * d,
    lng: lng + (hash01(`${key}-lng`) - 0.5) * dLng,
  }
}

// --- 顔アイコンのDOMを作る ---
const makeAvatarEl = (imgSrc, { size = 44, ring = '#7c3aed' } = {}) => {
  const wrap = document.createElement('div')
  wrap.className = 'gmk-wrap'
  wrap.style.width = `${size}px`
  wrap.style.height = `${size}px`
  wrap.style.border = `3px solid ${ring}`
  wrap.style.borderRadius = '9999px'
  wrap.style.boxShadow = '0 2px 6px rgba(0,0,0,.25)'
  wrap.style.background = '#fff'
  wrap.style.overflow = 'hidden'
  wrap.style.transform = 'translate(-50%, -50%)' // 中心合わせ

  const img = document.createElement('img')
  img.src = imgSrc
  img.alt = 'avatar'
  img.style.width = '100%'
  img.style.height = '100%'
  img.style.objectFit = 'cover'
  wrap.appendChild(img)

  return wrap
}

// --- InfoWindowの中身をDOMで作る（XSS防止のためinnerHTML使わない） ---
const makeInfoContent = (u) => {
  const box = document.createElement('div')
  box.style.display = 'grid'
  box.style.gap = '6px'
  box.style.minWidth = '180px'

  const h = document.createElement('div')
  h.textContent = u.name ?? 'ユーザー'
  h.style.fontWeight = '600'
  h.style.fontSize = '14px'
  box.appendChild(h)

  if (Number.isFinite(u.distance_m)) {
    const d = document.createElement('div')
    d.textContent = `約 ${Math.round(u.distance_m)} m`
    d.style.fontSize = '12px'
    d.style.color = '#666'
    box.appendChild(d)
  }

  const a = document.createElement('a')
  a.textContent = 'プロフィールを見る'
  a.href = `/advisor/${u.id}` // ルートに合わせて変更
  a.style.display = 'inline-block'
  a.style.padding = '6px 10px'
  a.style.borderRadius = '8px'
  a.style.background = '#7c3aed'
  a.style.color = '#fff'
  a.style.textDecoration = 'none'
  a.style.fontSize = '12px'
  a.addEventListener('click', (e) => {
    // Inertia側に任せるならここで何もしなくてOK
  })
  box.appendChild(a)

  return box
}

// --- 自分のピン（紫リング） ---
const upsertSelfMarker = (pos) => {
  const content = makeAvatarEl('/images/me-pin.png', { size: 24, ring: '#7c3aed' })
  if (!selfMarker) {
    selfMarker = new google.maps.marker.AdvancedMarkerElement({
      map, position: pos, title: 'あなた', content, zIndex: 9999,
    })
  } else {
    selfMarker.position = pos
  }
}

// --- 他ユーザーのピン（顔アイコン＋クリックで詳細） ---
const upsertUserMarker = (u) => {
  const id = u.id ?? u.email ?? u.name ?? ''
  const ulat = Number(u.lng), ulng = Number(u.lat)
  if (!Number.isFinite(ulat) || !Number.isFinite(ulng) || !id) return

  const p = jitterPos(ulat, ulng, id)
  const content = makeAvatarEl(avatarUrl(u), { size: 44, ring: '#ff0000' })
  const title = `${u.name ?? 'ユーザー'}（約${Math.round(u.distance_m)}m）`

  let m = markersById.get(id)
  if (!m) {
    m = new google.maps.marker.AdvancedMarkerElement({
      map, position: p, title, content
    })
    m.addListener('click', () => {
      infoWindow.setContent(makeInfoContent(u))
      infoWindow.open({ anchor: m, map })
    })
    markersById.set(id, m)
  } else {
    m.position = p
    m.title = title
    // contentは使い回し（画像が変わるケースが多いなら差し替え処理を）
  }
}

// --- いなくなったユーザーを掃除 ---
const gcMarkers = (aliveIds) => {
  for (const [id, m] of markersById.entries()) {
    if (!aliveIds.has(id)) {
      m.map = null
      markersById.delete(id)
    }
  }
}

let fetchSeq = 0
const fetchNearby = async (lat, lng) => {
  const mySeq = ++fetchSeq
  try {
    const { data } = await axios.get('/api/nearby', {
      params: { lat, lng, radius_km: 20, limit: 50 },
      withCredentials: true, timeout: 15000
    })
    console.log('nearby data:', data)
    if (mySeq !== fetchSeq) return

    upsertSelfMarker({ lat, lng })
    const alive = new Set()
    data.users.forEach(u => {
      alive.add(u.id ?? u.email ?? u.name ?? '')
      upsertUserMarker(u)
    })
    gcMarkers(alive)
  } catch (e) {
    console.error('nearby error', e?.response?.status, e?.response?.data || e.message)
  }
}

onMounted(async () => {
  const gmaps = await loadGoogleMaps() // libraries=marker 必須
  await axios.get('/sanctum/csrf-cookie')

  infoWindow = new google.maps.InfoWindow()

  navigator.geolocation.getCurrentPosition(async (p) => {
    const lat = p.coords.latitude
    const lng = p.coords.longitude

    map = new gmaps.Map(mapEl.value, {
      center: { lat, lng }, zoom: 15, mapId: 'YOASOBI_MAP', disableDefaultUI: false,
    })
console.log('map:', map)
    await fetchNearby(lat, lng)
    /*
    setInterval(() => {
      const c = map.getCenter()
      fetchNearby(c.lat(), c.lng())
    }, 15000)

    let timer
    map.addListener('idle', () => {
      clearTimeout(timer)
      timer = setTimeout(() => {
        const c = map.getCenter()
        fetchNearby(c.lat(), c.lng())
      }, 400)
    })
      */
  }, (err) => {
    console.warn('geolocation error', err)
    alert('現在地が取得できませんでした')
  }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 3000 })
})
</script>

<template>
  <div class="w-full h-[70vh] rounded-xl overflow-hidden border" ref="mapEl"></div>
</template>

<style scoped>
/* 追加で微調整したいとき用のクラス（今は JS 側でstyle付与してるので任意） */
</style>
