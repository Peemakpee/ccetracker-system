<template>
  <PageComponent header="CCE Dean’s Office Document Management System">
    <div>
      <div class="ml-16">
        <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
          Track Deliverables
        </div>
        <table class="custom-table-width bg-white shadow-md rounded-b-lg overflow-hidden">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="px-2 py-2 text-center" style="width: 25%">Submitted Deliverables</th>
              <th class="px-2 py-2 text-center" style="width: 25%">Approved by PH</th>
              <th class="px-2 py-2 text-center" style="width: 25%">Approved by Dean</th>
              <th class="px-2 py-2 text-center" style="width: 25%">For Revision</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(submission, index) in maxRowCount" :key="index">
              <td class="px-2 py-2 text-left border-right">
                <div class="flex items-center justify-between">
                  <div class="file-name-container">
                    <span class="text-sm">{{ filteredPendingSubmissions[index]?.file_name }}</span>
                  </div>
                  <button v-if="filteredPendingSubmissions[index]"
                    @click="handleViewStatusClick(filteredPendingSubmissions[index])" class="btn">View</button>
                </div>
              </td>
              <td class="px-2 py-2 text-left border-right">
                <div class="flex items-center justify-between">
                  <div class="file-name-container">
                    <span class="text-sm">{{ filteredApprovePhSubmissions[index]?.file_name }}</span>
                  </div>
                  <button v-if="filteredApprovePhSubmissions[index]"
                    @click="handleViewStatusClick(filteredApprovePhSubmissions[index])" class="btn">View</button>
                </div>
              </td>
              <td class="px-2 py-2 text-left border-right">
                <div class="flex items-center justify-between">
                  <div class="file-name-container">
                    <span class="text-sm">{{ approvedByDeanSubmissions[index]?.file_name }}</span>
                  </div>
                  <button v-if="approvedByDeanSubmissions[index]"
                    @click="handleViewStatusClick(approvedByDeanSubmissions[index])" class="btn">View</button>
                </div>
              </td>
              <td class="px-2 py-2 text-left">
                <div class="flex items-center justify-between">
                  <div class="file-name-container">
                    <span class="text-sm">{{ combinedToBeModifiedSubmissions[index]?.file_name }}</span>
                  </div>
                  <button v-if="combinedToBeModifiedSubmissions[index]"
                    @click="handleViewStatusClick(combinedToBeModifiedSubmissions[index])" class="btn">View</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="filteredPendingSubmissions.length === 0 &&
          filteredApprovePhSubmissions.length === 0 &&
          approvedByDeanSubmissions.length === 0 &&
          combinedToBeModifiedSubmissions.length === 0" class="text-xl font-semibold text-gray-600 mt-4 ml-96">
          No Submissions yet
        </div>
      </div>
    </div>
  </PageComponent>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import PageComponent from '../../components/PageComponent.vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const router = useRouter();

const store = useStore();
const currentUserId = store.state.user.data;
const loggedInUserId = currentUserId.id;

const currentTab = ref('all');

const pendingSubmissions = ref([]);
const approvedByPhSubmissions = ref([]);
const approvedByDeanSubmissions = ref([]);
const toBeModifiedSubmissions = ref([]);

const toBeModifiedByDeanSubmissions = ref([]);

const maxRowCount = computed(() => {
  const counts = [
    filteredPendingSubmissions.value.length,
    filteredApprovePhSubmissions.value.length,
    approvedByDeanSubmissions.value.length,
    combinedToBeModifiedSubmissions.value.length,
  ];
  return Math.max(...counts);
});

const handleViewStatusClick = (submission) => {
  const documentId = submission.document_id ? submission.document_id : submission.id;
  router.push({ name: 'UserDetailedTracking', params: { documentId } });
};

const filteredPendingSubmissions = computed(() => {
  return pendingSubmissions.value.filter(submission => {
    return submission.status !== 'Approve' && submission.status !== 'To Be Modified';
  });
});

const filteredApprovePhSubmissions = computed(() => {
  console.log(approvedByPhSubmissions.value);
  return approvedByPhSubmissions.value.filter(submission => {
    return submission.status !== 'Approve' && submission.status !== 'To Be Modified';
  });
});

const combinedToBeModifiedSubmissions = computed(() => {
  return [...toBeModifiedSubmissions.value, ...toBeModifiedByDeanSubmissions.value];
});


const formatDateTime = (dateTime) => {
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  };
  return new Date(dateTime).toLocaleDateString(undefined, options);
};

const fetchPendingSubmissions = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/get-pending-files/${loggedInUserId}`);
    pendingSubmissions.value = response.data;
  } catch (error) {
    console.error('Error fetching user\'s pending files:', error);
  }
};

const fetchApprovedByPhSubmissions = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/get-approved-ph-files/${loggedInUserId}`);
    approvedByPhSubmissions.value = response.data;
  } catch (error) {
    console.error('Error fetching files approved by Program Head:', error);
  }
};

const fetchApprovedByDeanSubmissions = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/get-approved-dean-files/${loggedInUserId}`);
    approvedByDeanSubmissions.value = response.data;
  } catch (error) {
    console.error('Error fetching files approved by Program Head:', error);
  }
};

const fetchTbmPhSubmissions = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/get-tbm-ph-files/${loggedInUserId}`);
    toBeModifiedSubmissions.value = response.data;
  } catch (error) {
    console.error('Error fetching files approved by Program Head:', error);
  }
};

const fetchTbmDeanSubmissions = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/get-tbm-dean-files/${loggedInUserId}`);
    toBeModifiedByDeanSubmissions.value = response.data;
  } catch (error) {
    console.error('Error fetching files tagged for revision by Dean:', error);
  }
};

onMounted(() => {
  fetchPendingSubmissions();
  fetchApprovedByPhSubmissions();
  fetchTbmPhSubmissions();
  fetchApprovedByDeanSubmissions();
  fetchTbmDeanSubmissions();
});
</script>

<style scoped>
table {
  border-collapse: collapse;
}

th,
td {
  border-right: 3px solid #e2e8f0;
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
