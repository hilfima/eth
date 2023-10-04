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
								<a href="{!!route('admin')!!}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-building"></i> <span> Menu & Akses </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="{!!route('be.menu')!!}"> Menu </a></li>
									<li><a href="{!!route('be.admin')!!}"> Role </a></li>
									<li><a href="{!!route('be.user')!!}"> User </a></li>
								</ul>
							</li>
							
							<?php 
							$sqlmenu="SELECT *,(select count(*) from m_menu b where b.parent = a.m_menu_id ) as count from m_menu a where a.parent = 0 and active=1 and type=1 and 
							(
							(select count(*) from users where role = -1 and id=$iduser)>=1
							 or
							 a.m_menu_id in (select users_admin.menu_id from users join users_admin on m_role_id = role and users_admin.active=1 where  id=$iduser)
							)  
							
							order  by urutan"; 
							$menuSide=DB::connection()->select($sqlmenu);
							function menuFunction ($parent,$iduser){
							 $sqlmenu="SELECT *,(select count(*) from m_menu b where b.parent = a.m_menu_id ) as count from m_menu a where a.parent = $parent and active=1 and type=1 and 
							(
							(select count(*) from users where role = -1 and id=$iduser)>=1
							 or
							 a.m_menu_id in (select users_admin.menu_id from users join users_admin on m_role_id = role and users_admin.active=1 where  id=$iduser)
							)  
							order  by urutan"; 
							 $return = '';
							 $menuSide=DB::connection()->select($sqlmenu);
							 if(count($menuSide)){
							 	
							 $return.= '<ul style="display: none;">';
							 foreach($menuSide as $menuSide){
							 	$link = $menuSide->link=='#'?'javascript:void(0)':route($menuSide->link,$menuSide->link_sub);
							 $return.= '
									<li class="nav-item">
		                                <a href="'.$link.'" class="nav-link">
		                                    
		                                  '.$menuSide->nama_menu.' 
		                                </a>
		                                
										'.menuFunction($menuSide->m_menu_id,$iduser).'
                            		</li>
                            	';
								 }
                            	$return.= '</ul>';
							 }
							 return $return;
							};
							
							
							foreach($menuSide as $menuSide){?>
							<li <?=$menuSide->count?'class="submenu"':'';?>>
								<a href="<?=$menuSide->link=='#'?'javascript:void(0)':route($menuSide->link,$menuSide->link_sub);?>"><?=$menuSide->icon?'<i class="'.$menuSide->icon.'"></i>':'';?> <span><?=$menuSide->nama_menu;?></span> <?=$menuSide->count?'<span class="menu-arrow"></span>':'';?></a>
								<?php if($menuSide->count){
									echo menuFunction($menuSide->m_menu_id,$iduser);
									 ?>
									
								
								<?php }?>
                            </li>
                            <?php }?>
                            
							
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
					 <div class="content-wrapper">
    @include('flash-message')
   
  <link rel="stylesheet" href="<?=url('plugins\orgchart\css/jquery.orgchart.css')?>">
  <link rel="stylesheet" href="<?=url('plugins\orgchart\css/style.css')?>">
   <style>
    	.orgchart .node .content {
  height: auto !important;
  min-height: 20px;
  }
    </style>
    
	<!-- Main content -->
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
			<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.struktur_organisasi') !!}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label>Entitas</label>
							<select class="form-control select2" name="entitas" style="width: 100%;" required>
								<option value="">Pilih Entitas</option>
								<?php
								foreach($entitas AS $entitas){
									if($entitas->m_lokasi_id==$e){
										echo '<option selected="selected" value="'.$entitas->m_lokasi_id.'">'.$entitas->nama.'</option>';
									}
									else{
										echo '<option value="'.$entitas->m_lokasi_id.'">'.$entitas->nama.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					</div>
					<a href="{!! route('be.struktur_organisasi') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
						<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
					</div>
    
  <div id="chart-container"></div> 
		</div>
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\orgchart\js/jquery.min.js')?>"></script>
  <script type="text/javascript" src="<?=url('plugins\orgchart\js/jquery.orgchart.js')?>"></script>
  <script type="text/javascript" src="<?=url('plugins\orgchart\js/jspdf.umd.min.js')?>"></script>
  <script type="text/javascript">
    $(function() {

    var datasource = <?=(substr(json_encode($struktur),1,-1))?>;

    $('#chart-container').orgchart({
      'data' : datasource,
      'nodeContent': 'title',
      'exportButton': true,
      'exportFileextension': 'pdf',
      'exportFilename': 'MyOrgChart'
    });

  });
  </script>
					
					<!-- /Content End -->
					
                </div>
				<!-- /Page Content -->
				
            </div>
			<!-- /Page Wrapper -->
			
        </div>
		<!-- /Main Wrapper -->
		
		<!-- Sidebar Overlay -->
	
		<!-- Bootstrap Core JS -->
        <script src="<?= url('plugins/purple/assets/js/popper.min.js')?>"></script>
        <script src="<?= url('plugins/purple/assets/js/bootstrap.min.js')?>"></script>
		
		<!-- Slimscroll JS -->
		<script src="<?= url('plugins/purple/assets/js/jquery.slimscroll.min.js')?>"></script>
		
		<!-- Custom JS -->
		
<!-- Summernote -->
<script src="{!! asset('plugins/summernote/summernote-bs4.min.js') !!}"></script>

<script src="{!! asset('plugins/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') !!}"></script>
		<script src="<?= url('plugins/purple/assets/js/jquery.dataTables.min.js')?>"></script>
		<script src="<?= url('plugins/purple/assets/js/dataTables.bootstrap4.min.js')?>"></script>
		<script src="<?= url('plugins/purple/assets/js/apps.js')?>"></script>
		<script src="{!! asset('plugins/select2/js/select2.full.min.js') !!}"></script>
		<script src="{!! asset('plugins/table-with-fixed-header-and-sidebar/dist/script.js') !!}"></script>
		<script src="{!! asset('plugins/pace-progress/pace.min.js') !!}"></script>
		<!-- ChartJS -->
		<script src="{!! asset('plugins/chart.js/Chart.min.js') !!}"></script>
		<!-- JQVMap -->
		<script src="{!! asset('plugins/jqvmap/jquery.vmap.min.js') !!}"></script>
		<script src="{!! asset('plugins/jqvmap/maps/jquery.vmap.usa.js') !!}"></script>
		<!-- jQuery Knob Chart -->
		<script src="{!! asset('plugins/jquery-knob/jquery.knob.min.js') !!}"></script>
		<!-- daterangepicker -->
		<script src="{!! asset('plugins/moment/moment.min.js') !!}"></script>
		<script src="{!! asset('plugins/daterangepicker/daterangepicker.js') !!}"></script>
		<!-- Tempusdominus Bootstrap 4 -->
		<script src="{!! asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') !!}"></script>
		<!-- Summernote -->
		<script src="{!! asset('plugins/summernote/summernote-bs4.min.js') !!}"></script>
		<!-- overlayScrollbars -->
		<script src="{!! asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') !!}"></script>
		<!-- AdminLTE App -->
		<script src="{!! asset('dist/js/adminlte.js') !!}"></script>
		<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		<script src="{!! asset('dist/js/pages/dashboard.js') !!}"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="{!! asset('dist/js/demo.js') !!}"></script>
		<!-- DataTables -->
		<script src="{!! asset('plugins/datatables/jquery.dataTables.min.js') !!}"></script>
		<script src="{!! asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') !!}"></script>
		<script src="{!! asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') !!}"></script>
		<script src="{!! asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') !!}"></script>
		<!-- bootstrap color picker -->
		<script src="{!! asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') !!}"></script>
		<!-- Select2 -->
		<script src="{!! asset('plugins/select2/js/select2.full.min.js') !!}"></script>
		<!-- Bootstrap Switch -->
		<script src="{!! asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}"></script>
		<!-- BS-Stepper -->
		<script src="{!! asset('plugins/bs-stepper/js/bs-stepper.min.js') !!}"></script>
		<!-- dropzonejs -->
		<script src="{!! asset('plugins/dropzone/min/dropzone.min.js') !!}"></script>
		<script src="{!! asset('plugins/chart.js/Chart.min.js') !!}"></script>
		<script src="{!! asset('plugins/filterizr/jquery.filterizr.min.js') !!}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    </body>
</html>
    <!-- Content Wrapper. Contains page content -->
   