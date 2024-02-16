<template>
    <section class="content">
        <div class="card">
            <div class="card-header custom-docheader">
                <h3 class="card-title custom-title">Memos</h3>
                <div class="filter-bar float-right">
                    <label for="dateFilter">Filter by Date:</label>
                    <input type="date" id="dateFilter" v-model="selectedDate" @change="updateTable">
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 17%">Memo Subject</th>
                            <th style="width: 17%">Memo From</th>
                            <th style="width: 17%">Date and Time Submitted</th>
                            <th style="width: 10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="memo in filteredMemos" :key="memo.id">
                            <td>{{ memo.memo_subject }}</td>
                            <td>{{ memo.memo_from }}</td>
                            <td>{{ formatDate(memo.created_at) }}</td>
                            <td class="project-actions text-left">
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <router-link :to="`/admin/detailedViewMemos/${memo.id}`" class="btn btn-primary btn-sm">
                                        <i class="fas fa-folder"></i> View
                                    </router-link>
                                    <button class="btn btn-danger btn-sm" @click="confirmDelete(memo.id)"
                                        v-if="memo.memo_to !== 'All PH'">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="filteredMemos.length === 0" class="text-center">
            No Memos available.
        </div>
    </section>
</template>

<script>
import axios from 'axios';
import { format } from 'date-fns';

export default {
    data() {
        return {
            memos: [],
            selectedDate: null,
        };
    },
    computed: {
        filteredMemos() {
            if (!this.selectedDate) {
                return this.memos;
            }

            const selectedDate = new Date(this.selectedDate);
            return this.memos.filter(memo => {
                const memoDate = new Date(memo.created_at);
                return memoDate.toISOString().split('T')[0] === selectedDate.toISOString().split('T')[0];
            });
        },
    },
    methods: {
        formatDate(dateTime) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateTime).toLocaleDateString('en-US', options);
        },
        async fetchMemosForProgramHeads() {
            try {
                const phId = this.$route.params.id;
                let apiUrl = `http://127.0.0.1:8000/api/memos-for-program-heads/${phId}`;

                if (this.selectedDate) {
                    apiUrl += `?date=${this.selectedDate}`;
                }

                const response = await axios.get(apiUrl);
                this.memos = response.data;
            } catch (error) {
                console.error('Error fetching memos:', error);
            }
        },

        confirmDelete(id) {
            const shouldDelete = window.confirm("Are you sure you want to delete this memo?");
            if (shouldDelete) {
                this.deleteMemo(id);
            }
        },

        deleteMemo(id) {
            axios
                .delete(`http://127.0.0.1:8000/api/delete-memo/${id}`)
                .then(() => {
                    this.memos = this.memos.filter(upload => upload.id !== id);
                })
                .catch(error => {
                    console.error('An error occurred while deleting the upload:', error);
                });
        },
    },
    mounted() {
        const formattedDate = format(new Date(this.selectedDate), 'yyyy-MM-dd HH:mm:ss');
        this.fetchMemosForProgramHeads();
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
    font-size: 26px;
    font-weight: bold;
}
</style>
