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

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{!! asset('plugins/sweetalert2/sweetalert2.min.css') !!}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{!! asset('plugins/toastr/toastr.min.css') !!}">

    <!-- Styles -->
<!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
</head>
<body>
<div id="app">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
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
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <!--<li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>-->
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{!! route('admin') !!}" class="brand-link">
            <img src="{!! asset('logo.png') !!}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">ES-iOS || HRMS</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                <?php $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,role FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);?>
                    @if($user[0]->foto!=null)
                        <img src="{!! asset('dist/img/profile/'.$user[0]->foto) !!}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                    @else
                        <img src="{!! asset('dist/img/profile/user.png') !!}" class="img-size-50 mr-3 img-circle elevation-2" alt="User Image">
                    @endif
                </div>
                <div class="info">
                    <a href="{!! route('admin') !!}" class="d-block">{!! Auth::user()->name !!} <marqu</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <!--<div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>-->

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="{!! route('home') !!}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard Karyawan
                            </p>
                        </a>
                    </li>
                  <?php 
                  if($user[0]->role==1 or  $user[0]->role==4		  ){ ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Perusahaan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{!! route('be.generate_nik') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Generate Absen </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{!! route('be.gallery') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Bank DISC</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.rmib') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Bank RMIB</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.berita') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Berita</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.divisi') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Departemen</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.fasilitas') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Fasilitas</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.lokasi') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Entitas</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.gallery') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Gallery</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.hari_libur') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Hari Libur</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{!! route('be.jabatan') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jabatan</p>
                                </a>
                            </li>
