<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CCEtracker | Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar-scroll-height {
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #FAF3DE;">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    @if (auth()->user()->is_aa)
                    <router-link to="/admin/registrationRequests" class="nav-link">Registration Requests</router-link>
                    @endif
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">

            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-scroll-height">
            <div class="sidebar-scroll">
                <a href="#" class="brand-link">
                    <img src="/images/ccelogo.png" alt="AdminLTE Logo" style="opacity: .8; width: 65px; height: 65px;">
                    <span class="brand-text font-weight-light">CCEtracker</span>
                </a>

                <div class="sidebar">

                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{ asset('images/userlogo.jpg') }}" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">{{auth()->user()->name}}</a>
                        </div>
                    </div>

                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <router-link to="/admin/dashboard/{{auth()->user()->id}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-header">TEMPLATES</li>
                            <li class="nav-item">
                                <router-link to="/admin/uploadDeliverables" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-upload"></i>
                                    <p>
                                        Upload Templates
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/admin/uploadedTemplates" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-file-upload"></i>
                                    <p>
                                        Uploaded Templates
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-header">DEADLINES AND COMPLIANCE</li>
                            <li class="nav-item">
                                <router-link to="/admin/deadlines/{{auth()->user()->program}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-calendar"></i>
                                    <p>
                                        Set Deadlines
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/admin/facultyCompliance/{{auth()->user()->program}}/{{auth()->user()->id}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-eye"></i>
                                    <p>
                                        Faculty Compliance
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-header">MEMO</li>
                            <li class="nav-item">
                                @if (auth()->user()->is_aa || auth()->user()->is_dean)
                                <router-link to="/admin/memos" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Upload Memo
                                    </p>
                                </router-link>
                                @else
                                <router-link to="/admin/viewMemos/{{auth()->user()->id}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        View Memo
                                    </p>
                                </router-link>
                                @endif
                            </li>
                            <li class="nav-header">APPROVALS</li>
                            <li class="nav-item">
                                <router-link to="/admin/pending/{{auth()->user()->program}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-clock"></i>
                                    <p>
                                        Pending Approval of PH
                                    </p>
                                </router-link>
                            </li>
                            @if (auth()->user()->is_dean)
                            <li class="nav-item">
                                <router-link to="/admin/pendingDean" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-clock"></i>
                                    <p>
                                        Pending Dean's Approval
                                    </p>
                                </router-link>
                            </li>
                            @endif
                            <li class="nav-header">DELIVERABLES</li>
                            <li class="nav-item">
                                <router-link to="/admin/approvedByDean/{{auth()->user()->program}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-check-double"></i>
                                    <p>
                                        Approved Deliverables
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/admin/approvedChanges/{{auth()->user()->program}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard-check"></i>
                                    <p>
                                        Approved Changes
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/admin/compliedChanges/{{auth()->user()->program}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-folder"></i>
                                    <p>
                                        Complied Changes
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/admin/archive/{{auth()->user()->program}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-archive"></i>
                                    <p>
                                        Archive
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/admin/dataRetention" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>
                                        Retention
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-header">OTHERS</li>
                            <li class="nav-item">
                                <router-link to="/admin/chat/{{auth()->user()->id}}" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-envelope"></i>
                                    <p>
                                        Messages
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/admin/allUsers" active-class="active" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        All Users
                                    </p>
                                </router-link>
                            </li>
                            <li class="nav-header">USER</li>
                            <li class="nav-item">
                                <a href="{{ route('logout') }}" class="nav-link" onclick="confirmLogout(event)">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>
                                        Logout
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </aside>
        <div class="content-wrapper p-4" style="background-color: #FAF3DE;">
            <router-view :user-program="{{ json_encode(auth()->user()->program) }}"></router-view>
        </div>
    </div>
    <script>
        function confirmLogout(event) {

            event.preventDefault();

            if (confirm("Are you sure you want to logout?")) {

                window.location.href = "{{ route('logout') }}";
            }
        }
    </script>
</body>

</html>