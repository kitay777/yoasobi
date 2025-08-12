<script setup>
import { ref, watch } from 'vue'
const props = defineProps(['modelValue'])
const emit = defineEmits(['close', 'select'])
const selected = ref(props.modelValue)
const bankList = ['三井住友', '三菱UFJ', 'みずほ', '住信SBIネット', '楽天', 'ゆうちょ', 'その他']
const otherBankName = ref('')

watch(() => props.modelValue, v => { selected.value = v })
function submit() {
  if (selected.value === 'その他') {
    if (!otherBankName.value) {
      alert('銀行名を入力してください')
      return
    }
    emit('select', otherBankName.value)
  } else {
    emit('select', selected.value)
  }
}
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-sm rounded-xl p-6">
      <div class="text-lg font-bold mb-4 text-center">銀行名</div>
      <div class="grid grid-cols-2 gap-3 mb-4">
        <label v-for="name in bankList" :key="name" class="flex items-center">
          <input type="radio" :value="name" v-model="selected" class="accent-blue-500 mr-2" />
          {{ name }}
        </label>
      </div>
      <div v-if="selected === 'その他'" class="mb-4">
        <input
          v-model="otherBankName"
          type="text"
          class="w-full border rounded px-3 py-2"
          placeholder="銀行名を入力"
        />
      </div>
      <div class="flex justify-end space-x-2">
        <button @click="$emit('close')" class="text-gray-500 px-4 py-2">キャンセル</button>
        <button @click="submit" class="bg-blue-500 text-white rounded px-4 py-2">決定</button>
      </div>
    </div>
  </div>
</template>
