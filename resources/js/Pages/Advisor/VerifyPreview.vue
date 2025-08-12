<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const previewUrl = ref(null)
const fileInput = ref(null)
const imageFile = ref(null)
const dateOfBirth = ref('1990-01-01')
const errors = ref({})
const loading = ref(false)

function goBack() {
  window.history.back()
}

function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    previewUrl.value = URL.createObjectURL(file)
    imageFile.value = file
    errors.value.image = null
  }
}

function clearFile() {
  previewUrl.value = null
  imageFile.value = null
  fileInput.value.value = ''
}

function submit() {
  errors.value = {}
  if (!imageFile.value) {
    errors.value.image = '画像を選択してください'
    return
  }
  if (!dateOfBirth.value) {
    errors.value.dateOfBirth = '生年月日を入力してください'
    return
  }
  loading.value = true

  const formData = new FormData()
  formData.append('birthdate', dateOfBirth.value)
  formData.append('image', imageFile.value) // ここで画像ファイルをappend

  router.post('/advisor/identity', formData, {
    forceFormData: true, // これでFormDataでそのまま送信
    onFinish: () => {
      loading.value = false
    },
    onError: (err) => {
      errors.value = err
    }
  })
}
</script>

<template>
  <AppLayout title="本人確認">
    <div class="bg-[#fafafa] min-h-screen flex flex-col items-center py-4">
      <div class="w-full max-w-md mx-auto">
        <!-- 戻るボタン & タイトル -->
        <div class="flex items-center py-3 mb-2">
          <button @click="goBack" class="w-8 h-8 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <div class="flex-1 text-center text-2xl font-bold -ml-8">本人確認</div>
        </div>

        <!-- プレビュー画像 -->
        <div
          class="w-full bg-gray-200 rounded-xl mb-8 flex items-center justify-center"
          :style="{height: '180px'}"
        >
          <img v-if="previewUrl" :src="previewUrl" class="object-contain h-full" />
          <span v-else class="text-gray-400 text-xl">ここに写真が表示されます</span>
        </div>
        <div v-if="errors.image" class="text-red-500 text-xs text-center mb-2">{{ errors.image }}</div>

        <!-- ファイル/カメラ選択ボタン -->
        <div class="flex justify-center mb-6">
          <input
            ref="fileInput"
            type="file"
            accept="image/*"
            capture="environment"
            class="hidden"
            @change="onFileChange"
          />
          <button
            class="px-4 py-2 rounded-full bg-pink-200 text-pink-700 font-bold shadow-sm"
            @click="$refs.fileInput.click()"
            type="button"
          >
            ファイルを選択・撮影
          </button>
        </div>

        <!-- 生年月日 -->
        <div class="flex items-center justify-between px-6 mb-3">
          <div class="text-gray-400 text-lg font-semibold">生年月日</div>
          <input
            type="date"
            v-model="dateOfBirth"
            class="text-right border-none outline-none bg-transparent text-lg font-bold text-gray-700"
            style="width: 160px"
          />
        </div>

        <div v-if="errors.dateOfBirth" class="text-red-500 text-xs text-center mb-2">{{ errors.dateOfBirth }}</div>
        <hr class="mb-6"/>

        <!-- 注意文 -->
        <div class="text-center text-sm text-gray-600 mb-8 px-3">
          異なる場合は審査を通過できません。<br>
          正しい生年月日に修正してから送信するを押してください。
        </div>

        <!-- 送信ボタン -->
        <div class="flex flex-col items-center space-y-6">
          <button
            class="w-[90%] max-w-xs h-12 rounded-full bg-gradient-to-r from-pink-400 to-pink-300 text-white font-bold text-lg shadow-md"
            :disabled="!imageFile || !dateOfBirth || loading"
            @click="submit"
            type="button"
          >
            <span v-if="!loading">送信する</span>
            <span v-else>送信中...</span>
          </button>
          <button class="text-gray-700 underline text-base" @click="clearFile" type="button">再選択する</button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
