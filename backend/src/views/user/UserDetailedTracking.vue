<template>
  <div v-if="successBannerVisible" class="success-banner">
    {{ successMessage }}
  </div>
  <PageComponent header="CCE Dean’s Office Document Management System">
    <div class="ml-16">
      <h1 class="text-3xl font-semibold mb-4">Document Tracking</h1>
      <div class="mb-6 bg-white shadow-lg rounded-lg overflow-hidden">
        <div style="background-color: #CFAE46; opacity: 1" class="text-black font-bold py-2 px-4 rounded-t-lg">
          <h2 class="text-2xl">Tracking Timeline</h2>
        </div>
        <div class="p-12">
          <div class="relative pt-1">
            <div class="flex items-start justify-between">
              <div class="flex-none w-24 flex flex-col items-center">
                <div class="w-12 h-12 rounded-full border-4 border-green-400 flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" fill="#00FF00"></path>
                  </svg>

                </div>
                <div class="mt-2 text-center text-sm">
                  <div class="text-black">Deliverable Submitted</div>
                  <button @click="() => handleFileClick(timeline[0])" style="background-color: #CFAE46; opacity: 1"
                    class="font-bold text-black px-4 py-2 rounded-lg mt-2">
                    Details
                  </button>
                </div>
              </div>
              <div :style="{ 'background-color': getLineColor(0) }" class="h-1 w-80 mt-5"></div>

              <div class="flex-none w-24 flex flex-col items-center">
                <div class="w-12 h-12 rounded-full border-4"
                  :class="{ 'border-green-400': getLineColor(0) === '#00FF00', 'border-red-500': getLineColor(0) === '#FF0000' }">
                  <div class="flex items-center justify-center h-full">
                    <svg v-if="getLineColor(0) === '#00FF00'" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                      viewBox="0 0 24 24">
                      <path d="M0 0h24v24H0z" fill="none" />
                      <path fill="#00FF00" d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" />
                    </svg>
                    <svg v-else-if="getLineColor(0) === '#FF0000'" xmlns="http://www.w3.org/2000/svg" width="30"
                      height="30" viewBox="0 0 24 24" class="text-red-500">
                      <path d="M6 6L18 18M18 6L6 18" stroke="#FF0000" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                  </div>
                </div>
                <div class="mt-2 text-center text-sm" v-if="timeline[1]">
                  <div class="text-black">
                    {{ getLineColor(0) === '#00FF00' ? 'Approved by' : 'For Revision by' }}
                  </div>
                  <div class="text-black">{{ timeline[1].status === 'To Be Modified' ? 'Program Head' : 'Program Head' }}
                  </div>
                  <button @click="() => handleFileClick(timeline[1])" style="background-color: #CFAE46; opacity: 1"
                    class="font-bold text-black px-4 py-2 rounded-lg mt-2">
                    Details
                  </button>
                </div>
              </div>
              <div :style="{ 'background-color': getLineColor(2) }" class="h-1 w-80 mt-5"></div>
              <div class="flex-none w-24 flex flex-col items-center">
                <div class="w-12 h-12 rounded-full border-4" :class="{
                  'border-green-400': timeline[2] && timeline[2].status === 'Approve',
                  'border-red-500': timeline[2] && timeline[2].status === 'To Be Modified',
                  'border-gray-200': !timeline[2]
                }">
                  <div class="flex items-center justify-center h-full">
                    <svg v-if="timeline[2] && timeline[2].status === 'Approve'" xmlns="http://www.w3.org/2000/svg"
                      width="20" height="20" viewBox="0 0 24 24">
                      <path d="M0 0h24v24H0z" fill="none" />
                      <path fill="#00FF00" d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" />
                    </svg>
                    <svg v-if="timeline[2] && timeline[2].status === 'To Be Modified'" xmlns="http://www.w3.org/2000/svg"
                      width="30" height="30" viewBox="0 0 24 24">
                      <path d="M6 6L18 18M18 6L6 18" stroke="#FF0000" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                  </div>
                </div>
                <div class="mt-2 text-center text-sm" v-if="timeline[2]">
                  <div class="text-black">
                    {{ timeline[2].status === 'To Be Modified' ? 'For Revision by' : 'Approved by' }}
                  </div>
                  <div class="text-black">{{ timeline[2].status === 'To Be Modified' ? 'Dean' : 'Dean' }}</div>
                  <button @click="() => handleFileClick(timeline[2])" style="background-color: #CFAE46; opacity: 1"
                    class="font-bold text-black px-4 py-2 rounded-lg mt-2">
                    Details
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="selectedFile" class="mt-6 bg-white shadow-lg rounded-lg overflow-hidden">
        <div style="background-color: #CFAE46; opacity: 1" class="text-black font-bold py-2 px-4 rounded-t-lg">
          <h2 class="text-2xl">File Details</h2>
        </div>
        <div v-if="showReuploadModal" class="fixed inset-0 flex items-center justify-center z-50">
          <div class="bg-white p-4 rounded-lg border">
            <h3 class="mb-4 font-bold">Reupload File</h3>
            <input type="file" ref="fileInput">
            <div class="mt-4">
              <button @click="handleReuploadClick" style="background-color: #CFAE46; opacity: 1"
                class="font-bold text-black px-4 py-2 rounded-lg">
                Upload
              </button>
              <button @click="showReuploadModal = false" class="ml-2 text-gray-600">
                Cancel
              </button>
            </div>
          </div>
        </div>

        <div v-if="showReuploadDeanModal" class="fixed inset-0 flex items-center justify-center z-50">
          <div class="bg-white p-4 rounded-lg border">
            <h3 class="mb-4 font-bold">Reupload File</h3>
            <input type="file" ref="fileInput">
            <div class="mt-4">
              <button @click="handleReuploadDeanClick" style="background-color: #CFAE46; opacity: 1"
                class="font-bold text-black px-4 py-2 rounded-lg">
                Upload
              </button>
              <button @click="showReuploadDeanModal = false" class="ml-2 text-gray-600">
                Cancel
              </button>
            </div>
          </div>
        </div>

        <div class="p-4">
          <div v-if="selectedFile.file_name" class="flex items-center">
            <div class="flex-1">
              <strong class="text-gray-600">File Name:</strong>
              <span class="ml-2 text-gray-800">{{ selectedFile.file_name }}</span>
            </div>
            <div v-if="selectedFile.firebase_url || selectedFile.firebaseUrl_wSign || selectedFile.firebaseUrl_wSignDean"
              class="mr-64">
              <button @click="() => handleDownload(selectedFile)" style="background-color: #CFAE46; opacity: 1"
                class="font-bold text-black px-2 py-1 rounded-lg text-sm"
                v-if="selectedFile && selectedFile !== timeline[0]">
                Download
              </button>
              <button v-if="showReuploadButton" @click="handleReupload" style="background-color: #CFAE46; opacity: 1"
                class="font-bold text-black px-2 py-1 rounded-lg text-sm ml-1">
                Reupload
              </button>
              <button v-if="showReuploadDeanButton" @click="handleReuploadDean"
                style="background-color: #CFAE46; opacity: 1"
                class="font-bold text-black px-2 py-1 rounded-lg text-sm ml-1">
                Reupload
              </button>
            </div>
          </div>
          <div v-if="selectedFile.created_at" class="my-2">
            <strong class="text-gray-600">Date Uploaded:</strong>
            <span class="ml-2 text-gray-800">{{ formatDate(selectedFile.created_at) }}</span>
          </div>
          <div v-if="selectedFile.comment && selectedFile.comment.length > 0" class="my-2">
            <strong class="text-gray-600">Comment:</strong>
            <div class="ml-2 text-gray-800">
              <div class="border rounded p-2">
                <template v-for="(comment, index) in selectedFile.comment">
                  <span>{{ comment }}</span>
                </template>
              </div>
            </div>
          </div>
          <div v-else class="my-2">
            <strong class="text-gray-600">Comment:</strong>
            <span class="ml-2 text-gray-800">No comment</span>
          </div>
        </div>
      </div>
    </div>
  </PageComponent>
