<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'


function getRoleParam() {
  const searchParams = new URLSearchParams(usePage().url.split('?')[1] || '')
  return searchParams.get('role') || 'cast'
}

// クエリから role を取得（デフォルトは cast）
const role = computed(() => {
  const params = new URLSearchParams(usePage().url.split('?')[1] || '')
  return params.get('role') || 'cast'
})

// useForm
const form = useForm({
  email: '',
  password: '',
  password_confirmation: '',
  birthday: '',
  region: '',
  experience: '',
  role: role.value, // ← 🔥 明示的にセット！
})


const showPassword = ref(false)
const showPasswordConfirm = ref(false)
const regions = ['東京都', '大阪府', '北海道', '福岡県']
const experiences = ['1年未満', '1年', '2年', '3年以上']

const submit = () => form.post('/register/email')
</script>

<template>
  <div class="min-h-screen bg-white px-6 pt-6">
    <!-- ヘッダー -->
    <div class="flex items-center mb-6">
      <button onclick="history.back()" class="text-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <h1 class="flex-1 text-center text-lg font-bold text-gray-800">新規登録</h1>
      <div class="w-6"></div>
    </div>

    <!-- フォーム -->
    <form @submit.prevent="submit" class="space-y-5 max-w-sm mx-auto">
      <!-- Email -->
      <div>
        <input
          type="email"
          v-model="form.email"
          placeholder="メールアドレス"
          class="w-full border border-gray-300 rounded-md px-4 py-3 placeholder-gray-400"
        />
        <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
      </div>

      <!-- パスワード入力 -->
      <div class="relative">
        <input
          :type="showPassword ? 'text' : 'password'"
          v-model="form.password"
          placeholder="パスワード"
          class="w-full border border-gray-300 rounded-md px-4 py-3 pr-10 placeholder-gray-400"
        />
        <button type="button"
          @click="showPassword = !showPassword"
          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
        >
          <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.047-3.338M9.88 9.88a3 3 0 104.243 4.243M3 3l18 18" />
          </svg>
        </button>
        <p class="text-xs text-gray-500 mt-1">* パスワードは6文字以上16文字以内の半角英数字で入力してください。大文字小文字を必ず入れてください。</p>
        <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
      </div>

      <!-- パスワード確認 -->
      <div class="relative">
        <input
          :type="showPasswordConfirm ? 'text' : 'password'"
          v-model="form.password_confirmation"
          placeholder="パスワード確認"
          class="w-full border border-gray-300 rounded-md px-4 py-3 pr-10 placeholder-gray-400"
        />
        <button type="button"
          @click="showPasswordConfirm = !showPasswordConfirm"
          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
        >
          <svg v-if="!showPasswordConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.047-3.338M9.88 9.88a3 3 0 104.243 4.243M3 3l18 18" />
          </svg>
        </button>
        <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
      </div>

      <!-- Birthday -->
      <div>
        <input
          type="date"
          v-model="form.birthday"
          placeholder="生年月日"
          class="w-full border border-gray-300 rounded-md px-4 py-3 text-gray-700"
        />
        <p class="text-xs text-gray-500 mt-1">* 登録後は変更できません。誤って登録されますとその後のやり取りができなくなる可能性があります。</p>
        <p v-if="form.errors.birthday" class="text-red-500 text-sm mt-1">{{ form.errors.birthday }}</p>
      </div>

      <!-- Region -->
      <div>
        <select
          v-model="form.region"
          class="w-full border border-gray-300 rounded-md px-4 py-3 text-gray-700"
        >
          <option disabled value="">地域を選択</option>
          <option v-for="region in regions" :key="region">{{ region }}</option>
        </select>
        <p v-if="form.errors.region" class="text-red-500 text-sm mt-1">{{ form.errors.region }}</p>
      </div>

      <!-- Experience -->
      <div>
        <select
          v-model="form.experience"
          class="w-full border border-gray-300 rounded-md px-4 py-3 text-gray-700"
        >
          <option disabled value="">経験年数を選択</option>
          <option v-for="exp in experiences" :key="exp">{{ exp }}</option>
        </select>
        <p v-if="form.errors.experience" class="text-red-500 text-sm mt-1">{{ form.errors.experience }}</p>
      </div>

      <!-- Submit -->
      <button
        type="submit"
        :disabled="form.processing"
        :class="[
          'w-full py-3 rounded-full font-bold transition',
          form.email && form.password && form.password_confirmation && form.birthday && form.region && form.experience
            ? 'bg-pink-400 text-white'
            : 'bg-gray-300 text-gray-500 cursor-not-allowed',
        ]"
      >
        登録する
      </button>

    </form>
  </div>
</template>
