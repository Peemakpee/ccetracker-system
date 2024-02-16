import AdminDashboard from './components/AdminDashboard.vue';
import UploadDeliverables from './pages/UploadDeliverables.vue';
import AddTemplates from './pages/AddTemplates.vue';
import Deadlines from './pages/Deadlines.vue';
import Memos from './pages/Memos.vue';
import Pending from './pages/Pending.vue';
import PendingDean from './pages/PendingDean.vue';
import DetailedUpload from './pages/DetailedUpload.vue';
import DetailedApprovedPh from './pages/DetailedApprovedPh.vue';
import FacultyCompliance from './pages/FacultyCompliance.vue';
import DetailedCompliance from './pages/DetailedCompliance.vue';
import ViewMemos from './pages/ViewMemos.vue';
import DetailedViewMemos from './pages/DetailedViewMemos.vue';
import RegistrationRequests from './pages/RegistrationRequests.vue';
import UploadedTemplates from './pages/UploadedTemplates.vue';
import AllUsers from './pages/AllUsers.vue';
import ApprovedByDean from './pages/ApprovedByDean.vue';
import DetailedApprovedByDean from './pages/DetailedApprovedByDean.vue';
import AdminDetailedChat from './pages/AdminDetailedChat.vue';
import Chat from './pages/Chat.vue';
import SentMessages from './pages/SentMessages.vue';
import ApprovedChanges from './pages/ApprovedChanges.vue';
import CompliedChanges from './pages/CompliedChanges.vue';
import Archive from './pages/Archive.vue';
import DataRetention from './pages/DataRetention.vue';


export default

    [
        {
            path: '/admin/dashboard/:id',
            name: 'admin.dashboard',
            component: AdminDashboard,
        },

        {
            path: '/admin/uploadDeliverables',
            name: 'admin.uploadDeliverable',
            component: UploadDeliverables,
        },

        {
            path: '/admin/addTemplates/:id',
            name: 'admin.addTemplates',
            component: AddTemplates,
        },

        {
            path: '/admin/uploadedTemplates',
            name: 'admin.uploadedTemplates',
            component: UploadedTemplates,
        },

        {
            path: '/admin/deadlines/:program',
            name: 'admin.Deadlines',
            component: Deadlines,
        },

        {
            path: '/admin/memos',
            name: 'admin.memos',
            component: Memos,
        },

        {
            path: '/admin/approvedByDean/:program',
            name: 'admin.approvedByDean',
            component: ApprovedByDean,
        },

        {
            path: '/admin/pending/:program',
            name: 'admin.pending',
            component: Pending,
        },

        {
            path: '/admin/detailedUpload/:id',
            name: 'admin.detailedUpload',
            component: DetailedUpload,
        },

        {
            path: '/admin/pendingDean',
            name: 'admin.pendingDean',
            component: PendingDean,
        },

        {
            path: '/admin/detailedApprovedPh/:id',
            name: 'admin.detailedApprovedPh',
            component: DetailedApprovedPh,
        },

        {
            path: '/admin/facultyCompliance/:program/:id',
            name: 'admin.facultyCompliance',
            component: FacultyCompliance,
        },

        {
            path: '/admin/detailedCompliance/:deadlineId/:id',
            name: 'admin.detailedCompliance',
            component: DetailedCompliance,
        },

        {
            path: '/admin/viewMemos/:id',
            name: 'admin.viewMemos',
            component: ViewMemos,
        },

        {
            path: '/admin/detailedViewMemos/:id',
            name: 'admin.detailedViewMemos',
            component: DetailedViewMemos,
        },

        {
            path: '/admin/registrationRequests',
            name: 'admin.registrationRequests',
            component: RegistrationRequests,
        },

        {
            path: '/admin/allUsers',
            name: 'admin.allUsers',
            component: AllUsers,
        },

        {
            path: '/admin/detailedApprovedByDean/:id',
            name: 'admin.detailedApprovedByDean',
            component: DetailedApprovedByDean,
        },

        {
            path: '/admin/chat/:id',
            name: 'admin.chat',
            component: Chat,
        },

        {
            path: '/admin/adminDetailedChat/:id',
            name: 'admin.adminDetailedChat',
            component: AdminDetailedChat,
        },

        {
            path: '/admin/sentMessages/:id',
            name: 'admin.sentMessages',
            component: SentMessages,
        },

        {
            path: '/admin/approvedChanges/:program',
            name: 'admin.approvedChanges',
            component: ApprovedChanges,
        },

        {
            path: '/admin/compliedChanges/:program',
            name: 'admin.compliedChanges',
            component: CompliedChanges,
        },

        {
            path: '/admin/archive/:program',
            name: 'admin.archive',
            component: Archive,
        },

        {
            path: '/admin/dataRetention',
            name: 'admin.dataRetention',
            component: DataRetention,
        },
        

    ]

