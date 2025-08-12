<template>
  <AppLayout title="ポイント購入">
    <div class="p-6 max-w-md mx-auto">
      <h1 class="text-2xl font-bold mb-6">ポイント購入</h1>

      <label class="block mb-4">
        <span class="text-lg font-medium">ポイントを選択</span>
        <select
          v-model="points"
          class="mt-2 w-full text-lg border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option :value="1000">1000pt</option>
          <option :value="5000">5000pt</option>
          <option :value="10000">10000pt</option>
        </select>
      </label>

      <button
        @click="purchase"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold px-4 py-3 rounded-lg"
      >
        購入する
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

const points = ref(100)

const purchase = async () => {
  try {
    const res = await axios.post('/points/checkout', { points: points.value })
    window.location.href = res.data.url
  } catch (e) {
    alert('エラーが発生しました')
    console.error(e)
  }
}
</script>
