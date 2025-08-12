<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AdvisorBottomNav from '@/Components/AdvisorBottomNav.vue'
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'


// propsでadvisorを受け取る（コントローラで渡したやつ！）
const props = defineProps({
  advisor: Object,
})

// タブ制御
const tab = ref('posts')

// 投稿データ
const posts = ref([])
const postsPage = ref(1)
const postsLastPage = ref(1)
const loadingPosts = ref(false)

// メディアデータ
const media = ref([])
const mediaPage = ref(1)
const mediaLastPage = ref(1)
const loadingMedia = ref(false)

// 投稿の取得
const loadPosts = async (page = 1) => {
  loadingPosts.value = true
  try {
    const res = await axios.get(`/api/advisors/${props.advisor.id}/posts?page=${page}`)
    posts.value = (page === 1 ? res.data.data : [...posts.value, ...res.data.data])
    postsPage.value = res.data.current_page
    postsLastPage.value = res.data.last_page
  } finally {
    loadingPosts.value = false
  }
}
const loadMorePosts = () => {
  if (postsPage.value < postsLastPage.value && !loadingPosts.value) {
    loadPosts(postsPage.value + 1)
  }
}

// メディアの取得
const loadMedia = async (page = 1) => {
  loadingMedia.value = true
  try {
    const res = await axios.get(`/api/advisors/${props.advisor.id}/media?page=${page}`)
    media.value = (page === 1 ? res.data.data : [...media.value, ...res.data.data])
    mediaPage.value = res.data.current_page
    mediaLastPage.value = res.data.last_page
  } finally {
    loadingMedia.value = false
  }
}
const loadMoreMedia = () => {
  if (mediaPage.value < mediaLastPage.value && !loadingMedia.value) {
    loadMedia(mediaPage.value + 1)
  }
}

// プロフィール（コントローラから渡したadvisorそのもの！）
const profile = computed(() => props.advisor)

// タブ初期表示で投稿ロード
onMounted(() => {
  loadPosts()
  document.body.style.overflow = 'hidden'
  document.documentElement.style.overflow = 'hidden'
})
onBeforeUnmount(() => {
  document.body.style.overflow = ''
  document.documentElement.style.overflow = ''
})

const identityStatus = computed(() => profile.value.identity_status)


</script>


<style>
/* 投稿エリアだけ縦スクロール可 */
.box {
  width: 100%;
  height: 300px;               /* 高さは画面サイズに合わせて調整（例: 300px）*/
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}
</style>

