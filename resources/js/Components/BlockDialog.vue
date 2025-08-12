<!-- src/Components/BlockDialog.vue -->
<script setup>
const props = defineProps({
  show: Boolean,
  onConfirm: Function,
  onClose: Function,
});
function highlight(text) {
  return text.replace(/(ブロック|ブロックする)/g, '<span class="bg-yellow-300 px-1 rounded font-bold">$1</span>')
}
</script>
<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 max-w-md w-full text-center relative">
      <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-pink-300 rounded-full w-20 h-20 flex items-center justify-center border-8 border-white shadow">
        <span class="text-5xl text-white">!</span>
      </div>
      <h2 class="mt-10 mb-3 text-lg font-bold">このユーザーを<span class="bg-yellow-300 px-1 rounded font-bold">ブロック</span>しますか？</h2>
      <div class="mb-3 whitespace-pre-wrap">
        <span v-html="highlight('ブロックすると、以後このユーザーとやり取りできなくなります。\n\n現在サブスク登録されている場合は、解約手続きを行わない限り、料金は自動で継続されます。')"></span>
      </div>
      <div class="mb-5 text-gray-600 whitespace-pre-wrap">
        <span v-html="highlight('ブロック前に、マイページからサブスクの解約をご確認ください。')"></span>
      </div>
      <button
        class="w-full py-2 rounded-full font-bold text-white mb-2"
        style="background: linear-gradient(90deg, #ff93a8 0%, #ffa6ec 100%);"
        @click="props.onConfirm && props.onConfirm(); props.onClose && props.onClose()"
      >
        ブロックする
      </button>
      <button class="w-full text-gray-400 py-2 rounded" @click="props.onClose && props.onClose()">
        閉じる
      </button>
    </div>
  </div>
</template>
