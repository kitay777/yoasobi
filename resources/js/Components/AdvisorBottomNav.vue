<script setup>
import { router } from '@inertiajs/vue3'

function gotoMenu() {
  router.visit('/advisor/menu')
}
function gotoMypage() {
  router.visit('/advisor/mypage')
}
</script>

<template>
  <div class="fixed left-0 w-full z-40 bottom-0">
    <div class="flex justify-around items-center h-16 bg-black text-white">
      <button @click="gotoMypage"><img src="/assets/imgs/advb01.png" class="w-8 h-8" /></button>
      <button><img src="/assets/imgs/advb02.png" class="w-8 h-8" /></button>
      <a href="/posts/create"><img src="/assets/imgs/advb03.png" class="w-8 h-8" /></a>
      <a href="/chat/list"><img src="/assets/imgs/advb04.png" class="w-8 h-8" /></a>
      <button @click="gotoMenu"><img src="/assets/imgs/advb05.png" class="w-8 h-8" /></button>
    </div>
  </div>
</template>
