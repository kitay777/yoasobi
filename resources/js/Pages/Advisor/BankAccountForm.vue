<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = ref({
  phone_number: '',
  bank_name: '',
  bank_code: '',
  branch_name: '',
  branch_code: '',
  account_type: '普通',
  account_number: '',
  account_holder_sei: '',
  account_holder_mei: '',
  invoice_number: '',
  consents: [],
  bank_name_other: '',
})

const errors = ref({})
const submitting = ref(false)

const consentOptions = [
  { value: 'contract', label: '収納代行業務委託契約書に同意します' },
  { value: 'privacy', label: 'プライバシーポリシーおよび個人情報の取扱いに同意します' },
  { value: 'anti_social', label: '反社会的勢力ではなく、これに該当しないことを表明・確約します' },
  { value: 'asset_law', label: '資金決済法・割賦販売法に抵触しない形でサービスを運用することに同意します' },
  { value: 'no_pyramid', label: '特定商取引法に関する同意' },
]

const submit = () => {
  errors.value = {}
  submitting.value = true
  router.post('/bank-account', form.value, {
    onError: (e) => {
      errors.value = e
      submitting.value = false
    },
    onFinish: () => {
      submitting.value = false
    }
  })
}
</script>

<template>
  <AppLayout :title="'口座情報設定'">

  <form @submit.prevent="submit" class="max-w-md mx-auto px-4 py-6">
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
    <div class="mb-4">
      <label class="font-semibold">電話番号 <span class="text-red-500 text-xs">必須</span></label>
      <input v-model="form.phone_number" type="tel" class="w-full border rounded px-2 py-1 mt-1" placeholder="例）0123456789" />
      <div class="text-xs text-gray-500 mt-1">※半角数字で入力してください</div>
      <div v-if="errors.phone_number" class="text-red-500 text-xs">{{ errors.phone_number }}</div>
    </div>
    <div class="mb-4">
      <label class="font-semibold">銀行名 <span class="text-red-500 text-xs">必須</span></label>
      <div class="grid grid-cols-2 gap-2">
        <label><input type="radio" value="三井住友" v-model="form.bank_name" />三井住友</label>
        <label><input type="radio" value="みずほ" v-model="form.bank_name" />みずほ</label>
        <label><input type="radio" value="三菱UFJ" v-model="form.bank_name" />三菱UFJ</label>
        <label><input type="radio" value="住信SBIネット" v-model="form.bank_name" />住信SBIネット</label>
        <label><input type="radio" value="楽天" v-model="form.bank_name" />楽天</label>
        <label><input type="radio" value="ゆうちょ" v-model="form.bank_name" />ゆうちょ</label>
        <label>
          <input type="radio" value="その他" v-model="form.bank_name" />その他
        </label>
        <input
          v-if="form.bank_name === 'その他'"
          v-model="form.bank_name_other"
          placeholder="銀行名を入力"
          class="border rounded px-2 py-1 ml-2 mt-2"
        />
      </div>
      <div v-if="errors.bank_name" class="text-red-500 text-xs">{{ errors.bank_name }}</div>
    </div>
    <div class="mb-4">
      <label class="font-semibold">支店名 <span class="text-red-500 text-xs">必須</span></label>
      <input v-model="form.branch_name" type="text" class="w-full border rounded px-2 py-1 mt-1" />
      <div v-if="errors.branch_name" class="text-red-500 text-xs">{{ errors.branch_name }}</div>
    </div>
    <div class="mb-4">
      <label class="font-semibold">口座の種類 <span class="text-red-500 text-xs">必須</span></label>
      <div>
        <label><input type="radio" value="普通" v-model="form.account_type" />普通預金</label>
        <label class="ml-4"><input type="radio" value="当座" v-model="form.account_type" />当座預金</label>
      </div>
      <div v-if="errors.account_type" class="text-red-500 text-xs">{{ errors.account_type }}</div>
    </div>
    <div class="mb-4">
      <label class="font-semibold">口座番号（半角数字） <span class="text-red-500 text-xs">必須</span></label>
      <input v-model="form.account_number" type="text" class="w-full border rounded px-2 py-1 mt-1" placeholder="例）1234567" />
      <div v-if="errors.account_number" class="text-red-500 text-xs">{{ errors.account_number }}</div>
    </div>
    <div class="mb-4">
      <label class="font-semibold">口座名義人（カナ） <span class="text-red-500 text-xs">必須</span></label>
      <div class="flex gap-2">
        <input v-model="form.account_holder_sei" type="text" class="border rounded px-2 py-1" placeholder="セイ" />
        <input v-model="form.account_holder_mei" type="text" class="border rounded px-2 py-1" placeholder="メイ" />
      </div>
      <div v-if="errors.account_holder_sei" class="text-red-500 text-xs">{{ errors.account_holder_sei }}</div>
      <div v-if="errors.account_holder_mei" class="text-red-500 text-xs">{{ errors.account_holder_mei }}</div>
    </div>
    <div class="mb-4">
      <label class="font-semibold">インボイス登録番号 <span class="text-gray-400 text-xs">任意</span></label>
      <input v-model="form.invoice_number" type="text" class="w-full border rounded px-2 py-1 mt-1" placeholder="T1234567890123" />
    </div>
    <div class="mb-4">
      <label class="font-semibold">同意事項</label>
      <div v-for="option in consentOptions" :key="option.value" class="mb-1">
        <label>
          <input type="checkbox" :value="option.value" v-model="form.consents" />
          {{ option.label }}
        </label>
      </div>
      <div v-if="errors.consents" class="text-red-500 text-xs">{{ errors.consents }}</div>
    </div>
    <button
      class="w-full py-3 mt-4 rounded-full bg-gradient-to-r from-pink-400 to-pink-300 text-white text-lg font-bold shadow"
      :disabled="submitting"
      type="submit"
    >
      登録する
    </button>
  </form>
  </AppLayout>
</template>
