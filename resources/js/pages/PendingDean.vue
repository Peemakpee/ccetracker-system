<template>
  <section class="content">
    <div class="card">
      <div class="card-header custom-docheader">
        <h3 class="card-title custom-title">Pending Deliverables for Approval of the Dean</h3>
        <div class="search-bar float-right">
          <input type="text" v-model="searchQuery" placeholder="Search...">
          <i class="fas fa-search ml-1"></i>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped projects">
          <thead>
            <tr>
              <th style="width: 10%">Program</th>
              <th style="width: 10%">Faculty Name</th>
              <th style="width: 10%">Deliverable Type</th>
              <th style="width: 10%">Academic Year</th>
              <th style="width: 10%">Date and Time Uploaded</th>
              <th style="width: 4%">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="upload in (searchQuery ? filteredCombinedUploads : paginatedCombinedUploads)" :key="upload.id">
              <td>{{ upload.program }}</td>
              <td>{{ upload.user_name }}</td>
              <td>{{ upload.deliverable_type }}</td>
              <td>{{ upload.academic_year }}</td>
              <td>{{ formatDate(upload.created_at) }}</td>
              <td class="project-actions text-right">
                <div class="btn-group" role="group" aria-label="Actions">
                  <router-link :to="`/admin/detailedApprovedPh/${upload.id}?source=${upload.source}`"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-folder"></i> View
                  </router-link>
                  <button class="btn btn-danger btn-sm" @click="confirmArchiveUpload(upload.id, upload.source)">
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
            <li class="page-item" v-for="page in totalPages" :key="page" :class="{ 'active': currentPage === page }">
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
        <div v-if="paginatedCombinedUploads.length === 0" class="text-center">
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
      phase2Uploads: [],
      reuploadsDean: [],
      currentPage: 1,
      perPage: 6,
      searchQuery: '',
    };
  },
  computed: {
    filteredCombinedUploads() {
      return this.combinedUploads.filter(upload => {
        const search = this.searchQuery.toLowerCase();
        return (
          upload.user_name.toLowerCase().includes(search) ||
          upload.deliverable_type.toLowerCase().includes(search) ||
          upload.academic_year.toLowerCase().includes(search) ||
          upload.program.toLowerCase().includes(search)
        );
      });
    },
    combinedUploads() {
      const normalizedPhase2Uploads = this.phase2Uploads.map(upload => ({
        id: upload.id,
        user_name: upload.user_name,
        deliverable_type: upload.deliverable_type,
        academic_year: upload.academic_year,
        created_at: upload.created_at,
        program: upload.program,
        source: 'phase2'
      }));

      const normalizedReuploadsDean = this.reuploadsDean.map(upload => ({
        id: upload.document_id, 
        user_name: upload.user_name,
        deliverable_type: upload.deliverable_type,
        academic_year: upload.academic_year,
        created_at: upload.created_at,
        program: upload.program,
        source: 'reuploadDean'
      }));

      return [...normalizedPhase2Uploads, ...normalizedReuploadsDean];
    },
    paginatedCombinedUploads() {
      const startIndex = (this.currentPage - 1) * this.perPage;
      const endIndex = startIndex + this.perPage;
      return this.combinedUploads.slice(startIndex, endIndex);
    },
    totalPages() {
      return Math.ceil(this.combinedUploads.length / this.perPage);
    },
  },
  methods: {
    clearSearch() {
      this.searchQuery = '';
      this.currentPage = 1; 
    },
    confirmArchiveUpload(id, source) {

      if (confirm('Are you sure you want to archive this entry? The file will be moved to the archives and no longer visible in the active uploads.')) {

        this.archiveUpload(id, source);
      }
    },
    formatDate(dateTime) {
      const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
      return new Date(dateTime).toLocaleDateString('en-US', options);
    },
    archiveUpload(id, source) {
      console.log('archive ID:', id, 'source:', source);
      axios
        .post(`http://127.0.0.1:8000/api/archive-approvePh/${id}`, { source })
        .then(() => {
          if (source === 'phase2') {
            this.phase2Uploads = this.phase2Uploads.filter(upload => upload.id !== id);
          } else if (source === 'reuploadDean') {
            this.reuploadsDean = this.reuploadsDean.filter(upload => upload.document_id !== id);
          }
        })
        .catch(error => {
          console.error('An error occurred while archiving the upload:', error);
        });
    },
    setPage(pageNumber) {
      this.currentPage = pageNumber;
    },
  },
  mounted() {
    axios
      .get('http://127.0.0.1:8000/api/get-approvedPh')
      .then(response => {
        if (Array.isArray(response.data)) {
          this.phase2Uploads = response.data;
        } else {
          console.error('Unexpected data format:', response.data);
        }
      })
      .catch(error => {
        console.error('An error occurred while fetching data:', error);
      });
    axios
      .get('http://127.0.0.1:8000/api/get-reuploadDean')
      .then(response => {
        if (Array.isArray(response.data)) {
          this.reuploadsDean = response.data;
          console.log('Reuploads:', this.reuploadsDean);
        } else {
          console.error('Unexpected data format:', response.data);
        }
      })
      .catch(error => {
        console.error('An error occurred while fetching data:', error);
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