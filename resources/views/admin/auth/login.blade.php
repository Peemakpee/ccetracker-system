<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CCEtracker | Log in</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/dist/css/adminlte.min.css') }}">
    <style>
        body {
            position: relative;
        }

        .background-image {
            position: fixed;
            height: 100%;
            width: 66.67%;
            left: 0;
            top: 0;
            z-index: -1;
        }

        .background-overlay {
            position: fixed;
            height: 100%;
            width: 66.67%;
            left: 0;
            top: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .cce-logo {
            position: fixed;
            top: 142px;
            left: 349.6px;
            z-index: 1;
            width: 320px;
            height: 320px;
        }

        .logo-text {
            position: fixed;
            top: 400px;
            left: 415px;
            color: #fff;
            z-index: 1;
            font-size: 39px;
            font-weight: 650;
        }

        .description-text {
            position: fixed;
            top: 445px;
            left: 255px;
            color: #fff;
            z-index: 1;
            font-size: 26px;
            line-height: 1.3;
            text-align: center;
            font-weight: 500;
        }

        .login-box {
            position: absolute;
            top: 50%;
            right: 5%;
            transform: translate(0, -50%);
            z-index: 1;
        }

        @media (max-width: 768px) {

            .background-image,
            .background-overlay,
            .cce-logo,
            .logo-text,
            .description-text {
                display: none;
            }

            .login-box {
                position: relative;
                top: auto;
                right: auto;
                transform: none;
                margin-top: 0;
            }
        }
    </style>
</head>

<body class="hold-transition login-page">
    <img src="{{ asset('images/ccelogo.png') }}" alt="CCE Logo" class="cce-logo">
    <img src="{{ asset('images/univ.png') }}" alt="Background Image" class="background-image">
    <div class="logo-text">CCEtracker</div>
    <div class="description-text">
        Web-based Document Management System for<br>
        the College of Computing Education with Data<br>
        Visualization
    </div>
    <div class="background-overlay"></div>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>CCEtracker Admin </b>Login</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                @if(session('error'))
                <div class="text-danger text-center">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                <div class="text-success text-center">{{ session('success') }}</div>
                @endif
                <p class="login-box-msg">Sign in to start your session</p>

                <form action="{{ route('postAdminLogin') }}" method="post">
                    @csrf
                    <div class="input-group mb-1">
                        <input name="email" type="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="input-group mb-1">
                        <input name="password" type="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <br>
                    <div class="row">
                        <div class="col-8">
                            <div class="col-8 mt-2">
                                <a href="{{ route('getRegister') }}">Register here</a>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('admin-assets/dist/js/adminlte.min.js') }}"></script>
    </div>
</body>

</html>