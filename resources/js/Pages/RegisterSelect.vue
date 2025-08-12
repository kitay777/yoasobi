<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'

function getRoleParam() {
  const searchParams = new URLSearchParams(usePage().url.split('?')[1] || '')
  return searchParams.get('role') || 'cast'
}
</script>

<template>
  <Head title="新規登録" />

  <div class="min-h-screen bg-white px-6 pt-6">
    <!-- ヘッダー -->
    <div class="flex items-center mb-10">
      <button onclick="history.back()" class="text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <h1 class="flex-1 text-center text-lg font-bold text-gray-800">新規登録</h1>
      <div class="w-6"></div> <!-- 空の右スペース（中央揃え用） -->
    </div>

    <div class="flex flex-col items-center space-y-4">
      <!-- メールアドレス登録 -->
      <Link
        :href="`/register/email?role=${getRoleParam()}`"
        class="w-full max-w-sm flex items-center gap-3 px-4 py-3 rounded-full border border-gray-300 text-gray-800 font-medium shadow-sm"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 12H8m0 0l4-4m-4 4l4 4" />
        </svg>
        メールアドレスで新規登録
      </Link>

      <!-- LINE登録 -->
      <a
        href="/login/line"
        class="w-full max-w-sm flex items-center gap-3 px-4 py-3 rounded-full bg-[#06C755] text-white font-medium shadow-md"
      >
        <img src="/assets/icons/line-icon.png" alt="LINE" class="w-5 h-5" />
        LINEで新規登録
    </a>
    </div>
  </div>
</template>