</template>


<script setup>
import { computed } from 'vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import PageComponent from '../../components/PageComponent.vue';
import { useStore } from 'vuex';
import { storage } from '../../firebase';
import { uploadBytes, getDownloadURL, ref as storageRef, listAll } from 'firebase/storage';

const successBannerVisible = ref(false);
const successMessage = ref('');
const fileInput = ref(null);
const store = useStore();
const currentUserId = store.state.user.data;
const userID = currentUserId.id;
const userName = currentUserId.name;
const uploadedFiles = ref([]);
const timeline = ref([]);
const error = ref(null);
const route = useRoute();
const selectedFile = ref({});
const showReuploadModal = ref(false);
const showReuploadDeanModal = ref(false);
const uploading = ref(false);

const circleTags = [
  "Deliverable Submitted",
  "Approved by Program Head",
  "Approved by Dean",
];

const handleFileClick = (file) => {
  console.log('File clicked:', file);
  selectedFile.value = file;
  console.log('Selected File:', selectedFile.value);
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString();
};

const showSuccessBanner = (message) => {
  successMessage.value = message;
  successBannerVisible.value = true;
  setTimeout(() => {
    successBannerVisible.value = false;
    successMessage.value = '';
  }, 1500);
};

const getLineColor = (index) => {
  const entry = timeline.value[index];
  if (!entry) return '#E2E8F0';
  if (entry.status === 'Approve') return '#00FF00';
  if (entry.status === 'To Be Modified') return '#FF0000';
  return '#E2E8F0';
};

