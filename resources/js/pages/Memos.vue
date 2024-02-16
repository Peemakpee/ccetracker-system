<template>
    <div class="container">
        <form @submit.prevent="submitMemo">
            <div class="card custom-card">
                <div class="card-header custom-header">
                    <h1 class="custom-title">Upload Memo</h1>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="memo_from">From:</label>
                        <input type="text" id="memo_from" v-model="memo.memo_from" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="memo_to">To:</label>
                        <select id="memo_to" v-model="selectedRecipient" class="form-control" @change="handleToChange"
                            required>
                            <option value="">Select Recipient</option>
                            <option value="All PH">To All Program Heads</option>
                            <option value="All Faculty">To All Faculty Members</option>
                            <option value="Individual">Individual Recipient</option>
                        </select>
                        <div v-if="selectedRecipient === 'Individual'" class="form-group mt-3">
                            <label for="individualRecipient">Recipient Name:</label>
                            <select id="individualRecipient" v-model="memo.individual_recipient" class="form-control"
                                required>
                                <option value="">Select Recipient Name</option>
                                <option v-for="recipient in recipientOptions" :key="recipient" :value="recipient">{{
                                    recipient }}</option>
                            </select>
                            <div v-if="recipientNameError" class="text-sm mt-2" style="color: red;">Recipient Name is not
                                valid.</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="memo_subject">Subject:</label>
                        <input type="text" id="memo_subject" v-model="memo.memo_subject" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="memoFile">Upload Memo File:</label>
                        <input type="file" id="memoFile" ref="memoFile" class="form-control-file" @change="handleFileChange"
                            required />
                    </div>
                    <div class="form-group">
                        <div v-if="uploading" class="text-center py-4">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" :style="{ width: uploadProgress + '%' }">
                                    {{ uploadProgress }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn custom-button" :disabled="uploading">
                            <span v-if="uploading">Uploading...</span>
                            <span v-else>Submit Memo</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
  
<script>
import axios from 'axios';
import { storage } from '../../../firebase';
import { ref as storageRef, uploadBytesResumable, getDownloadURL } from 'firebase/storage';

export default {
    data() {
        return {
            memo: {
                memo_from: '',
                memo_to: '',
                memo_subject: '',
                file: null,
            },
            recipientNameError: false,
            selectedRecipient: '',
            uploading: false,
            uploadProgress: 0,
            recipientOptions: [],
        };
    },
    mounted() {
        this.fetchRecipientNames();
    },
    methods: {
        async fetchRecipientNames() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/validate-recipient');
                this.recipientOptions = response.data;
            } catch (error) {
                console.error('An error occurred while fetching recipient names:', error);
            }
        },
        handleToChange() {
            this.memo.memo_to = this.selectedRecipient;
            if (this.selectedRecipient !== 'Individual') {
                this.memo.individual_recipient = '';
            }
        },
        async submitMemo() {
            const recipientName = this.memo.individual_recipient;

            if (this.selectedRecipient === 'Individual') {
                if (!this.recipientOptions.includes(recipientName)) {
                    this.recipientNameError = true;
                    console.error('Invalid recipient name. Please enter a valid name.');
                    return;
                }
            } else if (this.selectedRecipient === 'All PH') {
            } else if (this.selectedRecipient === 'All Faculty') {
            }
            try {
                const memoFile = this.$refs.memoFile.files[0];
                if (!memoFile) {
                    console.error('No file selected.');
                    return;
                }
                const storageRefInstance = storageRef(storage, 'memo/' + memoFile.name);
                const uploadTask = uploadBytesResumable(storageRefInstance, memoFile);

                uploadTask.on(
                    'state_changed',
                    (snapshot) => {
                        this.uploadProgress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                    },
                    (error) => {
                        console.error('Error uploading file:', error);
                        this.uploading = false;
                        this.uploadProgress = 0;
                    },
                    async () => {
                        const downloadURL = await getDownloadURL(uploadTask.snapshot.ref);
                        const memoData = {
                            memo_from: this.memo.memo_from,
                            memo_to: this.memo.memo_to,
                            memo_subject: this.memo.memo_subject,
                            firebase_url: downloadURL,
                        };

                        const response = await axios.post('http://127.0.0.1:8000/api/upload-memo', memoData);

                        console.log('Memo uploaded successfully:', response.data);
                        this.memo = {
                            memo_from: '',
                            memo_to: '',
                            memo_subject: '',
                            file: null,
                        };
                        this.selectedRecipient = '';
                        this.$refs.memoFile.value = null;
                        this.uploadProgress = 0;
                        this.uploading = false;
                    }
                );
                this.uploading = true;
            } catch (error) {
                console.error('Error uploading memo:', error);
                this.uploading = false;
                this.uploadProgress = 0;
            }
        },
        handleFileChange() {
        },
    },
    watch: {
        'memo.individual_recipient': function (newRecipient) {
            if (this.selectedRecipient === 'Individual') {
                this.memo.memo_to = newRecipient;
            }
        },
    },
};
</script>
  
<style scoped>
.custom-header {
    background-color: #CFAE46 !important;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.custom-button {
    background-color: #CFAE46 !important;
    height: 45px;
    font-weight: 600;
    font-size: large;
}

.custom-title {
    color: #000000;
    font-size: 28px;
    font-weight: bold;
}

.custom-card {
    max-width: 100%;
    margin: 0 auto;
}
</style>
  