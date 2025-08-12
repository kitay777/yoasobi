<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md mx-auto text-center relative">
      <!-- 警告アイコン -->
      <div class="flex justify-center">
        <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-pink-300 rounded-full flex items-center justify-center mb-3">
          <span class="text-3xl text-white">!</span>
        </div>
      </div>
      <!-- タイトル -->
      <div class="font-bold text-lg mb-3 text-gray-800">このユーザーをブロックしますか？</div>
      <!-- 本文 -->
      <div class="text-sm text-gray-700 mb-3 leading-relaxed">
        ブロックすると、以後このユーザーとやり取りできなくなります。<br /><br />
        <span class="font-bold text-pink-500">
          現在サブスク登録されている場合は、解約手続きを行わない限り、料金は自動で継続されます。
        </span><br /><br />
        ブロック前に、マイページからサブスクの解約をご確認ください。
      </div>
      <!-- ボタン -->
      <div class="flex flex-col gap-2 mt-6">
        <button
          class="bg-gradient-to-r from-pink-400 to-pink-300 text-white font-bold py-2 rounded-xl text-base shadow transition hover:opacity-90"
          @click="$emit('confirm')"
        >
          ブロックする
        </button>
        <button
          class="bg-gray-100 text-gray-500 font-medium py-2 rounded-xl text-base shadow hover:bg-gray-200 transition"
          @click="$emit('close')"
        >
          閉じる
        </button>
      </div>
    </div>
  </div>
</template>
