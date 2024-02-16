<template>
    <div class="card card-primary card-outline card-custom-outline">
        <div class="card-header">
            <h5 class="m-0 custom-title">Document Viewer</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">From:</dt>
                <dd class="col-sm-8">{{ memo.memo_from }}</dd>
                <dt class="col-sm-4">To:</dt>
                <dd class="col-sm-8">{{ memo.memo_to }}</dd>
                <dt class="col-sm-4">Subject:</dt>
                <dd class="col-sm-8">{{ memo.memo_subject }}</dd>
                <dt class="col-sm-4">Date and Time Uploaded:</dt>
                <dd class="col-sm-8">{{ formatDate(memo.created_at) }}</dd>
            </dl>
            <div class="card card-info">
                <iframe :src="googleViewLink" style="width:100%; height:536px;" frameborder="0"></iframe>
            </div>

            <div style="text-align: right;">
                <button class="btn custom-button" @click="downloadFile">Download</button>
            </div>
        </div>
    </div>
</template>
    
<script>
export default {
    data() {
        return {
            memo: [],
        };
    },
    computed: {
        googleViewLink() {
            const url = this.memo.firebase_url || 'fallback_value';
            return `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
        },
    },
    methods: {
        downloadFile() {

            if (this.memo.firebase_url) {
  
                window.open(this.memo.firebase_url, '_blank');
            } else {

                console.error('Firebase URL is not available.');
            }
        },
        formatDate(dateTime) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateTime).toLocaleDateString('en-US', options);
        },

    },
    mounted() {
        const memoId = this.$route.params.id;  

        axios
            .get(`http://127.0.0.1:8000/api/get-detailed-memo/${memoId}`)
            .then(response => {
                console.log('API Response:', response.data);
                this.memo = response.data;  
                console.log('Firebase URL:', this.document.firebase_url);
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