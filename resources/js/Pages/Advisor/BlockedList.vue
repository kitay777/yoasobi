<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
const props = defineProps({ blocked: Array })
const selected = ref(null)
const showModal = ref(false)

const unblock = () => {
  if (!selected.value) return
  router.delete(`/blocked-users/${selected.value}`, {
    onSuccess: () => {
      showModal.value = false
      selected.value = null
    }
  })
}
</script>

<template>
<app-layout :title="'ブロック一覧'">
  <div class="min-h-screen bg-white flex flex-col">
    <!-- ヘッダー -->
    <div class="flex items-center relative h-12 border-b mb-4">
      <a href="/advisor/menu" class="absolute left-0 px-4 py-2 focus:outline-none">
        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </a>

      <div class="flex-1 text-center text-lg font-bold text-gray-800">ブロック一覧</div>
    </div>

    <!-- ブロックユーザー一覧 -->
    <div>
      <div v-for="item in blocked" :key="item.id"
        class="flex items-center py-4 px-6 border-b cursor-pointer"
        @click="selected = (selected === item.id ? null : item.id)">
        <img :src="item.blocked_user.profile_photo_url" class="w-10 h-10 rounded-full mr-3"/>
        <div class="flex-1 text-base">{{ item.blocked_user.name }}</div>
        <span>
          <svg v-if="selected === item.id" class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" stroke-linecap="round" d="M5 13l4 4L19 7"/>
          </svg>
          <span v-else class="inline-block w-5 h-5 rounded-full border border-gray-300"></span>
        </span>
      </div>
    </div>

    <!-- フッターボタン -->
    <div class="fixed bottom-0 left-0 w-full px-4 pb-4 bg-white">
      <button
        :disabled="!selected"
        class="w-full py-3 rounded-full text-lg font-bold shadow
          bg-gradient-to-r from-pink-400 to-pink-300 text-white
          disabled:bg-gray-200 disabled:text-gray-400"
        @click="showModal = true"
      >ブロックを解除する</button>
    </div>

    <!-- モーダル -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white w-80 rounded-xl p-8 flex flex-col items-center">
        <div class="mb-6 text-lg font-bold text-center">ブロック解除しますか？</div>
        <div class="flex gap-4 w-full">
          <button class="flex-1 py-2 bg-black text-white rounded-full font-bold" @click="unblock">OK</button>
          <button class="flex-1 py-2 bg-gray-200 text-gray-500 rounded-full font-bold" @click="showModal = false">キャンセル</button>
        </div>
      </div>
    </div>
  </div>
</app-layout> 
</template>
