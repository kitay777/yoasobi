<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const slides = [
  '今すぐ探せる\n近くにいる人と話ができる',
  'トークはフリーでOK\n“気軽に話せる”がコンセプト',
  '一度登録すれば何人とも話せる\n納得するまで話ができる',
  '話して納得できれば\nリアルで食事も可能',
  'あなたの味方が、ここにいる\nさっそくはじめてみよう',
]

const current = ref(0)

const next = () => {
  if (current.value < slides.length - 1) {
    current.value++
  } else {
    router.visit('/register-select')
  }
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