<li class="nav-item">
                                <a href="{!! route('be.jam_kerja') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Jam Kerja</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.mesin') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mesin Absen</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.pangkat') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pangkat</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('	','absen') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Periode Absen</p>
                                </a>
                            </li>
							<li class="nav-item">
                                <a href="{!! route('be.periode','lembur') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Periode Lembur</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.slider') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Slider</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.struktur_organisasi') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Struktur Organisasi</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{!! route('be.status_pekerjaan') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Status Pekerjaan</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.sync_mesin') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sync Absen</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.departemen') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Unit Kerja</p>
                                </a>
                            </li>

                            <!--<li class="nav-item">
                                <a href="{!! route('be.grup_jabatan') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Grup Jabatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{!! route('be.rumpun_jabatan') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rumpun Jabatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{!! route('be.cluster') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cluster</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{!! route('be.grade') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Grade</p>
                                </a>
                            </li>-->

                        </ul>
                    </li>
				<?php }?>
                    <?php if($user[0]->role==1 or  $user[0]->role==3 or  $user[0]->role==5  ){ ?>
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>
                                Informasi Karyawan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{!! route('be.karyawan') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Karyawan</p>
                                </a>
                            </li>
                             <?php if($user[0]->role==1 or  $user[0]->role==5  ){ ?>
                    
                            <li class="nav-item">
                                <a href="{!! route('be.kontrak') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kontrak Karyawan</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{!! route('be.list_ajuan','cuti') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cuti</p>
                                </a>
                            </li>
 							<li class="nav-item">
                                <a href="{!! route('be.list_ajuan','ijin') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ijin</p>
                                </a>
                            </li>
							<li class="nav-item">
                                <a href="{!! route('be.list_ajuan','perdin') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Perjalanan Dinas</p>
                                </a>
                            </li>

							<li class="nav-item">
                                <a href="{!! route('be.list_ajuan','lembur') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Lembur</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Gaji</p>
                                </a>
                            </li>
                   			<?php }?>
                            <li class="nav-item">
                                <a href="{!! route('be.pa') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Penilaian Kinerja</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                   <?php }?>
                     <?php if($user[0]->role==1 or  $user[0]->role==4 or  $user[0]->role==5  ){ ?>
                     
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Lowongan Pekerjaan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{!! route('be.recruitment') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pelamar</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{!! route('be.loker') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Lowongan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php }?>
                    <?php if($user[0]->role==1 or  $user[0]->role==5  ){ ?>
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Penggajian
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            
                            <li class="nav-item">
                                <a href="{!! route('be.gapok') !!}" class="nav-link">
                                    <i class="nav-icon  fa fa-window-restore" aria-hidden="true"></i>

                                    <p>Master</p>
                                </a>
                                 <ul class="nav nav-treeview">
                            
		                            <li class="nav-item">
		                                <a href="{!! route('be.gapok') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Detail Gaji Karyawan</p>
		                                </a><a href="{!! route('be.master_tunjangan') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Tunjangan</p>
		                                </a><a href="{!! route('be.master_potongan') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Potongon</p>
		                                
		                                </a><a href="{!! route('be.master_beban') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Beban</p>
		                                </a><a href="{!! route('be.master_pajak_ptkp') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Pajak PTKP</p>
		                                </a><a href="{!! route('be.master_status') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Status</p>
		                                </a><a href="{!! route('be.master_pajak_status') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Pajak Status</p>
		                                </a>
		                            </li>
		                        </ul>
                            </li><li class="nav-item">
                                <a href="{!! route('be.gapok') !!}" class="nav-link">
                                  
<i class="fa fa-credit-card  nav-icon" aria-hidden="true"></i>

                                    <p>Transaksi</p>
                                </a>
                                 <ul class="nav nav-treeview">
                            
		                          
		                             <li class="nav-item">
		                                <a href="{!! route('be.generategaji') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Generate Gaji</p>
		                                </a>
		                            </li>
		                            <li class="nav-item">
		                                <a href="{!! route('be.previewgaji') !!}" class="nav-link">
		                                    <i class="far fa-circle nav-icon"></i>
		                                    <p>Preview dan Edit Gaji</p>
		                                </a>
		                            </li>
                            </ul>
                            </li>
                           
							<li class="nav-item">
                                <a href="{!! route('be.rekap_lembur') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rekap Gaji</p>
                                </a>
                            </li>
                               
                               
                        </ul>
                    </li> 
					 <?php }?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Laporan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <!--<li class="nav-item">
                                <a href="{!! route('be.absen') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Absen</p>
                                </a>
                            </li>-->
                            <li class="nav-item">
                                <a href="{!! route('be.rekapabsen') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rekap Absen</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{!! route('be.rekap_lembur') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rekap Lembur</p>
                                </a>
                            </li>
                                <li class="nav-item">
                                    <a href="{!! route('be.cek_absen') !!}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cek Absen</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{!! route('be.ajuan') !!}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Cek Ajuan</p>
                                    </a>
                                </li>
                            <li class="nav-item">
                                <a href="{!! route('be.karyawanresign') !!}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Karyawan Resign</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                     <?php if($user[0]->role==1 or  $user[0]->role==5  ){ ?>
                    
                    <li class="nav-item">
                        <a href="{!! route('be.user') !!}" class="nav-link">
                            <i class="nav-icon far fa-user-circle"></i>
                            <p>
                                Pengguna
                            </p>
                        </a>
                    </li>
					 <?php }?>
                  <li class="nav-header">Keluar</li>
                    <li class="nav-item">
                        <a href="{!! route('logout') !!}" class="nav-link">
                            <i class="nav-icon far fa-circle"></i>
                            <p>
                                Keluar
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <main class="py-4">
        @yield('content')
    </main>
</div>

<footer class="main-footer">
    <strong>Copyright &copy; 2020 - {!! date('Y') !!} <a href="http://203.210.84.185:84" target="_blank">ES-iOS || HRMS</a>.</strong>
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
<script src="{!! asset('plugins/jquery/jquery.min.js') !!}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{!! asset('plugins/jquery-ui/jquery-ui.min.js') !!}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{!! asset('plugins/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
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
<!-- ChartJS -->
<script src="{!! asset('plugins/chart.js/Chart.min.js') !!}"></script>
<script src="{!! asset('plugins/table-with-fixed-header-and-sidebar/dist/script.js') !!}"></script>
<!-- page script -->
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    // Summernote
    $('#summernote').summernote()
    $('#keterangan').summernote()
    $('#alamat_ktp').summernote()
    $('#deskripsi_berita').summernote()
    $('#tujuan').summernote()
    $('#persyaratan').summernote()
    $('#tujuanen').summernote()
    $('#persyaratanen').summernote()

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
        mode: "htmlmixed",
        theme: "monokai"
    });

    // SimpleMDE
    new SimpleMDE({ element: document.getElementById("simpleMDE") });

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservationdate').datetimepicker({
        format: 'dd/mm/yyyy'
    });
    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY hh:mm A'
        }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
            ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
        format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    });

    $(document).ready(function () {
        var stepper = new Stepper($('.bs-stepper')[0])
    })

    // DropzoneJS Demo Code Start
    Dropzone.autoDiscover = false;

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/target-url", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    });

    myDropzone.on("addedfile", function(file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
    });

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
    });

    myDropzone.on("sending", function(file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1";
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
    });

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        document.querySelector("#total-progress").style.opacity = "0";
    });

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };
    document.querySelector("#actions .cancel").onclick = function() {
        myDropzone.removeAllFiles(true);
    };
    // DropzoneJS Demo Code End
