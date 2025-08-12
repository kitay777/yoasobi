<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { useLocationSync } from '@/composables/useLocationSync' // ← JSでもOK
import MapNearby from '@/Pages/MapNearby.vue'
import { ref } from 'vue'
import axios from 'axios'

const seeding = ref(false)
const seedLog = ref('')

const runSeeding = async () => {
  if (!confirm('ID 2〜47 の位置と役割をランダム更新します。続行しますか？')) return
  seeding.value = true
  seedLog.value = ''
  try {
    // 既定 20km。変えたいときは radius_km: 5 など渡す
    const { data } = await axios.post('/dev/seed-nearby', { radius_km: 3 }, {
      withCredentials: true,
      timeout: 15000,
    })
    seedLog.value = JSON.stringify(data, null, 2)
    alert(`更新件数: ${data.updated}`)
  } catch (e) {
    alert('失敗: ' + (e?.response?.data?.error || e.message))
  } finally {
    seeding.value = false
  }
}
// 現在地の自動送信（10秒 or 20mでサーバに送る）
const { share } = useLocationSync({
  enabled: true,
  minMeters: 20,
  minIntervalMs: 10000,
})

defineProps({
  profiles: Array,
})
</script>

<template>
  <AppLayout title="Dashboard">
      <div class="mb-3 flex items-center gap-2">
        <button
          class="px-3 py-2 rounded bg-indigo-600 text-white disabled:opacity-50"
          :disabled="seeding"
          @click="runSeeding"
        >
          ダミー配置（ID 2〜47・20km）
        </button>
        <small class="text-gray-500">* 開発用。local環境でのみ動作</small>
      </div>

      <pre v-if="seedLog" class="text-xs bg-gray-100 p-2 rounded max-h-40 overflow-auto">{{ seedLog }}</pre>

    <div class="p-4">
      <label class="inline-flex items-center gap-2 mb-4">
        <input type="checkbox" v-model="share" />
        <span>位置情報を共有する</span>
      </label>
      <MapNearby />

      <!-- カード一覧 -->
      <div class="w-full max-w-md mx-auto space-y-16 pb-36">
        <div
          v-for="profile in profiles"
          :key="profile.id"
          class="relative rounded-[60px] border-2 border-white shadow-md w-full overflow-visible h-[72vh]"
        >
          <!-- 背景画像 -->
          <img
            :src="`/storage/${profile.cover_path}`"
            class="absolute inset-0 w-full h-full object-cover rounded-[60px]"
            alt="cover"
          />

          <!-- グラデーション背景 -->
          <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#e4b6f2cc] rounded-[60px]" />

          <!-- +ボタン → プロフィールページ -->
          <div class="absolute left-1/2 -bottom-6 transform -translate-x-1/2 z-20">
            <Link
              :href="`/advisor/${profile.user_id}`"
              class="w-12 h-12 flex items-center justify-center text-2xl font-bold rounded-full shadow-md"
              :class="profile.is_liked ? 'bg-white text-black' : 'bg-[#333] text-white'"
            >
              {{ profile.is_liked ? '−' : '+' }}
            </Link>
          </div>

          <!-- コンテンツ -->
          <div class="relative z-10 flex flex-col items-start justify-end text-white h-full px-4 pb-20 text-left">
            <h2 class="text-xl font-bold mb-1 pl-[15px]">
              {{ profile.name }}
              <span class="text-base font-normal">（{{ profile.age }}歳）</span>
            </h2>
            <div class="flex flex-wrap gap-2 mt-1 mb-2 pl-[15px]">
              <span
                v-for="tag in profile.appeal_tags"
                :key="tag.id"
                class="text-xs px-3 py-1 bg-white/70 text-gray-800 rounded-full"
              >
                {{ tag.name }}
              </span>
            </div>

            <p class="text-xs text-white/90 px-[15px] whitespace-pre-line line-clamp-3 break-words max-w-full">
              {{ profile.bio }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
