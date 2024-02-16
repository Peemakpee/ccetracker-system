<template>
    <section class="content">
        <div class="card">
            <div class="card-header custom-docheader">
                <h3 class="card-title custom-title">Approved Deliverables</h3>
                <div class="filter-bar float-right mr-5">
                    <div class="filter-dropdowns">
                        <label for="filterBy">Filter by:</label>
                        <select id="programFilter" v-model="selectedProgram" @change="updateTable" class="ml-2">
                            <option value="">Program</option>
                            <option v-for="program in filterProgramOptions" :key="program" :value="program">{{ program
                            }}</option>
                        </select>
                        <select id="academicYearFilter" v-model="selectedAcademicYear" @change="updateTable" class="ml-2">
                            <option value="">Academic Year</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                            <option value="2026-2027">2026-2027</option>
                            <option value="2027-2028">2027-2028</option>
                        </select>
                        <select id="semesterFilter" v-model="selectedSemester" @change="updateTable" class="ml-2">
                            <option value="">Semester</option>
                            <option value="1st Sem">1st Sem</option>
                            <option value="2nd Sem">2nd Sem</option>
                        </select>
                        <select id="termFilter" v-model="selectedTerm" @change="updateTable" class="ml-2">
                            <option value="">Term</option>
                            <option value="1st Term">1st Term</option>
                            <option value="2nd Term">2nd Term</option>
                        </select>
                        <select id="deliverableFilter" v-model="selectedDeliverableType" @change="updateTable" class="ml-2">
                            <option value="">Deliverable Type</option>
                            <option v-for="deliverable in filterDeliverableOptions" :key="deliverable" :value="deliverable">
                                {{ deliverable }}</option>
                        </select>
                        <select id="facultyFilter" v-model="selectedFacultyOptions" @change="updateTable" class="ml-2">
                            <option value="">Faculty Name</option>
                            <option v-for="faculty in filterFacultyOptions" :key="faculty" :value="faculty">{{ faculty
                            }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 5%">Program</th>
                            <th style="width: 10%">Faculty Name</th>
                            <th style="width: 10%">Deliverable Type</th>
                            <th style="width: 10%">Academic Year</th>
                            <th style="width: 10%">Date and Time Uploaded</th>
                            <th style="width: 9%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="approvedByDean in paginatedApprovedByDeanUploads" :key="approvedByDean.id">
                            <td>{{ approvedByDean.program }}</td>
                            <td>{{ approvedByDean.user_name }}</td>
                            <td>{{ approvedByDean.deliverable_type }}</td>
                            <td>{{ approvedByDean.academic_year }}</td>
                            <td>{{ formatDate(approvedByDean.created_at) }}</td>
                            <td class="project-actions text-left">
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <router-link :to="`/admin/detailedApprovedByDean/${approvedByDean.id}`"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-folder"></i> View
                                    </router-link>
                                    <button class="btn btn-danger btn-sm"
                                        @click="confirmArchiveUpload(approvedByDean.document_id)">
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
                <div v-if="paginatedApprovedByDeanUploads.length === 0" class="text-center">
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
            approvedByDeans: [],
            currentPage: 1,
            perPage: 6,
            program: '',
            filterFacultyOptions: [],
            filterDeliverableOptions: [],
            filterAYOptions: [],
            filterProgramOptions: [],
            originalApprovedByDeans: [],
            selectedDeliverableType: '',
            selectedProgram: '',
            selectedFacultyOptions: '',
            selectedAcademicYear: '',
            selectedSemester: '',
            selectedTerm: '',
            filterAcademicYearOptions: [],
            filterSemesterOptions: [],
            filterTermOptions: [],
        };
    },
    computed: {
        paginatedApprovedByDeanUploads() {
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            return this.approvedByDeans.slice(startIndex, endIndex);
        },
        totalPages() {
            return Math.ceil(this.approvedByDeans.length / this.perPage);
        },
    },
    watch: {
        selectedProgram: 'updateTable',
        selectedDeliverableType: 'updateTable',
        selectedFacultyOptions: 'updateTable',
        selectedAcademicYear: 'updateTable',
        selectedSemester: 'updateTable',
        selectedTerm: 'updateTable',
    },
    methods: {
        updateTable() {
            let filteredData = [...this.originalApprovedByDeans];

            if (this.selectedProgram) {
                filteredData = filteredData.filter(upload => upload.program === this.selectedProgram);
            }
            if (this.selectedDeliverableType) {
                filteredData = filteredData.filter(upload => upload.deliverable_type === this.selectedDeliverableType);
            }
            if (this.selectedFacultyOptions) {
                filteredData = filteredData.filter(upload => upload.user_name === this.selectedFacultyOptions);
            }
            if (this.selectedAcademicYear) {
                filteredData = filteredData.filter(upload => {
                    const ay = upload.academic_year.split('/')[0].replace('A.Y.', '').trim();
                    return ay === this.selectedAcademicYear;
                });
            }
            if (this.selectedSemester) {
                filteredData = filteredData.filter(upload => {
                    const semester = upload.academic_year.split('/')[1].trim();
                    return semester === this.selectedSemester;
                });
            }
            if (this.selectedTerm) {
                filteredData = filteredData.filter(upload => {
                    const term = upload.academic_year.split('/')[2].trim();
                    return term === this.selectedTerm;
                });
            }
            this.approvedByDeans = filteredData;

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
                .post(`http://127.0.0.1:8000/api/archive-approvedByDean/${id}`)
                .then(() => {
                    this.approvedByDeans = this.approvedByDeans.filter(upload => upload.document_id !== id);
                })
                .catch(error => {
                    console.error('An error occurred while deleting the upload:', error);
                });
        },
        fetchFilterFacultyOptions() {
            axios.get('http://127.0.0.1:8000/api/get-filterFacultyOptions')
                .then(response => {
                    console.log('Filter Options:', response.data);
                    this.filterFacultyOptions = response.data;
                })
                .catch(error => {
                    console.error('Error fetching filter options:', error);
                });
        },
        fetchFilterDeliverableOptions() {
            axios.get('http://127.0.0.1:8000/api/get-filterDeliverableOptions')
                .then(response => {
                    console.log('Filter Options:', response.data);
                    this.filterDeliverableOptions = response.data;
                })
                .catch(error => {
                    console.error('Error fetching filter options:', error);
                });
        },
        fetchFilterProgramOptions() {
            axios.get('http://127.0.0.1:8000/api/get-filterProgramOptions')
                .then(response => {
                    console.log('Filter Options:', response.data);
                    this.filterProgramOptions = response.data;
                })
                .catch(error => {
                    console.error('Error fetching filter options:', error);
                });
        },
        fetchFilterAYOptions() {
            const academicYears = this.originalApprovedByDeans.map(upload => {

                return upload.academic_year.split('/')[0].replace('A.Y.', '').trim();
            });
            this.filterAcademicYearOptions = [...new Set(academicYears)];
        },

        fetchFilterSemOptions() {
            const semesters = this.originalApprovedByDeans.map(upload => {

                return upload.academic_year.split('/')[1].trim();
            });
            this.filterSemesterOptions = [...new Set(semesters)];
        },

        fetchFilterTermOptions() {
            const terms = this.originalApprovedByDeans.map(upload => {

                return upload.academic_year.split('/')[2].trim();
            });
            this.filterTermOptions = [...new Set(terms)];
        },
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },
    },
    mounted() {
        this.fetchFilterFacultyOptions();
        this.fetchFilterDeliverableOptions();
        this.fetchFilterProgramOptions();
        const program = this.$route.params.program;
        const isDean = this.$route.params.program === 'DEAN';
        const isAA = this.$route.params.program === 'AA';

        let apiUrl = 'http://127.0.0.1:8000/api/get-approvedByDean';

        if (!isDean && !isAA) {
            apiUrl += `/${program}`;
        }

        axios.get(apiUrl)
            .then(response => {
                if (Array.isArray(response.data)) {
                    this.originalApprovedByDeans = response.data;
                    this.approvedByDeans = response.data;
                    console.log(' this.approvedByDeans', response.data);
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
    /* Change to your desired header color */
    height: 40px;
    font-weight: 700;
    font-size: medium;
}


.custom-title {
    color: #000000;
    /* Change to your desired title text color */
    font-size: 20px;
    font-weight: bold;

}
</style>
  