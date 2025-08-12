<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'

const goMypage = () => {
  router.visit('/advisor/mypage') // 変更後の遷移先に合わせて
}
</script>

<template>
  <AppLayout :title="'口座情報設定 完了'">
    <div class="min-h-screen flex flex-col items-center justify-start bg-white px-4 pt-10">
      <div class="w-full max-w-md">
        <div class="mb-6 flex items-center">
          <span class="bg-yellow-400 text-black text-lg px-4 py-1 rounded font-bold">口座情報設定</span>
        </div>
        <div class="text-2xl font-bold mb-6 text-center">口座情報の変更が<br>完了しました</div>
        <div class="text-center text-base text-gray-700 mb-10">
          内容に誤りがある場合は<br>
          マイページから再度修正してください。
        </div>
        <button
          @click="goMypage"
          class="w-full h-14 rounded-full bg-gradient-to-r from-pink-400 to-pink-300 text-white text-xl font-bold shadow-md"
        >
          マイページへ戻る
        </button>
      </div>
    </div>
  </AppLayout>
</template>
