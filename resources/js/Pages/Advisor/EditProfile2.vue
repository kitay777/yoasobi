<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  profile: Object,
  tags: Array
})

const form = useForm({
  name: props.profile?.name || '',
  age: props.profile?.age || '',
  region: props.profile?.region || '',
  bio: props.profile?.bio || '',
  subscription_price: props.profile?.subscription_price || '',
  talk_price: props.profile?.talk_price || '',
  avatar: null,
  cover: null,
  tags: []
})

const avatarPreview = ref(props.profile?.avatar_path ? `/storage/${props.profile.avatar_path}` : null)
const coverPreview = ref(props.profile?.cover_path ? `/storage/${props.profile.cover_path}` : null)

const handleAvatarUpload = (e) => {
  const file = e.target.files[0]
  form.avatar = file
  if (file) avatarPreview.value = URL.createObjectURL(file)
}

const handleCoverUpload = (e) => {
  const file = e.target.files[0]
  form.cover = file
  if (file) coverPreview.value = URL.createObjectURL(file)
}

const tagInput = ref('')
const tagsInput = ref(Array.isArray(props.tags) ? [...props.tags] : [])
const errorMessage = ref('')

const addTag = () => {
  const value = tagInput.value.trim()
  if (!value) return
  if (value.length > 20) {
    errorMessage.value = '20文字以内で入力してください。'
    return
  }
  if (tagsInput.value.includes(value)) {
    errorMessage.value = '同じタグがすでに追加されています。'
    return
  }
  tagsInput.value.push(value)
  tagInput.value = ''
  errorMessage.value = ''
}

const removeTag = (index) => {
  tagsInput.value.splice(index, 1)
}

const submit = () => {
  form.tags = tagsInput.value
  form.post('/advisor/profile2', {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => alert('プロフィールを更新しました！')
  })
}
</script>

<template>
  <AppLayout title="プロフィール編集">
    <div class="bg-white min-h-screen">
      <div class="flex items-center justify-between px-4 pt-6">
        <button onclick="history.back()">
          <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <h1 class="font-bold text-lg relative">
          <span class="bg-yellow-300 px-2 py-1 rounded">プロフィール</span>
          <span class="ml-2 text-sm text-gray-500">編集</span>
        </h1>
        <div class="w-6"></div>
      </div>

      <!-- カバー画像 -->
      <div class="relative mt-4">
        <img :src="coverPreview || '/images/default-cover.jpg'" class="w-full h-40 object-cover" />
        <input type="file" accept="image/*" @change="handleCoverUpload" class="absolute top-2 right-2 text-sm" />
      </div>

      <!-- アバター -->
      <div class="relative w-24 h-24 -mt-10 justify-center mx-auto">
        <img :src="avatarPreview || '/images/default-avatar.png'" class="w-24 h-24 rounded-full object-cover border-4 border-white" />
        <label class="absolute inset-0 cursor-pointer flex items-center justify-center bg-black bg-opacity-30 rounded-full hover:bg-opacity-50">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          <input type="file" accept="image/*" @change="handleAvatarUpload" class="hidden" />
        </label>
      </div>
       <button @click="submit" class="w-full mt-6 bg-pink-400 text-white py-3 rounded-full font-bold">
          変更保存する
        </button>

        <div class="flex justify-between mt-4 text-sm text-gray-500">
          <button @click="$inertia.visit('/')" class="underline">トップへ</button>
          <button @click="$inertia.visit('/dashboard')" class="underline">ダッシュボードへ</button>
        </div>
    </div>
  
  </AppLayout>
</template>
