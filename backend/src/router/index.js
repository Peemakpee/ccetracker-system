import { createRouter, createWebHistory } from "vue-router";
import UserDashboard from '../views/user/UserDashboard.vue';
import UserLogin from '../views/user/UserLogin.vue';
import UserRegister from '../views/user/UserRegister.vue';
import DefaultLayout from '../components/DefaultLayout.vue';
import UserDeliverables from '../views/user/UserDeliverables.vue';
import UserApproved from '../views/user/UserApproved.vue';
import UserMemo from '../views/user/UserMemo.vue';
import UserDTrack from '../views/user/UserDTrack.vue';
import UserReminder from '../views/user/UserReminder.vue';
import UserAuthLayout from '../components/UserAuthLayout.vue';
import NotFound from '../components/NotFound.vue';
import TOS from '../views/user/deliverables/TOS.vue';
import PSG from '../views/user/deliverables/PSG.vue';
import UserRequestRegister from '../views/user/UserRequestRegister.vue';
import UserDetailedTracking from '../views/user/UserDetailedTracking.vue';
import UploadDeliverables from '../components/UploadDeliverables.vue';
import DetailedMemo from '../views/user/DetailedMemo.vue';
import DetailedTemplates from '../views/user/DetailedTemplates.vue';
import ViewDetailedChanges from '../views/user/ViewDetailedChanges.vue';
import UserChat from '../views/user/UserChat.vue';
import DetailedChat from '../views/user/DetailedChat.vue';

// import AdminDashboard from '../views/admin/AdminDashboard.vue';
// import AdminDefault from '../components/AdminDefault.vue';
import store from "../store";


const routes = [
    {
        path: '/',
        redirect: '/user/dashboard',
        component: DefaultLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '/user/dashboard', name: 'UserDashboard', component: UserDashboard },

            { path: '/user/upload-deliverables/:deadlineId', name: 'UploadDeliverables', component: UploadDeliverables },

            { path: '/user/approved', name: 'UserApproved', component: UserApproved },

            { path: '/user/memo', name: 'UserMemo', component: UserMemo },

            { path: '/user/tracker', name: 'UserDTrack', component: UserDTrack },

            { path: '/user/reminder', name: 'UserReminder', component: UserReminder },

            { path: '/user/deliverables', name: 'UserDeliverables', component: UserDeliverables },

            { path: '/user/deliverables/tos', name: 'TOS', component: TOS },

            { path: '/user/deliverables/psg', name: 'PSG', component: PSG },

            { path: '/user/detailedTracking/:documentId', name: 'UserDetailedTracking', component: UserDetailedTracking },

            { path: '/user/detailedMemo/:memoId', name: 'DetailedMemo', component: DetailedMemo },

            { path: '/user/detailedTemplates/:id', name: 'DetailedTemplates', component: DetailedTemplates },

            { path: '/user/viewDetailedChanges/:documentId', name: 'ViewDetailedChanges', component: ViewDetailedChanges },

            { path: '/user/userChat', name: 'UserChat', component: UserChat },

            { path: '/user/detailedChat/:messageId', name: 'DetailedChat', component: DetailedChat },
            
        ]
    },

    {
        path: '/user/auth',
        redirect: '/user/login',
        name: 'UserAuth',
        component: UserAuthLayout,
        meta: { isGuest: true },
        children: [
            {

                path: '/user/login',
                name: 'UserLogin',
                component: UserLogin,
            },

            {
                path: '/user/request-register',
                name: 'UserRequestRegister',
                component: UserRequestRegister
            },

            {
                path: '/user/register',
                name: 'UserRegister',
                component: UserRegister
            },
        ]
    },

    {
        path: '/:pathMatch(.*)',
        name: 'notfound',
        component: NotFound,
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: 'UserLogin' })
    } else if (store.state.user.token && (to.meta.isGuest)) {
        next({ name: 'UserDashboard' });
    } else {
        next()
    }
})

export default router; 