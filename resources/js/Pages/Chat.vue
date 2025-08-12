<script setup>
import BlockConfirmModal from '@/Components/BlockConfirmModal.vue'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Chat from '@/Components/Chat.vue'
import { onMounted } from 'vue'
import axios from 'axios'

onMounted(async () => {
  try {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
  } catch (e) {
    console.error('CSRF Cookie取得失敗:', e);
  }
});

const props = defineProps({
  friend: Object,
  user: Object,
})

const showBlockModal = ref(false)
const showMenu = ref(false)

const handleMenuClick = () => {
  showMenu.value = !showMenu.value
}

const handleBlock = async () => {
  try {
    const res = await axios.post('/api/block-user', {
    friend_id: props.friend.id,
    }, { withCredentials: true });

    showBlockModal.value = false;
    if (res.data.status === 'ok') {
      alert('このユーザーをブロックしました');
    } else if (res.data.status === 'already_blocked') {
      alert('すでにブロック済みです');
    } else {
      alert('ブロックに失敗しました');
    }
  } catch (e) {
    console.log('❌ axios error:', e);
    alert('通信エラーです');
  }
}

const viewProfile = () => {
  showMenu.value = false
  alert('プロフィール画面へ遷移（仮）')
}
</script>


<template>
  <AppLayout title="Chat Page">
    <div class="py-0">
      <div class="flex items-center mb-2 relative">
        <img
          :src="`/storage/${friend.profile_photo_path}`"
          alt="Friend's profile photo"
          class="w-12 h-12 rounded-full mr-3"
        />
        <div class="font-bold text-xl text-white">
          {{ friend.name }}
        </div>
        <!-- 3点リーダーボタン -->
        <button
          class="absolute right-0 top-1/2 -translate-y-1/2 bg-white rounded-full shadow p-2 transition hover:bg-gray-100"
          @click="handleMenuClick"
        >
          <img src="/assets/imgs/dot3.png" alt="メニュー" class="w-6 h-6" />
        </button>
        <!-- メニュー -->
        <div
          v-if="showMenu"
          class="absolute right-0 mt-12 w-48 bg-white rounded-xl shadow-xl z-50 py-2 animate-fade-in"
        >
          <button @click="showBlockModal = true" class="flex items-center w-full px-4 py-3 hover:bg-gray-100">
            <span class="mr-3">🚫</span>
            ブロックする
          </button>

          <button @click="viewProfile" class="flex items-center w-full px-4 py-3 hover:bg-gray-100">
            <span class="mr-3">👤</span>
            プロフィール見る
          </button>
        </div>
      </div>
        <BlockConfirmModal
            v-if="showBlockModal"
            @close="showBlockModal = false"
            @confirm="handleBlock"
        />
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow-xl sm:rounded-lg">
          <div class="overflow-hidden shadow-sm sm:rounded-lg">
            <!-- チャットボックス -->
            <Chat
              :friend="friend"
              :currentUser="user"
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(-8px);}
  to   { opacity: 1; transform: translateY(0);}
}
.animate-fade-in {
  animation: fade-in 0.15s;
}
</style>
