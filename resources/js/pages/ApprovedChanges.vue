<template>
    <section class="content">
        <div class="card">
            <div class="card-header custom-docheader">
                <h3 class="card-title custom-title">Approved Deliverables With Changes</h3>
                <div class="search-bar float-right">
                    <input type="text" v-model="searchQuery" placeholder="Search...">
                    <i class="fas fa-search ml-1"></i>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 12%">Faculty Name</th>
                            <th style="width: 12%">Deliverable Type</th>
                            <th style="width: 12%">Program</th>
                            <th style="width: 12%">Academic Year</th>
                            <th style="width: 12%">Date and Time Uploaded</th>
                            <th style="width: 7%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="approvedChange in (searchQuery ? filteredApprovedChanges : paginatedApprovedChanges)" :key="approvedChange.id">
                            <td>{{ approvedChange.user_name }}</td>
                            <td>{{ approvedChange.deliverable_type }}</td>
                            <td>{{ approvedChange.program }}</td>
                            <td>{{ approvedChange.academic_year }}</td>
                            <td>{{ formatDate(approvedChange.created_at) }}</td>
                            <td class="project-actions text-right">
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <a :href="approvedChange.firebaseUrl_changes" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <button class="btn btn-danger btn-sm"
                                        @click="confirmArchiveUpload(approvedChange.document_id)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="pagination pagination-sm m-0 float-right">
                    <ul class="pagination">
                        <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                            <a class="page-link" @click.prevent="setPage(1)">
                                «
                            </a>
                        </li>
                        <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                            <a class="page-link" @click.prevent="setPage(currentPage - 1)">
                                ‹
                            </a>
                        </li>
                        <li class="page-item" v-for="page in totalPages" :key="page"
                            :class="{ 'active': currentPage === page }">
                            <a class="page-link" @click.prevent="setPage(page)">
                                {{ page }}
                            </a>
                        </li>
                        <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                            <a class="page-link" @click.prevent="setPage(currentPage + 1)">
                                ›
                            </a>
                        </li>
                        <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                            <a class="page-link" @click.prevent="setPage(totalPages)">
                                »
                            </a>
                        </li>
                    </ul>
                </div>
                <div v-if="paginatedApprovedChanges.length === 0" class="text-center">
                    No Data available.
                </div>
            </div>
        </div>
    </section>
</template>
  
<script>
import axios from 'axios';

export default {
    data() {
        return {
            approvedChanges: [],
            program: '',
            currentPage: 1,       
            perPage: 6,        
            searchQuery: '',
        };
    },
    computed: {
        filteredApprovedChanges() {
            return this.approvedChanges.filter(upload => {
                const search = this.searchQuery.toLowerCase();
                return (
                    upload.user_name.toLowerCase().includes(search) ||
                    upload.deliverable_type.toLowerCase().includes(search) ||
                    upload.academic_year.toLowerCase().includes(search) ||
                    upload.program.toLowerCase().includes(search)
                );
            });
        },
        paginatedApprovedChanges() {
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            return this.approvedChanges.slice(startIndex, endIndex);
        },
        totalPages() {
            return Math.ceil(this.approvedChanges.length / this.perPage);
        },
    },
    methods: {
        clearSearch() {
            this.searchQuery = '';
            this.currentPage = 1;
        },
        confirmArchiveUpload(id) {
       
            if (confirm('Are you sure you want to archive this entry? The file will be moved to the archives and no longer visible in the active uploads.')) {
            
                this.archiveUpload(id);
            }
        },
        formatDate(dateTime) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateTime).toLocaleDateString('en-US', options);
        },
        archiveUpload(id) {
        
            axios
                .post(`http://127.0.0.1:8000/api/archive-approvedChanges/${id}`)
                .then(() => {
                    this.approvedChanges = this.approvedChanges.filter(upload => upload.document_id !== id);
                })
                .catch(error => {
                    console.error('An error occurred while deleting the upload:', error);
                });
        },
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },
    },
    mounted() {
        const program = this.$route.params.program;
        const isDean = this.$route.params.program === 'DEAN';
        const isAA = this.$route.params.program === 'AA';

        let apiUrl = 'http://127.0.0.1:8000/api/get-approved-changes';

        if (!isDean && !isAA) {
            apiUrl += `/${program}`; 
        }

        axios.get(apiUrl)
            .then(response => {
                if (Array.isArray(response.data)) {
                    this.approvedChanges = response.data;
                } else {
                    console.error('Unexpected data format:', response.data);
                }
            })
            .catch(error => {
                if (error.response && error.response.status === 404) {
                    console.log('No documents found.');
                } else {
                    console.error('An error occurred while fetching data:', error);
                }
            });
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
    font-weight: 700;
    font-size: medium;
}


.custom-title {
    color: #000000;

    font-size: 20px;
    font-weight: bold;
}
</style>
  