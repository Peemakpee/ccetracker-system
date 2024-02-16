<template>
    <div class="container">
        <div class="success-banner" v-if="successBannerVisible">
            {{ successMessage }}
        </div>
        <div class="flex-container">
            <div class="flex-child">
                <form @submit.prevent="submitMessage">
                    <div class="card card-info">
                        <div class="card-header custom-header">
                            <h1 class="custom-title">Send a Message</h1>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="chat_to">To:</label>
                                <select v-model="chat.chat_to" class="form-control" required>
                                    <option v-for="recipient in recipientOptions" :key="recipient" :value="recipient">{{
                                        recipient }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message">Message:</label>

                                <textarea id="message" v-model="chat.message" class="form-control" required
                                    rows="4"></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn custom-button" :disabled="loadingSendMessage">
                                    <span v-if="!loadingSendMessage">Submit Message</span>
                                    <span v-else>
                                        <i class="fas fa-circle-notch fa-spin"></i> Sending...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex-child">
                <div class="card card-info">
                    <div class="card-header custom-header">
                        <h3 class="custom-title">Inbox</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 17%">Message From</th>
                                    <th style="width: 17%">Date and Time Sent</th>
                                    <th style="width: 9%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="box in inbox" :key="box.id">
                                    <td>{{ box.chat_from }}</td>
                                    <td>{{ formatDate(box.created_at) }}</td>
                                    <td class="project-actions text-left">
                                        <div class="btn-group" role="group" aria-label="Actions">
                                            <router-link :to="`/admin/adminDetailedChat/${box.id}`"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-folder"></i> View
                                            </router-link>
                                            <button class="btn btn-danger btn-sm" @click="confirmDeleteInbox(box.id)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-info">
                    <div class="card-header custom-header">
                        <h3 class="custom-title">Sent Messages</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 17%">Message To</th>
                                    <th style="width: 17%">Date and Time Sent</th>
                                    <th style="width: 9%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sentMessage in sentMessages" :key="sentMessage.id">
                                    <td>{{ sentMessage.chat_to }}</td>
                                    <td>{{ formatDate(sentMessage.created_at) }}</td>
                                    <td class="project-actions text-left">
                                        <div class="btn-group" role="group" aria-label="Actions">
                                            <router-link :to="`/admin/sentMessages/${sentMessage.id}`"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-folder"></i> View
                                            </router-link>
                                            <button class="btn btn-danger btn-sm"
                                                @click="confirmDeleteMessage(sentMessage.id)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    data() {
        return {
            chat: {
                chat_to: '',
                chat_from: '',
                message: ''
            },
            loadingSendMessage: false,
            successBannerVisible: false,
            successMessage: '',
            inbox: [],
            sentMessages: [],
            recipientOptions: [],
        };
    },
    methods: {
        async fetchRecipientNames() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/validate-recipient');
                this.recipientOptions = response.data;
                console.log('recipients:', this.recipientOptions);
            } catch (error) {
                console.error('An error occurred while fetching recipient names:', error);
            }
        },
        confirmDeleteInbox(id) {
            const shouldDelete = window.confirm("Are you sure you want to delete this inbox message?");
            if (shouldDelete) {
                this.deleteInbox(id);
            }
        },
        confirmDeleteMessage(id) {
            const shouldDelete = window.confirm("Are you sure you want to delete this sent message?");
            if (shouldDelete) {
                this.deleteMessage(id);
            }
        },
        deleteInbox(id) {

            axios
                .delete(`http://127.0.0.1:8000/api/delete-inbox/${id}`)
                .then(() => {
                    this.inbox = this.inbox.filter(upload => upload.id !== id);
                })
                .catch(error => {
                    console.error('An error occurred while deleting the inbox:', error);
                });
        },
        deleteMessage(id) {
            axios
                .delete(`http://127.0.0.1:8000/api/delete-message/${id}`)
                .then(() => {
                    this.sentMessages = this.sentMessages.filter(upload => upload.id !== id);
                })
                .catch(error => {
                    console.error('An error occurred while deleting the inbox:', error);
                });
        },
        formatDate(dateTime) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateTime).toLocaleDateString('en-US', options);
        },
        async getSender() {
            try {
                const userId = this.$route.params.id;
                const response = await axios.get(`http://127.0.0.1:8000/api/get-sender/${userId}`);
                this.chat.chat_from = response.data.name;
                console.log('User ID:', userId);

                const adminName = this.chat.chat_from;
                console.log('Admin Name:', adminName);
                const inboxResponse = await axios.get(`http://127.0.0.1:8000/api/get-inbox/${adminName}`);
                this.inbox = inboxResponse.data;
                console.log('API response:', inboxResponse.data);

                const sentMessagesResponse = await axios.get(`http://127.0.0.1:8000/api/get-sent-messages/${adminName}`);
                this.sentMessages = sentMessagesResponse.data;
                console.log('Sent Messages:', this.sentMessages);
            } catch (error) {
                console.error('Error fetching sender or inbox:', error);
            }
        },
        async submitMessage() {
            try {
                this.loadingSendMessage = true;
                const messageData = {
                    chat_to: this.chat.chat_to,
                    chat_from: this.chat.chat_from,
                    message: this.chat.message,
                };

                const response = await axios.post('http://127.0.0.1:8000/api/send-message', messageData);
                console.log('API response:', response);

                this.successBannerVisible = true;
                this.successMessage = 'Message sent successfully!';

                this.chat = {
                    chat_to: '',
                    chat_from: '',
                    message: ''
                };
                setTimeout(() => {
                    this.successBannerVisible = false;
                    this.successMessage = '';
                }, 1500);
            } catch (error) {
                console.error('Error sending message:', error);
                this.successBannerVisible = true;
                this.successMessage = 'Error sending message. Please try again.';
            } finally {
                this.loadingSendMessage = false;
            }
        }
    },
    mounted() {
        this.getSender();
        this.fetchRecipientNames();

    }
}
</script>
  
<style scoped>
.custom-header {
    background-color: #CFAE46 !important;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.custom-button {
    background-color: #CFAE46 !important;
    height: 45px;
    font-weight: 600;
    font-size: large;
}

.custom-title {
    color: #000000;
    font-size: 25px;
    font-weight: bold;
    text-align: center;
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

.flex-container {
    display: flex;
    justify-content: space-between;
    gap: 10px;

}

.flex-child {
    flex: 1;

}
</style>
  