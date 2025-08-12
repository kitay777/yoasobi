<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const message = ref('')
const submitting = ref(false)
const maxLength = 1000

const messageLength = computed(() => message.value.length)

function onSubmit() {
  if (submitting.value) return
  submitting.value = true
  router.post('/taikai/confirm', { message: message.value }, {
    onFinish: () => { submitting.value = false }
  })
}

function onCancel() {
  router.visit('/dashboard')
}
</script>

<template>
  <AppLayout>
    <div class="max-w-lg mx-auto px-4 py-8 bg-white min-h-screen">
      <!-- ヘッダー -->
      <div class="flex items-center justify-center relative mb-6">
        <button @click="$inertia.visit('/help/taikai')" class="absolute left-0">
          <svg class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
        <h1 class="text-xl font-bold text-gray-800 text-center">退会</h1>
      </div>

      <div class="mb-6 text-gray-800 text-center">
        今後のサービス改善のため、退会アンケートにご回答お願いします。<br>
        頂いた内容が許可なく外部公開されることはございません。
      </div>

      <div class="mb-8">
        <textarea
          class="w-full h-40 rounded-xl bg-gray-100 text-lg px-4 py-4 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-300 resize-none"
          v-model="message"
          :maxlength="maxLength"
          placeholder="入力してください"
        ></textarea>
        <div class="text-right text-gray-400 text-sm mt-1">{{ messageLength }}/{{ maxLength }}</div>
      </div>

      <div class="flex flex-col gap-4 mt-8">
        <button
          class="w-full py-4 rounded-full text-white font-bold bg-gradient-to-r from-pink-400 to-pink-300 shadow disabled:opacity-50 text-lg"
          :disabled="submitting"
          @click="onSubmit"
        >
          退会する
        </button>
        <button
          class="w-full py-4 rounded-full font-bold bg-white text-pink-400 border border-pink-300 shadow text-lg"
          @click="onCancel"
          type="button"
        >
          YOASOBIを続ける
        </button>
      </div>
    </div>
  </AppLayout>
</template>
