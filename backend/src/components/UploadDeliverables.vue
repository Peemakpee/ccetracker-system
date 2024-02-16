<template>
    <div v-if="successBannerVisible" class="success-banner">
        {{ successMessage }}
    </div>
    <div class="p-5">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-24">
            <h1 class="text-xl font-medium tracking-tight text-gray-900">
                CCE Dean’s Office Document Management System
            </h1>
        </div>
    </div>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="text-3xl font-bold sm:px-6 lg:px-10 hidden sm:block">
                Upload Deliverables
            </div>

            <div class="ml-10 mt-5">
                <div class="bg-white shadow-md rounded-lg overflow-hidden p-5">
                    <div class="flex justify-between mb-4">
                        <div class="flex-1 flex items-center">
                            <label for="programDropdown" class="text-lg font-medium tracking-tight text-gray-900">
                                Program:
                            </label>
                            <select id="programDropdown" v-model="selectedProgram"
                                class="mt-1 px-2 py-1 border rounded w-40 ml-1">
                                <option value="">Select Program</option>
                                <option value="BSIT">BSIT</option>
                                <option value="BSCS">BSCS</option>
                                <option value="BSIS">BSIS</option>
                                <option value="BSEMC">BSEMC</option>
                                <option value="BLIS">BLIS</option>
                                <option value="BMMA">BMMA</option>
                            </select>
                        </div>

                        <div class="flex-1 flex items-center ml-5">
                            <label for="delTypeDropdown" class="text-lg font-medium tracking-tight text-gray-900">
                                <span style="white-space: nowrap;">Deliverable Type:</span>
                            </label>
                            <select id="deltypeDropdown" v-model="selectedDeltype"
                                class="mt-1 px-2 py-1 border rounded w-40 ml-1">
                                <option value="">Select Type</option>
                                <option v-for="type in deliverableTypes" :key="type" :value="type">{{ type }}</option>
                            </select>
                        </div>
                        <div class="flex-1 flex items-center ml-5">
                            <label for="academicYearDropdown" class="text-lg font-medium tracking-tight text-gray-900">
                                <span style="white-space: nowrap;">Academic Year/Semester/Term:</span>
                            </label>
                            <select id="academicYearDropdown" v-model="selectedAcademicYear"
                                class="mt-1 px-2 py-1 border rounded w-72 ml-1">
                                <option value="">Select AY/Sem/Term</option>
                                <option value="A.Y.2023-2024/1st Sem/1st Term">A.Y.2023-2024/1st Sem/1st Term</option>
                                <option value="A.Y.2023-2024/1st Sem/2nd Term">A.Y.2023-2024/1st Sem/2nd Term</option>
                                <option value="A.Y.2023-2024/2nd Sem/1st Term">A.Y.2023-2024/2nd Sem/1st Term</option>
                                <option value="A.Y.2023-2024/2nd Sem/2nd Term">A.Y.2023-2024/2nd Sem/2nd Term</option>
                                <option value="Summer 2024">Summer 2024</option>
                                <option value="A.Y.2024-2025/1st Sem/1st Term">A.Y.2024-2025/1st Sem/1st Term</option>
                                <option value="A.Y.2024-2025/1st Sem/2nd Term">A.Y.2024-2025/1st Sem/2nd Term</option>
                                <option value="A.Y.2024-2025/2nd Sem/1st Term">A.Y.2024-2025/2nd Sem/1st Term</option>
                                <option value="A.Y.2024-2025/2nd Sem/2nd Term">A.Y.2024-2025/2nd Sem/2nd Term</option>
                                <option value="Summer 2025">Summer 2025</option>
                                <option value="A.Y.2025-2026/1st Sem/1st Term">A.Y.2025-2026/1st Sem/1st Term</option>
                                <option value="A.Y.2025-2026/1st Sem/2nd Term">A.Y.2025-2026/1st Sem/2nd Term</option>
                                <option value="A.Y.2025-2026/2nd Sem/1st Term">A.Y.2025-2026/2nd Sem/1st Term</option>
                                <option value="A.Y.2025-2026/2nd Sem/2nd Term">A.Y.2025-2026/2nd Sem/2nd Term</option>
                                <option value="Summer 2026">Summer 2026</option>
                                <option value="A.Y.2026-2027/1st Sem/1st Term">A.Y.2026-2027/1st Sem/1st Term</option>
                                <option value="A.Y.2026-2027/1st Sem/2nd Term">A.Y.2026-2027/1st Sem/2nd Term</option>
                                <option value="A.Y.2026-2027/2nd Sem/1st Term">A.Y.2026-2027/2nd Sem/1st Term</option>
                                <option value="A.Y.2026-2027/2nd Sem/2nd Term">A.Y.2026-2027/2nd Sem/2nd Term</option>
                                <option value="Summer 2027">Summer 2027</option>
                            </select>
                        </div>
                    </div>

                    <div class="ml-64 mt-4 flex">
                        <div class="flex items-center mr-2">
                            <label for="subject" class="text-lg font-medium tracking-tight text-gray-900">
                                Subject:
                            </label>
                            <input type="text" id="subject" v-model="selectedSubject"
                                class="mt-1 px-2 py-1 border rounded w-48 ml-1" />
                        </div>

                        <div class="flex items-center ml-10">
                            <label for="subjectCode" class="text-lg font-medium tracking-tight text-gray-900">
                                Subject Code:
                            </label>
                            <input type="number" id="subjectCode" v-model="selectedSubjectCode"
                                class="mt-1 px-2 py-1 border rounded w-48 ml-1" />
                        </div>
                    </div>

                    <div class="flex justify-center p-5">
                        <div class="flex items-center">
                            <label for="fileInput" class="text-lg font-medium tracking-tight text-gray-900"></label>
                            <input type="file" id="fileInput" ref="fileInput" @change="handleFileChange"
                                accept=".pdf, .doc, .docx, .xlsx" />
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



            <div class="mt-6 ml-10">
                <h2 class="text-xl font-medium tracking-tight text-gray-900">Uploaded Files</h2>
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden mt-4">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="py-2 px-4 text-left">File Name</th>
                            <th class="py-2 px-4 text-left">Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="file in uploadedFiles" :key="file.name">
                            <td class="py-2 px-4">{{ file.name }}</td>
                            <td class="py-2 px-4">
                                <a :href="file.url" target="_blank" class="text-blue-500 hover:underline">Download</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';
