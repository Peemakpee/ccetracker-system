<template>
    <div class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Detailed View of the Submitted Deliverable</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary card-outline card-custom-outline">
                            <div class="card-header">
                                <h5 class="m-0 custom-title">Document Viewer</h5>
                            </div>
                            <div class="card-body">

                                <div class="card card-info">

                                    <iframe :src="googleViewLink" style="width:100%; height:600px;"
                                        frameborder="0"></iframe>
                                </div>

                                <div style="text-align: right;">
                                    <button class="btn custom-button" @click="downloadFile">Download</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header custom-docheader">
                                <h5 class="m-0 custom-title">Document Details</h5>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">File Name</dt>
                                    <dd class="col-sm-8">{{ document.file_name }}</dd>
                                    <dt class="col-sm-4">Submitted By</dt>
                                    <dd class="col-sm-8">{{ document.user_name }}</dd>
                                    <dt class="col-sm-4">Program</dt>
                                    <dd class="col-sm-8">{{ document.program }}</dd>
                                    <dt class="col-sm-4">Type</dt>
                                    <dd class="col-sm-8">{{ document.deliverable_type }}</dd>
                                    <dt class="col-sm-4">Subject</dt>
                                    <dd class="col-sm-8">{{ document.subject }}</dd>
                                    <dt class="col-sm-4">Subject Code</dt>
                                    <dd class="col-sm-8">{{ document.subject_code }}</dd>
                                    <dt class="col-sm-4">Date Uploaded</dt>
                                    <dd class="col-sm-8">{{ formatDate(document.created_at) }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="card card-danger">
                            <div class="card-header custom-docheader">
                                <h5 class="m-0 custom-title">Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="comment">Comment:</label>
                                    <textarea id="comment" class="form-control" v-model="document.comment"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" class="form-control" v-model="document.status">
                                        <option value="Approve">Approve</option>
                                        <option value="To Be Modified">For Revision</option>
                                    </select>
                                </div>

                                <div class="form-group" v-if="document.status !== 'To Be Modified'">
                                    <label for="signedFile">Upload Signed File:</label>
                                    <input type="file" id="signedFile" ref="signedFile" class="form-control-file"
                                        @change="handleFileChange" />
                                </div>
                                <div style="text-align: right;">
                                    <button class="btn custom-button" @click="saveChanges" :disabled="loadingSaveChanges">
                                        <span v-if="!loadingSaveChanges">Save Changes</span>
                                        <span v-else>
                                            <i class="fas fa-circle-notch fa-spin"></i> Saving...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>



<script>
import axios from 'axios';
import { storage } from '../../../firebase';
import { uploadBytes, getDownloadURL, ref as storageRef } from 'firebase/storage';

export default {
    data() {
        return {
            document: {
                fileName: '',
                submittedBy: '',
                program: '',
                deliverableType: '',
                firebaseUrl_wSign: '',
                status: '',
                comment: '',
                filePath: '',
                academicYear: '',
                documentId: '',
                userId: '',
                dateUploadByUser: '',
                username: '',
                deadline_id: null,
            },
            loadingSaveChanges: false,
            signedFile: null,
            folderPath: '',

        };
    },
    computed: {
        googleViewLink() {
            const url = this.document.firebase_url || 'fallback_value';
            return `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
        },
    },
    methods: {
        handleFileChange(event) {
            this.signedFile = event.target.files[0];

        },
        async uploadSignedFile() {
            if (!this.signedFile) {
                console.error('No file selected.');
                return;
            }

            const documentId = this.$route.params.id;
            this.folderPath = `tracking/phase1/${this.document.status.toLowerCase()}_ph/${this.document.program}/${this.document.academic_year}/user ${this.document.user_id}`;
            const storageRefInstance = storageRef(storage, `${this.folderPath}/${this.signedFile.name}`);

            try {
                const snapshot = await uploadBytes(storageRefInstance, this.signedFile);
                const downloadURL = await getDownloadURL(snapshot.ref);

                this.document.firebaseUrl_wSign = downloadURL;
                this.signedFile = null;

            } catch (error) {
                console.error('Error uploading signed file:', error);
            }
        },
        downloadFile() {
            if (this.document.firebase_url) {

                window.open(this.document.firebase_url, '_blank');
            } else {

                console.error('Firebase URL is not available.');
            }
        },
        formatDate(dateTime) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateTime).toLocaleDateString('en-US', options);
        },

        async saveChanges() {
            this.loadingSaveChanges = true;
            const documentId = this.$route.params.id;
            const status = this.document.status;
            const comment = this.document.comment;
            const deliverableType = this.document.deliverable_type;
            const fileName = this.signedFile ? this.signedFile.name : null;
            const academicYear = this.document.academic_year;
            const program = this.document.program;
            const userId = this.document.user_id;
            const username = this.document.user_name;
            const deadlineId = this.document.deadline_id;

            const dateUploadByUser = new Date(this.document.created_at).toISOString().slice(0, 19).replace('T', ' ');

            this.folderPath = `tracking/phase1/${this.document.status.toLowerCase()}_ph/${this.document.program}/${this.document.academic_year}/user ${this.document.user_id}`;

            if (status === 'Approve' || status === 'To Be Modified') {
                await this.uploadSignedFile();

                const statusUpdateData = {
                    documentId: documentId,
                    status: status,
                };

                try {

                    const statusUpdateEndpoint = 'http://127.0.0.1:8000/api/update-upload-status';

                    const response = await axios.post(statusUpdateEndpoint, statusUpdateData);
                    console.log('API Response:', response.data);
                } catch (error) {
                    console.error('An error occurred while updating status in upload_files table:', error);
                }
            }

            if (status === 'Approve' || status === 'To Be Modified') {
                await this.uploadSignedFile();

                const statusUpdateDataReupload = {
                    documentId: documentId,
                    status: status,
                };

                try {

                    const statusUpdateEndpointReupload = 'http://127.0.0.1:8000/api/update-reupload-status';

                    const responseReupload = await axios.post(statusUpdateEndpointReupload, statusUpdateDataReupload);
                    console.log('API Response for reuploads:', responseReupload.data);
                } catch (error) {
                    console.error('An error occurred while updating status in reuploads table:', error);
                }
            }

            if (status === 'Approve' || status === 'To Be Modified') {
                await this.uploadSignedFile();

                const formData = new FormData();
                formData.append('document_id', documentId);
                formData.append('status', status);
                formData.append('comment', comment);

                if (status === 'To Be Modified') {
                    formData.append('firebaseUrl_wSign', this.document.firebase_url); 
                    formData.append('file_name', this.document.file_name); 
                    formData.append('file_path', this.folderPath); 
                } else {
                    formData.append('firebaseUrl_wSign', this.document.firebaseUrl_wSign);
                    formData.append('file_name', fileName);
                    formData.append('file_path', this.folderPath);
                }

                formData.append('date_upladedByUser', dateUploadByUser);
                formData.append('deliverable_type', deliverableType);
                formData.append('academic_year', academicYear);
                formData.append('program', program);
                formData.append('user_id', userId);
                formData.append('user_name', username);
                formData.append('deadline_id', deadlineId);
                formData.append('subject', this.document.subject);
                formData.append('subject_code', this.document.subject_code);

                try {
                    let apiEndpoint = '';
                    if (status === 'Approve') {
                        apiEndpoint = 'http://127.0.0.1:8000/api/approved-ph';
                    } else if (status === 'To Be Modified') {
                        apiEndpoint = 'http://127.0.0.1:8000/api/tbm-ph';
                    }

                    const response = await axios.post(apiEndpoint, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    });
                    console.log('API Response:', response.data);
                } catch (error) {
                    console.error('An error occurred while saving changes:', error);
                }
            } else {
                console.error('Invalid status:', status);
            }

            this.loadingSaveChanges = false;
            this.document.status = '';
            this.document.comment = '';

            this.$router.push({ name: 'admin.pending', params: { program: this.document.program } });
        },
    },
    mounted() {
        const documentId = this.$route.params.id;
        const source = this.$route.query.source;

        let apiUrl;
        if (source === 'phase1') {
            apiUrl = `http://127.0.0.1:8000/api/get-uploaded/${documentId}`;
        } else if (source === 'reupload') {
            apiUrl = `http://127.0.0.1:8000/api/get-reuploaded/${documentId}`;
        } else {
            console.error('Invalid source');
            return;
        }

        axios.get(apiUrl)
            .then(response => {
                if (source === 'reupload' && response.data.data) {

                    this.document = { ...this.document, ...response.data.data };
                } else {

                    this.document = { ...this.document, ...response.data };
                }
                console.log("Updated Document:", this.document);
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
    font-weight: 600;
    font-size: large;
}


.custom-title {
    color: #000000;

    font-size: 20px;
    font-weight: bold;

}

.card-custom-outline {
    border-top: 3.5px solid #CFAE46;

}
</style>