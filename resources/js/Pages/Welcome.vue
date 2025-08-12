<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Welcome" />
  <div
    class="min-h-screen flex flex-col items-center justify-center bg-cover bg-center"
    style="background-image: url('/assets/imgs/back.png');"
  >
    <h1 class="text-white text-4xl font-bold mb-12 drop-shadow-lg">YOASOBI</h1>

    <div class="space-y-4 w-3/4 max-w-sm">
      <Link
        href="/first-step"
        class="block text-center w-full py-3 rounded-full bg-blue-400 text-white font-semibold shadow-md hover:bg-pink-500 transition"
      >
        キャストを探す(男性向け)
      </Link>

      <Link
        href="/advisor-entry?role=advisor"
        class="block text-center w-full py-3 rounded-full bg-pink-400 text-white font-semibold shadow-md hover:bg-pink-500 transition"
      >
        キャストになる(女性向け)
      </Link>
    </div>
  </div>
</template>
