<template>
    <div class="sm:mx-auto sm:w-full sm:max-w-sm bg-white rounded-lg shadow-md p-6">

        <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
            CCEtracker User Login
        </h2>

        <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" @submit="login">
                <div v-if="errorMsg || showMessage"
                    class="py-3 px-5 text-white rounded text-sm flex justify-between items-center relative"
                    :class="errorMsg ? 'bg-red-500' : 'bg-green-500'">
                    <span>{{ errorMsg ? errorMsg : showMessage }}</span>
                    <button @click="clearMessage"
                        class="absolute top-2 right-2 w-5 h-5 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                        address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required="" v-model="user.email"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        <div class="text-sm">
                        </div>
                    </div>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required=""
                            v-model="user.password"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6" />
                    </div>
                </div>

                <div>
                    <button type="submit" :disabled="loading"
                        class="flex w-full justify-center rounded-md bg-yellow-500 px-3 py-1.5 text-sm font-semibold leading-6 text-black shadow-sm hover:bg-yellow-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600"
                        :class="{
                            'cursor-not-allowed': loading,
                            'hover:bg-yellow-300': loading,
                        }">
                        <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">

                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>

                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 8.291V20">
                            </path>
                        </svg>
                        Sign in</button>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" v-model="user.remember"
                            class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded" />
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>

                    </div>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Not a member?
                {{ ' ' }}
                <router-link :to="{ name: 'UserRequestRegister' }"
                    class="font-semibold leading-6 text-yellow-600 hover:text-indigo-500">Request Registration</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import '../../index.css'
import store from "../../store";
import { useRouter } from "vue-router";
import { ref } from "vue";
import { useRoute } from 'vue-router';

const route = useRoute();
const router = useRouter();
const showMessage = ref(route.query.showMessage || "");

const user = {
    email: '',
    password: '',
    remember: false
}
let errorMsg = ref('');
let loading = ref(false);

function clearMessage() {
    errorMsg.value = '';
    showMessage.value = '';

    if (route) {
        const currentQuery = { ...route.query };
        currentQuery.showMessage = undefined;
        router.replace({
            path: route.path,
            query: currentQuery
        });
    }
}

function login(ev) {
    ev.preventDefault();
    loading.value = true;

    store.dispatch('login', user)
        .then(() => {
            loading.value = false;
            router.push({
                name: 'UserDashboard'
            })
        })
        .catch(err => {
            if (err.response && err.response.status === 401) {

                errorMsg.value = "Incorrect email or password.";
            } else {
                errorMsg.value = "Incorrect email or password.";
            }
            loading.value = false;
        })
}

</script>

<style scoped></style>