<template>
    <section class="content">
        <div class="card">
            <div class="card-header custom-docheader">
                <h3 class="m-0 custom-title text-center">Files Over 2 Years Old</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 30%">File Name</th>
                            <th style="width: 30%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="file in paginatedFiles" :key="file.name">
                            <td>{{ file.file_name }}</td>
                            <td class="project-actions text-left">
                                <button @click="downloadFile(file)" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </button>
                                <button @click="deleteFile(file)" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="pagination pagination-sm m-0 float-right">
                    <ul class="pagination">
                        <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                            <a class="page-link" @click.prevent="setPage(1)">«</a>
                        </li>
                        <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                            <a class="page-link" @click.prevent="setPage(currentPage - 1)">‹</a>
                        </li>
                        <li class="page-item" v-for="page in totalPages" :key="page"
                            :class="{ 'active': currentPage === page }">
                            <a class="page-link" @click.prevent="setPage(page)">{{ page }}</a>
                        </li>
                        <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                            <a class="page-link" @click.prevent="setPage(currentPage + 1)">›</a>
                        </li>
                        <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                            <a class="page-link" @click.prevent="setPage(totalPages)">»</a>
                        </li>
                    </ul>
                </div>
                <div v-if="paginatedFiles.length === 0" class="text-center">
                    No Files available.
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from 'axios';
import { storage } from '../../../firebase';
import { getStorage, ref, deleteObject } from 'firebase/storage';

export default {
    data() {
        return {
            oldFiles: [],
            currentPage: 1,
            perPage: 5,
        };
    },
    computed: {
        paginatedFiles() {
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            return this.oldFiles.slice(startIndex, endIndex);
        },
        totalPages() {
            return Math.ceil(this.oldFiles.length / this.perPage);
        },
    },
    methods: {
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },
        formatSize(size) {
        },
        async fetchOldFiles() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/retention-data');
                this.oldFiles = response.data;
                console.log('API Data:', this.oldFiles);
            } catch (error) {
                console.error('Error fetching old files:', error);
            }
        },
        async deleteFile(file) {
            if (!confirm(`Are you sure you want to delete ${file.file_name}?`)) {
                return;
            }

            try {
                const storage = getStorage();
                console.log('Delete file name:', file.file_name);
                console.log('Deleting file with path:', file.file_path);
                const fullPath = `${file.file_path}/${file.file_name}`;
                const fileRef = ref(storage, fullPath);
                await deleteObject(fileRef);

                const response = await axios.delete(`http://127.0.0.1:8000/api/delete-file/${file.id}`);

                const index = this.oldFiles.findIndex(item => item.id === file.id);
                if (index !== -1) {
                    this.oldFiles.splice(index, 1);
                }

                console.log('File deleted successfully:', response.data);
            } catch (error) {
                console.error('An error occurred while deleting the file:', error);
                alert('Failed to delete the file. Please try again.');
            }
        },
        downloadFile(file) {
            if (file.firebase_url) {
                window.open(file.firebase_url, '_blank');
            } else {
                console.error('Firebase URL is not available.');
            }
        },
    },
    mounted() {
        this.fetchOldFiles();
    },
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

.card-custom-outline {
    border-top: 3.5px solid #CFAE46;
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
