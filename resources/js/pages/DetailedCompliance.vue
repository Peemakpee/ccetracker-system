<template>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center mb-4">
                        Detailed View for Faculty Compliance
                    </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="text-lg">Deadline for this Deliverable: {{ formatDate(deadline.deadline) }}</h2>
                    <h2 class="text-lg">Deliverable Type: {{ deadline.deliverable_type }}</h2>
                    <h2 class="text-lg">Program: {{ deadline.program }}</h2>
                </div>
                <div class="col-lg-6 d-flex align-items-center justify-content-end">
                    <button v-if="nonCompliantUsers.length > 0" @click="generatePDF" class="custom-buttonText mt-5">Generate
                        Report</button>
                </div>
            </div>
            <div class="row">

                <div ref="pdfModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
                    aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">PDF Modal Title</h5>
                            </div>
                            <div class="modal-body">
                                <!-- Your table structure goes here -->
                                <table>
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Faculty Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in nonCompliantUsers" :key="item.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ item.name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" @click="generatePDF">Generate PDF</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header custom-docheader">
                            <h2 class="card-title custom-title">Submitted</h2>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Date Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="file in uploadFiles" :key="file.id">
                                        <td>{{ file.user_name }}</td>
                                        <td>{{ formatDate(file.created_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header custom-docheader">
                            <h2 class="card-title custom-title">Not Submitted Yet</h2>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in nonCompliantUsers" :key="user.id">
                                        <td>{{ user.name }}</td>
                                        <td>
                                            <button
                                                @click="sendReminder(user.id, deadline.program, deadline.deliverable_type)"
                                                class="custom-button">Send
                                                Reminder</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="success-banner" v-if="successBannerVisible">
            {{ successMessage }}
        </div>
    </div>
</template>



<script>
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import axios from 'axios';

export default {
    data() {
        return {
            deadline: {},
            uploadFiles: [],
            nonCompliantUsers: [],
            successBannerVisible: false,
            successMessage: '',
            currentUsername: ''
        };
    },
    computed: {
        pdfTableData() {
            if (Array.isArray(this.nonCompliantUsers) && this.nonCompliantUsers.length > 0) {
                return [
                    ["No.", "Faculty Name"],
                    ...this.nonCompliantUsers.map((item, index) => [index + 1, item.name])
                ];
            } else {
                return [["No data available"]];
            }
        },
        deadlineId() {
            return this.$route.params.deadlineId;
        },
        currentUser() {
            return this.$route.params.id;
        }
    },
    methods: {
        generatePDF() {

            const doc = new jsPDF();

            const logoPath = '/images/ccelogo.png';
            doc.addImage(logoPath, 'PNG', 23, -2, 35, 35);

            const umlogoPath = '/images/umlogo.png';
            doc.addImage(umlogoPath, 'PNG', 9, 5.5, 20, 20);

            doc.text('Non-Compliance Summary Report', 10, 40);
            doc.setFontSize(12);
            doc.text(`Deadline: ${this.formatDate(this.deadline.deadline)}`, 10, 55);
            doc.text(`Deliverable Type: ${this.deadline.deliverable_type}`, 10, 60);
            doc.text(`Program: ${this.deadline.program}`, 10, 65);

            this.fetchCurrentUser();

            setTimeout(() => {
                const generatedByText = `Generated by: ${this.currentUsername.name}`;
                doc.text(generatedByText, 10, 70); // Adjust the Y-coordinate as needed

                const generatedDate = new Date().toLocaleString();
                const generatedDateText = `Generated on: ${generatedDate}`;
                doc.text(generatedDateText, 10, 75);

                const startY = 85;
                const tableData = this.pdfTableData;

                console.log(tableData);

                const headStyles = {
                    fillColor: [207, 174, 70],
                    textColor: [0, 0, 0],
                    fontStyle: 'bold',
                };

                doc.autoTable({
                    head: [tableData[0]],
                    body: tableData.slice(1),
                    startY,
                    headStyles,

                });

                doc.save(`${this.deadline.deliverable_type}_${this.formatDate(this.deadline.deadline)}_non_compliance_report.pdf`);
            }, 500);
        },
        showSuccessBanner(message) {
            this.successMessage = message;
            this.successBannerVisible = true;

            setTimeout(() => {
                this.successBannerVisible = false;
                this.successMessage = '';
            }, 1500);
        },
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = new Date(dateString);
            return date.toLocaleDateString(undefined, options);
        },
        fetchDeadline() {
            axios
                .get(`http://127.0.0.1:8000/api/get-deadlines-upload/${this.deadlineId}`)
                .then(response => {
                    this.deadline = response.data;
                })
                .catch(error => {
                    console.error('An error occurred while fetching the deadline:', error);
                });
        },
        fetchUploadFiles() {
            axios
                .get(`http://127.0.0.1:8000/api/get-upload-by-deadline/${this.deadlineId}`)
                .then(response => {
                    this.uploadFiles = response.data;
                })
                .catch(error => {
                    console.error('An error occurred while fetching upload files data:', error);
                });
        },
        fetchNonCompliantUsers() {
            axios
                .get(`http://127.0.0.1:8000/api/get-non-compliant-users/${this.deadlineId}`)
                .then(response => {
                    this.nonCompliantUsers = Array.isArray(response.data)
                        ? response.data
                        : Object.values(response.data);
                    console.log("Non-compliant users:", this.nonCompliantUsers);
                })
                .catch(error => {
                    console.error('An error occurred while fetching non-compliant users data:', error);
                });
        },
        fetchCurrentUser() {
            console.log('Current User ID:', this.currentUser);
            axios
                .get(`http://127.0.0.1:8000/api/get-currrent-user/${this.currentUser}`)
                .then(response => {
                    this.currentUsername = response.data;
                    console.log('Current User:', this.currentUsername);
                })
                .catch(error => {
                    console.error('An error occurred while fetching upload files data:', error);
                });
        },
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            const date = new Date(dateString);
            return date.toLocaleDateString(undefined, options);
        },
        sendReminder(userId, program, deliverableType) {
            axios
                .post(`http://127.0.0.1:8000/api/send-reminder/${userId}/${program}/${deliverableType}`)
                .then(response => {
                    this.showSuccessBanner('Reminder sent successfully.');
                })
                .catch(error => {
                    console.error('An error occurred while sending the reminder:', error);
                });
        },
        getPDFTableData() {
            const tableData = [];

            if (this.$refs.pdfModal) {
                const tableRows = this.$refs.pdfModal.querySelectorAll('tr');

                tableRows.forEach((row) => {
                    const rowData = [];
                    const cells = row.querySelectorAll('td, th');

                    cells.forEach((cell) => {
                        rowData.push(cell.innerText);
                    });

                    tableData.push(rowData);
                });
            }

            return tableData;
        }

    },
    mounted() {
        this.fetchUploadFiles();
        this.fetchNonCompliantUsers();
        this.fetchDeadline();
        this.fetchCurrentUser();
    }
};
</script>


<style scoped>
.custom-docheader {
    background-color: #CFAE46 !important;

}

.custom-button {
    background-color: #CFAE46 !important;
    height: 24px;
    font-weight: 600;
    font-size: small;
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


.custom-title {
    color: #000000;
    font-size: 20px;
    font-weight: bold;

}

.custom-buttonText {
    color: #ffffff;
    font-size: 18px;
    background-color: rgb(9, 153, 33);
}
</style>
