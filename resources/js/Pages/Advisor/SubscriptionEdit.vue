<template>
  <AppLayout title="サブスク料金を設定">
    <div class="min-h-screen flex flex-col justify-start" >
      <!-- トップバー -->
      <div class="flex justify-between items-center px-4 pt-4 pb-2">
        <button @click="goBack" class="text-2xl text-gray-700 font-bold p-1 leading-none">&lt;</button>
        <button @click="onSkip" class="text-gray-500 font-semibold text-base">Skip</button>
      </div>
      <!-- コンテンツ -->
      <div class="w-full max-w-md mx-auto rounded-xl p-6 mt-2">
        <div class="mb-1">
          <span class="font-bold px-2 py-1 rounded text-lg align-middle mr-2">サブスク</span>
          <span class="text-xl font-bold align-middle">料金を設定しよう</span>
        </div>
        <div class="mb-2 text-left text-gray-700 leading-relaxed text-[15px]">
          あなたのプロフィールに登録したサポート内容を、月額制
          <span class="font-bold px-1 rounded">サブスク</span>
          として提供できます。<br>
          料金は自由に設定でき、ファンやリピーターが継続して応援しやすくなります。
          <div class="text-xs text-gray-500 mt-2">※売上の20%は運営手数料として差し引かれます。</div>
        </div>
        <form @submit.prevent="submit" class="w-full">
          <div class="mb-8 flex items-center gap-2 mt-8">
            <input
              type="number"
              v-model="form.subscription_price"
              :placeholder="String(defaultPrice)"
              class="flex-1 p-3 rounded border text-lg text-right bg-white placeholder-gray-400"
              min="0"
              max="100000"
              inputmode="numeric"
              required
            />
            <span class="text-lg font-semibold">円</span>
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
      <!-- 下部余白確保 -->
      <div class="h-28"></div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({ subscription_price: Number })
const defaultPrice = props.subscription_price || 10000

const form = useForm({
  subscription_price: props.subscription_price ?? ''
})

const isFilled = computed(() => {
  return !!form.subscription_price && Number(form.subscription_price) > 0
})

// 戻るボタン
const goBack = () => {
  window.history.length > 1 ? history.back() : window.location.href = '/'
}
// Skipボタン
const onSkip = () => {
  // 遷移先を実際の動線にあわせて修正
  window.location.href = '/advisor/talk-price'
}

const submit = () => {
  if (isFilled.value) {
    form.post('/advisor/subscription')
  }
}

</script>
