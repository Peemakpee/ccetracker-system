<template>
    <section class="content">
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="alert alert-danger">
            {{ errorMessage }}
        </div>
        <div class="card">
            <div class="card-header custom-docheader">
                <h3 class="card-title custom-title">Users Registration Requests</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th style="width: 17%">Name</th>
                            <th style="width: 17%">Email Address</th>
                            <th style="width: 17%">Program</th>
                            <th style="width: 9%">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="request in requests" :key="request.id">

                            <td>{{ request.name }}</td>
                            <td>{{ request.email }}</td>

                            <td>{{ request.program }}</td>
                            <td class="project-actions text-left">
                                <div class="btn-group" role="group" aria-label="Actions">
                                    <button @click="approveRequest(request.id)" class="btn btn-primary btn-sm">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button @click="disapproveRequest(request.id)" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> Disapprove
                                    </button>
                                </div>


                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>
        <div v-if="requests.length === 0" class="text-center">
            No registration requests available.
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            requests: [], 
            successMessage: null,
            errorMessage: null,
        };
    },
    methods: {
        async fetchRegistrationRequests() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/get-registration-requests'); // Replace with your API endpoint
                this.requests = response.data;
                console.log('API response:', response.data);
            } catch (error) {
                console.error('Error fetching memos:', error);
            }
        },
        async approveRequest(requestorId) {
            try {
                const response = await axios.post(`http://127.0.0.1:8000/api/approve-request/${requestorId}`); // Replace with your API endpoint
                console.log('Approval response:', response.data);
                this.requests = this.requests.filter(request => request.id !== requestorId);
                this.successMessage = "The user has been approved";

                setTimeout(() => {
                    this.successMessage = null;
                }, 1500);
            } catch (error) {
                console.error('Error approving request:', error);
                this.errorMessage = "There was an error approving the user";

                setTimeout(() => {
                    this.errorMessage = null;
                }, 1500);
            }
        },
        async disapproveRequest(requestorId) {
            try {
                const response = await axios.post(`http://127.0.0.1:8000/api/disapprove-request/${requestorId}`); // Replace with your API endpoint
                console.log('Disapproval response:', response.data);

                this.requests = this.requests.filter(request => request.id !== requestorId);
                this.errorMessage = "The user has been disapproved";

                setTimeout(() => {
                    this.errorMessage = null;
                }, 1500);
            } catch (error) {
                console.error('Error disapproving request:', error);
                this.errorMessage = "There was an error disapproving the user";

                setTimeout(() => {
                    this.errorMessage = null;
                }, 1500);
            }
        },
    },
    mounted() {
        this.fetchRegistrationRequests();
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
