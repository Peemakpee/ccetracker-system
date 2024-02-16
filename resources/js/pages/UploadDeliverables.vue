<template>
    <div>
        <div class="success-banner" v-if="successBannerVisible">
            {{ successMessage }}
        </div>
        <div class="card custom-card">
            <div class="card-header custom-docheader">
                <h5 class="m-0 custom-title text-center">Upload Deliverable Template</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Please use the SAME deliverable type for uploading additional template versions.
                </p>
                <div class="form-group">
                    <label class="text-lg" for="templateType">Deliverable Type:</label>
                    <input id="templateType" type="text" class="form-control" v-model="template.type">
                </div>
                <div class="form-group">
                    <label class="text-lg" for="templateFile">Upload Template File:</label>
                    <input type="file" id="templateFile" ref="templateFile" class="form-control-file"
                        @change="handleFileChange" />
                </div>
                <div style="text-align: right;">
                    <button class="btn custom-button" @click="uploadTemplate" :disabled="loadingSaveChanges">
                        <span v-if="!loadingSaveChanges">Save Changes</span>
                        <span v-else>
                            <i class="fas fa-circle-notch fa-spin"></i> Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';
import { storage } from '../../../firebase';
import { uploadBytes, getDownloadURL, ref as storageRef } from 'firebase/storage';


export default {
    data() {
        return {
            template: {
                type: '',
                firebase_url: ''

            },
            templateFile: null,
            loadingSaveChanges: false,
            successBannerVisible: false,
            successMessage: '',
        };
    },
    methods: {
        showSuccessBanner(message) {
            this.successMessage = message;
            this.successBannerVisible = true;
            setTimeout(() => {
                this.successBannerVisible = false;
                this.successMessage = '';
            }, 1500);
        },

        handleFileChange(event) {
            this.templateFile = event.target.files[0];
        },
        async uploadToFirebase() {
            if (!this.templateFile) {
                console.error('No file selected.');
                return;
            }

            const folderPath = `deliverable_templates/${this.template.type}`;
            const storageRefInstance = storageRef(storage, `${folderPath}/${this.templateFile.name}`);

            try {
                const snapshot = await uploadBytes(storageRefInstance, this.templateFile);
                const downloadURL = await getDownloadURL(snapshot.ref);

                return downloadURL;
            } catch (error) {
                console.error('Error uploading to Firebase:', error);
                return null;
            }
        },
        async uploadTemplate() {
            this.loadingSaveChanges = true;
            const firebaseUrl = await this.uploadToFirebase();

            if (!firebaseUrl) {
                console.error('Failed to upload to Firebase');
                this.loadingSaveChanges = false;
                return;
            }

            let formData = new FormData();
            formData.append('type', this.template.type);
            formData.append('firebase_url', firebaseUrl); 

            try {
                const response = await axios.post('http://127.0.0.1:8000/api/upload-template', formData);
                console.log('Upload Successful:', response.data);

                this.$refs.templateFile.value = null;
                this.showSuccessBanner('Upload successful!');
            } catch (error) {
                console.error('An error occurred while saving changes:', error);
            } finally {
                this.loadingSaveChanges = false;
                this.template.type = '';
            }
        }
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
    max-width: 98.5%;
    margin: 0 auto;
}
</style>