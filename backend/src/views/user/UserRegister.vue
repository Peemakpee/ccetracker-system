<template>
    <div class="sm:mx-auto sm:w-full sm:max-w-sm bg-white rounded-lg shadow-md p-6">

        <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
            CCEtracker User Register
        </h2>
        <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-3" @submit="register">
                <div>
                    <label for="fullname" class="block text-sm font-medium leading-6 text-gray-900">Full name
                    </label>
                    <div class="mt-2">
                        <input id="fullname" name="name" type="text" autocomplete="name" v-model="user.name"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6" />
                    </div>
                    <span class="text-red-600" v-if="isSubmitted && isFullNameInvalid">{{ isFullNameInvalid }}</span>
                </div>
                <div>
                    <label for="program" class="block text-sm font-medium leading-6 text-gray-900">Program</label>
                    <div class="mt-2">
                        <select id="program" name="program" v-model="user.program"
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
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                        address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required="" v-model="user.email"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6" />
                    </div>
                    <span class="text-red-600" v-if="isSubmitted && isEmailInvalid">{{ isEmailInvalid }}</span>
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
                    <span class="text-red-600" v-if="isSubmitted && isPasswordInvalid">{{ isPasswordInvalid }}</span>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password_confirmation"
                            class="block text-sm font-medium leading-6 text-gray-900">Password confirmation</label>
                        <div class="text-sm">
                        </div>
                    </div>
                    <div class="mt-2">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="current-password_confirmation" required="" v-model="user.password_confirmation"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-500 sm:text-sm sm:leading-6" />
                    </div>
                    <span class="text-red-600" v-if="isSubmitted && isPasswordConfirmationInvalid">{{ isPasswordConfirmationInvalid }}</span>
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
                        Sign Up</button>
                </div>
            </form>

            <p class="mt-5 text-center text-sm text-gray-500">
                Already have an account?
                {{ ' ' }}
                <router-link :to="{ name: 'UserLogin' }"
                    class="font-semibold leading-6 text-yellow-500 hover:text-indigo-500">Login</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import store from "../../store";
import { useRouter } from "vue-router";

const signatureFile = ref(null);
const router = useRouter();
const user = {
    name: '',
    email: '',
    program: '',
    password: '',
    password_confirmation: '',

};
const isSubmitted = ref(false);


const isFullNameInvalid = computed(() => {
    return isSubmitted && !user.name ? 'Full name is required' : '';
});
const isEmailInvalid = computed(() => {
    return isSubmitted && !/\S+@\S+\.\S+/.test(user.email) ? 'Please enter a valid email address' : '';
});
const isPasswordInvalid = computed(() => {
    if (!isSubmitted.value) return '';
    
    const password = user.password;
    const hasCapitalLetter = /[A-Z]/.test(password);
    const hasSpecialCharacter = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password);
    const hasNumber = /\d/.test(password);

    if (password.length < 6) {
        return 'Password must be at least 6 characters';
    } else if (!hasCapitalLetter || !hasSpecialCharacter || !hasNumber) {
        return 'Password must have at least 1 capital letter and at least 1 special character';
    } else {
        return '';
    }
});
const isPasswordConfirmationInvalid = computed(() => {
    return isSubmitted && user.password !== user.password_confirmation ? 'Passwords do not match' : '';
});

let loading = ref(false);
async function register(ev) {
    ev.preventDefault();
    isSubmitted.value = true;
    loading.value = true;

    store
        .dispatch('register', user)
        .then((res) => {
            loading.value = false;
            router.push({
                name: 'UserDashboard'
            });
        }).catch(err => {
            loading.value = false;
            console.error(err);
        });

}

</script>

<style scoped></style>