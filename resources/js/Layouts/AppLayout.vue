<script setup>
import { ref, provide } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import Banner from '@/Components/Banner.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import BlockDialog from '@/Components/BlockDialog.vue'
import AdvisorGuideModal from '@/Components/AdvisorGuideModal.vue'
import AdvisorBottomNav from '@/Components/AdvisorBottomNav.vue'

defineProps({ title: String })

// ログインユーザー情報（未ログイン時はnullになる）
const page = usePage()
console.log('auth:', page.props.auth)
const user = page.props.auth?.user ?? null
console.log('user:', user)

const showAdvisorGuideModal = ref(false)
function openAdvisorGuideModal(event) {
  event.preventDefault()
  showAdvisorGuideModal.value = true
}

const showBlockDialog = ref(false)
const blockDialogOptions = ref({ onConfirm: null })
function openBlockDialog(options = {}) {
  blockDialogOptions.value = { ...options, onConfirm: options.onConfirm || null }
  showBlockDialog.value = true
}
function closeBlockDialog() {
  showBlockDialog.value = false
}
provide('openBlockDialog', openBlockDialog)

const showingNavigationDropdown = ref(false)
const logout = () => { router.post(route('logout')) }

function gotoAdvisorProfile() {
  console.log('user:', user)
  console.log('user.role:', user.role)
  if (!user) return
  if (user.role === 'cast') {
    console.log('遷移: /cast/mypage')
    router.visit('/cast/mypage')
  } else if (user.role === 'advisor') {
    console.log('遷移: /advisor/mypage')
    router.visit('/advisor/mypage')
  } else {
    console.log('遷移: /')
    router.visit('/')
  }
}
</script>

<template>
  <div class="flex flex-col h-screen overflow-hidden bg-[url('/assets/imgs/back.png')] bg-cover bg-center">
    <Head :title="title" />
    <Banner />

    <!-- ヘッダー -->
    <nav class="bg-transparent border-b border-gray-100 shrink-0 bg-[url('/assets/imgs/back.png')] bg-cover bg-center">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <div class="flex items-center">
            <img src="/assets/imgs/h01.png" class="w-10 h-10 rounded-full bg-white p-1" alt="logo" />
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
              <NavLink v-if="user" :href="route('dashboard')" :active="route().current('dashboard')">
                Dashboard
              </NavLink>
            </div>
          </div>
          <div class="absolute left-1/2 transform -translate-x-1/2 text-center">
            <span class="text-white font-bold text-xl tracking-wide">YOASOBI</span>
          </div>
          <div class="hidden sm:flex sm:items-center sm:ms-6">
            <Dropdown v-if="user" align="right" width="48">
              <template #trigger>
                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none">
                  <img class="size-8 rounded-full object-cover" :src="user.profile_photo_url" :alt="user.name" />
                </button>
              </template>
              <template #content>
                <DropdownLink :href="route('profile.show')">Profile</DropdownLink>
                <form @submit.prevent="logout">
                  <DropdownLink as="button">Log Out</DropdownLink>
                </form>
              </template>
            </Dropdown>
          </div>
          <!-- ハンバーガー -->
          <div class="-me-2 flex items-center sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="w-10 h-10 rounded-full bg-white flex items-center justify-center focus:outline-none"
            >
              <img src="/assets/imgs/h02.png" class="w-6 h-6" alt="menu" />
            </button>
          </div>
        </div>
      </div>
      <!-- SPナビ -->
      <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <ResponsiveNavLink v-if="user" :href="route('dashboard')" :active="route().current('dashboard')">
            Dashboard
          </ResponsiveNavLink>
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200" v-if="user">
          <div class="flex items-center px-4">
            <img class="h-10 w-10 rounded-full object-cover" :src="user.profile_photo_url" :alt="user.name" />
            <div class="ml-3">
              <div class="font-medium text-base text-gray-800">{{ user.name }}</div>
              <div class="font-medium text-sm text-gray-500">{{ user.email }}</div>
            </div>
          </div>
          <div class="mt-3 space-y-1">
            <ResponsiveNavLink :href="route('advisor.profile.edit')">アドバイザープロファイル</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('profile.show')">Profile</ResponsiveNavLink>
            <form method="POST" @submit.prevent="logout">
              <ResponsiveNavLink as="button">Log Out</ResponsiveNavLink>
            </form>
          </div>
        </div>
      </div>
    </nav>

    <!-- メイン -->
    <main class="flex-1 overflow-y-auto py-6 bg-transparent" style="margin-bottom: 55px;">
      <slot />
    </main>

    <!-- フッター -->
    
    <template v-if="user && user.role === 'advisor'">
      <AdvisorBottomNav />
    </template>
    <template v-else>
    <footer class="fixed bottom-0 left-0 w-full z-50 bg-white border-t border-gray-200">
      <div class="flex justify-around items-center h-[60px] text-gray-400">
        <Link :href="route('dashboard')">
          <img src="/assets/imgs/f1.png" class="w-8 h-8" />
        </Link>
        <Link href="/CategoryList">
          <img src="/assets/imgs/f2.png" class="w-8 h-8" />
        </Link>
        <Link
          v-if="user && user.role === 'advisor'"
          href="/posts/create"
      
        >
          <img src="/assets/imgs/f3.png" class="w-8 h-8" />
        </Link>
        <div v-else class="w-10 h-10" /> <!-- 空白で中央を保つ -->

        <Link v-if="user" href="/likes">
          <img src="/assets/imgs/f4.png" class="w-8 h-8" />
        </Link>
        <a href="" @click="openAdvisorGuideModal">
          <img src="/assets/imgs/f5.png" class="w-8 h-8" />
        </a>
      </div>
    </footer>
    </template>

    <AdvisorGuideModal
      :show="showAdvisorGuideModal"
      @close="showAdvisorGuideModal = false"
      @confirm="gotoAdvisorProfile"
      @gotoMyPage="gotoAdvisorProfile"
    />
    <!-- グローバルBlockDialog
    <BlockDialog
      :show="showBlockDialog"
      :onConfirm="blockDialogOptions.onConfirm"
      :onClose="closeBlockDialog"
    />
     -->
  </div>
</template>
