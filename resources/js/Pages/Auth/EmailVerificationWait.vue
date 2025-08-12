<script setup>
import { ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const email = usePage().props.auth.user.email
const resent = ref(false)

const resend = () => {
  router.post('/email/verification-notification', {}, {
    onSuccess: () => (resent.value = true),
  })
}
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center px-6">
    <h1 class="text-lg font-bold mb-4">登録まであともう少しです！</h1>
    <p class="mb-2">以下のアドレスに確認メールを送信しました。</p>
    <p class="mb-4 font-bold text-gray-700">📧 {{ email }}</p>
    <div class="bg-pink-100 text-pink-700 px-4 py-2 rounded mb-4">
      メールに記載のURLにアクセスし、登録を完了してください。
    </div>
    <button @click="resend" class="bg-gradient-to-r from-pink-400 to-pink-300 text-white py-2 px-6 rounded-full">
      再送信
    </button>
    <p v-if="resent" class="text-sm text-green-600 mt-3">メールを再送信しました！</p>
  </div>
</template>
