<template>
    <div v-if="showBanner" class="success-banner" @click="hideBanner">
        <p class="text-white">Login Successful!</p>
    </div>
</template>
  
<script setup>
import { ref, watch, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore(); 

const showBanner = ref(false);

watch(() => {
    const loginSuccess = store.state.loginSuccess;
    if (loginSuccess) {
        showBanner.value = true;
        setTimeout(() => {
            hideBanner();
        }, 1500);
    }
});

const hideBanner = () => {
    showBanner.value = false;
    store.commit('setLoginSuccess', false);
};

</script>
  
<style scoped>
.success-banner {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    padding: 10px;
    transition: opacity 0.5s;
    opacity: 1;
}

.success-banner.fade-out {
    opacity: 0;
}
</style>
  