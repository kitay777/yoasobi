<script setup>
import AppLayout from '@/Layouts/AppLayout.vue' // This is the layout component for the page
import { usePage } from '@inertiajs/vue3' // Import Inertia.js for
</script setup>

<template>
  <AppLayout title="クレジット">
    <div class="min-h-screen flex flex-col justify-between items-center px-6 pt-10 pb-[100px]">
      <!-- タイトル -->
      <div class="w-full max-w-md">
        <h1 class="text-xl font-bold text-gray-800 mb-2">運営問い合わせ</h1>
        <p class="text-gray-600 text-sm">運営が決定すると作成されます</p>

      </div>
    </div>
  </AppLayout>
</template>
