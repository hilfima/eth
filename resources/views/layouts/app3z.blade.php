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
                                <a href="{!! route('be.periode','absen') !!}" class="nav-link">
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
