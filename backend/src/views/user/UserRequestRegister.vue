<template>
    <div class="sm:mx-auto sm:w-full sm:max-w-sm bg-white rounded-lg shadow-md p-6">

        <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
            Request Access to Registration
        </h2>

        <form class="mt-5 space-y-3" @submit="sendRequest">
            <div>
                <label for="fullname" class="block text-sm font-medium leading-6 text-gray-900">Full name</label>
                <div class="mt-2">
                    <input id="fullname" name="name" type="text" autocomplete="name" v-model="requester.name" required
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6" />
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <div class="mt-2">
                    <input id="email" name="email" type="email" autocomplete="email" required v-model="requester.email"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6" />
                </div>
            </div>

            <div>
                <label for="program" class="block text-sm font-medium leading-6 text-gray-900">Program</label>
                <div class="mt-2">
                    <select id="program" name="program" v-model="requester.program" required
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6">
                        <option value="BSIT">BSIT</option>
                        <option value="BSCS">BSCS</option>
                        <option value="BSIS">BSIS</option>
                        <option value="BSEMC">BSEMC</option>
                        <option value="BLIS">BLIS</option>
                        <option value="BMMA">BMMA</option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" :disabled="loading"
                    class="flex w-full justify-center rounded-md bg-yellow-500 mt-5 px-3 py-1.5 text-sm font-semibold leading-6 text-black shadow-sm hover:bg-yellow-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600"
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
                    Request Access</button>
            </div>
        </form>
        <p class="mt-5 text-center text-sm text-gray-500">
            Already have an account?
            {{ ' ' }}
            <router-link :to="{ name: 'UserLogin' }"
                class="font-semibold leading-6 text-yellow-500 hover:text-indigo-500">Login</router-link>
        </p>
    </div>
</template>
  
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';


const requester = {
    name: '',
    email: '',
    program: ''
};

const store = useStore();
const router = useRouter();
const showMessage = ref('');
const loading = ref(false);


const sendRequest = async (event) => {
    event.preventDefault();
    loading.value = true;
    try {
        const response = await axios.post('http://127.0.0.1:8000/api/request-register', requester);
        console.log('API Response:', response.data);

        showMessage.value = "Please wait for an email confirming the approval or disapproval of your registration access by the Administrative Assistant.";

        router.push({
            name: 'UserLogin',
            query: { showMessage: showMessage.value }
        });

    } catch (err) {
        console.error("Error requesting access:", err);
    } finally {
        loading.value = false;
    }
}
</script>

  
<style scoped></style>
  