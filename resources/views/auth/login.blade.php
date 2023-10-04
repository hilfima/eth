<?php
if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>Login - HCMS ETHICS GROUP</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/bootstrap.min.css')?>">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/font-awesome.min.css')?>">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/style.css')?>">
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body class="account-page">
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
			<div class="account-content">
					<!-- Account Logo 
				<a href="job-list.html" class="btn btn-primary apply-btn">Apply Job</a>
					-->
				<div class="container">
				
					
					<div class="account-logo" style="margin-bottom: 0;">
					<!-- Account Logo -->
						<img src="<?= asset('/dist/img/logo/logo.png');?>" style="height:auto; width: 173px;">
					</div>
					<!-- /Account Logo -->
					
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Login</h3>
							<p class="account-subtitle">Access to our dashboard</p>
							@include('flash-message')
							<!-- Account Form -->
							<form name="formLogin" action="{{ route('login') }}" method="post">
							 @csrf
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
								<div class="form-group">
									<label>Username</label>
									<input class="form-control" type="text" placeholder="Username" name="username" id="username" required="required" autocomplete="off">
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col">
											<label>Password</label>
										</div>
<style>
	.field-icon {
  float: right;
  margin-left: -25px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
	function show(){
//alert();
  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $('#password');
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
};
</script>
									</div>
									<input class="form-control" type="password" placeholder="Password" name="password" id="password" required="required"> <a href="javascript:void(0)" toggle="#password-field" onclick="show()" class="fa fa-fw fa-eye field-icon toggle-password"></a>
								</div>
								<div class="form-group text-center">
									<button class="btn btn-primary account-btn" type="submit">Login</button>
								</div>
								<div class="account-footer">
							<!-- /Account Form 
									<p>Don't have an account yet? <a href="register.html">Register</a></p>
							-->
								</div>
							</form>	
							
						</div>
					</div>
				</div>
			</div>
        </div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script src="<?= url('plugins/purple/assets/js/jquery-3.2.1.min.js')?>"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="<?= url('plugins/purple/assets/js/popper.min.js')?>"></script>
        <script src="<?= url('plugins/purple/assets/js/bootstrap.min.js')?>"></script>
		
		<!-- Custom JS -->
		<script src="<?= url('plugins/purple/assets/js/app.js')?>"></script>
		
    </body>
</html>