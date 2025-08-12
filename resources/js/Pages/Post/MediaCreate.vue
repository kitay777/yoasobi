<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  file: null,
})
const filePreview = ref(null)
const fileType = ref('') // 'image' or 'video' でプレビュー分岐

const handleFileChange = (e) => {
  const file = e.target.files[0]
  form.file = file
  if (!file) {
    filePreview.value = null
    return
  }
  if (file.type.startsWith('image/')) {
    fileType.value = 'image'
    filePreview.value = URL.createObjectURL(file)
  } else if (file.type.startsWith('video/')) {
    fileType.value = 'video'
    filePreview.value = URL.createObjectURL(file)
  }
}

const submit = () => {
  form.post(route('media.store'), {
    forceFormData: true,
  })
}
</script>

<template>
  <AppLayout title="メディア投稿">
    <div class="max-w-lg mx-auto p-4 my-8 rounded-3xl shadow-lg">
      <div class="flex items-center mb-6 justify-between">
        <div class="flex items-center">
          <button @click="$inertia.visit('/dashboard')" class="mr-2">
            <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <h1 class="text-xl font-bold">メディア投稿</h1>
        </div>
        <!-- 投稿追加ボタン -->
        <button
          @click="$inertia.visit('/posts/create')"
          class="flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition"
        >
          <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          投稿追加
        </button>
      </div>

      <form @submit.prevent="submit">
        <!-- タイトル -->
        <div class="mb-5">
          <label class="block font-bold mb-1 text-gray-800">タイトル <span class="text-red-500 text-xs">必須</span></label>
          <input
            type="text"
            v-model="form.title"
            placeholder="メディアタイトル"
            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-pink-200"
            maxlength="80"
            required
          />
        </div>

        <!-- ファイルアップロード -->
        <div class="mb-6">
          <label class="block font-bold mb-1 text-gray-800">画像・動画 <span class="text-red-500 text-xs">必須</span></label>
          <div class="flex justify-center items-center w-full">
            <label class="cursor-pointer group w-full">
              <input type="file" class="hidden" accept="image/*,video/*" @change="handleFileChange" />
              <div class="flex flex-col items-center justify-center h-48 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 group-hover:border-pink-400 transition">
                <template v-if="filePreview">
                  <img v-if="fileType === 'image'" :src="filePreview" class="h-36 object-contain mb-2 rounded" />
                  <video v-if="fileType === 'video'" :src="filePreview" controls class="h-36 rounded bg-black" />
                  <span class="text-xs text-gray-500 truncate">{{ form.file?.name }}</span>
                </template>
                <template v-else>
                  <img src="/assets/imgs/upimage.png" alt="アップロード" class="w-14 h-14 opacity-60 mb-1" />
                  <span class="text-sm text-gray-400">画像または動画をアップロード</span>
                </template>
              </div>
            </label>
          </div>
        </div>

        <button
          type="submit"
          class="w-full py-3 bg-gradient-to-r from-pink-400 to-pink-300 text-white rounded-full font-bold text-lg shadow mt-2"
          :disabled="form.processing || !form.title || !form.file"
        >
          投稿する
        </button>
      </form>
    </div>
  </AppLayout>
</template>

