<template>
    <PageComponent header="CCE Dean’s Office Document Management System">
        <div class="flex">
            <div v-if="successMessage" class="success-banner">
                {{ successMessage }}
            </div>
            <div class="ml-16">
                <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
                    Inbox
                </div>
                <table class="min-w-full bg-white shadow-md rounded-b-lg overflow-hidden">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="py-2 px-4 text-left">Message From</th>
                            <th class="py-2 px-4 text-left">Date Sent</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="message in messages" :key="message.id" @click="handleRowClick(message)"
                            class="hover:bg-blue-100 cursor-pointer">
                            <td class="py-2 px-4 text-left">{{ message.chat_from }}</td>
                            <td class="py-2 px-4 text-left">{{ formatDateTime(message.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-8">
                    <div class="py-2 text-2xl text-center font-bold rounded-t-lg"
                        style="background-color: #CFAE46; opacity: 1">
                        Sent Messages
                    </div>
                    <table class="min-w-full bg-white shadow-md rounded-b-lg overflow-hidden">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="py-2 px-4 text-left">Message To</th>
                                <th class="py-2 px-4 text-left">Date Sent</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="sentMessage in sendMessages" :key="sentMessage.id"
                                @click="handleSentMessageClick(sentMessage)" class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-2 px-4 text-left">{{ sentMessage.chat_to }}</td>
                                <td class="py-2 px-4 text-left">{{ formatDateTime(sentMessage.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white ml-10 w-96 h-96">
                <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
                    Create Message
                </div>
                <div class="mb-4 p-5">
                    <label for="recipient" class="block text-sm font-medium text-gray-700">Recipient:</label>
                    <select v-model="recipient" id="recipient" class="mt-1 p-2 border rounded-md w-full">
                        <option value="" disabled>Select a recipient</option>
                        <option v-for="user in userList" :value="user">{{ user }}</option>
                    </select>
                </div>
                <div class="mb-2 p-5">
                    <label for="message" class="block text-sm font-medium text-gray-700">Message:</label>
                    <textarea id="message" v-model="newMessage" class="mt-1 p-2 border rounded-md w-full"></textarea>
                </div>
                <div class="text-right p-2">
                    <button @click="sendMessage" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Send Message
                    </button>
                </div>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import axios from 'axios';
import PageComponent from '../../components/PageComponent.vue';
import { ref, onMounted, computed } from 'vue';

import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const currentUser = store.state.user.data;
const currentUserName = computed(() => currentUser.name)

const userList = ref([]);
const successMessage = ref('');
const messages = ref([]);
const error = ref(null);
const sendMessages = ref([]);
const newMessage = ref('');
const recipient = ref('');

const formatDateTime = (dateTime) => {
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Date(dateTime).toLocaleDateString(undefined, options);
};

const router = useRouter();

function handleRowClick(message) {
    const messageId = message.id;
    console.log("Selected Message:", messageId);
    router.push({ name: 'DetailedChat', params: { messageId } });
}

function handleSentMessageClick(sentMessage) {
    const messageId = sentMessage.id;
    console.log("Selected Sent Message:", messageId);
    router.push({ name: 'DetailedChat', params: { messageId } });
}

const fetchMessages = async () => {
    try {
        const recipientName = currentUserName.value;
        const response = await axios.get(`http://127.0.0.1:8000/api/get-messages/${recipientName}`);
        messages.value = response.data;
    } catch (err) {
        console.error('Error fetching memos:', err);
        error.value = 'Error fetching memos.';
    }
};

const fetchSentMessages = async () => {
    try {
        const senderName = currentUserName.value;
        const response = await axios.get(`http://127.0.0.1:8000/api/get-sent-messages/${senderName}`);
        sendMessages.value = response.data;
    } catch (err) {
        console.error('Error fetching sent messages:', err);
        error.value = 'Error fetching sent messages.';
    }
};

const sendMessage = async () => {
    try {
        const postData = {
            chat_from: currentUserName.value,
            message: newMessage.value,
            chat_to: recipient.value,
        };
        const response = await axios.post('http://127.0.0.1:8000/api/send-message', postData);
        sendMessages.value = response.data;
        console.log('API response:', response.data);

        newMessage.value = '',
            recipient.value = '',
            successMessage.value = 'Message sent successfully';

        setTimeout(() => {
            successMessage.value = '';
        }, 1500);
    } catch (err) {
        console.error('Error fetching messages:', err);
        error.value = 'Error fetching messages.';
    }
};

const fetchUserList = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/get-recipient');
        userList.value = response.data;
    } catch (err) {
        console.error('Error fetching user list:', err);
        error.value = 'Error fetching user list.';
    }
};


onMounted(() => {
    fetchMessages();
    fetchSentMessages();
    fetchUserList();
});
</script>

<style scoped>
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
</style>