<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const form = useForm({
  account_name: '',
})

const canSubmit = computed(() => {
  return form.account_name.length >= 3 && form.account_name.length <= 8 && !form.processing
})

const submit = () => {
  if (canSubmit.value) {
    form.post(route('account.setup.store'))
  }
}
</script>

<template>
  <AppLayout title="アカウント名設定">
    <div class="min-h-screen-[100px] bg-gradient-to-b flex flex-col justify-between relative pt-6 pb-[100px] px-4">

      <!-- 中央カード：上下中央 -->
      <div class="flex-1 flex justify-center">
        <div class="w-full bg-white rounded-2xl shadow-lg p-6">
          <h1 class="text-lg font-bold text-gray-800 mb-2 text-left">アカウント名</h1>
          <p class="text-sm text-gray-500 mb-6 text-left">
            8文字以内で入力してください。<br>
            ニックネームはあとから変更できます。
          </p>

          <input
            v-model="form.account_name"
            type="text"
            maxlength="8"
            placeholder="アカウント名"
            class="w-full border border-gray-300 rounded-md p-3 text-base text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300"
            :class="{ 'border-red-500': form.errors.account_name }"
          />
          <p v-if="form.errors.account_name" class="text-red-500 text-sm mt-1">
            {{ form.errors.account_name }}
          </p>
        </div>
      </div>

      <!-- フッターの少し上に配置された「次へ」ボタン -->
      <div class="fixed bottom-[72px] left-0 right-0 px-4">
        <div class="w-full max-w-md mx-auto">
          <button
            @click="submit"
            class="w-full py-3 rounded-full font-semibold text-white transition-all duration-300"
            :class="canSubmit
              ? 'bg-gradient-to-r from-pink-400 to-pink-300 shadow-md'
              : 'bg-gray-300 cursor-not-allowed'"
            :disabled="!canSubmit"
          >
            次へ
          </button>
        </div>
      </div>

    </div>
  </AppLayout>
</template>
