<!DOCTYPE html>
<html lang="en">
<head>
    <title>ES-iOS HRMS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{!! asset('logo.png') !!}"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{!! asset('csslogin/vendor/bootstrap/css/bootstrap.min.css') !!}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{!! asset('csslogin/fonts/font-awesome-4.7.0/css/font-awesome.min.css') !!}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{!! asset('csslogin/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') !!}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{!! asset('csslogin/vendor/animate/animate.css') !!}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{!! asset('csslogin/vendor/css-hamburgers/hamburgers.min.css') !!}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{!! asset('csslogin/vendor/select2/select2.min.css') !!}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{!! asset('csslogin/css/util.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('csslogin/css/main.css') !!}">
    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100" style="background-image: url('csslogin/images/img-01.jpg');">
        <div class="wrap-login100 p-t-190 p-b-30">
            <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                @csrf
                <div class="login100-form-avatar">
                    <img src="{!! asset('logo.png') !!}" alt="AVATAR">
                </div>

                <span class="login100-form-title p-t-20 p-b-45">
						ES-iOS | HRMS
					</span>
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="wrap-input100 validate-input m-b-10" data-validate = "Username is required">
                    <input class="input100" type="text" name="username" placeholder="Username">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-user"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-lock"></i>
						</span>
                </div>

                <div class="container-login100-form-btn p-t-10">
                    <button class="login100-form-btn">
                        Login
                    </button>
                </div>

                <div class="container-login100-form-btn p-t-10">
                    <a href="{!! route('landingpage') !!}" class="login100-form-btn"> Cancel </a>
                </div>

            </form>
        </div>
    </div>
</div>




<!--===============================================================================================-->
<script src="{!! asset('csslogin/vendor/jquery/jquery-3.2.1.min.js') !!}"></script>
<!--===============================================================================================-->
<script src="{!! asset('csslogin/vendor/bootstrap/js/popper.js') !!}"></script>
<script src="{!! asset('csslogin/vendor/bootstrap/js/bootstrap.min.js') !!}"></script>
<!--===============================================================================================-->
<script src="{!! asset('csslogin/vendor/select2/select2.min.js') !!}"></script>
<!--===============================================================================================-->
<script src="{!! asset('csslogin/js/main.js') !!}"></script>

</body>
</html>