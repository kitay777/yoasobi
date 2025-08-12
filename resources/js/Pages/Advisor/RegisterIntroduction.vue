<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const form = useForm({
  introduction: '',
})

const canSubmit = computed(() => {
  const len = form.introduction.length
  return len >= 10 && len <= 3000 && !form.processing
})

const submit = () => {
  if (canSubmit.value) {
    form.post(route('advisor.introduction.update'))
  }
}
</script>

<template>
  <AppLayout title="自己紹介入力">
    <div class="min-h-screen-[100px] bg-gradient-to-b flex flex-col justify-between relative pt-6 pb-[100px] px-4">

      <!-- 中央カード -->
      <div class="flex-1 flex justify-center">
        <div class="w-full bg-white rounded-2xl shadow-lg p-6">
          <h1 class="text-lg font-bold text-gray-800 mb-2 text-left">自己紹介</h1>
          <p class="text-sm text-gray-500 mb-6 text-left">
            3000文字以内で入力してください。<br />
            自己紹介はあとから変更できます。
          </p>

          <textarea
            v-model="form.introduction"
            maxlength="3000"
            placeholder="ここに自己紹介を入力してください"
            rows="8"
            class="w-full border border-gray-300 rounded-md p-3 text-base text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 resize-none"
            :class="{ 'border-red-500': form.errors.introduction }"
          ></textarea>

          <p v-if="form.errors.introduction" class="text-red-500 text-sm mt-1">
            {{ form.errors.introduction }}
          </p>
        </div>
      </div>

      <!-- 次へボタン -->
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
