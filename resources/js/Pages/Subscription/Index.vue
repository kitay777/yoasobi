<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
const props = defineProps({ subscriptions: Array })
const goBack = () => {
  window.history.length > 1 ? window.history.back() : location.href = '/';
};
</script>

<template>
  <AppLayout title="サブスク登録">
    <div class="bg-white max-w-lg mx-auto p-4">
      <div class="flex items-center mb-4">
        <button @click="goBack()" class="mr-2">
          <svg class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
        <span class="px-3 py-1 rounded shadow font-bold text-lg mr-2">サブスク</span>
        <span class="text-lg font-bold">登録</span>
      </div>
      <div v-if="subscriptions.length" class="divide-y border rounded bg-white">
        <template v-for="s in subscriptions" :key="s.id">
          <Link :href="route('subscriptions.show', s.id)" class="flex items-center w-full p-4 hover:bg-gray-50">
            <img :src="s.advisor.profile_photo_url" class="w-10 h-10 rounded-full mr-4" />
            <span class="flex-1 text-base font-medium text-left">
              {{ s.advisor?.advisor_profile?.name ?? '（名前未登録）' }}
            </span>
            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </Link>
        </template>
      </div>
      <div v-else class="p-8 text-center text-gray-400">現在登録されているサブスクはありません。</div>
    </div>
  </AppLayout>
</template>
