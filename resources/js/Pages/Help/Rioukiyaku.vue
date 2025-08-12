<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
//import { useRouter } from 'vue-router'
const goBack = () => {
  window.history.length > 1 ? window.history.back() : location.href = '/';
};
</script>

<template>
  <AppLayout title="利用規約">
    <div class="relative max-w-2xl mx-auto min-h-[80vh] px-4 py-8 bg-white">
      <!-- ヘッダー（中央タイトル＋戻る） -->
      <div class="flex items-center justify-center relative mb-8">
        <button @click="goBack" class="absolute left-0 p-2">
          <svg class="w-7 h-7 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
        <h1 class="text-2xl font-bold text-gray-700 text-center w-full">利用規約</h1>
      </div>

      <!-- 規約本文（ここに規約をMarkdownなどで貼り付けてもOK） -->
      <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-line">
        <!-- 例文：ここに規約本文をコピペ -->
        第1条（適用）
        この利用規約（以下、「本規約」といいます。）は、YOASOBI（以下、「当サービス」といいます。）が提供するサービスの利用条件を定めるものです。

        第2条（利用登録）
        ・利用者は本規約に同意の上、当サービスの定める方法によって利用登録を申請し、これを承認することにより、利用登録が完了します。
        ・・・
      </div>
    </div>
  </AppLayout>
</template>
