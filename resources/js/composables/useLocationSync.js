import { onMounted, onBeforeUnmount, ref } from 'vue'
import axios from 'axios'

export function useLocationSync(opts = {}) {
  const enabled = ref(opts.enabled ?? true)
  const minMeters = opts.minMeters ?? 20
  const minIntervalMs = opts.minIntervalMs ?? 10000
  const share = ref(opts.share ?? true)

  let watchId = null
  let lastSentAt = 0
  let lastPos = null

  const distM = (a, b) => {
    const toRad = (d) => (d * Math.PI) / 180
    const R = 6371000
    const dLat = toRad(b.lat - a.lat)
    const dLng = toRad(b.lng - a.lng)
    const la1 = toRad(a.lat)
    const la2 = toRad(b.lat)
    const h =
      Math.sin(dLat / 2) ** 2 +
      Math.cos(la1) * Math.cos(la2) * Math.sin(dLng / 2) ** 2
    return 2 * R * Math.asin(Math.sqrt(h))
  }

const send = async (lat, lng) => {
  try {
    console.log('[geo] POST /api/me/location', { lat, lng })
    const res = await axios.post(
      '/api/me/location',
      { lat, lng, share_location: share.value },
      { withCredentials: true }
    )
    console.log('[geo] ok', res.status, res.data)
  } catch (e) {
    if (e.response) {
      console.error('[geo] fail', e.response.status, e.response.data)
    } else {
      console.error('[geo] fail', e.message)
    }
  }
}

  const maybeSend = (lat, lng) => {
    const now = Date.now()
    const moved = lastPos ? distM(lastPos, { lat, lng }) : Infinity
    if (now - lastSentAt > minIntervalMs || moved > minMeters) {
      lastSentAt = now
      lastPos = { lat, lng }
      send(lat, lng)
    }
  }

  const start = () => {
    if (!enabled.value || !('geolocation' in navigator)) return
    navigator.geolocation.getCurrentPosition(
      (p) => maybeSend(p.coords.latitude, p.coords.longitude),
      (err) => console.warn('geo current error', err),
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    )
    watchId = navigator.geolocation.watchPosition(
      (p) => maybeSend(p.coords.latitude, p.coords.longitude),
      (err) => console.warn('geo watch error', err),
      { enableHighAccuracy: true, timeout: 20000, maximumAge: 5000 }
    )
  }

  const stop = () => {
    if (watchId !== null) {
      navigator.geolocation.clearWatch(watchId)
      watchId = null
    }
  }

  onMounted(start)
  onBeforeUnmount(stop)

  return { enabled, share, start, stop }
}
