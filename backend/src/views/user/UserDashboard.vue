<template>
    <PageComponent header="CCE Dean’s Office Document Management System">
        <div class="text-3xl font-bold sm:px-6 lg:px-16 hidden sm:block">
            MANAGE DOCUMENTS EASILY
        </div>

        <div class="text-xl mt-5 ml-16 font-medium">
            Streamline your deliverables, maximize productivity.
        </div>

        <SuccessBanner />

        <div class="ml-16 mt-6">
            <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
                Deadlines
            </div>

            <div v-if="loading" class="flex justify-center py-4">

                <div class="loader ease-linear rounded-full border-4 border-t-4 border-yellow-500 h-12 w-12 mr-2"></div>
                <div class="text-xl font-semibold text-gray-600">Fetching deadlines...</div>
            </div>

            <div v-else-if="deadlines.length === 0 || filteredDeadlines.length === 0"
                class="text-xl font-semibold text-gray-600 mt-4 ml-44">
                No deadlines
            </div>

            <table v-else class="custom-table-width bg-white shadow-md rounded-b-lg overflow-hidden">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="py-2 px-4 text-left">Deadline Date</th>
                        <th class="py-2 px-4 text-left">Deliverable Type</th>
                        <th class="py-2 px-4 text-left">Action</th>

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="filteredDeadline in slicedDeadlines" :key="filteredDeadline.id">
                        <td class="py-2 px-4 text-left">{{ formatDate(filteredDeadline.deadline) }}</td>
                        <td class="py-2 px-4 text-left">{{ filteredDeadline.deliverable_type }}</td>

                        <td class="py-2 px-4 text-left">
                            <div class="w-20 rounded-2xl py-1 text-center cursor-pointer text-sm font-semibold"
                                :style="{ 'background-color': getButtonColor(filteredDeadline) }"
                                @click="handleButtonClick(filteredDeadline)">
                                <div class="hover:text-white">{{ getButtonText(filteredDeadline) }}</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-1 ml-16 flex items-center justify-between">
            <div>
                <span class="text-gray-600 text-sm">Page {{ currentPage }} of {{ totalPages }}</span>
            </div>
            <div class="flex items-center">
                <button @click="setPage(1)" :disabled="currentPage === 1"
                    class="px-2 py-1 rounded-md bg-gray-200 cursor-pointer">«</button>
                <button @click="setPage(currentPage - 1)" :disabled="currentPage === 1"
                    class="px-2 py-1 rounded-md bg-gray-200 cursor-pointer">‹</button>
                <button @click="setPage(currentPage + 1)" :disabled="currentPage === totalPages"
                    class="px-2 py-1 rounded-md bg-gray-200 cursor-pointer">›</button>
                <button @click="setPage(totalPages)" :disabled="currentPage === totalPages"
                    class="px-2 py-1 rounded-md bg-gray-200 cursor-pointer">»</button>
            </div>
        </div>
    </PageComponent>
</template>
  
<script setup>
import PageComponent from '../../components/PageComponent.vue';
import SuccessBanner from '../../components/SuccessBanner.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';


const router = useRouter();

const store = useStore();
const currentUser = store.state.user.data;
const loggedInUserProgram = currentUser.program;
const loggedInUserId = currentUser.id;
const loggedInUserStatus = currentUser.status;
const documentId = '';

const currentPage = ref(1);
const totalPages = computed(() => filteredDeadlines.value ? Math.ceil(filteredDeadlines.value.length / 5) : 0);

const setPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const itemsPerPage = 6;

const slicedDeadlines = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredDeadlines.value.slice(start, end);
});

const uploads = ref([]);
const deadlines = ref([]);
const deadline_Id = ref('');
const loading = ref(true);

const getButtonColor = (deadline) => {
    const hasUploadedFile = uploads.value.some((upload) => upload.deadline_id === deadline.id);
    return hasUploadedFile ? '#318947' : '#CFAE46';
};

const getButtonText = (deadline) => {
    const hasUploadedFile = uploads.value.some((upload) => upload.deadline_id === deadline.id);
    return hasUploadedFile ? 'TRACK' : 'COMPLY';
};

const handleButtonClick = (deadline) => {
    const upload = uploads.value.find((upload) => upload.deadline_id === deadline.id);


    if (upload) {
        const documentId = upload.id;

        router.push({ name: 'UserDetailedTracking', params: { documentId } });
        console.log(documentId);
    } else {

        router.push({ name: 'UploadDeliverables', params: { deadlineId: deadline.id } });
    }
};

const filteredDeadlines = computed(() => {
    return deadlines.value.filter((deadline) => !deadline_Id.value.includes(deadline.id));
});


const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString();
};

const fetchUploads = async () => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/get-uploaded-userId/${loggedInUserId}`);
        uploads.value = response.data;
        console.log(uploads);
        loading.value = false;
    } catch (error) {
        console.error('Error fetching uploads:', error);
    }
    loading.value = false;
};
const fetchDeadlines = async () => {
    try {
        console.log('User status:', loggedInUserStatus);
        if (currentUser.status === 'active') {
            const response = await axios.get(`http://127.0.0.1:8000/api/get-deadlines/${loggedInUserProgram}`);
            deadlines.value = response.data;
            console.log(deadlines);
        } else {

            deadlines.value = [];
        }
        loading.value = false;
    } catch (error) {
        console.error('Error fetching deadlines:', error);
        loading.value = false;
    }
};

const fetchApprovedDeadlines = async () => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/get-approved-deadlines/${loggedInUserId}`);
        deadline_Id.value = response.data;
        console.log(deadline_Id);
        loading.value = false;
    } catch (error) {
        console.error('Error fetching approved deliverables:', error);
    }
    loading.value = false;
};

onMounted(() => {
    fetchDeadlines();
    fetchUploads();
    fetchApprovedDeadlines();
});
</script>
  
<style>
.custom-table-width {
    width: 1000px;
}
</style>
