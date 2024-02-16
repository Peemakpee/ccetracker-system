<template>
    <section class="content">
        <div class="card">
            <div class="card-header custom-docheader">
                <h3 class="m-0 custom-title text-center">Uploaded Deliverable Template</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 30%">Template Type</th>
                            <th style="width: 40%">Date Uploaded</th>
                            <th style="width: 30%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(group, type) in templates" :key="type">
                            <tr v-for="(template, index) in group" :key="template.id">
                                <template v-if="index === 0">
                                    <td>{{ template.type }}</td>
                                    <td>{{ formatDate(template.created_at) }}</td>
                                    <td class="project-actions text-left">
                                        <router-link :to="`/admin/addTemplates/${template.id}`"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-folder"></i> View
                                        </router-link>
                                        <button class="btn btn-danger btn-sm" @click="deleteTemplate(template.type)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </template>
                            </tr>
                        </template>
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
                <div v-if="paginatedTemplates.length === 0" class="text-center">
                    No Templates available.
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
            templates: [],
            currentPage: 1,    
            perPage: 8,
        };
    },
    computed: {
        paginatedTemplates() {
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            return this.templates.slice(startIndex, endIndex);
        },
        totalPages() {
            return Math.ceil(this.templates.length / this.perPage);
        },
    },
    methods: {
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },
        deleteTemplate(templateType) {
            if (!confirm('Are you sure you want to delete this template?')) {
                return;
            }
            axios
                .delete(`http://127.0.0.1:8000/api/delete-template/${templateType}`)
                .then(() => {
                    this.removeTemplateFromList(templateType);
                })
                .catch(error => {
                    console.error('An error occurred while deleting the template:', error);
                    alert('Failed to delete the template. Please try again.');
                });
        },
        removeTemplateFromList(templateType) {
            const index = this.templates.findIndex(template => template[0].type === templateType);

            if (index !== -1) {
                this.templates.splice(index, 1);
            }
        },
        formatDate(dateTime) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateTime).toLocaleDateString('en-US', options);
        }
    },
    mounted() {
        axios
            .get('http://127.0.0.1:8000/api/get-templates')  
            .then(response => {
                const templates = response.data;
                const groupedTemplates = {};
                templates.forEach(template => {
                    if (!groupedTemplates[template.type]) {
                        groupedTemplates[template.type] = [];
                    }
                    groupedTemplates[template.type].push(template);
                });
                this.templates = Object.values(groupedTemplates);
            })
            .catch(error => {
                console.error('An error occurred while fetching templates:', error);
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
