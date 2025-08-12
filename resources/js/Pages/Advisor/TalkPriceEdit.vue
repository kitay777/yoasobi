<template>
  <AppLayout title="ポイントを設定しよう">
    <div class="min-h-screen flex flex-col">
      <!-- トップバー -->
      <div class="flex justify-between items-center px-4 pt-4 pb-2">
        <button @click="goBack" class="text-2xl text-gray-700 font-bold p-1 leading-none">&lt;</button>
        <button @click="onSkip" class="text-gray-500 font-semibold text-base">Skip</button>
      </div>
      <!-- コンテンツ -->
      <div class="w-full max-w-md mx-auto rounded-xl p-6 mt-2">
        <div class="mb-1">
          <span class="text-xl font-bold align-middle">ポイントを設定しよう</span>
        </div>
        <div class="mb-2 text-left text-gray-700 leading-relaxed text-[15px]">
          キャストとトークをすると、ポイントが報酬として付与されます。<br>
          ポイントは【5分単位】で設定でき、あなたのスキルに見合った価格を自由に決められます。
        </div>
        <form @submit.prevent="submit" class="w-full">
          <div class="mb-8 flex items-center gap-2 mt-8">
            <input
              type="number"
              v-model="form.talk_price"
              :placeholder="String(defaultPrice)"
              class="flex-1 p-3 rounded border text-lg text-right bg-white placeholder-gray-400"
              min="0"
              max="100000"
              inputmode="numeric"
              required
            />
            <span class="text-lg font-semibold">P/5分</span>
          </div>
        </form>
      </div>
      <!-- ボタン -->
      <div class="fixed left-0 w-full bottom-10 bg-gradient-to-t from-white/90 to-transparent z-30 pb-8 pt-2 pointer-events-none">
        <button
          :disabled="!isFilled"
          @click="submit"
          :class="[
            'w-[90vw] max-w-md mx-auto block py-3 text-lg rounded-full font-bold shadow pointer-events-auto',
            isFilled
              ? 'bg-pink-500 hover:bg-pink-600 text-white transition'
              : 'bg-gray-300 text-gray-400 cursor-not-allowed'
          ]"
        >
          次へ
        </button>
      </div>
      <div class="h-28"></div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({ talk_price: Number })
const defaultPrice = props.talk_price || 1000

const form = useForm({
  talk_price: props.talk_price ?? ''
})

const isFilled = computed(() => {
  return !!form.talk_price && Number(form.talk_price) > 0
})

const goBack = () => {
  window.history.length > 1 ? history.back() : window.location.href = '/'
}
const onSkip = () => {
  window.location.href = '/dashboard'
}

const submit = () => {
  if (isFilled.value) {
    form.post('/advisor/talk-price')
  }
}
</script>
