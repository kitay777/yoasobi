<script setup>
import { ref, watch } from 'vue'
const props = defineProps(['modelValue'])
const emit = defineEmits(['close', 'select'])
const branch = ref(props.modelValue || '')
watch(() => props.modelValue, v => { branch.value = v })
function submit() { emit('select', branch.value) }
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-sm rounded-xl p-6">
      <div class="text-lg font-bold mb-4 text-center">支店名</div>
      <input v-model="branch" class="w-full border px-3 py-2 mb-3" placeholder="支店名を入力" />
      <div class="text-xs text-gray-500 mb-4">
        ※支店名を入力し、表示される候補の中から選択してください。<br>
        金融機関名の後ろに「銀行」等の表記は不要です。
      </div>
      <div class="flex justify-end space-x-2">
        <button @click="$emit('close')" class="text-gray-500 px-4 py-2">キャンセル</button>
        <button @click="submit" class="bg-blue-500 text-white rounded px-4 py-2">決定</button>
      </div>
    </div>
  </div>
</template>
