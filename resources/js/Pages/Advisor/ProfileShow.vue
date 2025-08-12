<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

// ✅ props の受け取り
const props = defineProps({
  advisor: Object,
  subscription_active: Boolean
})

const posts = ref([])
const currentPage = ref(1)
const lastPage = ref(1)
const advisor = ref({ ...props.advisor })
const showMore = ref(false)
const showSubDialog = ref(false)

const tab = ref('posts') // 'posts' または 'media'
const postsPage = ref(1)
const postsLastPage = ref(1)
const media = ref([])
const mediaPage = ref(1)
const mediaLastPage = ref(1)

const loadPosts = async (page = 1) => {
  const res = await axios.get(`/api/advisors/${props.advisor.id}/posts?page=${page}`)
  posts.value = res.data.data
  postsPage.value = res.data.current_page
  postsLastPage.value = res.data.last_page
}

const loadNext = () => {
  if (currentPage.value < lastPage.value) {
    loadPosts(currentPage.value + 1)
  }
}

// メディア取得
const loadMedia = async (page = 1) => {
  const res = await axios.get(`/api/advisors/${props.advisor.id}/media?page=${page}`)
  media.value = res.data.data
  mediaPage.value = res.data.current_page
  mediaLastPage.value = res.data.last_page
}

const loadMorePosts = () => {
  if (postsPage.value < postsLastPage.value) {
    loadPosts(postsPage.value + 1)
  }
}
const loadMoreMedia = () => {
  if (mediaPage.value < mediaLastPage.value) {
    loadMedia(mediaPage.value + 1)
  }
}

onMounted(() => {
  loadPosts()
  // メディアタブを最初から表示したい場合は loadMedia()
})


// ✅ いいねトグル処理（そのまま）
const toggleLike = () => {
  if (advisor.value.is_liked) {
    axios.delete(`/advisor/${advisor.value.id}/like`)
      .then(() => { advisor.value.is_liked = false })
      .catch(() => alert('解除に失敗しました'))
  } else {
    axios.post(`/advisor/${advisor.value.id}/like`)
      .then(() => { advisor.value.is_liked = true })
      .catch(() => alert('いいねに失敗しました'))
  }
}

const doSubscribe = () => {
  // サブスク登録API呼び出し（Inertia router推奨）
  router.post(`/subscriptions`, {
    advisor_id: advisor.value.id,
    price: advisor.value.subscription_price, // ←これ！
  }, {
    onSuccess: () => {
      showSubDialog.value = false
      window.location.reload()
    }
  })
}

// サブスク登録ボタン押下でモーダル表示
const onSubClick = () => {
  showSubDialog.value = true
}

onSuccess: () => {
  showSubDialog.value = false
  props.subscription_active = true // ←refやreactive化していれば
}

const showSubRequiredDialog = ref(false) // ←サブスク未登録時のチャット警告

const onChatClick = (e) => {
  if (!props.subscription_active) {
    showSubRequiredDialog.value = true
    return false // モーダル出して終わり
  }
  // サブスク有効なら手動で遷移
  router.visit(`/chat/${advisor.value.id}`)
}
</script>

<template>
  <AppLayout :title="advisor.profile_name">
    <div class="bg-white">
      <div class="relative w-full h-64 bg-cover bg-center" :style="`background-image: url('/storage/${advisor.cover_photo_path}')`">
        <div class="absolute -bottom-6 right-4 flex space-x-3 z-10">
          <button @click="toggleLike">
            <img
              :src="advisor.is_liked ? '/assets/imgs/b0001-on.png' : '/assets/imgs/b0001.png'"
              alt="オファー"
              class="w-12 h-12 object-contain"
            />
          </button>
          <a
            :href="`/chat/${advisor.id}`"
            @click.prevent="onChatClick"
          >
            <img src="/assets/imgs/b0002.png" alt="チャット" class="w-12 h-12 object-contain" />
          </a>


        </div>
      </div>

      <div class="px-4 -mt-12 relative text-left">
        <img
          :src="`/storage/${advisor.profile_photo_path}`"
          class="w-24 h-24 rounded-full border-4 border-white object-cover shadow-lg mb-2"
        />
      </div>
