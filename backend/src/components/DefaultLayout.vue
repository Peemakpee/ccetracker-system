<template>
    <div class="min-h-screen flex">

        <Disclosure as="nav" class="border-r border-gray-500" style="background-color: #343a40;" v-slot="{ open }">

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 w-64">
                <div class="flex-shrink-0 hidden md:block ml-8">
                    <img class="h-32 w-32" src="/images/ccelogo.png" alt="Your Company" />
                </div>

                <div class="hidden md:block">
                    <Menu as="div" class="relative flex justify-center mt-10">
                        <div>
                            <MenuButton
                                class="relative flex max-w-xs items-center rounded-full bg-lemon text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                <span class="absolute -inset-1.5" />
                                <span class="sr-only">Open user menu</span>
                                <img class="h-10 w-10 rounded-full" src="/images/userlogo.jpg" alt="user logo" />
                            </MenuButton>
                        </div>
                        <transition enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95">
                        </transition>
                    </Menu>
                </div>
                <div class="text-white text-xl">
                    <div class="p-1 flex justify-center">
                        <a href="#" class="font-bold hover:text-yellow-500">{{ user.name }}</a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="w-16 rounded-2xl py-1 " style="background-color: #CFAE46; opacity: 1;">
                        <h1 class="text-xs text-center text-black">Faculty</h1>
                    </div>
                </div>
                <div class="flex-col mt-12 mr-4 items-start justify-between">
                    <div class="flex-col items-start">
                        <div class="hidden md:block">
                            <div class="flex flex-col">
                                <router-link v-for="item in navigation" :key="item.name" :to="item.to"
                                    active-class="bg-green-800 text-white"
                                    :class="[this.$route.name === item.to.name ? '' : 'text-white font-semibold hover:bg-white hover:text-black', 'rounded-md px-3 py-2 text-lg font-medium']">{{
                                        item.name }}</router-link>
                            </div>
                            <div class="mt-16 space-y-1 px-2">
                                <button
                                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-500 hover:text-white cursor-pointer"
                                    @click="logout">Sign out</button>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <DisclosureButton
                            class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="absolute -inset-0.5" />
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                            <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                        </DisclosureButton>
                    </div>
                </div>
            </div>
            <DisclosurePanel class="md:hidden">
                <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                    <router-link v-for="item in navigation" :key="item.name" :to="item.to"
                        active-class="bg-green-800 text-white"
                        :class="[this.$route.name === item.to.name ? '' : 'text-white font-semibold hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']">{{
                            item.name }}</router-link>
                </div>
                <div class="border-t border-gray-700 pb-3 pt-4">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="/images/userlogo.jpg" alt="" />
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-white">{{ user.name }}</div>
                            <div class="text-sm font-medium leading-none text-gray-500">{{ user.email }}</div>
                        </div>

                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <DisclosureButton as="a" @click="logout"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-700 hover:text-white cursor-pointer">
                            Sign out</DisclosureButton>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <div class="flex-row">
            <router-view></router-view>
        </div>

    </div>
</template>
  
<script>

import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { useStore } from 'vuex'
import { computed } from 'vue'
import { useRouter } from 'vue-router'

const navigation = [
    { name: 'Dashboard', to: { name: 'UserDashboard' } },
    { name: 'Templates', to: { name: 'UserDeliverables' } },
    { name: 'Approved', to: { name: 'UserApproved' } },
    { name: 'Memos', to: { name: 'UserMemo' } },
    { name: 'Tracker', to: { name: 'UserDTrack' } },
    { name: 'Messages', to: { name: 'UserChat' } },

]
export default {
    components: {
        Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems,
        Bars3Icon, BellIcon, XMarkIcon
    },
    setup() {
        const store = useStore();
        const router = useRouter();

        function logout() {
            store.dispatch('logout')
                .then(() => {
                    router.push({
                        name: 'UserLogin',
                    });
                });
        }
        return {
            user: computed(() => store.state.user.data),
            navigation,
            logout,
        };
    },
};
</script>