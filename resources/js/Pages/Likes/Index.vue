<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  likes: Array,  // [{ id, user or cast, last_message, unread_count }]
  role: String
})
</script>

<template>
  <AppLayout title="いいね一覧">
    <div class="px-4 py-6">
      <h1 class="text-2xl font-bold mb-4">
        {{ role === 'cast' ? '承認済アドバイザー' : '承認済キャスト' }}
      </h1>

      <div class="space-y-4">
        <div
          v-for="like in likes"
          :key="like.id"
          class="flex items-center justify-between border-b pb-4"
        >
<div
  v-for="like in likes"
  :key="like.id"
  class="flex items-start border-b pb-4 space-x-4"
>
  <!-- アバター -->
  <img
    :src="(role === 'cast'
            ? like.advisor?.profile_photo_url
            : like.user?.profile_photo_url) ||
          `https://ui-avatars.com/api/?name=${role === 'cast' ? like.advisor?.name : like.user?.name}`"
    alt="avatar"
    class="w-14 h-14 rounded-full object-cover"
  />

  <!-- 中央：名前＋メッセージを縦並びに -->
  <div class="flex-1 flex flex-col">
    <!-- ニックネーム（上段） -->
    <div class="text-sm text-gray-600 font-semibold mb-1">
      {{ role === 'cast' ? like.advisor?.profile_name : like.user?.profile_name }}
    </div>

    <!-- メッセージ本文（下段） -->
    <div class="text-sm text-gray-800 whitespace-pre-line break-words">
      {{ like.last_message ?? 'メッセージはまだありません' }}
    </div>
  </div>

  <!-- 右側：チャットボタンと未読 -->
  <div class="flex flex-col items-end space-y-2">
<Link
  :href="`/chat/${role === 'cast' ? like.advisor?.id : like.user?.id}`"
  class="text-sm text-blue-600 hover:underline"
>
  チャット
</Link>


    <span
      v-if="like.unread_count > 0"
      class="text-xs bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center"
    >
      {{ like.unread_count }}
    </span>
  </div>
</div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>