</script>
<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })
    function handleNumber(event, mask) {
    /* numeric mask with pre, post, minus sign, dots and comma as decimal separator
        {}: positive integer
        {10}: positive integer max 10 digit
        {,3}: positive float max 3 decimal
        {10,3}: positive float max 7 digit and 3 decimal
        {null,null}: positive integer
        {10,null}: positive integer max 10 digit
        {null,3}: positive float max 3 decimal
        {-}: positive or negative integer
        {-10}: positive or negative integer max 10 digit
        {-,3}: positive or negative float max 3 decimal
        {-10,3}: positive or negative float max 7 digit and 3 decimal
    */
    with (event) {
        stopPropagation()
        preventDefault()
        if (!charCode) return
        var c = String.fromCharCode(charCode)
        if (c.match(/[^-\d,]/)) return
        with (target) {
            var txt = value.substring(0, selectionStart) + c + value.substr(selectionEnd)
            var pos = selectionStart + 1
        }
    }
    var dot = count(txt, /\./, pos)
    txt = txt.replace(/[^-\d,]/g,'')

    var mask = mask.match(/^(\D*)\{(-)?(\d*|null)?(?:,(\d+|null))?\}(\D*)$/); if (!mask) return // meglio exception?
    var sign = !!mask[2], decimals = +mask[4], integers = Math.max(0, +mask[3] - (decimals || 0))
    if (!txt.match('^' + (!sign?'':'-?') + '\\d*' + (!decimals?'':'(,\\d*)?') + '$')) return

    txt = txt.split(',')
    if (integers && txt[0] && count(txt[0],/\d/) > integers) return
    if (decimals && txt[1] && txt[1].length > decimals) return
    txt[0] = txt[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.')

    with (event.target) {
        value = mask[1] + txt.join(',') + mask[5]
        selectionStart = selectionEnd = pos + (pos==1 ? mask[1].length : count(value, /\./, pos) - dot) 
    }

    function count(str, c, e) {
        e = e || str.length
        for (var n=0, i=0; i<e; i+=1) if (str.charAt(i).match(c)) n+=1
        return n
    }
}
	function format(number, prefix='Rp ', decimals = 2, decimalSeparator = ',', thousandsSeparator = '.') {
	  const roundedNumber = number.toFixed(decimals);
	  let integerPart = '',
	    fractionalPart = '';
	  if (decimals == 0) {
	    integerPart = roundedNumber;
	    decimalSeparator = '';
	  } else {
	    let numberParts = roundedNumber.split('.');
	    integerPart = numberParts[0];
	    fractionalPart = numberParts[1];
	  }
	  integerPart =prefix+ integerPart.replace(/(\d)(?=(\d{3})+(?!\d))/g, `$1${thousandsSeparator}`);
	  return `${integerPart}${decimalSeparator}${fractionalPart}`;
	}
	function rupiahtonumber(text){
		var chars = {'.':'',',':'.','R':'','p':'',' ':''};

	text = text.replace(/[.,Rp ]/g, m => chars[m]);


	 return text
	}
	
</script>
</body>
</html>
