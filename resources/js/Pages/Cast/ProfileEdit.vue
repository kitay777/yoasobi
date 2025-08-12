<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'


const props = defineProps({
  profile: Object, // cast_profilesの内容
  user: Object,    // usersの内容
})

const editField = ref(null)
const prefs = [/* ...都道府県リスト... */]

const previewUrl = ref(props.user?.profile_photo_path
  ? `/storage/${props.user.profile_photo_path}`
  : null
)
const avatarInput = ref(null)

const handleBack = () => {
  if (window.history.length > 1) {
    window.history.back()
  } else {
    // 直接アクセス等で履歴がなければメニューへ
    router.visit('/advisor/menu')
  }
}

const form = useForm({
  name: props.profile?.name || '',
  age: props.profile?.age || '',
  region: props.profile?.region || '',
  experience: props.profile?.experience || '',
  support_request: props.profile?.support_request || '',
  avatar: null,
})

const handleAvatarClick = () => {
  avatarInput.value?.click()
}
const handleAvatarChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.avatar = file
    previewUrl.value = URL.createObjectURL(file)
  }
}

const submitField = () => {
  form.post(route('cast.profile.update'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      editField.value = null
      // 再取得し直す場合はリロードやemitを利用
    }
  })
}
</script>

<template>
<AppLayout title="Cast Profile Edit">
  <form @submit.prevent="submitField" class="max-w-md mx-auto px-4 py-8">
<div class="relative flex items-center justify-center mb-6 h-12">
  <!-- 戻るボタン（絶対位置/左） -->
  <button
    type="button"
    @click="handleBack"
    class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center"
  >
    <img src="/assets/imgs/backicon.png" alt="戻る" class="w-7 h-7 object-contain" />
  </button>
  <!-- タイトルはwidth-autoで中央寄せ（絶対位置に依存しない） -->
  <div class="text-xl font-bold text-center w-full pointer-events-none">
    プロフィール編集
  </div>
  <!-- 右側アイコン（絶対位置/右） -->
  <button
    type="button"
    @click="router.visit('/advisor/menu')"
    class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center"
  >
    <img src="/assets/imgs/seticon.png" alt="設定" class="w-7 h-7 object-contain" />
  </button>
</div>

    <div class="flex flex-col items-center mb-6">
      <div class="relative w-28 h-28">
        <img
          :src="previewUrl"
          class="w-28 h-28 rounded-full object-cover border-2 border-gray-200"
          alt="プロフィール画像"
          @click="handleAvatarClick"
          style="cursor:pointer;"
        />
        <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-90 bg-black bg-opacity-30 transition"
             @click="handleAvatarClick"
             style="cursor:pointer;">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
            d="M15.232 5.232l3.536 3.536M9 11l6 6M8 7l8 8"></path></svg>
        </div>
        <input
          ref="avatarInput"
          type="file"
          accept="image/*"
          class="hidden"
          @change="handleAvatarChange"
        />
      </div>
      <div class="text-center text-xs text-gray-500 mt-1">写真をクリックして変更</div>
    </div>

    <!-- アカウント名（直接編集） -->
    <div class="mb-3">
      <label class="text-sm font-bold">アカウント名</label>
      <input
        type="text"
        v-model="form.name"
        maxlength="20"
        class="border rounded w-full p-2 mt-1 text-right"
        placeholder="入力してください"
      />
    </div>

    <!-- 年齢 -->
<div class="flex items-center justify-between border-b py-2">
  <span class="text-gray-700">年齢</span>
  <template v-if="editField === 'age'">
    <input
      type="number"
      v-model="form.age"
      class="border p-1 w-16 mr-2 text-right"
      min="16" max="99"
      @blur="editField = null"
      @keyup.enter="editField = null"
    />
  </template>
  <template v-else>
    <span class="flex-1 text-right">{{ form.age ? `${form.age}歳` : '-' }}</span>
    <button type="button" @click="editField = 'age'" class="ml-2 text-gray-400 text-lg">&gt;</button>
  </template>
</div>

    <!-- 地域 -->
    <div class="flex items-center justify-between border-b py-2">
      <span class="text-gray-700">地域</span>
      <template v-if="editField === 'region'">
        <select
          v-model="form.region"
          class="border p-1 w-16 mr-2 text-right"
          @blur="editField = null"
          @change="editField = null"
        >
          <option value="">選択</option>
          <option v-for="pref in prefs" :key="pref" :value="pref">{{ pref }}</option>
        </select>
      </template>
      <template v-else>
        <span class="flex-1 text-right">{{ form.region || '-' }}</span>
        <button type="button" @click="editField = 'region'" class="ml-2 text-gray-400 text-lg">&gt;</button>
      </template>
    </div>

    <!-- 経験年数 -->
    <div class="flex items-center justify-between border-b py-2">
      <span class="text-gray-700">経験年数</span>
      <template v-if="editField === 'experience'">
        <input
          type="text"
          v-model="form.experience"
          class="border p-1 w-16 mr-2 text-right"
          @blur="editField = null"
          @keyup.enter="editField = null"
        />
      </template>
      <template v-else>
        <span class="flex-1 text-right">{{ form.experience || '-' }}</span>
        <button type="button" @click="editField = 'experience'" class="ml-2 text-gray-400 text-lg">&gt;</button>
      </template>
    </div>

    <!-- サポート内容 -->
    <div class="mt-5">
      <label class="text-sm font-bold">希望サポート内容</label>
      <textarea
        v-model="form.support_request"
        maxlength="1000"
        rows="4"
        class="border rounded w-full p-2 mt-1 text-left"
        placeholder="希望内容を入力してください"
      />
    </div>

    <!-- 保存ボタン -->
    <button
      type="submit"
      class="w-full mt-8 py-3 rounded-full text-white text-lg font-bold"
      style="background: linear-gradient(90deg, #ff93a8 0%, #ffa6ec 100%);"
    >
      変更保存する
    </button>
  </form>
</AppLayout>
</template>
