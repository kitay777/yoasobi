<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue'

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const showPassword = ref(false)

const goBack = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    // ルートパスなど、任意の場所へ
    window.location.href = '/';
  }
};

</script>

<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-white px-4 py-8">
    <!-- 戻るボタン（画面左上固定） -->
    <button
    type="button"
    class="absolute left-4 top-4 z-10 bg-white/80 rounded-full p-2 shadow hover:bg-gray-100"
    @click="goBack"
    aria-label="戻る"
    >
    <!-- 矢印アイコン（heroicons/outline/arrow-left） -->
    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
    </button>
    <!-- タイトル -->
    <div class="mb-10 mt-6">
      <h1 class="text-2xl font-extrabold text-black bg-yellow-400 rounded-xl shadow-md px-7 py-2 text-center inline-block">
        ログイン
      </h1>
    </div>


    <!-- ログインフォーム -->
    <form @submit.prevent="submit" class="w-full max-w-md space-y-6">
      <!-- メール -->
      <div>
        <input
          v-model="form.email"
          type="email"
          placeholder="メールアドレス"
          class="w-full h-12 px-4 rounded border border-gray-300 bg-gray-50 text-gray-900 focus:outline-none"
          required
        />
      </div>
      <!-- パスワード -->
<div class="relative">
  <input
    v-model="form.password"
    :type="showPassword ? 'text' : 'password'"
    placeholder="パスワード"
    class="w-full h-12 px-4 pr-12 rounded border border-gray-300 bg-gray-50 text-gray-900 focus:outline-none"
    required
  />
  <button
    type="button"
    class="absolute right-4 top-1/2 -translate-y-1/2"
    @click="showPassword = !showPassword"
    tabindex="-1"
  >
    <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 01-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
    <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.974 9.974 0 013.043-4.536M7.57 7.57a9.953 9.953 0 014.43-1.57m4.832 4.833A9.952 9.952 0 0119.542 12c-1.274 4.057-5.064 7-9.542 7-1.038 0-2.045-.162-2.992-.46" />
    </svg>
  </button>
</div>

      <!-- パスワード忘れ -->
      <div class="text-blue-500 text-sm mt-[-12px] mb-2">
        <a :href="route('password.request')" class="hover:underline">パスワードお忘れの方</a>
      </div>
      <!-- ログインボタン -->
      <div>
        <button
          type="submit"
          class="w-full h-12 text-lg font-bold rounded-xl shadow-md transition disabled:opacity-50
                 bg-yellow-400 text-black hover:bg-yellow-300"
          :disabled="form.processing"
        >
          ログイン
        </button>
      </div>
    </form>

    <!-- 新規登録 -->
    <div class="mt-6 mb-2 text-center">
      <a :href="route('register')" class="text-base text-black underline">新規登録はこちらから</a>
    </div>

    <!-- または -->
    <div class="text-gray-400 text-center mb-4 mt-2">または</div>

    <!-- LINEログイン -->
    <div class="w-full max-w-md">
      <a
        href="/login/line"
        class="flex items-center justify-center gap-2 h-14 rounded-full bg-green-500 hover:bg-green-600 shadow-md text-white text-lg font-bold"
      >
        <img src="/assets/imgs/line-logo.png" class="w-7 h-7" alt="LINE" />
        LINEで続ける
      </a>
    </div>
  </div>
</template>


