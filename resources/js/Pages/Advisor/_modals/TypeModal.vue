<script setup>
import { ref, watch } from 'vue'
const props = defineProps(['modelValue'])
const emit = defineEmits(['close', 'select'])
const selected = ref(props.modelValue)
const typeList = ['普通預金', '当座預金']
watch(() => props.modelValue, v => { selected.value = v })
function submit() { emit('select', selected.value) }
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-xs rounded-xl p-6">
      <div class="text-lg font-bold mb-4 text-center">口座の種類</div>
      <div class="flex justify-center gap-8 mb-4">
        <label v-for="type in typeList" :key="type" class="flex items-center">
          <input type="radio" :value="type" v-model="selected" class="accent-blue-500 mr-2" />
          {{ type }}
        </label>
      </div>
      <div class="flex justify-end space-x-2">
        <button @click="$emit('close')" class="text-gray-500 px-4 py-2">キャンセル</button>
        <button @click="submit" class="bg-blue-500 text-white rounded px-4 py-2">決定</button>
      </div>
    </div>
  </div>
</template>
