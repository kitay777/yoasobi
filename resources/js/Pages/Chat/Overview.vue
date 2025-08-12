<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { onMounted, ref } from 'vue'
import axios from 'axios'

const recentMatches = ref([])
const unreadMessages = ref([])

onMounted(async () => {
  const res = await axios.get('/api/chat/overview')
  recentMatches.value = res.data.recentMatches
  unreadMessages.value = res.data.unreadMessages
})
</script>

<template>
  <AppLayout title="マッチとメッセージ">
    <div class="p-4 space-y-6 bg-black min-h-screen text-white">

      <!-- 🆕 新しいマッチ -->
      <div>
        <h2 class="text-lg font-bold mb-2">新しいマッチ</h2>
        <div class="flex space-x-4 overflow-x-auto">
          <div
            v-for="user in recentMatches"
            :key="user.id"
            class="flex-shrink-0 w-20 text-center"
          >
            <img
              :src="user.photo"
              alt="プロフィール画像"
              class="w-16 h-16 object-cover rounded-full border-2 border-yellow-500 mx-auto"
            />
            <p class="text-xs mt-1 truncate">{{ user.label }}</p>
          </div>
        </div>
      </div>

      <!-- 💬 未読メッセージ -->
      <div>
        <h2 class="text-lg font-bold mb-2">メッセージ</h2>
        <div
          v-for="user in unreadMessages"
          :key="user.id"
          class="flex items-center space-x-4 p-3 hover:bg-gray-800 rounded"
        >
          <img :src="user.photo" class="w-12 h-12 rounded-full object-cover" />
          <div class="flex-1">
            <p class="font-semibold">{{ user.name }}</p>
            <p class="text-sm text-gray-400 truncate">{{ user.lastMessage }}</p>
          </div>
          <div v-if="user.unread" class="w-2 h-2 bg-red-500 rounded-full"></div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>