<template>
  <AppLayout title="マイページ">
   <div class="relative pb-24 bg-transparent overflow-x-hidden overflow-y-hidden h-screen">
      <!-- カバー画像を横幅いっぱい -->
      <div class="w-full relative">
        <img
          :src="`/storage/${profile.cover_photo_path}`"
          alt="カバー画像"
          class="w-full h-[200px] object-cover"
          style="min-height:120px; max-height:300px;"
        />
      </div>
      <!-- 上半分は透明、画像の中央より下から白い背景 -->
      <div
        class="absolute left-0 w-full bg-white rounded-t-3xl z-0"
        :style="{
          top: '200px',           // 画像の中央位置（w-28 → 7rem/2=56pxくらい画像がはみ出す）
          height: 'calc(100vh - 120px)',
        }"
      ></div>

      <!-- プロフィール画像を上に重ねて配置 -->
      <div class="relative flex flex-col items-center z-10" style="margin-top: -90px; z-index: 10;">
        <div class="mt-8"></div>
        <div class="relative w-28 h-28 flex items-center justify-center">
          <img
            :src="`/storage/${profile.profile_photo_path}`"
            alt="プロフィール画像"
            class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg"
            style="z-index: 1;"
          >
        </div>
        
        <div class="mt-2 text-xl font-semibold text-gray-800">{{ profile.name }}</div>
        <div v-if="identityStatus === 'pending'" class="mt-1 bg-yellow-400 text-xs rounded-full px-3 py-1 text-black font-bold">本人確認 審査中</div>
        <div v-else-if="identityStatus === 'approved'" class="mt-1 bg-green-200 text-xs rounded-full px-3 py-1 text-green-700">本人確認 済み</div>
        <div v-else-if="identityStatus === 'rejected'" class="mt-1 bg-red-200 text-xs rounded-full px-3 py-1 text-red-600">本人確認 却下・再申請をお願いします</div>
        <div v-else class="mt-1 bg-gray-300 text-xs rounded-full px-3 py-1 text-gray-700">本人確認 未申請</div>
      </div>  
      
      <!-- ====== 報酬タブ ====== -->
      <div class="relative z-10 w-full mt-3 shadow-sm border-t border-b border-purple-200 bg-white">
        <div class="bg-purple-300 text-white text-sm px-4 py-2 font-semibold">報酬</div>
        <div class="flex items-center justify-around py-5">
          <div class="flex flex-col items-center flex-1 border-r border-gray-200">
            <div class="text-xs text-gray-600">所持ポイント</div>
            <div class="text-2xl font-bold text-purple-500 mt-1">3210P</div>
          </div>
          <div class="flex flex-col items-center flex-1">
            <div class="text-xs text-gray-600">サブスク報酬</div>
            <div class="text-2xl font-bold text-purple-500 mt-1">¥3210</div>
          </div>
        </div>
        <div class="flex justify-center pb-4">
          <Link
            href="/rewards"
            class="bg-gradient-to-r from-pink-400 to-pink-200 text-white rounded-full px-6 py-2 font-semibold shadow"
          >
            報酬履歴・申請
          </Link>
        </div>
      </div>

     <!-- ====== 投稿/メディアタブ ====== -->
      <div class="relative z-10 w-full mt-3">
      <div class="mt-6 px-4 flex border-b">
      <button
        class="flex-1 py-2 text-center border-b-2"
        :class="tab === 'posts' ? 'border-purple-500 text-purple-600 font-semibold' : 'text-gray-400'"
        @click="tab = 'posts'; loadPosts()"
      >投稿</button>
      <button
        class="flex-1 py-2 text-center border-b-2"
        :class="tab === 'media' ? 'border-purple-500 text-purple-600 font-semibold' : 'text-gray-400'"
        @click="tab = 'media'; loadMedia()"
      >メディア</button>
    </div>
    <!-- 投稿リスト -->
    <div
      v-show="tab === 'posts'"
      class="px-4 overflow-y-auto max-h-96 mt-4 space-y-4"
      style="scroll-behavior: smooth;"
    >
      <div v-for="post in posts" :key="post.id" class="bg-gray-100 p-3 rounded shadow">
        <p class="text-sm text-gray-800 whitespace-pre-line">{{ post.content }}</p>
        <img v-if="post.image_url" :src="post.image_url" class="mt-2 rounded max-h-48 object-contain" />
      </div>
      <div v-if="postsPage < postsLastPage" class="text-center mt-4">
        <button @click="loadMorePosts" class="text-purple-600 font-bold">もっと見る</button>
      </div>
    </div>
    <!-- メディアリスト -->
    <div
      v-show="tab === 'media'"
      class="px-4 overflow-y-auto max-h-96 mt-4 space-y-4"
      style="scroll-behavior: smooth;"
    >
      <div v-for="item in media" :key="item.id" class="bg-gray-100 p-3 rounded shadow flex flex-col items-center">
        <div class="font-bold mb-1 text-center">{{ item.title }}</div>
        <img
          v-if="item.media_type === 'image'"
          :src="item.media_url"
          class="rounded mb-2 max-h-48 object-contain"
        />
        <video
          v-else-if="item.media_type === 'video'"
          :src="item.media_url"
          controls
          class="rounded mb-2 max-h-48 bg-black"
        />
      </div>
      <div v-if="mediaPage < mediaLastPage" class="text-center mt-4">
        <button @click="loadMoreMedia" class="text-purple-600 font-bold">もっと見る</button>
      </div>
    </div>
      </div>

      <!-- ====== 黒いカスタムフッター ====== -->
    </div>
  </AppLayout>
</template>
