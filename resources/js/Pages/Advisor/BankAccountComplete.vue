<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import BankModal from './_modals/BankModal.vue'
import BranchModal from './_modals/BranchModal.vue'
import TypeModal from './_modals/TypeModal.vue'

const props = defineProps({ account: Object })

const form = ref({
  phone_number: props.account?.phone_number ?? '',
  bank_name: props.account?.bank_name ?? '',
  branch_name: props.account?.branch_name ?? '',
  account_type: props.account?.account_type ?? '',
  account_number: props.account?.account_number ?? '',
  account_holder_sei: props.account?.account_holder_sei ?? '',
  account_holder_mei: props.account?.account_holder_mei ?? '',
  invoice_number: props.account?.invoice_number ?? '',
})

const errors = ref({})
const submitting = ref(false)

const showBankModal = ref(false)
const showBranchModal = ref(false)
const showTypeModal = ref(false)

const openBankSelect = () => { showBankModal.value = true }
const openBranchSelect = () => { showBranchModal.value = true }
const openTypeSelect = () => { showTypeModal.value = true }

const submit = () => {
  errors.value = {}
  submitting.value = true
  router.patch(`/bank-account/${props.account.id}`, form.value, {
    onError: (e) => { errors.value = e; submitting.value = false },
    onFinish: () => { submitting.value = false }
  })
}

</script>

<template>
  <AppLayout :title="'口座情報設定'">
    <div class="min-h-screen bg-white flex flex-col px-4">
    <form @submit.prevent="submit">
      <!-- タイトルラベル -->
      <div class="flex items-center justify-between px-4 pt-6 mb-4">
        <button onclick="history.back()">
          <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <h1 class="font-bold text-lg relative">
          <span class="px-2 py-1 rounded">口座情報設定</span>
        </h1>
        <div class="w-6"></div>
      </div>

      <!-- 電話番号 -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-1">電話番号</label>
        <input v-model="form.phone_number" type="tel"
          class="w-full border-none rounded-none px-3 py-2 bg-gray-50 focus:outline-none"
          placeholder="0123456789" />
      </div>

      <!-- 銀行名 -->
      <div class="flex items-center justify-between border-b border-gray-200 py-3 cursor-pointer" @click="openBankSelect">
        <span class="text-gray-700 text-sm">銀行名</span>
        <span class="flex items-center text-gray-900 text-base">
          <span class="truncate mr-1">{{ form.bank_name || '選択してください' }}</span>
          <span class="text-gray-400 text-lg">&gt;</span>
        </span>
      </div>

      <!-- 支店名 -->
      <div class="flex items-center justify-between border-b border-gray-200 py-3 cursor-pointer" @click="openBranchSelect">
        <span class="text-gray-700 text-sm">支店名</span>
        <span class="flex items-center text-gray-900 text-base">
          <span class="truncate mr-1">{{ form.branch_name || '選択してください' }}</span>
          <span class="text-gray-400 text-lg">&gt;</span>
        </span>
      </div>

      <!-- 口座の種類 -->
      <div class="flex items-center justify-between border-b border-gray-200 py-3 cursor-pointer" @click="openTypeSelect">
        <span class="text-gray-700 text-sm">口座の種類</span>
        <span class="flex items-center text-gray-900 text-base">
          <span class="truncate mr-1">{{ form.account_type || '選択してください' }}</span>
          <span class="text-gray-400 text-lg">&gt;</span>
        </span>
      </div>

      <!-- 口座番号 -->
      <div class="mb-4 mt-2">
        <label class="block text-gray-700 mb-1">口座番号（半角数字）</label>
        <input v-model="form.account_number"
          type="text"
          class="w-full border-none rounded-none px-3 py-2 bg-gray-50 focus:outline-none"
          placeholder="1234567"
        />
        <div class="text-xs  mt-1">
          <span class=" px-1 py-0.5 rounded">口座情報</span>
          が不正の場合は先頭の0をつけてください。
        </div>
      </div>

      <!-- 口座名義人 -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-1">口座名義人（カナ）</label>
        <input v-model="form.account_holder_sei" type="text"
          class="w-full border-none rounded-none px-3 py-2 mb-2 bg-gray-50 focus:outline-none"
          placeholder="セイ" />
        <input v-model="form.account_holder_mei" type="text"
          class="w-full border-none rounded-none px-3 py-2 bg-gray-50 focus:outline-none"
          placeholder="メイ" />
      </div>

      <div class="text-xs  mb-4">
        <span class="px-1 py-0.5 rounded">口座情報設定</span>
        の登録情報に誤りがあり、振り込みができず再申込みとなった場合、手数料が必要となってしまいますのでご注意ください。
      </div>

      <!-- インボイス登録番号 -->
      <div class="mb-4">
        <label class="block text-gray-700 mb-1">インボイス登録番号</label>
        <input v-model="form.invoice_number" type="text"
          class="w-full border-none rounded-none px-3 py-2 bg-gray-50 focus:outline-none"
          placeholder="T1234567890123" />
        <div class="text-xs text-gray-500 mt-1">(T+13桁の番号)で入力してください。</div>
      </div>

      <button
          type="submit"
          class="w-full py-3 mt-4 rounded-full bg-gradient-to-r from-pink-400 to-pink-300 text-white text-lg font-bold shadow"
          :disabled="submitting"
        >
        変更する
      </button>
    </form>
    </div>
    <!-- モーダル -->
    <BankModal v-if="showBankModal"
      :model-value="form.bank_name"
      @close="showBankModal = false"
      @select="val => { form.bank_name = val; showBankModal = false }" />

    <BranchModal v-if="showBranchModal"
      :model-value="form.branch_name"
      @close="showBranchModal = false"
      @select="val => { form.branch_name = val; showBranchModal = false }" />

    <TypeModal v-if="showTypeModal"
      :model-value="form.account_type"
      @close="showTypeModal = false"
      @select="val => { form.account_type = val; showTypeModal = false }" />

  </AppLayout>
</template>
