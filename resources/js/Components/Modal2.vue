<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- 背景クリックでclose（bg: 透明黒） -->
    <div class="absolute inset-0 bg-black bg-opacity-30" @click="$emit('close')"></div>
    <!-- モーダル本体 -->
    <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full z-10">
      <slot />
    </div>
  </div>
</template>
