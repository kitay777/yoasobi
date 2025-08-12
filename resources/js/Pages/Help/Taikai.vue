<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const user = usePage().props.auth.user

const autoRenewalChecked = ref(!!user.auto_renewal_stopped)
const expireAt = user.paid_plan_expire_at || '未設定'
const submitting = ref(false)

const canWithdraw = computed(() => autoRenewalChecked.value)

function onWithdraw() {
  router.visit('/taikai/confirm')
}

function onContinue() {
  // 「有効期限まで続ける」ボタン押下時の挙動（必要に応じてAPI遷移）
  router.visit('/dashboard')
}
</script>

<template>
<AppLayout title="退会手続き">
  <div class="max-w-lg mx-auto px-4 py-8 bg-white min-h-screen">
    <!-- ヘッダー -->
    <div class="flex items-center justify-center relative mb-6">
      <button @click="$inertia.visit('/help')" class="absolute left-0">
        <svg class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <h1 class="text-xl font-bold text-gray-800 text-center">退会</h1>
    </div>

    <div class="text-center mb-6">
      <p class="text-base font-semibold">退会する前に<br />以下の内容をご確認ください</p>
    </div>

    <div class="mb-6 text-gray-800 space-y-5">
      <div>
        <span class="font-bold">1.</span> YOASOBIでは一度退会すると一定期間再登録することができません。
      </div>
      <div>
        <span class="font-bold">2.</span> 退会すると一定期間保管された後、以下の情報がすべて削除されます。
        <ul class="list-disc ml-6 text-sm mt-1">
          <li>登録プロフィール情報</li>
          <li>マッチやメッセージの履歴</li>
        </ul>
      </div>
      <div>
        <span class="font-bold">3.</span> 自動更新の停止は、完了しましたか？<br>
        <span class="font-bold text-sm text-pink-500">有料プラン有効期限　{{ expireAt }}</span>
        <p class="mt-2 text-sm">
          YOASOBIの退会手続きを行っても、自動更新は停止されません。<br>
          停止方法をご確認のうえ、退会手続き前に有料プランやプレミアムオプションの自動更新停止を行ってください。
        </p>
        <div class="mt-2">
          <a href="https://help.yoasobi.com/auto-renewal-stop" target="_blank" class="text-blue-600 underline text-sm">
            自動更新の停止方法を確認
          </a>
        </div>
        <div class="mt-2 text-sm">
          自動更新の停止を完了しましたら、下のチェックボックスを選択してください。
        </div>
        <label class="flex items-center mt-2 text-base">
          <input type="checkbox" v-model="autoRenewalChecked" class="w-5 h-5 accent-pink-400 mr-2">
          自動更新を停止しました
        </label>
      </div>
    </div>

    <div class="mt-8 flex flex-col gap-4">
      <button
        class="py-3 rounded-full text-white font-bold bg-gradient-to-r from-pink-400 to-pink-300 shadow disabled:opacity-50"
        :disabled="!canWithdraw || submitting"
        @click="onWithdraw"
      >
        退会手続きを進める
      </button>
      <button
        class="py-3 rounded-full font-bold bg-white text-pink-400 border border-pink-300 shadow"
        @click="onContinue"
        type="button"
      >
        有効期限まで続ける
      </button>
    </div>
  </div>
</AppLayout>
</template>
