<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>ADMIN | HCMS ETHICS GROUP</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/bootstrap.min.css')?>">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/font-awesome.min.css')?>">
		<link rel="stylesheet" href="{!! asset('plugins/select2/css/select2.min.css') !!}">
	    <link rel="stylesheet" href="{!! asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') !!}">

		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/line-awesome.min.css')?>">
		<link rel="stylesheet" href="{!! asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') !!}">
    	<link rel="stylesheet" href="{!! asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') !!}">

		<!-- Main CSS -->
        <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/style.css')?>">
		<!-- Datatable CSS -->
		<link rel="stylesheet" href="<?= url('plugins/purple/assets/css/dataTables.bootstrap4.min.css')?>">
		 <!-- Select2 -->
	    
		
		<link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">
		<link rel="stylesheet" href="{!! asset('plugins/purple/assets/plugins/summernote/dist/summernote-bs4.css')!!}">
		<script src="{!! asset('plugins/purple/assets/plugins/summernote/dist/summernote-bs4.min.js')!!}"></script>
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
        $sqluser="SELECT p_recruitment.foto,role,m_lokasi.nama as nmlokasi,p_karyawan.nama,users.name FROM users
				left join p_karyawan on p_karyawan.user_id=users.id
				left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
				left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
				left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
				where users.id=$iduser";
        $user=DB::connection()->select($sqluser);?>
				<!-- Logo -->
                <div class="header-left">
                    <a href="{!! route('admin') !!}" class="logo">
                     <img src="{!! asset('logo.png') !!}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8;width: 50px;height: 50px">
            
						
					</a>
                </div>
				<!-- /Logo margin-top: 20px -->
				
				<a id="toggle_btn" href="javascript:void(0);" style="">
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
				<?php 
				if(Auth::user()->role==3 or Auth::user()->role==-1 or Auth::user()->role==5  ) {
            
							$date = date('Y-m-d');
			$sql = "SELECT count(*) FROM t_permit a
WHERE 1=1 and a.active=1 and
((
a.m_jenis_ijin_id =22 and
(case
WHEN status_appr_1=1 and appr_2 is null THEN  tgl_appr_1<='$date'
WHEN status_appr_1=1 and status_appr_2=1 THEN  tgl_appr_2<='$date'
WHEN appr_1 is null and status_appr_2=1 THEN  tgl_appr_2<='$date'
end)
) or (((tgl_awal<='$date') or (tgl_akhir<='$date')) and a.m_jenis_ijin_id !=22)) and status_appr_hr!=1  ";
			$appr_hr=DB::connection()->select($sql);
			$sql = "SELECT count(*) FROM t_permit a
WHERE 1=1 and a.active=1 and a.appr_1=-1  and status_appr_1 = 3 ";
			$ajuan_direksi=DB::connection()->select($sql);
			$sql = "SELECT count(*) FROM t_permintaan_surat
WHERE 1=1  and active=1 and status=3";
			$permintaan_surat=DB::connection()->select($sql);
			$sql = "SELECT count(*) FROM t_faskes
WHERE 1=1 and jenis = 2    and t_faskes.active = 1 and appr_status=3";
			$faskes=DB::connection()->select($sql);
			$sql = "SELECT count(*) FROM t_cover_bpjs
                WHERE 1=1   and status = 3";
			$bpjs=DB::connection()->select($sql);
			$sql = "select count(*)
				
			 from t_mudepro 
			where active=1 and (status=11 or status=1)";
			$mudepro_asesment=DB::connection()->select($sql);
			$sql = "select count(*)
				
			 from t_mudepro 
			where active=1 and (status=3)";
			$mudepro_final=DB::connection()->select($sql);
			
			$sql = "select count(*)
from t_karyawan
where t_karyawan.active = 1 and status=2" ;
			$ajuan_karyawan_proses=DB::connection()->select($sql);
			$sql = "select  count(*) 
from t_karyawan
where t_karyawan.active = 1 and status=3" ;
			$ajuan_karyawan_interview=DB::connection()->select($sql);
			$sqltotalkontrak = "SELECT count(*) as total 
                    FROM p_karyawan_kontrak
                    LEFT JOIN p_karyawan on p_karyawan.p_karyawan_id=p_karyawan_kontrak.p_karyawan_id
                    LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
                    WHERE 1=1 and p_karyawan_kontrak.active=1  and p_karyawan_kontrak.m_status_pekerjaan_id NOT IN(10) 
                    and p_karyawan_kontrak.tgl_akhir <'" . $date . "' and p_karyawan.active=1  and p_karyawan_kontrak.active=1 ";
        $totalkontrak = DB::connection()->select($sqltotalkontrak);				
					$sqltotalkontrak = "select count(*) from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id
left join p_karyawan b on appr = b.p_karyawan_id
where 1=1     and ((tanggal<='$date' and tujuan<6))
and selesai in(0,2,4)
";
        $klarifikasi_absen = DB::connection()->select($sqltotalkontrak);			
					$sqltotalkontrak = "select count(*) from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id
left join p_karyawan b on appr = b.p_karyawan_id
where 1=1     and ((tanggal<='$date' and tujuan>6))
and selesai in(0,2,4)
";
        $klarifikasi_gaji = DB::connection()->select($sqltotalkontrak);				
		$sql = "select count(*) from t_mudepro where (status=1 or status=11) and active=1";
    	$asesment = DB::connection()->select($sql);				
		$sql = "select count(*) from t_mudepro where (status=3) and active=1";
    	$finalisasi = DB::connection()->select($sql);
   					
							?>
				<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"></path></svg> <span class="badge badge-pill"><?=$appr_hr[0]->count+$totalkontrak[0]->total+$ajuan_karyawan_interview[0]->count+$ajuan_karyawan_proses[0]->count+$klarifikasi_absen[0]->count+$klarifikasi_gaji[0]->count+$ajuan_direksi[0]->count+$permintaan_surat[0]->count+$faskes[0]->count+$bpjs[0]->count+$mudepro_asesment[0]->count+$mudepro_final[0]->count+$asesment[0]->count+$finalisasi[0]->count;?></span>
						</a>
						<div class="dropdown-menu notifications" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 60px, 0px);">
							<div class="topnav-dropdown-header">
								<span class="notification-title">Notifications</span>
								<a href="javascript:void(0)" class="clear-noti"> Clear All </a>
							</div>
							
							<div class="noti-content">
								<ul class="notification-list">
								<?php if($appr_hr[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.hr_appr');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Approval HR</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$appr_hr[0]->count;?> Ajuan Belum terposes</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($totalkontrak[0]->total){?>
									<li class="notification-message">
										<a href="<?=route('be.hr_appr');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Kontrak Kerja</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$totalkontrak[0]->total;?> Belum terposes</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($ajuan_karyawan_interview[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.karyawan_baru');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Pengajuan Karyawan Baru</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$ajuan_karyawan_interview[0]->count;?> Konfirmasi Hasil Interview</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($ajuan_karyawan_proses[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.karyawan_baru');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Pengajuan Karyawan Baru</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$ajuan_karyawan_proses[0]->count;?> Ajuan belum diproses</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($asesment[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.karyawan_baru');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Pengajuan Mutasi Demosi Promosi</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$asesment[0]->count;?> Ajuan perlu asesment HC</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?><?php if($finalisasi[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.karyawan_baru');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Pengajuan Mutasi Demosi Promosi</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$finalisasi[0]->count;?> Ajuan perlu Finalisasi Perpindahan</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($klarifikasi_absen[0]->count){?>
									
									<li class="notification-message">
										<a href="<?=route('be.klarifikasi_absen');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Klarifikasi Absen</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$klarifikasi_absen[0]->count;?> Belum terposes</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($klarifikasi_gaji[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.klarifikasi_absen');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Klarifikasi Gaji</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$klarifikasi_gaji[0]->count;?> Belum terposes</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($ajuan_direksi[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.pengajuan_direksi');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Ajuan Direksi</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$ajuan_direksi[0]->count;?> Ajuan Belum terposes</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($permintaan_surat[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.permintaan_surat');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Permintaan Surat</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$permintaan_surat[0]->count;?> Ajuan Belum terposes</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($faskes[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.pengajuan_faskes');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Pengajuan Faskes	</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$faskes[0]->count;?> Ajuan Belum terposes</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($bpjs[0]->count){?>
									
									<li class="notification-message">
										<a href="<?=route('be.pengajuan_bpjs');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Pengajuan BPJS	</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$bpjs[0]->count;?> Ajuan Belum terposes</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									<?php if($mudepro_asesment[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.mudepro');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Pengajuan Perpindahan Karyawan	</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$mudepro_asesment[0]->count;?> Menunggu Asesment HR</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									
									<?php if($mudepro_final[0]->count){?>
									<li class="notification-message">
										<a href="<?=route('be.mudepro');?>">
											<div class="media">
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Pengajuan Perpindahan Karyawan	</span> </p>
													<p class="noti-time"><span class="text-red"> <?=$mudepro_final[0]->count;?> Menunggu Finalisasi HR</span></p>
												</div>
											</div>
										</a>
									</li>
									<?php }?>
									
								</ul>
							</div>
							
						</div>
					</li>
					<?php }?>
					
					<li class="nav-item dropdown has-arrow main-drop">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<span class="user-img">
							<span class="status online"></span></span>
							<span><?=$user[0]->name;?></span>
						</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="{!! route('password') !!}">Ubah Password</a>
							<a class="dropdown-item" href="{!! route('logout') !!}">Logout</a>
						</div>
					</li>
				</ul>
				<!-- /Header Menu -->
				
				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="{!! route('password') !!}">Ubah Password</a>
						<a class="dropdown-item" href="{!! route('logout') !!}">Logout</a>
					</div>
				</div>
				<!-- /Mobile Menu -->
				
            </div>