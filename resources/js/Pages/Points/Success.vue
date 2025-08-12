<template>
<AppLayout title="購入完了">
  <div class="p-6 max-w-md mx-auto text-center">
    <h1 class="text-green-600 text-xl font-bold">購入完了</h1>
    <p>{{ points }}ポイントを追加しました！！！</p>
  </div>
  <div class="flex justify-center mt-6">
    <Link href="/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded">ダッシュボードに戻る</Link>
  </div>
</AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({ points: Number })
</script>
