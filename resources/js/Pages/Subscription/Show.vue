<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { router } from '@inertiajs/vue3'
const props = defineProps({ subscription: Object })
const goBack = () => {
  window.history.length > 1 ? window.history.back() : location.href = '/';
};

function cancelSubscription() {
  if (confirm('本当にこのサブスクを解除しますか？')) {
    router.post(route('subscriptions.cancel', props.subscription.id))
  }
}
</script>

<template>
  <AppLayout title="サブスク詳細">
    <div class="bg-white max-w-lg mx-auto p-4">
      <div class="flex items-center mb-4">
        <button @click="goBack()" class="mr-2">
          <svg class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
        <span class="px-2 py-1 rounded font-bold mr-2">サブスク</span>
        <span class="text-lg font-bold">登録</span>
      </div>
      <div class="flex items-center mb-6">
        <img :src="subscription.advisor.profile_photo_url" class="w-12 h-12 rounded-full mr-4" />
        <span class="text-lg font-semibold">{{ subscription.advisor.name }}</span>
      </div>
      <div class="text-gray-500 px-2">
        <div class="flex justify-between py-2">
          <span>料金</span>
          <span class="font-bold text-black">¥{{ subscription.price.toLocaleString() }}</span>
        </div>
        <div class="flex justify-between py-2">
          <span>契約期間</span>
          <span class="font-bold text-black">
            {{ subscription.start_date }} ～
            {{ subscription.end_date ? subscription.end_date : '継続中' }}
          </span>
        </div>
      </div>
      <div class="mt-8 text-center">
        <button
          class="text-red-500 font-bold"
          @click="cancelSubscription"
        >
          <span class="text-black px-1">サブスク</span>オプションを解除する
        </button>
      </div>
    </div>
  </AppLayout>
</template>
