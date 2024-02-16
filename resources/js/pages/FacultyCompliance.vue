<template>
    <section class="content">
        <div class="card">
            <div class="card-header custom-docheader">
                <h3 class="card-title custom-title">Faculty Compliance Data</h3>
                <div class="search-bar float-right">
                    <input type="text" v-model="searchQuery" placeholder="Search...">
                    <i class="fas fa-search ml-1"></i>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 17%">Deliverable Type</th>
                            <th style="width: 15%">Program</th>
                            <th style="width: 15%">Deadline Date</th>
                            <th>Progress</th>
                            <th style="width: 15%" class="text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(compliance) in (searchQuery ? filteredFacultyComplianceData : paginatedFacultyComplianceData)"
                            :key="compliance.id">

                            <td>{{ compliance.deliverable_type }}</td>
                            <td>{{ compliance.program }}</td>
                            <td>{{ formatDate(compliance.deadline) }}</td>
                            <td class="project_progress">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" role="progressbar"
                                        :aria-valuenow="calculateProgress(compliance.program, compliance.deliverable_type, compliance.id)"
                                        :aria-valuemin="0" :aria-valuemax="100"
                                        :style="{ width: calculateProgress(compliance.program, compliance.deliverable_type, compliance.id) + '%' }">
                                    </div>
                                </div>
                                <small>{{ calculateProgress(compliance.program, compliance.deliverable_type, compliance.id)
                                }}% Complete</small>

                            </td>
                            <td class="project-actions text-center">
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <router-link
                                        :to="{ name: 'admin.detailedCompliance', params: { deadlineId: compliance.id, userId: userID } }"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-folder"></i> View
                                    </router-link>
                                    <button class="btn btn-danger btn-sm" @click="confirmDelete(compliance.id)">
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
                <div v-if="paginatedFacultyComplianceData.length === 0" class="text-center">
                    No Faculty Compliance Data available.
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
            facultyComplianceData: [],
            uploadFilesData: [],
            deadlinesData: [],
            usersData: [],
            currentPage: 1,
            itemsPerPage: 7,
            searchQuery: '',
            userID: null,
        };
    },
    computed: {
        filteredFacultyComplianceData() {
            return this.facultyComplianceData.filter(compliance => {
                const search = this.searchQuery.toLowerCase();
                return compliance.deliverable_type.toLowerCase().includes(search) ||
                    compliance.program.toLowerCase().includes(search) ||
                    compliance.deadline.toLowerCase().includes(search);
            });
        },
        paginatedFacultyComplianceData() {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            const endIndex = startIndex + this.itemsPerPage;
            return this.facultyComplianceData.slice(startIndex, endIndex);
        },

        totalPages() {
            return Math.ceil(this.facultyComplianceData.length / this.itemsPerPage);
        },

        displayedPages() {
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, start + 4);
            const pages = [];
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },
    },
    methods: {
        clearSearch() {
            this.searchQuery = '';
            this.currentPage = 1;
        },
        confirmDelete(id) {
            if (confirm('Warning: Once you delete this entry, it cannot be recovered. Are you sure you want to continue?')) {
                this.deleteFacultyCompliance(id);
            }
        },
        formatDate(dateTime) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateTime).toLocaleDateString('en-US', options);
        },
        setPage(pageNumber) {
            if (pageNumber >= 1 && pageNumber <= this.totalPages) {
                this.currentPage = pageNumber;
            }
        },
        deleteFacultyCompliance(id) {
            axios
                .delete(`http://127.0.0.1:8000/api/delete-faculty-compliance/${id}`)
                .then(() => {
                    this.facultyComplianceData = this.facultyComplianceData.filter(item => item.id !== id);
                })
                .catch(error => {
                    console.error('An error occurred while deleting faculty compliance data:', error);
                });
        },
        calculateProgress(program, deliverableType, deadlineId) {
            if (!this.uploadFilesData || !this.deadlinesData) {
                return 0;
            }

            const relevantData = this.uploadFilesData.filter(file => {
                return file.program === program &&
                    file.deliverable_type === deliverableType &&
                    file.deadline_id === deadlineId;
            });

            const submissionCount = relevantData.length;

            const deadline = this.deadlinesData.find(deadline => deadline.id === deadlineId);

            if (!deadline) {
                return 0;
            }

            const programUsers = this.usersData.filter(user => {
                return user.program === program && user.is_admin === 0;

            });

            const totalDeliverables = programUsers.length;

            if (totalDeliverables === 0) {
                return 0;
            }

            const progress = (submissionCount / totalDeliverables) * 100;

            return progress.toFixed(1);
        },
    },
    mounted() {
        const program = this.$route.params.program;
        const isDean = this.$route.params.program === 'DEAN';
        const isAA = this.$route.params.program === 'AA';

        let apiUrl = 'http://127.0.0.1:8000/api/get-faculty-compliance';

        if (!isDean && !isAA) {
            apiUrl += `/${program}`;
        }

        axios
            .get(apiUrl)
            .then(response => {
                console.log('API Response:', response.data);
                this.facultyComplianceData = response.data;
            })
            .catch(error => {
                console.error('An error occurred while fetching data:', error);
            });

        axios
            .get('http://127.0.0.1:8000/api/get-upload-pending')
            .then(response => {
                this.uploadFilesData = response.data;
                console.log('Upload Files Data:', this.uploadFilesData);
            })
            .catch(error => {
                console.error('An error occurred while fetching upload files data:', error);
            });

        axios
            .get('http://127.0.0.1:8000/api/get-deadlines')
            .then(response => {
                this.deadlinesData = response.data;
                console.log('Deadlines Data:', this.deadlinesData);
            })
            .catch(error => {
                console.error('An error occurred while fetching deadlines data:', error);
            });
        axios
            .get('http://127.0.0.1:8000/api/get-users')
            .then(response => {
                this.usersData = response.data;
                console.log('Users Data:', this.usersData);
            })
            .catch(error => {
                console.error('An error occurred while fetching Users data:', error);
            });
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
    font-weight: 700;
    font-size: medium;
}


.custom-title {
    color: #000000;
    font-size: 20px;
    font-weight: bold;

}
</style>