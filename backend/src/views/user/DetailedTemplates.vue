<template>
    <PageComponent header="CCE Dean’s Office Document Management System">
        <div class="ml-16">
            <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1 fixed; width: 87%" >
                Templates
            </div>
            <table class="bg-white shadow-md rounded-b-lg overflow-hidden" style="table-layout: fixed; width: 87%">
                <colgroup>
                    <col style="width: 30%">
                    <col style="width: 30%">
                    <col style="width: 30%">
                </colgroup>
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="py-2 px-4 text-left">File Name</th>
                        <th class="py-2 px-4 text-left">Date Uploaded</th>
                        <th class="py-2 px-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="template in detailedTemplates" :key="template.id">
                        <td class="py-2 px-4 text-left">
                            <div v-for="(file, index) in template.files" :key="index">
                                {{ file.fileName }}
                            </div>
                        </td>
                        <td class="py-2 px-4 text-left">
                            <div v-for="(file, index) in template.files" :key="index">
                                {{ formatDate(file.uploadTime) }}
                            </div>
                        </td>
                        <td class="py-2 px-4 text-left">
                            <div v-for="(file, index) in template.files" :key="index">
                                <a :href="file.url" target="_blank" class="text-blue-500 hover:underline">Download</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </PageComponent>
</template>

<script setup>
import axios from 'axios';
import PageComponent from '../../components/PageComponent.vue';
import { ref, onMounted, computed } from 'vue';
import { storage } from '../../firebase';
import { ref as storageRef, listAll, getDownloadURL, getMetadata } from 'firebase/storage';
import { useRoute } from 'vue-router';

const props = defineProps(['formatSize']);
const route = useRoute();
const templateId = computed(() => route.params.id);

const detailedTemplates = ref([]);
const error = ref(null);

function formatDate(dateTime) {
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Date(dateTime).toLocaleDateString(undefined, options);
}

const fetchFilesForTemplateType = async (templateType) => {
    const folderPath = `deliverable_templates/${templateType}`;
    const folderRef = storageRef(storage, folderPath);
    const items = await listAll(folderRef);

    return Promise.all(
        items.items.map(async (item) => {
            const url = await getDownloadURL(item);
            const metadata = await getMetadata(item);

            console.log("Metadata for file:", item.name, "is:", metadata);

            return {
                url,
                fileName: item.name,
                size: metadata.size,
                type: metadata.contentType,
                uploadTime: metadata.timeCreated
            };
        })
    );
}



const fetchTemplatesFromDatabase = async () => {
    try {
        const tempId = templateId.value;
        const response = await axios.get(`http://127.0.0.1:8000/api/get-detailed-templates/${tempId}`);
        if (!Array.isArray(response.data)) {
            console.error('Expected an array but received:', response.data);
            return [];
        }
        return response.data;
    } catch (err) {
        console.error('Error fetching templates:', err);
        error.value = 'Error fetching templates from the database.';
        return [];
    }
};

const fetchDetailedTemplates = async () => {
    const template = await fetchTemplatesFromDatabase();

    const templates = Array.isArray(template) ? template : [template];

    for (const template of templates) {
        template.files = await fetchFilesForTemplateType(template.type);
    }

    detailedTemplates.value = templates;
};

onMounted(() => {
    fetchDetailedTemplates();
});
</script>



  