<script setup>
defineProps({ profiles: Array })
</script>

<template>
  <div class="p-4 min-h-screen bg-gradient-to-b from-purple-100 to-white space-y-6">
    <h1 class="text-2xl font-bold">アドバイザー一覧</h1>

    <div v-for="profile in profiles" :key="profile.id" class="rounded-xl shadow bg-white overflow-hidden">
      <div class="relative">
        <img :src="`/storage/${profile.cover_path}`" class="w-full h-40 object-cover" />
        <img :src="`/storage/${profile.avatar_path}`" class="w-24 h-24 object-cover rounded-full border-4 border-white absolute -bottom-12 left-4" />
      </div>
      <div class="p-4 pt-12">
        <div class="flex justify-between">
          <h2 class="text-lg font-bold">{{ profile.name }} <span class="text-sm text-gray-500">{{ profile.age }}歳</span></h2>
          <div class="text-sm text-gray-400">{{ profile.region }}</div>
        </div>
        <div class="flex flex-wrap gap-2 mt-2">
          <span
            v-for="tag in (typeof profile.tags === 'string' ? profile.tags.split(',') : profile.tags)"
            :key="tag"
            class="text-xs bg-purple-100 text-purple-800 rounded-full px-2 py-1"
          >{{ tag }}</span>
        </div>
        <p class="text-sm text-gray-700 mt-2">{{ profile.bio }}</p>
        <div class="text-sm mt-3 text-gray-600">
          💬 トーク：¥{{ profile.talk_price }} / 🎫 サブスク：¥{{ profile.subscription_price }}
        </div>
      </div>
    </div>
  </div>
</template>
