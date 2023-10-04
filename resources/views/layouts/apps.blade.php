<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>HCMS ETHICS GROUP</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/bootstrap.min.css')?>">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/font-awesome.min.css')?>">
		
		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/line-awesome.min.css')?>">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/style.css')?>">
		
		<link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!-- Header -->
            <div class="header">
			 <?php $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,role,m_lokasi.nama as nmlokasi,p_karyawan.nama FROM users
				left join p_karyawan on p_karyawan.user_id=users.id
				left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
				left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
				left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
				where users.id=$iduser";
        $user=DB::connection()->select($sqluser);?>
				<!-- Logo -->
                <div class="header-left">
                    <a href="index.html" class="logo">
                     <img src="{!! asset('logo.png') !!}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8;width: 50px;height: 50px">
            
						
					</a>
                </div>
				<!-- /Logo -->
				
				<a id="toggle_btn" href="javascript:void(0);" style="margin-top: 20px">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</a>
				
				<!-- Header Title -->
                <div class="page-title-box">
					<h3><?= $user[0]->nmlokasi;?></h3>
                </div>
				<!-- /Header Title -->
				
				<a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>
				
				<!-- Header Menu -->
				<ul class="nav user-menu">
				
					<!-- Search 
					<li class="nav-item">
						<div class="top-nav-search">
							<a href="javascript:void(0);" class="responsive-search">
								<i class="fa fa-search"></i>
						   </a>
							<form action="search.html">
								<input class="form-control" type="text" placeholder="Search here">
								<button class="btn" type="submit"><i class="fa fa-search"></i></button>
							</form>
						</div>
					</li>
					<!-- /Search -->
				
					<!-- Flag
					<li class="nav-item dropdown has-arrow flag-nav">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
							<img src="assets/img/flags/us.png" alt="" height="20"> <span>English</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="javascript:void(0);" class="dropdown-item">
								<img src="assets/img/flags/us.png" alt="" height="16"> English
							</a>
							<a href="javascript:void(0);" class="dropdown-item">
								<img src="assets/img/flags/fr.png" alt="" height="16"> French
							</a>
							<a href="javascript:void(0);" class="dropdown-item">
								<img src="assets/img/flags/es.png" alt="" height="16"> Spanish
							</a>
							<a href="javascript:void(0);" class="dropdown-item">
								<img src="assets/img/flags/de.png" alt="" height="16"> German
							</a>
						</div>
					</li>
					<!-- /Flag -->
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
						
						$appr = "SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,tgl_awal,tgl_akhir,status_appr_1,c.tipe,(select count(*) FROM t_permit where t_permit.p_karyawan_id = $idnotifi and status_appr_1 = 3) as countapprove FROM t_permit a
						left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
						left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
						left join p_karyawan d on d.p_karyawan_id=a.appr_1
						WHERE 1=1 and a.active=1 and a.appr_1=$idnotifi  ORDER BY a.tgl_awal desc limit 5
						";
						$notifiappr=DB::connection()->select($appr); 
						
						 $sqlberitia="SELECT * FROM hr_care where active=1 and tgl_posting>='".date('Y-m-d')."' or tgl_posting_akhir>='".date('Y-m-d')." 24:00' ";
       					 $beritia=DB::connection()->select($sqlberitia);
						?>
				
					<!-- Notifications -->
					<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<i class="far fa-comments"></i> <span class="badge badge-pill"><?=count($beritia)?></span>
						</a>
						<div class="dropdown-menu notifications">
							<div class="topnav-dropdown-header">
								<span class="notification-title">Berita</span>
							</div>
							<div class="noti-content">
								<ul class="notification-list">
								<?php foreach($beritia as $beritia){
							?>
									<li class="notification-message">
										<a href="activities.html">
											<div class="media">
												<span class="avatar">
													
												</span>
												<div class="media-body">
													<p class="noti-details">{!! $beritia->judul!!}</p>
													<!--<p class="noti-time"><span class="notification-time">4 mins ago</span></p>-->
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									
								</ul>
							</div>
							
						</div>
					</li>
					<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
						<i class="far fa-bell"></i>
							@if(!empty($notifi)   )
							<span class="badge badge-pill"><?=count($notifi)?></span>
							@else
							<span class="badge badge-pill">0</span>
							@endif
							
						</a>
						<div class="dropdown-menu notifications">
							<div class="topnav-dropdown-header">
								<span class="notification-title">Notifications</span>
								
							</div>
							<div class="noti-content">
								<ul class="notification-list">
								<?php foreach($notifi as $notifi){
							if($notifi->tipe==1)
							$link = 'izin';
							else if($notifi->tipe==2)
							$link = 'lembur';
							else
							$link = 'cuti';
							?>
									<li class="notification-message">
										<a href="{!! route('fe.lihat_'.$link,$notifi->t_form_exit_id) !!}">
											<div class="media">
												
												<div class="media-body">
												
													<p class="noti-details"><?= $notifi->nama_ijin?>
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
								@endif</p>
													<p class="noti-time"><span class="notification-time">
								<?= $notifi->tgl_appr_1?'Aproved tgl:'.$notifi->tgl_appr_1:'';?></span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									
								</ul>
							</div>
							
						</div>
					</li>
					<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
						<i class="fa fa-exclamation "></i>
							@if(!empty($notifiappr)   )
							<span class="badge badge-warning navbar-badge"><?=($notifiappr[0]->countapprove);?></span>
							@else
							<span class="badge badge-warning navbar-badge">0</span>
							@endif
							
						</a>
						<div class="dropdown-menu notifications">
							<div class="topnav-dropdown-header">
								<span class="notification-title">Aprroval</span>
								
							</div>
							<div class="noti-content">
								<ul class="notification-list">
								<?php 
								foreach($notifiappr as $notifi){
							if($notifi->tipe==1)
							$link = 'izin';
							else if($notifi->tipe==2)
							$link = 'lembur';
							else
							$link = 'cuti';
							?>
									<li class="notification-message">
										<a href="{!! route('fe.lihat_'.$link,$notifi->t_form_exit_id) !!}">
											<div class="media">
												
												<div class="media-body">
												
													<p class="noti-details"><?= $notifi->nama_ijin?>
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
								@endif</p>
													<p class="noti-time"><span class="notification-time">
								<?= 'Pengaju '.$notifi->nama;?></span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									
								</ul>
							</div>
							
						</div>
					</li>
					<!-- /Notifications -->
					
					<!-- Message Notifications -->
					
					<!-- /Message Notifications -->

					<li class="nav-item dropdown has-arrow main-drop">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<span class="user-img"><img src="assets/img/profiles/avatar-21.jpg" alt="">
							<span class="status online"></span></span>
							<span><?=$user[0]->nama;?></span>
						</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="profile.html">My Profile</a>
							<a class="dropdown-item" href="{!! route('logout') !!}">Logout</a>
						</div>
					</li>
				</ul>
				<!-- /Header Menu -->
				
				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="profile.html">My Profile</a>
						<a class="dropdown-item" href="{!! route('logout') !!}">Logout</a>
					</div>
				</div>
				<!-- /Mobile Menu -->
				
            </div>
			<!-- /Header -->
			
			<!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							<li> 
								<a href="index.html"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
							</li>
							
							<li >
								<a href="#" class="noti-dot"><i class="la la-user"></i> <span> Profil</span> <span class="menu-arrow"></span></a>
								
							</li>
							<li> 
								<a href="events.html"><i class="la la-calendar"></i> <span>Absensi</span></a>
							</li>
							<li> 
								<a href="events.html"><i class="la la-building"></i> <span>Permit</span></a>
							</li>
							<li> 
								<a href="events.html"><i class="la la-question"></i> <span>Informasi</span></a>
							</li>
							<li> 
								<a href="events.html"><i class="la la-money"></i> <span>Pay</span></a>
							</li>
							<li> 
								<a href="events.html"><i class="la la-object-ungroup"></i> <span>Gallery</span></a>
							</li>
							
						</ul>
					</div>
                </div>
            </div>
			<!-- /Sidebar -->
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
			
				<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-12">
							<h4 class="page-title">Blank Page</h4>
						</div>
					</div>
					<!-- /Page Title -->
					
					<!-- Content Starts -->
						Content Starts Here
					<!-- /Content End -->
					
                </div>
				<!-- /Page Content -->
				
            </div>
			<!-- /Page Wrapper -->
			
        </div>
		<!-- /Main Wrapper -->
		
		<!-- Sidebar Overlay -->
		<div class="sidebar-overlay" data-reff=""></div>
		
		<!-- jQuery -->
        <script src="<?= url('plugins/purple/assets/js/jquery-3.2.1.min.js')?>"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="<?= url('plugins/purple/assets/js/popper.min.js')?>"></script>
        <script src="<?= url('plugins/purple/assets/js/bootstrap.min.js')?>"></script>
		
		<!-- Slimscroll JS -->
		<script src="<?= url('plugins/purple/assets/js/jquery.slimscroll.min.js')?>"></script>
		
		<!-- Custom JS -->
		<script src="<?= url('plugins/purple/assets/js/app.js')?>"></script>
		
    </body>
</html>