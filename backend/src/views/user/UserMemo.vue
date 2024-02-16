<template>
    <PageComponent header="CCE Dean’s Office Document Management System">
        <div class="ml-16">
            <div class="py-2 text-2xl text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
                Memos
            </div>
            <table class="custom-table-width bg-white shadow-md rounded-b-lg overflow-hidden">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="py-2 px-4 text-left">Memo From</th>
                        <th class="py-2 px-4 text-left">Subject</th>
                        <th class="py-2 px-4 text-left">Actions</th>
                        <th class="py-2 px-4 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="memo in filteredMemos" :key="memo.id" class="hover:bg-blue-100 cursor-pointer">
                        <td class="py-2 px-4 text-left">{{ memo.memo_from }}</td>
                        <td class="py-2 px-4 text-left">{{ memo.memo_subject }}</td>
                        <td class="py-2 px-4 text-left">
                            <button @click="handleViewButtonClick(memo)" class="btn">View</button>
                            <button v-if="memo.memo_to !== 'All Faculty'" @click="confirmDelete(memo.id)" class="btn btn-delete">Delete</button>
                        </td>
                        <td class="py-2 px-4 text-left">{{ formatDate(memo.created_at) }}</td>
                    </tr>
                </tbody>
            </table>
            <div v-if="filteredMemos.length === 0" class="text-xl font-semibold text-gray-600 mt-4 ml-96">
                No Memos available
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

const memos = ref([]);
const error = ref(null);

const store = useStore();
const currentUser = store.state.user.data;
const currentUserName = computed(() => currentUser.name)

const formatDate = (dateTime) => {
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

const handleViewButtonClick = (memo) => {
    const memoId = memo.id;
    router.push({ name: 'DetailedMemo', params: { memoId } });
};

const handleDeleteButtonClick = async (memoId) => {
    try {
        await axios.delete(`http://127.0.0.1:8000/api/delete-user-memo/${memoId}`);
        memos.value = memos.value.filter(memo => memo.id !== memoId);
    } catch (err) {
        console.error('Error deleting memo:', err);
    }
};

const confirmDelete = (memoId) => {
  const shouldDelete = window.confirm("Are you sure you want to delete this memo?");
  if (shouldDelete) {
    handleDeleteButtonClick(memoId);
  }
};

const fetchMemos = async () => {
    try {
        const recipientName = currentUserName.value;
        const response = await axios.get(`http://127.0.0.1:8000/api/get-memo-recipient/${recipientName}`);
        memos.value = response.data;
    } catch (err) {
        console.error('Error fetching memos:', err);
        error.value = 'Error fetching memos.';
    }
};

const filteredMemos = computed(() => {
    return memos.value.filter(memo => memo.memo_to === currentUserName.value || memo.memo_to === 'All Faculty');
});

onMounted(() => {
    fetchMemos();
});
</script>

<style>
.custom-table-width {
    width: 1000px;
}

.btn {
    width: 3rem;
    padding: 0.2rem 0.5rem;
    background-color: #CFAE46;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    font-size: 0.8rem;
    color: #000;
    text-align: center;
    font-weight: 600;
}

.btn:hover {
    background-color: #E1C787;
    color: #000;
}

.btn-delete {
    width: 4rem;
    margin-left: 0.5rem;
    padding: 0.2rem 0.5rem;
    background-color: #d70505;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    font-size: 0.8rem;
    color: #FFF;
    text-align: center;
    font-weight: 600;
}

.btn-delete:hover {
    background-color: #CC0000;
}
</style>