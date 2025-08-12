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
  form.post('/advisor/profile', {
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
          <span class="px-2 py-1 rounded">プロフィール編集</span>
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

      <div class="px-4 space-y-6 mt-6">
        <div>
          <label class="block text-sm font-medium">アカウント名</label>
          <input v-model="form.name" class="w-full border-b border-gray-300 py-1" />
        </div>

        <div>
          <label class="block text-sm font-medium">サブスク料金</label>
          <input v-model="form.subscription_price" type="number" class="w-full border-b border-gray-300 py-1" />
        </div>

        <div>
          <label class="block text-sm font-medium">トーク料金（P / 5分）</label>
          <input v-model="form.talk_price" type="number" class="w-full border-b border-gray-300 py-1" />
        </div>

        <div class="flex space-x-4">
          <div class="flex-1">
            <label class="block text-sm font-medium">年齢</label>
            <input v-model="form.age" type="number" class="w-full border-b border-gray-300 py-1" />
          </div>
          <div class="flex-1">
            <label class="block text-sm font-medium">地域</label>
            <input v-model="form.region" class="w-full border-b border-gray-300 py-1" />
          </div>
        </div>

        <!-- タグ編集対応 -->
        <div>
          <label class="block text-sm font-medium">アピールタグ</label>
          <div class="flex items-center gap-2 mt-1">
            <input
              v-model="tagInput"
              @keydown.enter.prevent="addTag"
              type="text"
              maxlength="20"
              placeholder="例：キャリア相談"
              class="flex-1 border-b border-gray-300 py-1"
            />
            <button
              @click="addTag"
              class="bg-pink-400 text-white px-3 py-1 rounded text-sm font-semibold hover:bg-pink-500"
            >
              追加
            </button>
          </div>
          <p v-if="errorMessage" class="text-red-500 text-sm mt-1">{{ errorMessage }}</p>
          <div class="flex flex-wrap gap-2 mt-2">
            <span
              v-for="(tag, index) in tagsInput"
              :key="index"
              class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-sm flex items-center"
            >
              {{ tag }}
              <button @click="removeTag(index)" class="ml-2 text-pink-500 hover:text-pink-700">×</button>
            </span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium">自己紹介</label>
          <textarea v-model="form.bio" rows="6" class="w-full border border-gray-300 rounded p-2 text-sm"></textarea>
        </div>

        <button @click="submit" class="w-full mt-6 bg-pink-400 text-white py-3 rounded-full font-bold">
          変更保存する
        </button>

        <div class="flex justify-between mt-4 text-sm text-gray-500">
          <button @click="$inertia.visit('/')" class="underline">トップへ</button>
          <button @click="$inertia.visit('/dashboard')" class="underline">ダッシュボードへ</button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
