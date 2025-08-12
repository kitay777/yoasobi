<script setup>
import AppLayout from '@/Layouts/AppLayout.vue' // This is the layout component for the page
import { usePage } from '@inertiajs/vue3' // Import Inertia.js for
</script setup>

<template>
  <AppLayout title="クレジット">
    <div class="min-h-screen flex flex-col justify-between items-center px-6 pt-10 pb-[100px]">
      <!-- タイトル -->
      <div class="w-full max-w-md">
        <h1 class="text-xl font-bold text-gray-800 mb-2">クレジットカード</h1>
        <p class="text-gray-600 text-sm">クレジットカードの登録は、決済会社が決定すると表示されます。</p>

      </div>
    </div>
  </AppLayout>
</template>
