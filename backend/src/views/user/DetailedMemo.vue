<template>
    <PageComponent header="CCE Dean’s Office Document Management System">
        <div class="ml-28">
            <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
                Memo
            </div>
            <div class="relative bg-white rounded-lg shadow-md p-4">
                <iframe :src="googleViewLink" class="w-[700px] h-[700px]" frameborder="0"></iframe>
                <div class="mt-16">
                    <button @click="downloadFile" style="background-color: #CFAE46; opacity: 1"
                        class="absolute bottom-4 right-4 font-bold text-black px-4 py-2 rounded-lg ">
                        Download
                    </button>
                </div>
            </div>
        </div>
    </PageComponent>
</template>
  

<script setup>
import axios from 'axios';
import PageComponent from '../../components/PageComponent.vue';
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const memo = ref([]); 

const googleViewLink = computed(() => {
    const url = memo.value.firebase_url || 'fallback_value';
    return `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
});

const fetchMemoData = (memoId) => {
    axios
        .get(`http://127.0.0.1:8000/api/get-detailed-memo/${memoId}`)
        .then(response => {
            console.log('API Response:', response.data);
            memo.value = response.data; 
            console.log('Firebase URL:', memo.value.firebase_url);
        })
        .catch(error => {
            console.error('An error occurred while fetching data:', error);
        });
};

const downloadFile = () => {
    if (memo.value.firebase_url) {
        window.open(memo.value.firebase_url, '_blank');
    } else {
        console.error('Firebase URL is not available.');
    }
};

onMounted(() => {
    const memoId = route.params.memoId;
    console.log("Memo ID:", memoId);
    if (memoId) {
        fetchMemoData(memoId);
    } else {
        console.error('Document ID not provided');
    }
});
</script>
