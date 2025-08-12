<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
const goBack = () => {
  window.history.length > 1 ? window.history.back() : location.href = '/';
};
</script>

<template>
  <AppLayout title="Help">
    <div class="relative flex items-center justify-center h-14 border-b bg-white">
      <!-- 戻るボタン -->
      <button @click="goBack" class="absolute left-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <span class="text-base font-bold text-gray-800">ヘルプ</span>
      <!-- 設定アイコン右上などはAppLayoutで共通実装されている前提 -->
    </div>

    <!-- ヘルプバッジ -->
    <div class="bg-gray-100 p-2">
      <span class="text-black px-2 py-1 rounded shadow">ヘルプ</span>
    </div>

    <!-- リスト部分 -->
    <div class="divide-y">
      <button class="w-full flex items-center justify-between px-4 py-4 bg-white hover:bg-gray-50"
              @click="$inertia.visit('/help/taikai')">
        <span>退会</span>
        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
      <div class="w-full flex items-center px-4 py-4 bg-gray-50 text-gray-400">
        <span>運営情報</span>
      </div>
      <button class="w-full flex items-center justify-between px-4 py-4 bg-white hover:bg-gray-50"
              @click="$inertia.visit('/riyoukiyaku')">
        <span>利用規約</span>
        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
      <button class="w-full flex items-center justify-between px-4 py-4 bg-white hover:bg-gray-50"
              @click="$inertia.visit('/privacy')">
        <span>プライバシーポリシー</span>
        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
      <button class="w-full flex items-center justify-between px-4 py-4 bg-white hover:bg-gray-50"
              @click="$inertia.visit('/tokutei')">
        <span>特定商取引法</span>
        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
      <button class="w-full flex items-center justify-between px-4 py-4 bg-white hover:bg-gray-50"
              @click="$inertia.visit('/tokuyaku')">
        <span>特約</span>
        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </AppLayout>
</template>
