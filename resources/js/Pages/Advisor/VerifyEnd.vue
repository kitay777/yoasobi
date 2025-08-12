<template>
  <AppLayout title="本人確認">
    <div class="min-h-screen flex flex-col items-center justify-center bg-white">
      <div class="mt-12 flex flex-col items-center">
        <div class="mb-6">
          <span class="bg-yellow-400 text-black text-lg px-4 py-1 rounded font-bold">本人確認</span>
        </div>
        <div class="text-2xl font-bold mb-6">ただいま審査中です</div>
        <div class="text-center text-base text-gray-700 mb-10">
          順次対応致します。<br>
          確認後、YOASOBIの「お知らせ」にて<br>
          お知らせいたします。
        </div>
        <button
          @click="goMypage"
          class="w-72 h-14 rounded-full bg-gradient-to-r from-pink-400 to-pink-300 text-white text-xl font-bold shadow-md"
        >
          マイページへ
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'

function goMypage() {
  router.visit('/advisor/mypage') // ←マイページルートに合わせて変更
}
</script>
