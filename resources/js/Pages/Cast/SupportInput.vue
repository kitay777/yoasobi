<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  content: '',
})

const submit = () => {
  form.post(route('cast.support.store'))
}

const skip = () => {
  window.location.href = route('next.route') // ← スキップ時の遷移先
}
</script>

<template>
  <AppLayout title="希望サポート内容">
    <div class="min-h-screen flex flex-col justify-between items-center px-6 pt-10 pb-[100px] bg-white">
      <!-- タイトル -->
      <div class="w-full max-w-md">
        <h1 class="text-xl font-bold text-gray-800 mb-2">あなたのプロフィールアピール</h1>
        <p class="text-sm text-gray-500 mb-4">
          1000文字以内で入力してください。<br />
          アピール内容はあとから変更できます。
        </p>

        <!-- 入力欄 -->
        <textarea
          v-model="form.content"
          class="w-full h-40 p-3 border rounded-md resize-none"
          :class="{ 'border-red-500': form.errors.content }"
        />
        <p v-if="form.errors.content" class="text-red-500 text-sm mt-1">
          {{ form.errors.content }}
        </p>
      </div>

      <!-- ボタン群 -->
      <div class="w-full max-w-md fixed bottom-[72px] px-4 space-y-2">
        <button
          @click="submit"
          class="w-full py-3 rounded-full font-semibold text-white"
          :class="form.content.length >= 10 ? 'bg-gradient-to-r from-pink-400 to-pink-300' : 'bg-gray-300'"
          :disabled="form.processing || form.content.length < 10"
        >
          次へ
        </button>

        <button @click="skip" class="w-full text-sm text-gray-500 underline">
          スキップ
        </button>
      </div>
    </div>
  </AppLayout>
</template>
