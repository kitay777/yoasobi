<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AdvisorBottomNav from '@/Components/AdvisorBottomNav.vue'

const props = defineProps({
  user: Object,
})
// メニュー定義
const menus = [
  { label: 'ヘルプ',        img: '/assets/imgs/yamenu01.png', href: '/help' },
  {
    label: 'プロフィール編集',
    img: '/assets/imgs/yamenu02.png',
    href:
      props.user?.role === 'cast'
        ? '/cast/profile/edit'
        : props.user?.role === 'advisor'
          ? '/advisor/profile/edit'
          : '/profile/edit'
  },
  { label: '本人確認',      img: '/assets/imgs/yamenu03.png', href: '/advisor/verifyintro' },
  { label: '口座情報登録',   img: '/assets/imgs/yamenu04.png', href: '/bank-account' },
  { label: 'ブロック一覧',   img: '/assets/imgs/yamenu05.png', href: '/blocked-users' },
  { label: '通知設定',      img: '/assets/imgs/yamenu06.png', href: '/tuchi' },
  { label: '運用ガイド',    img: '/assets/imgs/yamenu07.png', href: '/unyo' },
  { label: '運営問い合わせ', img: '/assets/imgs/yamenu08.png', href: '/toiawase' },
]
</script>

<template>
  <AppLayout title="設定・サポート">
    <div class="relative pb-24 bg-white min-h-screen">

      <!-- タイトル -->
      <div class="flex items-center justify-center py-4">
        <span class="text-xl font-bold text-gray-700">設定・サポート</span>
      </div>

      <!-- メニューグリッド -->
      <div class="grid grid-cols-3 gap-y-7 gap-x-2 px-6 mt-2">
        <template v-for="(item, i) in menus" :key="i">
          <a :href="item.href" class="flex flex-col items-center group">
            <img :src="item.img" class="w-32 h-32 mb-2 opacity-80 group-hover:opacity-100 transition" />
          </a>
        </template>
      </div>

      <!-- ===== 黒いカスタムフッター（共通） ===== -->
          <AdvisorBottomNav />

    </div>
  </AppLayout>
</template>
