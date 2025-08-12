<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  price: ''
})

const isValid = computed(() => {
  const p = Number(form.price)
  return !form.processing && p >= 100 && p <= 100000
})

const submit = () => {
  if (isValid.value) {
    form.post(route('advisor.subscription.update'))
  }
}
</script>

<template>
  <AppLayout title="サブスク料金設定">
    <div class="min-h-screen bg-white flex flex-col justify-between relative pt-6 pb-[100px] px-4">
      <div>
        <h1 class="text-lg font-bold text-gray-800 mb-2">サブスク料金を設定しよう</h1>
        <p class="text-sm text-gray-600 mb-4">
          あなたのプロフィールに登録したサポート内容を、<br />
          月額制（サブスク）として提供することができます。<br />
          ※売上の20%は運営手数料として差し引かれます。
        </p>

        <div class="flex items-center justify-between border border-gray-300 rounded-md px-4 py-3 bg-gray-100 text-xl text-gray-800">
          <input
            type="number"
            v-model="form.price"
            min="0"
            max="100000"
            class="bg-transparent w-full outline-none text-right"
            placeholder="金額を入力"
          />
          <span class="ml-2 text-base font-bold text-gray-700">円</span>
        </div>

        <p v-if="form.errors.price" class="text-red-500 text-sm mt-1">
          {{ form.errors.price }}
        </p>
      </div>

      <div class="fixed bottom-[72px] left-0 right-0 px-4">
        <div class="w-full max-w-md mx-auto">
          <button
            @click="submit"
            class="w-full py-3 rounded-full font-semibold text-white"
            :class="isValid
              ? 'bg-gradient-to-r from-pink-400 to-pink-300 shadow-md'
              : 'bg-gray-300 cursor-not-allowed'"
            :disabled="!isValid"
          >
            次へ
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
