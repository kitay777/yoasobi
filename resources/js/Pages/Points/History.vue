<template>
  <AppLayout title="ポイント購入履歴">
    <div class="p-6 max-w-3xl mx-auto">
      <h1 class="text-xl font-bold mb-6">ポイント購入履歴</h1>

      <table class="w-full text-sm border">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="px-4 py-2 border">日時</th>
            <th class="px-4 py-2 border">ポイント</th>
            <th class="px-4 py-2 border">金額</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="t in transactions.data" :key="t.id">
            <td class="border px-4 py-2">{{ formatDate(t.created_at) }}</td>
            <td class="border px-4 py-2">{{ t.points }}P</td>
            <td class="border px-4 py-2">¥{{ t.amount }}</td>
          </tr>
        </tbody>
      </table>

      <!-- ページネーション -->
      <div class="mt-6 flex flex-wrap gap-2 justify-center">
        <Link
          v-for="(link, i) in transactions.links"
          :key="i"
          :href="link.url"
          class="px-3 py-1 border rounded text-sm"
          :class="{
            'bg-blue-500 text-white': link.active,
            'text-gray-600': !link.active,
            'pointer-events-none text-gray-400': !link.url,
          }"
          v-html="link.label"
        />
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  transactions: Object
})

function formatDate(dateStr) {
  const date = new Date(dateStr)
  return date.toLocaleString('ja-JP', {
    year: 'numeric', month: '2-digit', day: '2-digit',
    hour: '2-digit', minute: '2-digit'
  })
}
</script>
