<script setup>
import axios from "axios";
import { nextTick, onMounted, ref, watch, computed } from "vue";

// 追加！顔写真のURL
const friendProfilePhotoUrl = computed(() => {
    if (!props.friend.profile_photo_path) {
        return '/default-profile.png'; // 顔写真がない場合のデフォルト画像
    }
    return `${window.location.origin}/storage/${props.friend.profile_photo_path}`;
});
const props = defineProps({
    friend: {
        type: Object,
        required: true,
    },
    currentUser: {
        type: Object,
        required: true,
    },
});

const messages = ref([]);
const newMessage = ref("");
const messagesContainer = ref(null);
const isFriendTyping = ref(false);
const isFriendTypingTimer = ref(null);

watch(
    messages,
    () => {
        nextTick(() => {
            messagesContainer.value.scrollTo({
                top: messagesContainer.value.scrollHeight,
                behavior: "smooth",
            });
        });
    },
    { deep: true }
);

const sendMessage = () => {
  if (!newMessage.value.trim() && !selectedImage.value) {
    return; // テキストも画像もないときだけ送信を拒否
  }

  const formData = new FormData();
  formData.append('message', newMessage.value);
  if (selectedImage.value) {
    formData.append('image', selectedImage.value);
  }

  axios.post(`/messages/${props.friend.id}`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
    .then((response) => {
      messages.value.push(response.data);
      newMessage.value = '';
      selectedImage.value = null;
      previewUrl.value = null;
      if (objectUrl) {
        URL.revokeObjectURL(objectUrl);
        objectUrl = null;
      }
    })
    .catch((error) => {
      console.error("❌ メッセージ送信失敗:", error);
    });
};



const sendTypingEvent = () => {
    Echo.private(`chat.${props.friend.id}`).whisper("typing", {
        userID: props.currentUser.id,
    });
};

onMounted(() => {
    axios.get(`/messages/${props.friend.id}`).then((response) => {
        console.log(response.data);
        messages.value = response.data;
    });

    Echo.private(`chat.${props.currentUser.id}`)
        .listen("MessageSent", (response) => {
            messages.value.push(response.message);
        })
        .listenForWhisper("typing", (response) => {
            isFriendTyping.value = response.userID === props.friend.id;

            if (isFriendTypingTimer.value) {
                clearTimeout(isFriendTypingTimer.value);
            }

            isFriendTypingTimer.value = setTimeout(() => {
                isFriendTyping.value = false;
            }, 1000);
        });
});
const formatTime = (timestamp) => {
    if (!timestamp) return '';
    const date = new Date(timestamp);
    const month = (date.getMonth() + 1).toString(); // 0月始まりなので +1
    const day = date.getDate().toString();
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    return `${month}/${day} ${hours}:${minutes}`;
};

const selectedImage = ref(null);

const previewUrl = ref(null);
let objectUrl = null;



import { onBeforeUnmount } from 'vue';

onBeforeUnmount(() => {
  if (objectUrl) {
    URL.revokeObjectURL(objectUrl);
  }
});

function handleFileChange(event) {
  const file = event.target.files[0];
  if (file && file.type.startsWith('image/')) {
    if (objectUrl) {
      URL.revokeObjectURL(objectUrl);
    }
    selectedImage.value = file;
    objectUrl = URL.createObjectURL(file);
    previewUrl.value = objectUrl;

    // ✅ nextTick で selectedImage.value 更新後に送信
    nextTick(() => {
      sendMessage();
    });
  } else {
    selectedImage.value = null;
    previewUrl.value = null;
    if (objectUrl) {
      URL.revokeObjectURL(objectUrl);
      objectUrl = null;
    }
  }
}


</script>

<template>
    <div class="bg-black/5 text-white min-h-full">
      <!-- メッセージ一覧 -->
      <div class="flex flex-col justify-end" style="height:calc(100vh - 400px)">
        <div ref="messagesContainer" class="p-4 overflow-y-auto max-h-fit">
          <div
            v-for="message in messages"
            :key="message.id"
            class="flex flex-col mb-4"
          >
            <!-- 送信時間 -->
            <div
              :class="message.sender_id === currentUser.id ? 'text-right mr-0' : 'text-left ml-10'"
              class="text-xs text-black mb-1"
            >
              {{ formatTime(message.created_at) }}
            </div>
  
            <!-- メッセージ本体 -->
            <div class="flex items-center w-full">
              <!-- 自分のメッセージ -->
              <div
                v-if="message.sender_id === currentUser.id"
                class="p-2 ml-auto text-white bg-blue-500 rounded-lg"
              >
                <div v-if="message.text" style="white-space: pre-wrap;">
                  {{ message.text }}
                </div>
                <div v-if="message.image_url" class="mt-2">
                  <img :src="message.image_url" alt="sent image" class="max-h-40 rounded" />
                </div>
              </div>
  
              <!-- 相手のメッセージ -->
              <div v-else class="flex items-start">
                <img
                  :src="friendProfilePhotoUrl"
                  alt="Friend's profile photo"
                  class="w-8 h-8 rounded-full mr-2"
                />
                <div class="p-2 bg-gray-700 rounded-lg">
                  <div v-if="message.text" style="white-space: pre-wrap;">
                    {{ message.text }}
                  </div>
                  <div v-if="message.image_url" class="mt-2">
                    <img :src="message.image_url" alt="received image" class="max-h-40 rounded" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- 入力・送信部分 -->
      <div class="flex flex-col p-2">
        <!-- 入力欄 -->
        <div class="flex items-center mb-2">
          <textarea
            v-model="newMessage"
            @keydown="sendTypingEvent"
            @keyup.enter.shift="sendMessage"
            placeholder="メッセージを入力..."
            class="flex-1 px-2 py-1 border rounded-lg text-black"
          ></textarea>
          <button
            @click="sendMessage"
            class="px-4 py-1 ml-2 text-white bg-blue-500 rounded-lg"
          >
            送信
          </button>
        </div>
  
        <!-- 画像プレビュー -->
        <!-- ✅ 修正済みの computed を使って安全にアクセス -->
        <div v-if="previewUrl" class="mb-2">
        <img :src="previewUrl" alt="プレビュー画像" class="max-h-40 rounded" />
        </div>

  
        <!-- 画像ファイル選択 -->
        <div class="text-sm">
          <input type="file" accept="image/*" @change="handleFileChange" />
        </div>
  
        <!-- タイピングインジケータ -->
        <small v-if="isFriendTyping" class="text-gray-400">
          {{ friend.name }} が入力中...
        </small>
      </div>
    </div>
  </template>
  

<style scoped>

</style>