import { storage } from '../../../firebase';
import { uploadBytes, getDownloadURL, ref as storageRef, listAll } from 'firebase/storage';
import { useRoute } from 'vue-router';

const successBannerVisible = ref(false);
const successMessage = ref('');
const route = useRoute();
const selectedDeltype = ref('');
const selectedProgram = ref('');
const selectedSubject = ref('');
const selectedSubjectCode = ref('');
const selectedAcademicYear = ref('');
const fileInput = ref(null);
const selectedFile = ref(null);
const uploading = ref(false);
const store = useStore();
const currentUserId = store.state.user.data;
const userID = currentUserId.id;
const userName = currentUserId.name;
const uploadedFiles = ref([]);


const showSuccessBanner = (message) => {
    successMessage.value = message;
    successBannerVisible.value = true;
    setTimeout(() => {
        successBannerVisible.value = false;
        successMessage.value = '';
    }, 1500);
};

const deliverableTypes = ref([]);

const fetchDeliverableTypes = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/get-deliverable-types');
        deliverableTypes.value = response.data;
    } catch (error) {
        console.error('Error fetching deliverable types:', error);
    }
};

const handleFileChange = () => {
    selectedFile.value = fileInput.value.files[0];
};

const uploadFile = async () => {
    if (!selectedFile.value || uploading.value) {
        return;
    }

    if (!userID) {
        console.error('User ID not found in Vuex store');
        return;
    }

    uploading.value = true;

    const deadlineId = route.params.deadlineId;

    const folderPath = `tracking/phase1/programs/${selectedProgram.value}/${selectedAcademicYear.value}/user ${userID}`;
    const storageRefInstance = storageRef(storage, `${folderPath}/${selectedFile.value.name}`);

    try {
        const snapshot = await uploadBytes(storageRefInstance, selectedFile.value);
        const downloadURL = await getDownloadURL(snapshot.ref);

        uploadedFiles.value.push({
            name: selectedFile.value.name,
            url: downloadURL,
        });
        const postData = {
            file_name: selectedFile.value.name,
            file_path: folderPath,
            firebase_url: downloadURL,
            program: selectedProgram.value,
            academic_year: selectedAcademicYear.value,
            subject: selectedSubject.value,
            subject_code: selectedSubjectCode.value,
            user_id: userID,
            user_name: userName,
            deliverable_type: selectedDeltype.value,
            deadline_id: deadlineId
        };

        await axios.post('http://127.0.0.1:8000/api/upload', postData);

        showSuccessBanner('File uploaded successfully');

        selectedFile.value = null;
        fileInput.value.value = '';
        uploading.value = false;
        selectedProgram.value = '';
        selectedAcademicYear.value = '';
        selectedDeltype.value = '';
        selectedSubject.value = '';
        selectedSubjectCode.value = '';

    } catch (error) {
        console.error('Error uploading file:', error);
        uploading.value = false;
    }
};

