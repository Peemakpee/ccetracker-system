<template>
  <PageComponent header="CCE Dean’s Office Document Management System">
    <div class="ml-16">
      <div class="overflow-x-auto">
        <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
          Approved Deliverables
        </div>
        <table class="custom-table-width bg-white shadow-md rounded-b-lg overflow-hidden">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="py-2 px-4 text-left">File Name</th>
              <th class="py-2 px-4 text-left">Deliverable Type</th>
              <th class="py-2 px-4 text-left">Download</th>
              <th class="py-2 px-4 text-left">Date & Time Approved</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="approved in slicedApproved" :key="approved.id">
              <td class="py-2 px-4">{{ approved.file_name }}</td>
              <td class="py-2 px-4">{{ approved.deliverable_type }}</td>
              <td class="py-2 px-4">
                <a :href="approved.firebaseUrl_wSignDean" target="_blank"
                  class="text-blue-500 hover:underline">Download</a>
              </td>

              <td class="py-2 px-4">{{ formatDate(approved.created_at) }}</td>
            </tr>
          </tbody>
        </table>
        <div v-if="approvedDeliverables.length === 0" class="text-xl font-semibold text-gray-600 mt-4 ml-80">
          You have no Approved Deliverables yet
        </div>
      </div>
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
    <div class="ml-16 mt-2">
      <div class="overflow-x-auto">
        <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
          Approved Deliverables with Changes
        </div>
        <table class="custom-table-width bg-white shadow-md rounded-b-lg overflow-hidden">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="py-2 px-4 text-left">File Name</th>
              <th class="py-2 px-4 text-left">Deliverable Type</th>
              <th class="py-2 px-4 text-left">Action</th>
              <th class="py-2 px-4 text-left">Date & Time Approved</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="approved in slicedApprovedChanges" :key="approved.id">
              <td class="py-2 px-4">{{ approved.file_name }}</td>
              <td class="py-2 px-4">{{ approved.deliverable_type }}</td>
              <td class="py-2 px-4">
                <button @click="handleRowClickChange(approved)" class="btn">View</button>
              </td>

              <td class="py-2 px-4">{{ formatDate(approved.created_at) }}</td>
            </tr>
          </tbody>
        </table>
        <div v-if="approvedWithChanges.length === 0" class="text-xl font-semibold text-gray-600 mt-4 ml-64">
          You have no Approved Deliverables with Changes yet
        </div>
      </div>
    </div>
    <div class="mt-1 ml-16 flex items-center justify-between">
      <div>
        <span class="text-gray-600 text-sm">Page {{ currentApprovedPage }} of {{ totalApprovedPages }}</span>
      </div>
      <div class="flex items-center">
        <button @click="setApprovedPage(1)" :disabled="currentApprovedPage === 1"
          class="px-2 py-1 rounded-md bg-gray-200 cursor-pointer">«</button>
        <button @click="setApprovedPage(currentApprovedPage - 1)" :disabled="currentApprovedPage === 1"
          class="px-2 py-1 rounded-md bg-gray-200 cursor-pointer">‹</button>
        <button @click="setApprovedPage(currentApprovedPage + 1)" :disabled="currentApprovedPage === totalApprovedPages"
          class="px-2 py-1 rounded-md bg-gray-200 cursor-pointer">›</button>
        <button @click="setApprovedPage(totalApprovedPages)" :disabled="currentApprovedPage === totalApprovedPages"
          class="px-2 py-1 rounded-md bg-gray-200 cursor-pointer">»</button>
      </div>
    </div>
  </PageComponent>
</template>
  
<script setup>
import axios from 'axios';
import PageComponent from '../../components/PageComponent.vue';
import { useStore } from 'vuex';
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();

const approvedDeliverables = ref([]);
const approvedWithChanges = ref([]);
const store = useStore();
const currentUser = store.state.user.data;
const currentUserId = computed(() => currentUser.id)
const loggedUserId = ref(currentUser.id);

const currentPage = ref(1);
const totalPages = computed(() => approvedDeliverables.value ? Math.ceil(approvedDeliverables.value.length / 5) : 0);

const setPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const itemsPerPage = 5;

const slicedApproved = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return approvedDeliverables.value.slice(start, end);
});

const currentApprovedPage = ref(1);
const totalApprovedPages = computed(() => approvedWithChanges.value ? Math.ceil(approvedWithChanges.value.length / 3) : 0);

const setApprovedPage = (page) => {
  if (page >= 1 && page <= totalApprovedPages.value) {
    currentApprovedPage.value = page;
  }
};

const itemsApprovedPerPage = 3;

const slicedApprovedChanges = computed(() => {
  const start = (currentApprovedPage.value - 1) * itemsApprovedPerPage;
  const end = start + itemsApprovedPerPage;
  return approvedWithChanges.value.slice(start, end);
});

const handleRowClickChange = async (approved) => {
  const documentId = approved.document_id ? approved.document_id : approved.id;

  router.push({ name: 'ViewDetailedChanges', params: { documentId } });
};

const fetchApprovedDeliverables = async () => {
  try {
    const userId = loggedUserId.value;
    const response = await axios.get(`http://127.0.0.1:8000/api/get-approved-deliverables/${userId}`);
    approvedDeliverables.value = response.data;
  } catch (err) {
    console.error('Error fetching memos:', err);
    error.value = 'Error fetching memos.';
  }
};

const fetchApprovedWithChangesSubmissions = async () => {
  try {
    const userId = currentUserId.value;
    const response = await axios.get(`http://127.0.0.1:8000/api/get-approved-changes-files/${userId}`);
    approvedWithChanges.value = response.data;
  } catch (error) {
    console.error('Error fetching files approved by Program Head:', error);
  }
};

const formatDate = (dateTime) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  return new Date(dateTime).toLocaleDateString('en-US', options);
};

onMounted(() => {
  fetchApprovedDeliverables();
  fetchApprovedWithChangesSubmissions();
});
</script>

<style>
.custom-table-width {
  width: 1000px;
}

.btn {
  width: 3rem;
  padding: 0.2rem 0.5rem;
  background-color: #CFAE46;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
  font-size: 0.8rem;
  color: #000;
  text-align: center;
  font-weight: 600;
}

.btn:hover {
  background-color: #E1C787;
  color: #000;
}
</style>
  