<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
const props = defineProps({
  user: Object,
  point: Number,
})
const menus = [
  { label: 'ヘルプ',        img: '/assets/imgs/ycmenu01.png', href: '/help' },
  { label: 'プロフィール編集', img: '/assets/imgs/ycmenu02.png', href: '/cast/profile/edit' },
  { label: 'クレジットカード登録', img: '/assets/imgs/ycmenu03.png', href: '/credit' },
  { label: 'サブスク登録',   img: '/assets/imgs/ycmenu04.png', href: '/subscriptions' },
  { label: '通知設定',      img: '/assets/imgs/ycmenu05.png', href: '/tuchi' },
  { label: '運用ガイド',    img: '/assets/imgs/ycmenu06.png', href: '/unyo' },
  { label: '運営問い合わせ', img: '/assets/imgs/ycmenu07.png', href: '/toiawase' },
  { label: 'ブロック一覧',   img: '/assets/imgs/ycmenu08.png', href: '/blocked-users' },
]
const handleAvatarChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.avatar = file
    previewUrl.value = URL.createObjectURL(file)
  }
}
</script>

<template>
  <AppLayout title="マイページ">
  <div class="bg-[#f6f7fa] min-h-screen pb-24">
    <!-- 上部グラデ＋アイコン -->
    <div class="relative w-full h-40 bg-gradient-to-r from-pink-200 via-purple-200 to-blue-200 rounded-b-3xl shadow-lg">

      <div class="flex flex-col items-center justify-center h-full">
        <span class="text-white text-lg font-bold tracking-wider mb-2 drop-shadow">マイページ</span>
        <div class="relative">
          <img
            v-if="user?.profile_photo_path"
            :src="`/storage/${user.profile_photo_path}`"
            class="w-24 h-24 rounded-full border-4 border-white object-cover shadow"
            alt="アバター"
          />
          <div v-else class="w-24 h-24 rounded-full border-4 border-white bg-gray-200 flex items-center justify-center text-3xl text-gray-400">
            <span>?</span>
          </div>
        </div>
        <span class="mt-2 text-white text-lg font-bold drop-shadow">{{ user?.name ?? '未登録' }}</span>
      </div>
    </div>
    <!-- 報酬バー -->
    <div class="mt-2 bg-[#9c90c4] rounded-t-lg px-4 py-2 text-sm text-white font-semibold shadow-sm w-full">
      報酬
    </div>
    <!-- ポイント＆ボタン -->
      <div class="bg-white px-8 py-6 shadow rounded-b-3xl flex flex-col items-center w-full">
        <span class="text-gray-600 text-sm mt-2">所持ポイント</span>
        <span class="text-[2.4rem] font-extrabold text-purple-400 tracking-wide mb-1">{{ user.points ?? 0 }}P</span>

<Link
  href="/points"
  class="w-full py-3 mt-3 rounded-full text-white font-bold text-base text-center"
  style="background: linear-gradient(90deg, #ff93a8 0%, #ffa6ec 100%); display: block;"
>
  ポイント購入
</Link>

        <Link
          href="/points/history"
          class="mt-4 text-blue-600 text-sm underline"
        >
          ポイント購入履歴を見る
        </Link>
      </div>
    <!-- 設定・サポート -->
    <div class="bg-white px-4 py-6 mt-6 rounded-xl shadow w-full max-w-2xl mx-auto">
      <div class="text-center text-lg font-bold mb-6 text-gray-700 tracking-wide">
        設定・サポート
      </div>
      <div class="grid grid-cols-3 gap-y-8 gap-x-2">
        <template v-for="(item, i) in menus" :key="i">
          <a :href="item.href" class="flex flex-col items-center group">
            <img :src="item.img" class="w-30 h-30 mb-2 opacity-80 group-hover:opacity-100 transition" />
          </a>
        </template>
      </div>
    </div>
  </div>
</AppLayout>
</template>
