<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'


const slides = [
  'ひとりで悩まないで\n頼れる人がここにいる',
  '売上も接客も相談OK\n“聞ける”があるって安心',
  'ポイントを使えば\n特別な支えが受けられる',
  'あなたの味方が、ここにいる\nさっそくはじめてみよう',
]

const current = ref(0)

const next = () => {
  if (current.value < slides.length - 1) {
    current.value++
  } else {
    router.visit(`/register-select?role=${getRoleParam()}`)
  }
}


function getRoleParam() {
  const searchParams = new URLSearchParams(usePage().url.split('?')[1] || '')
  return searchParams.get('role') || 'cast'
}
</script>

<template>
  <div
    class="min-h-screen flex flex-col justify-between items-center bg-cover bg-center text-white px-4 pt-20 pb-10"
    style="background-image: url('/assets/imgs/back.png');"
  >
    <!-- メインメッセージ -->
    <div class="font-noto text-[24px] leading-[36px] font-bold text-center whitespace-pre-line">
      {{ slides[current] }}
    </div>


    <!-- 下部：ドット & ボタン -->
    <div class="w-full max-w-xs flex flex-col items-center">
      <!-- ドット（ボタンの20px上） -->
      <div class="flex items-center justify-center gap-1 mb-[20px]">
        <template v-for="(s, i) in slides" :key="i">
          <div
            class="w-2.5 h-2.5 rounded-full transition-all duration-300"
            :class="i <= current ? 'bg-pink-400' : 'bg-gray-400 opacity-50'"
          />
        </template>
      </div>

      <!-- ボタン -->
      <button
        @click="next"
        class="w-full py-3 rounded-full bg-gray-800 text-white font-medium tracking-wider"
      >
        {{ current < slides.length - 1 ? '次へ' : '新規登録' }}
      </button>
    </div>
  </div>
</template>
