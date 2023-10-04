<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!--<title>{{ config('app.name', 'ES-HRMS') }}</title>-->
		<title>ES-iOS || HRMS </title>

		<!-- Favicon -->
		<link href="{!! asset('logo.png') !!}" rel="shortcut icon" />

		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="{!! asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') !!}">
		<!-- JQVMap -->
		<link rel="stylesheet" href="{!! asset('plugins/jqvmap/jqvmap.min.css') !!}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{!! asset('dist/css/adminlte.min.css') !!}">
		<!-- overlayScrollbars -->
		<link rel="stylesheet" href="{!! asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') !!}">
		<!-- Daterange picker -->
		<link rel="stylesheet" href="{!! asset('plugins/daterangepicker/daterangepicker.css') !!}">
		<!-- summernote -->
		<link rel="stylesheet" href="{!! asset('plugins/summernote/summernote-bs4.min.css') !!}">
		<!-- Scripts -->
		<!--<script src="{{ asset('js/app.js') }}" defer></script>-->

		<!-- DataTables -->
		<link rel="stylesheet" href="{!! asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') !!}">

		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!--<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">-->

		<!-- Bootstrap Color Picker -->
		<link rel="stylesheet" href="{!! asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') !!}">
		<!-- Tempusdominus Bootstrap 4 -->
		<link rel="stylesheet" href="{!! asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') !!}">
		<!-- Select2 -->
		<link rel="stylesheet" href="{!! asset('plugins/select2/css/select2.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') !!}">

		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!--<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">-->

		<!-- Bootstrap4 Duallistbox -->
		<link rel="stylesheet" href="{!! asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') !!}">
		<!-- BS Stepper -->
		<link rel="stylesheet" href="{!! asset('plugins/bs-stepper/css/bs-stepper.min.css') !!}">
		<!-- dropzonejs -->
		<link rel="stylesheet" href="{!! asset('plugins/dropzone/min/dropzone.min.css') !!}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{!! asset('dist/css/adminlte.min.css') !!}">

		<!-- pace-progress -->
		<link rel="stylesheet" href="{!! asset('plugins/pace-progress/themes/black/pace-theme-flat-top.css') !!}">

		<!-- SweetAlert2 -->
		<link rel="stylesheet" href="{!! asset('plugins/sweetalert2/sweetalert2.min.css') !!}">
		<!-- Toastr -->
		<link rel="stylesheet" href="{!! asset('plugins/toastr/toastr.min.css') !!}">
		<!-- Ekko Lightbox -->
		<link rel="stylesheet" href="{!! asset('plugins/ekko-lightbox/ekko-lightbox.css') !!}">

		<!-- fullCalendar -->
		<link rel="stylesheet" href="{!! asset('plugins/fullcalendar/main.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/fullcalendar-daygrid/main.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/fullcalendar-timegrid/main.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/fullcalendar-bootstrap/main.min.css') !!}">

		<link rel="stylesheet" type="text/css" href="{!! asset('fancybox/jquery.fancybox.css') !!}">
		<!-- library JS -->
		<script src="//code.jquery.com/jquery-3.2.0.min.js"></script>
		<!-- library JS fancybox -->
		<script src="{!! asset('fancybox/jquery.fancybox.js') !!}"></script>

		<script type="text/javascript">
			$("[data-fancybox]").fancybox({ });
		</script>

		<style type="text/css">
			.gallery img {
				width: 20%;
				height: auto;
				border-radius: 5px;
				cursor: pointer;
				transition: .3s;
			}
		</style>
		<!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
	</head>
	<body class="hold-transition sidebar-mini pace-primary">
		<div id="app">
			<!-- Navbar -->
			<nav class="navbar navbar-expand navbar-white navbar-light">
				<!-- Left navbar links -->
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
					</li>
					<!--<li class="nav-item d-none d-sm-inline-block">
					<a href="index3.html" class="nav-link">Home</a>
					</li>
					<li class="nav-item d-none d-sm-inline-block">
					<a href="#" class="nav-link">Contact</a>
					</li>-->
				</ul>

				<!-- SEARCH FORM -->
				<!--<form class="form-inline ml-3">
				<div class="input-group input-group-sm">
				<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
				<div class="input-group-append">
				<button class="btn btn-navbar" type="submit">
				<i class="fas fa-search"></i>
				</button>
				</div>
				</div>
				</form>-->

				<!-- Right navbar links -->
				<?php
						$idusernotifi=Auth::user()->id;
						$sqlidkarnotifi="select * from p_karyawan where user_id=$idusernotifi";
						$idkarnotifi=DB::connection()->select($sqlidkarnotifi);
						$idnotifi=$idkarnotifi[0]->p_karyawan_id;
						$notifi = "SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,tgl_awal,tgl_akhir,status_appr_1,c.tipe,(select count(*) FROM t_permit where t_permit.p_karyawan_id = $idnotifi and status_appr_1 = 3) as countapprove FROM t_permit a
						left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
						left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
						left join p_karyawan d on d.p_karyawan_id=a.appr_1
						WHERE 1=1 and a.active=1 and a.p_karyawan_id=$idnotifi  ORDER BY a.tgl_awal desc limit 5";
						$notifi=DB::connection()->select($notifi); 
						
						 $sqlberitia="SELECT * FROM hr_care where active=1 and tgl_posting>='".date('Y-m-d')."' or tgl_posting_akhir>='".date('Y-m-d')." 24:00' ";
       					 $beritia=DB::connection()->select($sqlberitia);
						?>
				<ul class="navbar-nav ml-auto">
					<!-- Messages Dropdown Menu -->
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="far fa-comments"></i>
							<span class="badge badge-danger navbar-badge"><?=count($beritia)?></span>
						</a>
						
						@if(count($beritia))
					
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<?php foreach($beritia as $beritia){
							?>
								<a href=#" class="dropdown-item dropdown-header text-left" style="font-size: 12px;">
								<p>
									
									{!! $beritia->judul!!}                        			                                       		 							
								</p>
								
                        	                                                 	
								</a>
									<div class="dropdown-divider"></div>			
							<?php
							}
							?>
							</div>
						@endif
					</li>
					<!-- Notifications Dropdown Menu -->
					<li class="nav-item dropdown">
                 
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="far fa-bell"></i>
							@if(!empty($notifi)   )
							<span class="badge badge-warning navbar-badge"><?=($notifi[0]->countapprove);?></span>
							@else
							<span class="badge badge-warning navbar-badge">0</span>
							@endif
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    	

							<?php
							foreach($notifi as $notifi){
							if($notifi->tipe==1)
							$link = 'izin';
							else if($notifi->tipe==2)
							$link = 'lembur';
							else
							$link = 'cuti';
							?>
							<a href="{!! route('fe.lihat_'.$link,$notifi->t_form_exit_id) !!}" class="dropdown-item dropdown-header text-left" style="font-size: 10px">
								<?= $notifi->nama_ijin?>
								@if($notifi->status_appr_1==1)
								<span class="badge badge-success" style="font-size: 10px">Disetujui</span>
                                       
								@elseif($notifi->status_appr_1==2)
								<span cclass="badge badge-danger" style="font-size: 10px"> Ditolak</span>
								@else
								<span class="badge badge-warning" style="font-size: 10px"> Pending</span>
								@endif
								<br>
								@if($notifi->tgl_awal==$notifi->tgl_akhir)
								{!! date('d-m-Y', strtotime($notifi->tgl_awal)) !!} 
								@else
								{!! date('d-m-Y', strtotime($notifi->tgl_awal)) !!} s/d {!! date('d-m-Y', strtotime($notifi->tgl_akhir )) !!}
								@endif
								<?= $notifi->tgl_appr_1?>
                        	
							</a>
							<div class="dropdown-divider"></div>
							<?php
							}
							//print_r($beritia);
							?>
							<!--frontend/lihat_cuti/893-->
                       
							<!-- <div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>-->
						</div>
					</li>
				</ul>
			</nav>
			<!-- /.navbar -->

			<main class="py-4">
				@yield('content')
			</main>
		</div>

		<footer class="footer">
			<strong>Copyright &copy; 2020 - {!! date('Y') !!} <a href="{!! route('home') !!}"> ES-iOS || HRMS </a>.</strong>
			All rights reserved.
			<div class="float-right d-none d-sm-inline-block">
				<b>Version</b> 2.1.0
			</div>
		</footer>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->

		<!-- jQuery -->
		
	</body>
</html>
