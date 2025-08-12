<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import AdvisorGuideModal from '@/Components/AdvisorGuideModal.vue';

defineProps({
    title: String,
});

const showAdvisorGuideModal = ref(false);

function openAdvisorGuideModal(event) {
  event.preventDefault(); // 通常遷移を止める
  showAdvisorGuideModal.value = true;
}

function gotoAdvisorProfile() {
  // 必要ならここでInertia遷移
  // router.visit('/advisor/profile/edit');
  showAdvisorGuideModal.value = false;
}

const showingNavigationDropdown = ref(false);

/*
const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};
*/
const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
  <div class="flex flex-col h-screen overflow-hidden bg-[url('/assets/imgs/back.png')] bg-cover bg-center">
    <Head :title="title" />
    <Banner />

    <!-- ヘッダー固定 -->
    <nav class="bg-transparent border-b border-gray-100 shrink-0 bg-[url('/assets/imgs/back.png')] bg-cover bg-center">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <div class="flex items-center">
            <img src="/assets/imgs/h01.png" class="w-10 h-10 rounded-full bg-white p-1" alt="logo" />
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
              <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                Dashboard
              </NavLink>
            </div>
          </div>

          <!-- 中央 YOASOBI ロゴ文字 -->
          <div class="absolute left-1/2 transform -translate-x-1/2 text-center">
            <span class="text-white font-bold text-xl tracking-wide">YOASOBI</span>
          </div>

          <div class="hidden sm:flex sm:items-center sm:ms-6">
            <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
              <template #trigger>
                <span class="inline-flex rounded-md">
                  <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700">
                    {{ $page.props.auth.user.current_team.name }}
                    <svg class="ms-2 -me-0.5 size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                  </button>
                </span>
              </template>
              <template #content>
                <!-- チーム設定など -->
              </template>
            </Dropdown>

            <Dropdown align="right" width="48">
              <template #trigger>
                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none">
                  <img class="size-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
                </button>
              </template>
              <template #content>
                <DropdownLink :href="route('profile.show')">
                  Profile
                </DropdownLink>
                <form @submit.prevent="logout">
                  <DropdownLink as="button">Log Out</DropdownLink>
                </form>
              </template>
            </Dropdown>
          </div>

          <!-- ハンバーガーメニュー -->
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

      <!-- モバイル用メニュー -->
      <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
            Dashboard
          </ResponsiveNavLink>
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
          <div class="flex items-center px-4">
            <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
            <div class="ml-3">
              <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
              <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
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

    <!-- スクロール可能なメイン領域 -->
    <main class="flex-1 overflow-y-hidden py-6 bg-transparent">
      <slot />
    </main>

    <!-- 固定フッター -->
    <footer class="fixed bottom-0 left-0 w-full z-50 bg-white border-t border-gray-200">
      <div class="flex justify-around items-center h-[60px] text-gray-400">
        <Link :href="route('dashboard')">
          <img src="/assets/imgs/f1.png" class="w-8 h-8" />
        </Link>
        <Link href="/CategoryList">
          <img src="/assets/imgs/f2.png" class="w-8 h-8" />
        </Link>
        <Link href="/likes">
          <img src="/assets/imgs/f3.png" class="w-8 h-8" />
        </Link>
        <Link href="/likes">
          <img src="/assets/imgs/f4.png" class="w-8 h-8" />
        </Link>
        <a href="/advisor/profile/edit" @click="openAdvisorGuideModal">
          <img src="/assets/imgs/f5.png" class="w-8 h-8" />
        </a>
      </div>
      <AdvisorGuideModal
        :show="showAdvisorGuideModal"
        @close="showAdvisorGuideModal = false"
        @confirm="gotoAdvisorProfile"
      />
    </footer>
  </div>
</template>
