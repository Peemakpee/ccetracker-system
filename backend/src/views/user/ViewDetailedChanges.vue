<template>
    <div v-if="successBannerVisible" class="success-banner">
        {{ successMessage }}
    </div>
    <div class="p-5">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-16">
            <h1 class="text-xl font-medium tracking-tight text-gray-900">
                CCE Dean’s Office Document Management System
            </h1>
        </div>
        <div v-if="isLoading" class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8 text-center">
            Loading...
        </div>
        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
            <div class="sm:px-0">
                <div class="text-3xl font-bold sm:px-6 lg:px-9 hidden sm:block">
                    Detailed View of Approved Deliverable With Changes
                </div>

                <div class="container mx-auto p-4">
                    <div class="flex flex-wrap">
                        <div class="w-full md:w-2/3 px-2">
                            <div class="bg-white rounded shadow-md">
                                <div class="border-b p-4">
                                    <h5 class="text-lg font-semibold">Document Viewer</h5>
                                </div>
                                <div class="p-4">
                                    <div class="bg-blue-100 rounded">
                                        <iframe :src="googleViewLink" style="width: 700px; height: 446px;"
                                            frameborder="0"></iframe>

                                    </div>

                                    <div class="text-right mt-2">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                                            @click="downloadFile">Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 px-2">
                            <div class="bg-white rounded shadow-md mb-4">
                                <div class="border-b p-4">
                                    <h5 class="text-lg font-semibold">Document Details</h5>
                                </div>
                                <div class="p-4">
                                    <dl class="space-y-4">
                                        <div class="flex justify-between">
                                            <dt class="text-gray-600 label">File Name</dt>
                                            <dd class="text-gray-800 content">{{ approvedChanges.file_name || 'Loading...'
                                            }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-gray-600 label">Submitted By</dt>
                                            <dd class="text-gray-800 content">{{ approvedChanges.user_name || 'Loading...'
                                            }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-gray-600 label">Subject</dt>
                                            <dd class="text-gray-800 content">{{ approvedChanges.subject || 'Loading...'
                                            }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-gray-600 label">Subject Code</dt>
                                            <dd class="text-gray-800 content">{{ approvedChanges.subject_code ||
                                                'Loading...'
                                            }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-gray-600 label">Academic Year</dt>
                                            <dd class="text-gray-800 content">{{ approvedChanges.academic_year ||
                                                'Loading...' }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                            <div class="bg-white rounded shadow-md">
                                <div class="border-b p-4">
                                    <h5 class="text-lg font-semibold">Status</h5>
                                </div>
                                <div class="p-4">
                                    <div class="mb-4">
                                        <label for="comment" class="block text-sm font-medium text-gray-600">Change
                                            Description:</label>
                                        <dd class="text-gray-800">{{ approvedChanges.change_description || 'Loading...' }}
                                        </dd>
                                    </div>
                                    <div class="text-right">
                                        <label for="fileInput"
                                            class="text-lg font-medium tracking-tight text-gray-900"></label>
                                        <input type="file" id="fileInput" ref="fileInput" @change="handleFileChange"
                                            accept=".pdf, .doc, .docx" />
                                        <button @click="uploadFile" :disabled="uploading"
                                            class="ml-2 px-3 py-1 text-black rounded relative"
                                            style="background-color: #CFAE46; opacity: 1">
                                            <div class="flex items-center">
                                                <span>{{ uploading ? 'Uploading' : 'Upload' }}</span>
                                                <div v-if="uploading"
                                                    class="ml-2 loader ease-linear rounded-full border-4 border-t-4 border-yellow-200 h-4 w-4">
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>



    
<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import { storage } from '../../firebase';
import { useStore } from 'vuex';
const store = useStore();
import { uploadBytes, getDownloadURL, ref as storageRef, listAll } from 'firebase/storage';

const successBannerVisible = ref(false);
const successMessage = ref('');
const route = useRoute();
const selectedFile = ref(null);
const currentUserId = store.state.user.data;
const userID = currentUserId.id;
const documentId = computed(() => route.params.documentId);
const isLoading = ref(true);
const fileInput = ref(null);
const uploading = ref(false);
const uploadedFiles = ref([]);
const folderPath = ref('');

const showSuccessBanner = (message) => {
    successMessage.value = message;
    successBannerVisible.value = true;
    setTimeout(() => {
        successBannerVisible.value = false;
        successMessage.value = '';
    }, 1500);
};

const approvedChanges = ref({});
const googleViewLink = computed(() => {
    const url = approvedChanges.value.firebaseUrl_changes || 'fallback_value';
    return `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
});


const fetchDetailedApprovedChanges = () => {
    const docId = documentId.value;
    axios
        .get(`http://127.0.0.1:8000/api/get-detailed-approvedChanges/${docId}`)
        .then(response => {
            if (response.data && response.data.length > 0) {
                approvedChanges.value = response.data[0];
            } else {
                console.warn('No approved changes data received');
            }
            isLoading.value = false;
        })
        .catch(error => {
            console.error('An error occurred while fetching data:', error);
        });
};

const handleFileChange = () => {
    selectedFile.value = fileInput.value.files[0];
};

const uploadFile = async () => {
    if (!selectedFile.value || uploading.value) {
        return;
    }

    uploading.value = true;

    folderPath.value = `tracking/complied_changes/programs/${approvedChanges.value.program}/${approvedChanges.value.academic_year}/user ${userID}`;
    const storageRefInstance = storageRef(storage, `${folderPath.value}/${selectedFile.value.name}`);

    try {
        const snapshot = await uploadBytes(storageRefInstance, selectedFile.value);
        const downloadURL = await getDownloadURL(snapshot.ref);

        uploadedFiles.value.push({
            name: selectedFile.value.name,
            url: downloadURL,
        });

        folderPath.value = `tracking/complied_changes/programs/${approvedChanges.value.program}/${approvedChanges.value.academic_year}/user ${userID}`;
        const postData = {
            file_name: selectedFile.value.name,
            file_path: folderPath.value,
            firebaseUrl_complied: downloadURL,
            program: approvedChanges.value.program,
            academic_year: approvedChanges.value.academic_year,
            user_id: userID,
            user_name: approvedChanges.value.user_name,
            deliverable_type: approvedChanges.value.deliverable_type,
            document_id: approvedChanges.value.document_id,
            change_description: approvedChanges.value.change_description,
            date_upladedByUser: approvedChanges.value.date_upladedByUser,
            status: 'Changes Complied'
        };

        await axios.post('http://127.0.0.1:8000/api/upload-complied', postData);

        console.log('API response:', postData);

        showSuccessBanner('File uploaded successfully');

        selectedFile.value = null;
        fileInput.value.value = '';
        uploading.value = false;

    } catch (error) {
        console.error('Error uploading file:', error);
        uploading.value = false;
    }
};


const downloadFile = () => {

    if (approvedChanges.value.firebaseUrl_changes) {

        window.open(approvedChanges.value.firebaseUrl_changes, '_blank');
    } else {

        console.error('Firebase URL is not available.');
    }
};

onMounted(() => {
    fetchDetailedApprovedChanges();
});
</script>
    
<style scoped>
.label {
    width: 120px;

    text-align: left;
}

.content {
    text-align: right;
}

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
    