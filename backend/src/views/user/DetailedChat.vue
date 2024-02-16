<template>
    <PageComponent header="CCE Dean’s Office Document Management System">
        <div class="ml-16">
            <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
                Message
            </div>
            <div class="card chat-container">
                <div class="chat-header">
                    <h2>Chat with {{ message.chat_from }}</h2>
                </div>
                <div class="chat-messages">
                    <div class="message" v-if="message">
                        <div v-if="message.is_sender">
                            <div class="chat-name">{{ currentUserName.value }}</div>
                            <div class="message-content sender">
                                <p>{{ message.message }}</p>
                                <div class="message-timestamp timestamp-sender">
                                    {{ new Date(message.created_at).toLocaleTimeString() }}
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="chat-name">{{ message.chat_from }}</div>
                            <div class="message-content receiver">
                                <p>{{ message.message }}</p>
                                <div class="message-timestamp timestamp-receiver">
                                    {{ new Date(message.created_at).toLocaleTimeString() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="message" v-for="reply in replyMessage" :key="reply.id">
                        <div class="chat-name">{{ reply.reply_from }}</div>
                        <div class="message-content sender">
                            <p>{{ reply.reply_message }}</p>
                            <div class="message-timestamp timestamp-sender">
                                {{ new Date(reply.created_at).toLocaleTimeString() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chat-input">
                    <textarea id="reply" class="input-field" placeholder="Type your message..." v-model="reply"></textarea>
                    <button class="send-button" @click="SendReply(message.id)">Send</button>
                </div>
            </div>
        </div>
    </PageComponent>
</template>
  

<script setup>
import PageComponent from '../../components/PageComponent.vue';
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';


import { useStore } from 'vuex';
const store = useStore();
const currentUser = store.state.user.data;
const currentUserName = computed(() => currentUser.name)

const route = useRoute();


const message = ref([]);
const reply = ref('');
const replyMessage = ref([]);


const fetchMessageData = (messageId) => {
    axios
        .get(`http://127.0.0.1:8000/api/get-detailed-message/${messageId}`)
        .then(response => {
            console.log('API Response:', response.data);
            message.value = response.data;
        })
        .catch(error => {
            console.error('An error occurred while fetching data:', error);
        });
};

const fetchReplyData = (messageId) => {
    axios
        .get(`http://127.0.0.1:8000/api/get-reply-message/${messageId}`)
        .then(response => {
            console.log('API Response:', response.data);
            replyMessage.value = response.data;
        })
        .catch(error => {
            console.error('An error occurred while fetching data:', error);
        });
};

const SendReply = async (messageId) => {
    try {
        const postData = {
            message_id: messageId,
            reply_message: reply.value.trim(),
            reply_from: currentUserName.value,
            reply_to: message.value.chat_from
        };

        const response = await axios.post(`http://127.0.0.1:8000/api/send-reply`, postData);

        console.log('Reply To:', message.value.chat_from);
        reply.value = response.data;
        reply.value = ''
    } catch (error) {
        console.error('An error occurred while posting data:', error);
    }
};

onMounted(() => {
    const messageId = route.params.messageId;
    console.log("Message ID:", messageId);
    if (messageId) {
        fetchMessageData(messageId);
        fetchReplyData(messageId);
    } else {
        console.error('Message ID not provided');
    }
});

</script>

<style scoped>
.card {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.chat-messages {
    display: flex;
    flex-direction: column;
    max-height: 400px;
    overflow-y: auto;
}

.message {
    max-width: 80%;
    margin: 8px;
    padding: 12px;
    border-radius: 12px;
    position: relative;
    word-wrap: break-word;
    padding: 8px;
}

.chat-name {
    font-size: 0.8em;
    color: gray;
    margin: 4px 0;
}

.message-content {
    padding: 8px;
}

.timestamp-sender,
.timestamp-receiver {
    color: gray;
    text-align: right;
}

.sender,
.receiver {
    background-color: #f0f0f0;
    color: #333; 
}

.chat-input {
    display: flex;
    align-items: center;
    margin-top: 16px;
}

.input-field {
    flex-grow: 1;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    outline: none;
}

.send-button {
    background-color: #CFAE46; 
    opacity: 1; 
    color: #333; 
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    margin-left: 8px;
    cursor: pointer;
}

.send-button:hover {
    background-color: #e0e0e0;
}
</style>


