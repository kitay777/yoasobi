<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'

function getRoleParam() {
  const searchParams = new URLSearchParams(usePage().url.split('?')[1] || '')
  return searchParams.get('role') || 'cast'
}

</script>

<template>
  <Head title="アドバイザーになる" />
  <div
    class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-200 via-pink-200 to-purple-200"
  >
    <h1 class="text-white text-4xl font-bold mb-12 drop-shadow-lg">YOASOBI</h1>

    <div class="space-y-4 w-3/4 max-w-sm">
      <Link
        :href="`/advisor-intro?role=${getRoleParam()}`"
        class="w-full py-3 rounded-full bg-white text-gray-800 font-semibold shadow-md hover:bg-gray-100 transition text-center block"
      >
        はじめての方
      </Link>


      <Link
        href="/dashboard"
        class="w-full py-3 rounded-full bg-gray-800 text-white font-semibold shadow-md hover:bg-gray-700 transition text-center block"
      >
        ログイン
      </Link>
    </div>
  </div>
</template>
