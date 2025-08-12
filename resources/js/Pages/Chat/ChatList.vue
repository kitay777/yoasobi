<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
const props = defineProps({ chats: Array })
const openChat = (partnerId) => {
  window.location.href = `/chat/${partnerId}`
}
</script>

<template>
  <app-layout :title="'チャット一覧'">
  <div class="px-2">
    <div v-for="chat in chats" :key="chat.partner_id" class="flex items-center py-4 border-b cursor-pointer"
         @click="openChat(chat.partner_id)">
      <img :src="chat.partner.profile_photo_url" class="w-12 h-12 rounded-full mr-3"/>
      <div class="flex-1 min-w-0">
        <div class="font-bold text-lg">{{ chat.partner.name }}</div>
        <div class="text-gray-500 text-sm truncate">{{ chat.last_message }}</div>
      </div>
      <div class="ml-2 flex flex-col items-end">
        <div class="text-xs text-gray-400">{{ chat.last_time }}</div>
        <span v-if="chat.unread_count > 0"
              class="bg-red-500 text-white rounded-full px-2 text-xs font-bold mt-2">
          {{ chat.unread_count }}
        </span>
      </div>
    </div>
  </div>
  </app-layout>
</template>