<div class="text-xl font-bold ml-1 flex items-center gap-2">
  {{ advisor.profile_name }}
  <span class="text-sm text-gray-500 ml-2">{{ advisor.age }}歳</span>
  <!-- 本人確認バッジ（最小限サイズ・横並び） -->
  <span
    v-if="advisor.identity_status === 'pending'"
    class="ml-1 inline-block bg-yellow-400 text-black text-[10px] px-2 py-0.5 rounded-full font-bold align-middle"
    title="本人確認審査中"
  >審査中</span>
  <span
    v-else-if="advisor.identity_status === 'approved'"
    class="ml-1 inline-block bg-green-200 text-green-700 text-[10px] px-2 py-0.5 rounded-full font-bold align-middle"
    title="本人確認済み"
  >本人済</span>
  <span
    v-else-if="advisor.identity_status === 'rejected'"
    class="ml-1 inline-block bg-red-200 text-red-600 text-[10px] px-2 py-0.5 rounded-full font-bold align-middle"
    title="本人確認却下"
  >却下</span>
  <span
    v-else
    class="ml-1 inline-block bg-gray-300 text-gray-700 text-[10px] px-2 py-0.5 rounded-full font-bold align-middle"
    title="本人確認未申請"
  >未申請</span>
</div>
      <!-- タグ群 -->
      <div class="flex flex-wrap gap-2 px-4 mt-4 justify-start pl-1">
        <span
          v-for="tag in advisor.tags"
          :key="tag"
          class="text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-600"
        >
          {{ tag }}
        </span>
      </div>

      <!-- 自己紹介 -->
      <div class="px-4 mt-4 text-sm text-gray-700 whitespace-pre-line">
        <p v-if="!showMore">
          {{ advisor.bio.slice(0, 100) }}...
          <span class="text-blue-500 cursor-pointer" @click="showMore = true">さらに表示</span>
        </p>
        <p v-else>
          {{ advisor.bio }}
        </p>
      </div>
      <!-- サブスク登録 or 登録済みボタン -->
      <div class="mt-6 text-center px-4">
        <template v-if="props.subscription_active">
          <button
            class="bg-gray-300 text-gray-500 font-bold w-full py-3 rounded-full text-lg cursor-not-allowed shadow"
            disabled
          >
            サブスク登録済みです
          </button>
        </template>
        <template v-else>
          <button
            class="bg-pink-500 text-white font-bold w-full py-3 rounded-full text-lg"
            @click="onSubClick"
          >
            サブスク登録 ¥{{ advisor.subscription_price }}/月
          </button>
        </template>
      </div>
      <!-- タブ切り替え -->
   <!-- タブ切り替え -->
    <div class="mt-6 px-4 flex border-b">
      <button
        class="flex-1 py-2 text-center border-b-2"
        :class="tab === 'posts' ? 'border-purple-500 text-purple-600 font-semibold' : 'text-gray-400'"
        @click="tab = 'posts'; loadPosts()"
      >
        投稿
      </button>
      <button
        class="flex-1 py-2 text-center border-b-2"
        :class="tab === 'media' ? 'border-purple-500 text-purple-600 font-semibold' : 'text-gray-400'"
        @click="tab = 'media'; loadMedia()"
      >
        メディア
      </button>
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


      <div
          v-if="showSubRequiredDialog"
          class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-60"
        >
          <div class="bg-white rounded-3xl shadow-xl px-8 py-10 max-w-[90vw] w-[340px] flex flex-col items-center">
            <div class="text-xl font-bold mb-4 text-center">サブスクライブを購入してください</div>
            <button
              class="w-full py-3 rounded-full font-bold text-gray-400 bg-gray-100 mt-6"
              @click="showSubRequiredDialog = false"
            >
              閉じる
            </button>
          </div>
        </div>
      <!-- サブスク登録ダイアログ -->
      <div
        v-if="showSubDialog"
        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-60"
      >
        <div class="bg-white rounded-3xl shadow-xl px-8 py-10 max-w-[90vw] w-[340px] relative flex flex-col items-center">
          <div class="absolute -top-12 left-1/2 -translate-x-1/2 flex justify-center">
            <div class="bg-pink-300 w-20 h-20 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
              <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="12" cy="12" r="10" fill="white" opacity="0.2"/>
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                  d="M12 8v4m0 4h.01" />
                <path fill="currentColor" d="M12 15.5a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z"/>
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M12 7v3"/>
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
              </svg>
            </div>
          </div>
          <div class="mt-12 text-center">
            <div class="text-xl font-bold mb-4">サブスク登録が必要です</div>
            <button
              class="w-full py-3 rounded-full font-bold text-white text-lg bg-pink-500 mb-4 shadow"
              @click="doSubscribe"
            >
              登録する
            </button>
            <button
              class="w-full py-3 rounded-full font-bold text-gray-400 bg-gray-100"
              @click="showSubDialog = false"
            >
              閉じる
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
