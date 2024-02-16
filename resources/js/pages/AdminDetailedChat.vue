<template>
    <div class="container">
        <div class="card card-primary">
            <div class="card-header custom-docheader">
                <h3 class="card-title custom-title">Chat</h3>
                <div class="filter-bar float-right">
                    <label for="dateFilter" style="color: black;">Filter by Date:</label>
                    <input type="date" id="dateFilter" v-model="selectedDate" @change="filterMessagesByDate">
                </div>
            </div>
            <div class="card-body">
                <div class="direct-chat-messages">
                    <div v-for="message in inboxMessage" :key="message.id">
                        <div class="message"
                            :class="{ 'sent': message.chat_from === replyTo, 'received': message.chat_from === replyFrom }">
                            <div class="message-info">
                                <span>{{ message.chat_from }}</span>
                                <span class="text-right">{{ formatDate(message.created_at) }}</span>
                            </div>
                            <div class="message-content">
                                <p>{{ message.message }}</p>
                            </div>
                        </div>
                    </div>
                    <div v-for="reply in replyMessage" :key="reply.id">
                        <div class="message sent">
                            <div class="message-info">
                                <span>{{ reply.reply_from }}</span>
                                <span class="text-right">{{ formatDate(reply.created_at) }}</span>
                            </div>
                            <div class="message-content">
                                <p>{{ reply.reply_message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="input-group">
                        <input id="reply" v-model="reply" type="text" class="form-control"
                            placeholder="Type your message...">
                        <span class="input-group-append">
                            <button @click="sendReplyMessage" class="btn custom-button">Send</button>
                        </span>
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
            inboxMessage: [],
            reply: '',
            replyFrom: '',
            replyTo: '',
            replyMessage: [],
            selectedDate: null,
            originalInboxMessage: [],
            originalReplyMessage: [],
        };
    },
    methods: {
        formatDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            };
            const date = new Date(dateString);
            return date.toLocaleDateString(undefined, options);
        },

        async sendReplyMessage() {
            const messageId = this.$route.params.id;

            const replyFrom = this.replyFrom;
            const replyTo = this.replyTo;

            let formData = new FormData();
            formData.append('message_id', messageId);
            formData.append('reply_message', this.reply);
            formData.append('reply_from', replyFrom);
            formData.append('reply_to', replyTo);

            try {
                const response = await axios.post('http://127.0.0.1:8000/api/send-reply', formData);
                console.log('Replied Successfully:', response.data);

                this.reply = '';

            } catch (error) {
                console.error('An error occurred while sending reply:', error);
            }
        },
        fetchReplyMessage() {
            const messageId = this.$route.params.id;
            axios
                .get(`http://127.0.0.1:8000/api/get-reply-message/${messageId}`)
                .then((response) => {
                    this.replyMessage = response.data;
                    console.log('API response:', response.data);
                    this.originalReplyMessage = [...response.data];

                })
                .catch((error) => {
                    console.error('An error occurred while fetching inbox messages:', error);
                });
        },
        fetchInboxMessage() {
            const messageId = this.$route.params.id;
            axios
                .get(`http://127.0.0.1:8000/api/get-inboxMessage/${messageId}`)
                .then((response) => {
                    this.inboxMessage = response.data;
                    console.log('API response:', response.data);
                    this.originalInboxMessage = [...response.data];
                    if (response.data.length > 0) {
                        this.replyFrom = response.data[0].chat_to;
                        this.replyTo = response.data[0].chat_from;
                        console.log('Reply From:', this.replyFrom);
                        console.log('Reply To:', this.replyTo);
                    }
                })
                .catch((error) => {
                    console.error('An error occurred while fetching inbox messages:', error);
                });
        },

        filterMessagesByDate() {
            if (!this.originalInboxMessage.length || !this.originalReplyMessage.length) {
                this.fetchInboxMessage();
                this.fetchReplyMessage();
                return;
            }

            if (this.selectedDate) {
                const filteredInboxMessages = this.originalInboxMessage.filter((message) => {
                    const messageDate = new Date(message.created_at).toISOString().split('T')[0];
                    return messageDate === this.selectedDate;
                });

                const filteredReplyMessages = this.originalReplyMessage.filter((reply) => {
                    const replyDate = new Date(reply.created_at).toISOString().split('T')[0];
                    return replyDate === this.selectedDate;
                });

                this.inboxMessage = filteredInboxMessages;
                this.replyMessage = filteredReplyMessages;
            } else {
      
                this.inboxMessage = [...this.originalInboxMessage];
                this.replyMessage = [...this.originalReplyMessage];
            }
        },
    },

    mounted() {
        this.fetchInboxMessage();
        this.fetchReplyMessage();
    },
};
</script>

  
<style>
.message {
    display: flex;
    flex-direction: column;
    max-width: 70%;
    padding: 8px;
    margin: 5px;
    border-radius: 8px;
}

.message-info {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #828282;
}

.message-content {
    background-color: #E2E8F0;
    color: #333;
    padding: 10px;
}

.sent .message-content {
    background-color: #007BFF;
    color: white;
}
</style>
  