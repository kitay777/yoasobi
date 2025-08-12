<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  content: '',
  image: null
})

const imagePreview = ref(null)

const handleFileChange = (e) => {
  const file = e.target.files[0]
  form.image = file
  if (file) {
    imagePreview.value = URL.createObjectURL(file)
  }
}

const submit = () => {
  form.post(route('posts.store'))
}
</script>

<template>
  <AppLayout title="新しい投稿">
    <div class="max-w-lg mx-auto p-4 my-8 rounded-3xl shadow-lg">
      <div class="flex items-center mb-6 justify-between">
        <div class="flex items-center">
          <button @click="$inertia.visit('/dashboard')" class="mr-2">
            <!-- 戻るアイコン -->
            <svg class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <h1 class="text-xl font-bold">
            新しい投稿
          </h1>
        </div>
        <!-- メディア追加ボタン -->
        <button
          @click="$inertia.visit('/media/create')"
          class="flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition"
        >
          <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          メディア追加
        </button>
      </div>

      <form @submit.prevent="submit">
        <label class="block font-bold mb-1 text-gray-800">タイトル <span class="text-red-500 text-xs">必須</span></label>
          
        <textarea
          v-model="form.content"
          placeholder="内容を入力する"
          class="w-full border rounded p-2 mb-4 h-24 resize-none"
        ></textarea>
        <label class="block font-bold mb-1 text-gray-800">画像 <span class="text-red-500 text-xs"></span></label>
    
        <div class="mb-4">
          <label class="block w-full">
            <input type="file" @change="handleFileChange" class="hidden" accept="image/*" />
            <div class="flex flex-col items-center justify-center h-48 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 group-hover:border-pink-400 transition">
                <img v-if="imagePreview" :src="imagePreview" class="h-full object-contain" />
              <img v-else src="/assets/imgs/upimage.png" alt="画像アップロード" class="w-14 h-14 opacity-60 mb-1"  />
        
              <span class="text-sm text-gray-400">画像をアップロード</span>
            </div>
          </label>
        </div>

        <button
          type="submit"
          class="w-full py-3 bg-gradient-to-r from-pink-500 to-pink-400 text-white rounded-full font-bold"
        >
          投稿する
        </button>
      </form>
    </div>
  </AppLayout>
</template>
