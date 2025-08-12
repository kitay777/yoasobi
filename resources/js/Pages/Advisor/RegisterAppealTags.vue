<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  tags: Array
})

const tagInput = ref('')
const tags = ref([...props.tags])
const errorMessage = ref('')

const form = useForm({
  tags: []
})

const addTag = () => {
  const value = tagInput.value.trim()
  if (!value) return

  if (value.length > 20) {
    errorMessage.value = '20文字以内で入力してください。'
    return
  }

  if (tags.value.includes(value)) {
    errorMessage.value = '同じタグがすでに追加されています。'
    return
  }

  tags.value.push(value)
  tagInput.value = ''
  errorMessage.value = ''
}

const removeTag = (index) => {
  tags.value.splice(index, 1)
}

const submit = () => {
  if (tags.value.length === 0) {
    errorMessage.value = '少なくとも1つタグを入力してください。'
    return
  }

  form.tags = tags.value
  form.post(route('advisor.appeal-tags.update'))
}
</script>

<template>
  <AppLayout title="アピールタグ設定">
    <div class="min-h-screen-[100px] bg-white flex flex-col justify-between relative pt-6 pb-[100px] px-4">
      <!-- 上部案内 -->
      <div>
        <h1 class="text-lg font-bold text-gray-800 mb-2">アピールタグを設定しよう</h1>
        <p class="text-sm text-gray-600 mb-4">
          あなたの得意分野やサポート内容をタグでアピールできます。<br />
          まずは一つ作成してみましょう。<br />
          20文字以内で入力してください。
        </p>

        <!-- 入力フォーム -->
        <div class="flex items-center gap-2 mb-2">
          <input
            v-model="tagInput"
            @keydown.enter.prevent="addTag"
            type="text"
            maxlength="20"
            placeholder="例：キャリア相談"
            class="w-full border border-gray-300 rounded-md p-3 text-base text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300"
          />
          <button
            @click="addTag"
            class="bg-pink-400 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-pink-500"
          >
            追加
          </button>
        </div>

        <!-- バリデーション -->
        <p v-if="errorMessage" class="text-red-500 text-sm mb-2">{{ errorMessage }}</p>

        <!-- 追加済みタグ一覧 -->
        <div class="flex flex-wrap gap-2">
          <span
            v-for="(tag, index) in tags"
            :key="index"
            class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-sm flex items-center"
          >
            {{ tag }}
            <button @click="removeTag(index)" class="ml-2 text-pink-500 hover:text-pink-700">×</button>
          </span>
        </div>
      </div>

      <!-- 次へボタン -->
      <div class="fixed bottom-[72px] left-0 right-0 px-4">
        <div class="w-full max-w-md mx-auto">
          <button
            @click="submit"
            class="w-full py-3 rounded-full font-semibold text-white transition-all duration-300"
            :class="tags.length > 0
              ? 'bg-gradient-to-r from-pink-400 to-pink-300 shadow-md'
              : 'bg-gray-300 cursor-not-allowed'"
            :disabled="tags.length === 0 || form.processing"
          >
            次へ
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
