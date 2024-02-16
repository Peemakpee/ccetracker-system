<template>
    <section class="content">
        <div v-if="programHead">
            <h4>Program Head: {{ programHead.name }}</h4>
        </div>
        <div class="card custom-card">
            <div class="card-header custom-docheader">
                <h3 class="m-0 custom-title text-center">All Users</h3>
                <select v-model="selectedProgram" @change="filterUsers">
                    <option value="">All Programs</option>
                    <option v-for="program in programs" :value="program">{{ program }}</option>
                </select>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 30%">User Name</th>
                            <th style="width: 20%">Program</th>
                            <th style="width: 10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(programUsers, program) in paginatedUsers" :key="program">
                            <tr v-for="user in programUsers" :key="user.id">
                                <td>{{ user.name }}</td>
                                <td>{{ program }}</td>
                                <td>
                                    <select v-if="!user.is_admin" v-model="user.status" @change="updateUserStatus(user)">
                                        <option value="on_study_leave">Resigned</option>
                                        <option value="on_leave">On Leave</option>
                                        <option value="no_load">No Load</option>
                                        <option value="active">Active</option>
                                    </select>
                                    <span v-else>Admin User</span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example" class="d-flex justify-content-end p-2">
                    <ul class="pagination">
                        <li class="page-item" :class="{ disabled: currentPage === 1 }">
                            <a class="page-link" @click="changePage(currentPage - 1)" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item" v-for="page in totalPages" :key="page"
                            :class="{ active: currentPage === page }">
                            <a class="page-link" @click="changePage(page)">{{ page }}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                            <a class="page-link" @click="changePage(currentPage + 1)" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
</template>
  
<script>
import axios from 'axios';

export default {
    data() {
        return {
            usersByProgram: {},
            selectedProgram: '',
            programHead: null,
            perPage: 7,
            currentPage: 1,
        };
    },
    mounted() {
        axios.get('http://127.0.0.1:8000/api/users-by-program')
            .then(response => {
                this.usersByProgram = response.data;
            })
            .catch(error => {
                console.error('Error fetching users:', error);
            });
    },
    computed: {
        programs() {
            return Object.keys(this.usersByProgram);
        },
        filteredUsersByProgram() {
            if (this.selectedProgram) {
                return { [this.selectedProgram]: this.usersByProgram[this.selectedProgram] };
            } else {
                return this.usersByProgram;
            }
        },
        paginatedUsers() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;

            const allUsers = [].concat(...Object.values(this.filteredUsersByProgram));

            const paginatedUsers = allUsers.slice(start, end);

            return Object.fromEntries(
                Object.entries(this.filteredUsersByProgram).map(([key, value]) => [
                    key,
                    paginatedUsers.filter(user => user.program === key),
                ])
            );
        },
        totalPages() {

            const allUsers = [].concat(...Object.values(this.filteredUsersByProgram));

            return Math.ceil(allUsers.length / this.perPage);
        },
    },
    methods: {
        filterUsers() {
            if (this.selectedProgram) {
                this.getProgramHead(this.selectedProgram);
            } else {
                this.programHead = null;
            }
        },
        getProgramHead(program) {
            axios.get(`http://127.0.0.1:8000/api/program-head/${program}`)
                .then(response => {
                    this.programHead = response.data;
                })
                .catch(error => {
                    console.error('Error fetching program head:', error);
                });
        },
        updateUserStatus(user) {
            axios.put(`http://127.0.0.1:8000/api/update-user-status/${user.id}`, { status: user.status })
                .then(response => {
                    console.log('User status updated successfully:', response.data);
                })
                .catch(error => {
                    console.error('Error updating user status:', error);
                });
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
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

.pagination a,
.pagination span {
    font-size: 16px;
    padding: 4px 3px;
}

</style>