const fetchFullTimeline = async (documentId) => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/get-full-timeline/${documentId}`);
    timeline.value = response.data;
    console.log("Timeline Data:", response.data);
  } catch (err) {
    console.error('Error fetching full timeline:', err);
    error.value = 'Error fetching full timeline.';
  }
};

const showReuploadButton = computed(() => {
  const shouldShow = selectedFile.value && selectedFile.value.showPhButton === null;
  console.log('Should Show Reupload Button:', shouldShow);
  return shouldShow;
});

const showReuploadDeanButton = computed(() => {
  const shouldShow = selectedFile.value && selectedFile.value.showButton === null;
  console.log('Should Show Reupload Dean Button:', shouldShow);
  return shouldShow;
});



const handleReupload = () => {
  showReuploadModal.value = true;
};

const handleReuploadDean = () => {
  showReuploadDeanModal.value = true;
};


const handleReuploadClick = () => {
  if (!fileInput.value || !fileInput.value.files.length) {
    console.error('No file selected for reupload');
    return;
  }

  console.log('Selected file for reupload:', fileInput.value.files[0]);

  const documentId = route.params.documentId;
  reuploadFile(documentId);
};

const handleReuploadDeanClick = () => {
  if (!fileInput.value || !fileInput.value.files.length) {
    console.error('No file selected for reupload');
    return;
  }

  console.log('Selected file for reupload_dean:', fileInput.value.files[0]);

  const documentId = route.params.documentId;
  reuploadDeanFile(documentId);
};

const reuploadFile = async (documentId) => {
  if (!fileInput.value || uploading.value) {
    return;
  }

  if (!userID) {
    console.error('User ID not found in Vuex store');
    return;
  }

  uploading.value = true;

  if (!fileInput.value.files.length) {
    console.error('No file selected for reupload');
    return;
  }
  const file = fileInput.value.files[0];

  const folderPath = timeline.value && timeline.value[0] ? timeline.value[0].file_path : null;
  if (!folderPath) {
    console.error('Folder path not found');
    return;
  }
  const storageRefInstance = storageRef(storage, `reuploads/${folderPath}/${file.name}`);

  try {
    const snapshot = await uploadBytes(storageRefInstance, file);
    const downloadURL = await getDownloadURL(snapshot.ref);

    uploadedFiles.value.push({
      name: file.name,
      url: downloadURL,

    });

    showSuccessBanner('Reuploaded Successfully!');
    const postData = {
      file_name: file.name,
      file_path: folderPath,
      firebase_url: downloadURL,
      program: timeline.value[0]?.program,
      academic_year: timeline.value[0]?.academic_year,
      document_id: timeline.value[0]?.id,
      user_id: userID,
      user_name: userName,
      deliverable_type: timeline.value[0]?.deliverable_type,
      date_uploadedByUser: timeline.value[0]?.created_at,
      deadline_id: timeline.value[0]?.deadline_id,
      subject: timeline.value[0]?.subject,
      subject_code: timeline.value[0]?.subject_code,
    };
    console.log('PostData:', postData);

    await axios.post('http://127.0.0.1:8000/api/reupload', postData);

    showReuploadModal.value = false;
    fileInput.value = null;
    uploading.value = false;


  } catch (error) {
    console.error('Error reuploading file:', error);
    uploading.value = false;
    showReuploadModal.value = false;
  }

  fetchFullTimeline(documentId);
};

const reuploadDeanFile = async (documentId) => {
  if (!fileInput.value || uploading.value) {
    return;
  }

  if (!userID) {
    console.error('User ID not found in Vuex store');
    return;
  }

  uploading.value = true;

  if (!fileInput.value.files.length) {
    console.error('No file selected for reupload');
    return;
  }
  const file = fileInput.value.files[0];

  const folderPath = timeline.value && timeline.value[0] ? timeline.value[0].file_path : null;
  if (!folderPath) {
    console.error('Folder path not found');
    return;
  }
  const storageRefInstance = storageRef(storage, `reupload_dean/${folderPath}/${file.name}`);

  try {
    const snapshot = await uploadBytes(storageRefInstance, file);
    const downloadURL = await getDownloadURL(snapshot.ref);

    uploadedFiles.value.push({
      name: file.name,
      url: downloadURL,

    });

    showSuccessBanner('Reuploaded Successfully!');
    const postData = {
      file_name: file.name,
      file_path: folderPath,
      firebaseUrl_wSign: downloadURL,
      program: timeline.value[0]?.program,
      academic_year: timeline.value[0]?.academic_year,
      document_id: timeline.value[0]?.id,
      comment: timeline.value[2]?.comment,
      user_id: userID,
      user_name: userName,
      deliverable_type: timeline.value[0]?.deliverable_type,
      date_uploadedByUser: timeline.value[0]?.created_at,
      deadline_id: timeline.value[0]?.deadline_id,
      subject: timeline.value[0]?.subject,
      subject_code: timeline.value[0]?.subject_code,
    };
    console.log('PostData:', postData);

    await axios.post('http://127.0.0.1:8000/api/reupload-dean', postData);

    showReuploadDeanModal.value = false;
    fileInput.value = null;
    uploading.value = false;


  } catch (error) {
    console.error('Error reuploading file:', error);
    uploading.value = false;
    showReuploadDeanModal.value = false;
  }

  fetchFullTimeline(documentId);
};

const handleDownload = (entry) => {
  if (entry) {
    if (entry.firebase_url) {
      window.open(entry.firebase_url, '_blank');
    } else if (entry.firebaseUrl_wSign) {
      window.open(entry.firebaseUrl_wSign, '_blank');
    } else if (entry.firebaseUrl_wSignDean) {
      window.open(entry.firebaseUrl_wSignDean, '_blank');
    } else {
      console.error('No valid Firebase URLs are available for this entry.');
    }
  } else {
    console.error('Entry is undefined or null.');
  }
};

onMounted(() => {
  const documentId = route.params.documentId;
  console.log("Document ID:", documentId);
  if (documentId) {
    fetchFullTimeline(documentId);
  } else {
    console.error('Document ID not provided');
  }
});
</script>

<style scoped>
.success-banner {
  background-color: #4CAF50;
  color: white;
  text-align: center;
  padding: 20px 40px;
  position: fixed;
  top: 20px;
  right: 20px;
  font-size: 18px;
  font-weight: bold;
  z-index: 9999;
  border-radius: 8px;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
}
</style>