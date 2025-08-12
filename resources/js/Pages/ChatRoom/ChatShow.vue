<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Echo from 'laravel-echo'

const props = defineProps({
  room: Object,
  partnerUser: Object,
})

const user = usePage().props.auth.user
const messages = ref(props.room.messages)
const content = ref('')
const chatBoxRef = ref(null)

const scrollToBottom = () => {
  nextTick(() => {
    chatBoxRef.value.scrollTop = chatBoxRef.value.scrollHeight
  })
}

const sendMessage = async () => {
  if (!content.value.trim()) return

  const response = await axios.post('/chat/message', {
    target_user_id: props.partnerUser.id,
    content: content.value,
  })

  // 自分のメッセージを即時表示
  messages.value.push({
    id: Date.now(), // 仮ID
    content: content.value,
    sender: {
      id: user.id,
      name: user.name,
    },
    read_at: null,
    created_at: new Date().toISOString(),
  })

  content.value = ''
  scrollToBottom()
}

onMounted(() => {
  scrollToBottom()

  window.Echo.channel(`chat.${props.room.id}`)
    .listen('MessageSent', (e) => {
      console.log('[受信] メッセージ:', e)
      messages.value.push({
        ...e,
        sender: {
          id: e.sender.id,
          name: e.sender.name,
        },
      })
      scrollToBottom()
    })
})

</script>

<template>
  <AppLayout :title="`チャット - ${partnerUser.name}`">
    <div class="flex flex-col h-[calc(100vh-80px)] p-4 bg-gray-100">
      <!-- ヘッダー -->
      <div class="text-xl font-bold mb-4">
        チャット相手: {{ partnerUser.name }} ({{ partnerUser.role }})
      </div>

      <!-- メッセージ一覧 -->
      <div
        ref="chatBoxRef"
        class="flex-1 overflow-y-auto bg-white rounded-lg shadow p-4 space-y-2"
      >
        <div
          v-for="msg in messages"
          :key="msg.id"
          :class="[
            'p-2 rounded w-fit max-w-xs break-words',
            msg.sender.id === user.id ? 'ml-auto bg-blue-100' : 'mr-auto bg-gray-200',
          ]"
        >
          <div class="text-sm text-gray-700">
            {{ msg.sender.name }}
          </div>
          <div>{{ msg.content }}</div>
          <div class="text-xs text-gray-500 text-right">
            {{ new Date(msg.created_at).toLocaleTimeString() }}
            <span v-if="msg.read_at" class="text-green-500 ml-1">✔︎既読</span>
          </div>
        </div>
      </div>

      <!-- メッセージ送信欄 -->
      <div class="mt-4 flex">
        <input
          v-model="content"
          @keydown.enter="sendMessage"
          class="flex-1 border rounded-l px-3 py-2 focus:outline-none"
          placeholder="メッセージを入力"
        />
        <button
          @click="sendMessage"
          class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600"
        >
          送信
        </button>
      </div>
    </div>
  </AppLayout>
</template>
