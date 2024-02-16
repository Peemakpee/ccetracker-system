<template>
    <div>
        <div class="card custom-card">
            <div class="card-header custom-docheader">
                <h1 class="m-0 custom-title text-center">{{ templateDetails.type }}</h1>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 30%">File Name</th>
                            <th style="width: 40%">Size</th>
                            <th style="width: 30%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="file in templateDetails.uploadedFiles" :key="file.name">
                            <td>{{ file.name }}</td>
                            <td>{{ formatSize(file.size) }}</td>
                            <td class="project-actions text-left">
                                <a :href="file.url" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

  
<script>
import axios from 'axios';
import { storage } from '../../../firebase';
import { ref as storageRef, listAll, getDownloadURL, getMetadata } from 'firebase/storage';

export default {
    data() {
        return {
            templateDetails: {
                type: '',
                uploadedFiles: []
            }
        };
    },
    computed: {
        templateId() {
            return this.$route.params.id;
        },
        formatSize() {
            return (size) => {
                if (size === 0) return '0 Bytes';

                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(size) / Math.log(k));

                return parseFloat((size / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            };
        },
    },
    methods: {
        async fetchTemplateDetails() {
            try {
                const response = await axios.get(`http://127.0.0.1:8000/api/get-template/${this.templateId}`);
                const templateData = response.data;

                const uploadedFiles = await this.fetchUploadedFiles(templateData.type);

                this.templateDetails = {
                    type: templateData.type,
                    uploadedFiles
                };

                console.log('Success:', this.templateDetails);
            } catch (error) {
                console.error('Error fetching template details:', error);
            }
        },
        async fetchUploadedFiles(templateType) {
            const folderPath = `deliverable_templates/${templateType}`;
            const folderRef = storageRef(storage, folderPath);

            try {
                const items = await listAll(folderRef);

                const filePromises = items.items.map(async (item) => {
                    const url = await getDownloadURL(item);
                    const metadata = await getMetadata(item);
                    return {
                        name: item.name,
                        url,
                        size: metadata.size,
                        type: metadata.contentType,
                        uploadTime: metadata.timeCreated
                    };
                });

                return await Promise.all(filePromises);
            } catch (error) {
                console.error('Error fetching uploaded files:', error);
                return [];
            }
        }
    },
    mounted() {
        this.fetchTemplateDetails();
    }
};
</script>


<style scoped>
.custom-docheader {
    background-color: #CFAE46 !important;

}

.custom-button {
    background-color: #CFAE46 !important;

    height: 40px;
    font-weight: 600;
    font-size: large;
}


.custom-title {
    color: #000000;

    font-size: 26px;
    font-weight: bold;

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
    transform: none;

}

.custom-card {
    max-width: 90%;

    margin: 0 auto;

}
</style>
