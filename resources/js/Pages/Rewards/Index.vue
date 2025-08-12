<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
  layout: AppLayout
})

const rewards = ref({ data: [] })
const loading = ref(true)

const fetchPage = async (page = 1) => {
  loading.value = true
  console.log(`Fetching rewards for page ${page}`)
  try {
    const res = await axios.get(`/api/rewards?page=${page}`)
    rewards.value = res.data
  } finally {
    loading.value = false
  }
}

const formatDate = (str) => {
  if (!str) return '-'
  return new Date(str).toLocaleDateString()
}

onMounted(() => {
  fetchPage()
})
</script>


<template>

  <div class="p-4 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">報酬履歴</h2>

    <div v-if="loading" class="text-gray-500">読み込み中...</div>

    <div v-else>
      <div v-if="rewards.data.length === 0" class="text-gray-500">
        現在、報酬履歴はありません。
      </div>

      <table v-else class="w-full border border-gray-300 text-left">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border-b">金額</th>
            <th class="p-2 border-b">支払日</th>
            <th class="p-2 border-b">作成日</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="reward in rewards.data" :key="reward.id" class="hover:bg-gray-50">
            <td class="p-2 border-b">{{ reward.amount.toLocaleString() }} 円</td>
            <td class="p-2 border-b">{{ formatDate(reward.paid_at) }}</td>
            <td class="p-2 border-b">{{ formatDate(reward.created_at) }}</td>
          </tr>
        </tbody>
      </table>

      <div class="mt-4 flex gap-2">
        <button
          :disabled="!rewards.prev_page_url"
          @click="fetchPage(rewards.current_page - 1)"
          class="px-3 py-1 border rounded disabled:opacity-50"
        >
          &laquo; 前へ
        </button>

        <span>ページ {{ rewards.current_page }} / {{ rewards.last_page }}</span>

        <button
          :disabled="!rewards.next_page_url"
          @click="fetchPage(rewards.current_page + 1)"
          class="px-3 py-1 border rounded disabled:opacity-50"
        >
          次へ &raquo;
        </button>
      </div>
      <div class="flex justify-end mb-4">
        <Link
          href="/rewards/request"
          class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded shadow"
        >
          支払い申請
        </Link>
      </div>
    </div>
  </div>
</template>