const deadlineId = route.params.deadlineId;
const fetchDeadlineData = async () => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/get-deadlines-upload/${deadlineId}`);
        const deadlineData = response.data;

        console.log('Response:', response.data);
        selectedProgram.value = deadlineData.program;
        selectedDeltype.value = deadlineData.deliverable_type;
        const deadlineDate = new Date(deadlineData.deadline);
        const academicYear = calculateAcademicYear(deadlineDate);
        selectedAcademicYear.value = academicYear;

    } catch (error) {
        console.error('Error fetching deadline data:', error);
    }
};

const calculateAcademicYear = (deadlineDate) => {

    const academicYears = [
        { start: new Date('2023-08-07'), end: new Date('2023-10-09'), value: 'A.Y.2023-2024/1st Sem/1st Term' },
        { start: new Date('2023-10-10'), end: new Date('2024-01-07'), value: 'A.Y.2023-2024/1st Sem/2nd Term' },
        { start: new Date('2024-01-08'), end: new Date('2024-03-11'), value: 'A.Y.2023-2024/2nd Sem/1st Term' },
        { start: new Date('2024-03-12'), end: new Date('2024-05-11'), value: 'A.Y.2023-2024/2nd Sem/2nd Term' },
        { start: new Date('2024-06-14'), end: new Date('2024-07-11'), value: 'Summer 2024' },
        { start: new Date('2024-08-07'), end: new Date('2024-10-09'), value: 'A.Y.2024-2025/1st Sem/1st Term' },
        { start: new Date('2024-10-10'), end: new Date('2025-01-07'), value: 'A.Y.2024-2025/1st Sem/2nd Term' },
        { start: new Date('2025-01-08'), end: new Date('2025-03-11'), value: 'A.Y.2024-2025/2nd Sem/1st Term' },
        { start: new Date('2025-03-12'), end: new Date('2025-05-11'), value: 'A.Y.2024-2025/2nd Sem/2nd Term' },
        { start: new Date('2025-06-14'), end: new Date('2025-07-11'), value: 'Summer 2025' },
        { start: new Date('2025-08-07'), end: new Date('2025-10-09'), value: 'A.Y.2025-2026/1st Sem/1st Term' },
        { start: new Date('2025-10-10'), end: new Date('2026-01-07'), value: 'A.Y.2025-2026/1st Sem/2nd Term' },
        { start: new Date('2026-01-08'), end: new Date('2026-03-11'), value: 'A.Y.2025-2026/2nd Sem/1st Term' },
        { start: new Date('2026-03-12'), end: new Date('2026-05-11'), value: 'A.Y.2025-2026/2nd Sem/2nd Term' },
        { start: new Date('2026-06-14'), end: new Date('2026-07-11'), value: 'Summer 2026' },
        { start: new Date('2026-08-07'), end: new Date('2026-10-09'), value: 'A.Y.2026-2027/1st Sem/1st Term' },
        { start: new Date('2026-10-10'), end: new Date('2027-01-07'), value: 'A.Y.2026-2027/1st Sem/2nd Term' },
        { start: new Date('2027-01-08'), end: new Date('2027-03-11'), value: 'A.Y.2026-2027/2nd Sem/1st Term' },
        { start: new Date('2027-03-12'), end: new Date('2027-05-11'), value: 'A.Y.2026-2027/2nd Sem/2nd Term' },
        { start: new Date('2027-06-14'), end: new Date('2027-07-11'), value: 'Summer 2027' },
    ];

    const matchedAcademicYear = academicYears.find((academicYearRange) => {
        return deadlineDate >= academicYearRange.start && deadlineDate <= academicYearRange.end;
    });

    return matchedAcademicYear ? matchedAcademicYear.value : 'Unknown Academic Year';
};

const listUploadedFiles = async () => {
    const folderPath = `tracking/phase1/programs/${selectedProgram.value}/${selectedAcademicYear.value}/user ${userID}`;
    const folderRef = storageRef(storage, folderPath);

    try {
        const items = await listAll(folderRef);

        const filePromises = items.items.map(async (item) => {
            const url = await getDownloadURL(item);
            return {
                name: item.name,
                url,
            };
        });

        uploadedFiles.value = await Promise.all(filePromises);
    } catch (error) {
        console.error('Error listing uploaded files:', error);
    }
};

onMounted(() => {
    fetchDeadlineData();
    listUploadedFiles();
    fetchDeliverableTypes();
});
</script>
  

<style>
.loader {
    border-top-color: green;
    border-left-color: green;
    animation: spin 1.5s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
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