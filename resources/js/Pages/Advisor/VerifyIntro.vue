<template>
  <AppLayout title="本人確認">
  <div class="bg-[#fafafa] min-h-screen flex flex-col items-center py-4">
    <div class="w-full max-w-md mx-auto">
      <!-- タイトル -->
      <div class="text-center mt-6 mb-4">
        <h1 class="text-3xl font-bold">本人確認</h1>
      </div>
      <!-- サブタイトル -->
      <div class="text-center text-base text-gray-700 mb-8">
        <p>
          YOASOBIでは、他ユーザーとマッチングや<br>
          メッセージのやり取りには<br>
          本人確認を必須としています。
        </p>
      </div>
      <!-- 個人情報保護 -->
      <div class="bg-white rounded-xl p-6 mb-8 shadow-sm text-center">
        <h2 class="font-semibold text-lg mb-2">安心の個人情報保護</h2>
        <p class="text-gray-700 text-sm mb-2">
          個人情報保護法に基づき、厳正な管理、<br>
          適正な取り扱いを徹底して行います。
        </p>
        <p class="text-gray-700 text-sm">
          提出して頂いた書類は、運営で確認し、<br>
          一定期間後に責任をもって破棄致します。
        </p>
      </div>
      <!-- 公的証明書の提出 -->
      <div class="text-center text-2xl font-semibold mb-4">公的証明書の提出</div>
      <div class="bg-[#bcb6e6] rounded-2xl py-6 px-3 mb-8 text-center">
        <div class="text-base font-semibold mb-3">
          公的証明書を使って<br>
          氏名・住所・生年月日などを確認します。
        </div>
        <div class="flex justify-around items-center mb-4 space-x-1">
          <div class="flex flex-col items-center">
            <img src="/assets/imgs/honnin1.png" alt="運転免許証" class="h-16 mb-2" />
            <span class="text-sm text-gray-700">運転免許証</span>
          </div>
          <div class="flex flex-col items-center">
            <img src="/assets/imgs/honnin2.png" alt="健康保険証" class="h-16 mb-2" />
            <span class="text-sm text-gray-700">健康保険証</span>
          </div>
          <div class="flex flex-col items-center">
            <img src="/assets/imgs/honnin3.png" alt="健康保険証" class="h-16 mb-2" />
            <span class="text-xs text-blue-700 font-bold">82.08×52</span>
          </div>
        </div>
        <div class="text-xs text-gray-700 mb-2 leading-tight">
          マイナンバー通知カードや年金手帳、障害者手帳、学生証は証明書としてご利用いただけません。<br>
          上記に当てはまらない証明書類をお持ちの方は、ヘルプページをご参照し、YOASOBI運営にお問い合わせください。
        </div>
      </div>
      <!-- 書類撮影時の注意 -->
      <div class="text-center text-2xl font-semibold mb-2">書類撮影時の注意</div>
      <div class="mb-8 text-center text-gray-800 text-base">
        以下がハッキリと見えるようにしてください。
        <ul class="mt-2 mb-4 text-left text-sm mx-auto max-w-[320px] space-y-1">
          <li>① 生年月日</li>
          <li>② 発行者名称</li>
          <li>③ 書類名称</li>
          <li>④ 氏名</li>
        </ul>
      </div>
      <!-- 登録ボタン -->
    <div class="flex justify-center mb-8">
      <button
        class="w-[90%] max-w-xs h-12 rounded-full bg-gradient-to-r from-pink-400 to-pink-300 text-white font-bold text-lg shadow-md"
        @click="showModal = true"
      >
        身分証明書を登録する
      </button>
    </div>
  </div>
     <!-- モーダル -->
    <Modal v-if="showModal" @close="showModal = false">
      <div class="text-center px-4 pt-8 pb-4">
        <p class="mb-6 text-lg font-semibold">書類を選択してください</p>
        <button
          class="w-[90%] max-w-xs h-12 rounded-full bg-gradient-to-r from-pink-400 to-pink-300 text-white font-bold text-lg shadow-md"
          @click="goPreview"
        >
          身分証明書を登録する
        </button>
        <button
          class="w-full rounded-full py-3 bg-gray-200 text-gray-800 font-bold text-lg"
          @click="showModal = false"
        >
          キャンセルする
        </button>
      </div>
      <!-- ファイル選択 input は display:none で用意してもOK -->
      <input
        type="file"
        ref="fileInput"
        class="hidden"
        @change="onFileSelected"
        accept="image/*,application/pdf"
      />
    </Modal>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import Modal from '@/Components/Modal2.vue'
import { router } from '@inertiajs/vue3'

const showModal = ref(false)
const fileInput = ref(null)

function goPreview() {
  console
  router.visit('/advisor/verifypreview')
}

function selectFile() {
  fileInput.value.click()
}

function onFileSelected(e) {
  const files = e.target.files
  if (files.length) {
    router.visit('/advisor/verifypreview')
    showModal.value = false
  }
}
</script>
