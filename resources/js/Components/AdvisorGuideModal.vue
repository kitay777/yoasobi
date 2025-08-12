<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-3xl p-6 w-11/12 max-w-md mx-auto text-center relative">
      <div class="flex flex-col items-center">
        <div class="bg-pink-200 rounded-full h-14 w-14 flex items-center justify-center mb-2">
          <span class="text-3xl text-pink-500 font-bold">i</span>
        </div>
        <div class="font-bold text-xl mb-2">運用ガイド</div>
        <div class="text-gray-600 mb-4 text-base">
          スムーズにスタートするために、運用ガイドをご覧になりますか？<br>
          使い方やサポートの流れをわかりやすくまとめています。<br><br>
          ※マイページ内の「運用ガイド」から、あとで確認することもできます。
        </div>
        <button
          class="bg-gradient-to-r from-pink-400 to-pink-200 text-white font-semibold rounded-full px-6 py-2 text-base mb-2"
          @click="$emit('confirm')"
        >
          確認する
        </button>
        <button
          class="bg-gradient-to-r from-pink-400 to-pink-200 text-white font-semibold rounded-full px-6 py-2 text-base mb-2"
          @click="$emit('gotoMyPage')"
        >
          マイページへ
        </button>
        <button class="text-gray-400 font-bold mt-2" @click="$emit('close')">閉じる</button>
      </div>
    </div>
  </div>
</template>
<script setup>

import { router, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.user
const props = defineProps({
  user: Object,
  show: Boolean,
})




function gotoMyPage() {
  if (user?.role === 'advisor') {
    router.visit('/advisor/mypage')
  } else if (user?.role === 'cast') {
    router.visit('/cast/mypage')
  } else {
    router.visit('/cast/mypage')
  }
}
</script>

