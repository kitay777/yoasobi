<script setup>
import { ref } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({
  layout: AppLayout
})

// フォーム定義
const form = useForm({
  amount: '',
  bank_name: '',
  bank_account: '',
  note: ''
})

const submit = () => {
  form.post('/api/rewards/request', {
    onSuccess: () => {
      alert('申請が送信されました')
      router.visit('/rewards') // 履歴画面に戻る
    }
  })
}
</script>

<template>
  <div class="p-4 max-w-xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">支払い申請</h2>

    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">申請金額（円）</label>
        <input type="number" v-model="form.amount" class="border w-full px-3 py-2 rounded" />
        <div v-if="form.errors.amount" class="text-sm text-red-600">{{ form.errors.amount }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">銀行名</label>
        <input type="text" v-model="form.bank_name" class="border w-full px-3 py-2 rounded" />
        <div v-if="form.errors.bank_name" class="text-sm text-red-600">{{ form.errors.bank_name }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">口座番号</label>
        <input type="text" v-model="form.bank_account" class="border w-full px-3 py-2 rounded" />
        <div v-if="form.errors.bank_account" class="text-sm text-red-600">{{ form.errors.bank_account }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">備考（任意）</label>
        <textarea v-model="form.note" class="border w-full px-3 py-2 rounded"></textarea>
      </div>

      <div>
        <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-600">
          申請する
        </button>
      </div>
    </form>
  </div>
</template>
