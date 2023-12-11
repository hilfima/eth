<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dleohr.dreamguystech.com/template-1/dleohr-horizontal/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Jul 2022 01:57:49 GMT -->
<?php
if (! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off' ) {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url");
    exit();
}
?>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HCMS ETHICS  GROUP</title>

    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png">

    <link rel="stylesheet" href="<?= url('plugins/dleohr/assets/css/bootstrap.min.css') ?>">

    <link rel="stylesheet" href="<?= url('plugins/dleohr/assets/css/lnr-icon.css') ?>">

    <link rel="stylesheet" href="<?= url('plugins/dleohr/assets/css/font-awesome.min.css') ?>">

    <link rel="stylesheet" href="<?= url('plugins/dleohr/assets/css/bootstrap-datetimepicker.min.css') ?>">
    <link rel="stylesheet" href="<?= url('plugins/dleohr/assets/plugins/select2/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= url('plugins/dleohr/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= url('plugins/purple/assets/css/line-awesome.min.css') ?>">


    <link rel="stylesheet" href="assets/plugins/select2/select2.min.css">
    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    <style>
        @media only screen and (max-width: 575.98px) {

            .header {
                padding: 0 0px 85px;
            }

            .logo a img {
                width: 115px;
                position: absolute;
                top: -31px !important;
            }
        }

        .stickyheader {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9;
        }

        .content {
            min-height: 120%
        }

        .badge {
            position: relative;
            width: 20px !important;
            height: 17px !important;
            top: -15px !important;
            font-size: 10px !important;
        }
        tr td,th th{
                font-size:10px
            }
        th{
            text-align: center;
        	vertical-align: middle !important;
        }tr td:first-child,
		tr td:last-child {
					text-align: center;
		    /* styles */
        }
		textarea.form-control {
             color: #555; 
        }
        label{
            font-weight:700;
		}
    </style><style>
		    body{
		        font-size:12px;
		    }
		    .btn{
		        font-size:12px;
		    }
		    .form-control{
		        font-size:12px;
		    }
		    label{
		        font-size:12px;
		    }
		    .select2-selection__rendered{
		          font-size:12px !important;
		    }
		    .dropdown-item{
		          font-size:12px !important;

		    }.list-group li{
		          /*font-size:16px !important;*/
		          font-size: .875rem;
    line-height: 20px;
    letter-spacing: .2px;
		        
		    }
		</style>
</head>

<body>
    <?php

    use App\Helper_function;

    $iduser = Auth::user()->id;
    $sqluser = "SELECT p_recruitment.foto,role,m_lokasi.nama as nmlokasi,p_karyawan.nama ,p_karyawan_pekerjaan.m_jabatan_id,p_karyawan.p_karyawan_id,p_karyawan_kontrak.*,periode_gajian,p_karyawan.*,p_karyawan_pekerjaan.* FROM users
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
		left join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id=p_karyawan.p_karyawan_id
		left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
    $user = DB::connection()->select($sqluser);

    $id = $user[0]->p_karyawan_id;
    $sqlkaryawan = "SELECT a.p_karyawan_id,a.nik,nama_darurat,hubungan_darurat,b.nama_lengkap,b.nama_panggilan,c.nama as nmjk,d.nama as nmkota,e.nama as nmagama,g.nama as nmlokasi,h.nama as nmjabatan,i.nama as nmdept,j.nama as nmdivisi,l.nama as nmstatus,m.no_absen,b.foto,p.password,
			case when f.is_shift=0 then 'Shift' else 'Non Shift' end as shift,kontak_darurat,k.tgl_awal,k.tgl_akhir,a.tgl_bergabung,a.email_perusahaan,k.keterangan,case when a.active=1 then 'Active' else 'Non Active' end as status,o.nama as nmpangkat,b.alamat_ktp,a.pendidikan,a.jurusan,a.nama_sekolah,a.domisili,coalesce(a.jumlah_anak::int	,0) as jumlah_anak,b.tempat_lahir,case when m_status_id=0 then 'Belum Menikah' else 'Sudah Menikah' end as status_pernikahan,b.no_hp,c.nama as jenis_kelamin,e.nama as agama,b.email,b.tgl_lahir,f.kantor,n.ktp,n.no_npwp,n.no_bpjstk,n.no_bpjsks,b.m_status_id,b.m_kota_id,b.m_jenis_kelamin_id,b.m_agama_id,f.m_lokasi_id,f.m_jabatan_id,k.m_status_pekerjaan_id,f.m_departemen_id,f.m_divisi_id,a.active,n.no_sima,n.no_simc,n.no_pasport,n.kartu_keluarga,case when f.periode_gajian=1 then 'Bulanan' else 'Pekanan' end as periode,f.periode_gajian,q.nama as nama_kantor,f.bank,f.norek,f.kota,n.file_kk,n.file_ktp,n.file_bpjs_karyawan, aa.nama_grade as grade,o.nama as nmpangkat
			FROM p_karyawan a
			LEFT JOIN p_recruitment b on b.p_recruitment_id=a.p_recruitment_id
			LEFT JOIN m_jenis_kelamin c on c.m_jenis_kelamin_id=b.m_jenis_kelamin_id
			LEFT JOIN m_kota d on d.m_kota_id=b.m_kota_id
			LEFT JOIN m_agama e on e.m_agama_id=b.m_agama_id
			LEFT JOIN p_karyawan_pekerjaan f on f.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN m_lokasi g on g.m_lokasi_id=f.m_lokasi_id
			LEFT JOIN m_jabatan h on h.m_jabatan_id=f.m_jabatan_id
			LEFT JOIN m_pangkat o on o.m_pangkat_id=h.m_pangkat_id
			LEFT JOIN m_departemen i on i.m_departemen_id=f.m_departemen_id
			LEFT JOIN m_divisi j on j.m_divisi_id=f.m_divisi_id
			LEFT JOIN p_karyawan_kontrak k on k.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN m_status_pekerjaan l on l.m_status_pekerjaan_id=k.m_status_pekerjaan_id
			LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN p_karyawan_kartu n on n.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN m_office q on q.m_office_id=f.m_kantor_id
			LEFT JOIN users p on p.id=a.user_id	
			LEFT JOIN m_karyawan_grade aa on h.job>=aa.job_min and h.job<= aa.job_max
			WHERE a.p_karyawan_id=$id";
    $karyawan = DB::connection()->select($sqlkaryawan);



    $checking_field = array(
        "Nama Lengkap"        => 'nama_lengkap',
        "Nama Panggilan"     => 'nama_panggilan',
        "Tempat Lahir"        => 'tempat_lahir',
        "Tanggal Lahir"        => 'tgl_lahir',
        "Jenis Kelamin"    => 'm_jenis_kelamin_id',
        "Foto"                => 'foto',
        "Domisili"            => 'domisili',
        //"Pendidikan Terkahir"=>'pendidikan',
        //"Jurusan"=> 'jurusan',
        //"Nama Sekolah"=>'nama_sekolah',
        "Status perkawinan" => 'm_status_id',
        "Jumlah Anak" => 'jumlah_anak',
        "Nama Kontak Darurat" => 'nama_darurat',
        "Hubungan Kontak  Darurat" => 'hubungan_darurat',
        "No Kontak Darurat" => 'kontak_darurat',
        "Agama" => 'm_agama_id',
        "Email" => 'email_perusahaan',
        "File KK" => 'file_kk',
        "File KTP" => 'file_ktp',
        "No Hp" => 'no_hp',
        "No Kartu Keluarga" => 'kartu_keluarga',
        "No KTP" => 'ktp',
        "Alamat" => 'alamat_ktp',
        "No NPWP" => 'no_npwp',
    );
    $checking_jumlah = array(
        "Data Keluarga" => 'keluarga',
        "Data Pendidikan" => 'pendidikan',
        "Data Pakaian" => 'datapakaiankeluarga',
    );
    $required_field = array();
    foreach ($checking_field as $key => $value) {
        if (($karyawan[0]->$value)==null or ($karyawan[0]->$value)=='') {
            if ($value == 'no_npwp')

                $append = " - Kartu Identitas (isi dengan -1 jika beluum mempunyai | No NPWP diwajibkan untuk perhitungan potongan pajak tetap penggajian)";
            else if ($value == 'jumlah_anak')

                $append = "  (isi dengan -1 jika belum mempunyai anak)";
            else
                $append = "";
            $required_field[] = "Data " . $key . " Belum Terisi" . $append;
        }
    }
    $sql = "SELECT * FROM p_karyawan_keluarga WHERE active=1 and p_karyawan_id=$id ORDER BY nama ASC ";
    $keluarga = DB::connection()->select($sql);
    $sql = "SELECT * FROM p_karyawan_pendidikan WHERE active=1 and p_karyawan_id=$id ORDER BY nama_sekolah ASC ";
    $pendidikan = DB::connection()->select($sql);
    $sql = "SELECT * FROM p_karyawan_kursus WHERE active=1 and p_karyawan_id=$id ORDER BY nama_kursus ASC ";
    $kursus = DB::connection()->select($sql);
    $sqlidkar = "SELECT *
	    ,CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN d.nama
	   				ELSE c.nama END as nama
	   				,
	   				CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN 'Diri Sendiri'
	   				ELSE c.hubungan END as hubungan
	   				
	     FROM p_karyawan_pakaian 
	     	left join p_karyawan D on p_karyawan_pakaian.p_karyawan_id = d.p_karyawan_id 
	     	left join p_karyawan_keluarga c on p_karyawan_pakaian.p_karyawan_keluarga_id = c.p_karyawan_keluarga_id 
	     
	     WHERE p_karyawan_pakaian.p_karyawan_id=$id and tipe=2 and p_karyawan_pakaian.active=1";
    $datapakaiankeluarga = DB::connection()->select($sqlidkar);

    foreach ($checking_jumlah as $key => $value) {

        if (!count($$value))
            $required_field[] = "Data " . $key . " Belum Terisi";
    }






    if ($user[0]->periode_gajian == 1) {
        $sql = "select * from m_gaji_bulanan order by tanggal desc limit 1";
        $gapen = DB::connection()->select($sql);
        $help = new Helper_function();
        $tgl_awal_gaji = $gapen[0]->tanggal;
        $next_gajian = $help->tambah_bulan($gapen[0]->tanggal, 1);
        if ($next_gajian < date('Y-m-d')) {

            $tgl_awal_gaji = $next_gajian;
            DB::connection()->table("m_gaji_bulanan")
                ->insert([
                    "tanggal" => $next_gajian,
                ]);

            $next_gajian = $help->tambah_bulan($next_gajian, 1);
        }
        $tgl_akhir_gaji = $next_gajian;
        $tgl_akhir_gaji = date('Y-m-d');
        $tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji, 2);
        $tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian, 2);
    } else if ($user[0]->periode_gajian == 0) {


        $sql = "select * from m_gaji_pekanan order by tanggal limit 1";
        $gapen = DB::connection()->select($sql);
        $help = new Helper_function();
        $tgl_awal_gaji = $gapen[0]->tanggal;
        $next_gajian = $help->tambah_tanggal($gapen[0]->tanggal, 14);
        if ($next_gajian < date('Y-m-d')) {
            $tgl_awal_gaji = $next_gajian;
            DB::connection()->table("m_gaji_pekanan")
                ->insert([
                    "tanggal" => $next_gajian,
                ]);

            $next_gajian = $help->tambah_tanggal($next_gajian, 14);
        }
        $tgl_akhir_gaji = $next_gajian;
        $tgl_akhir_gaji = date('Y-m-d');
        $tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji, 1);
        $tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian, 1);
    }

    //echo $tgl_akhir_gaji;die;

    $tgl_awal = $tgl_awal_gaji;
    $tgl_akhir = date('Y-m-d');

    $id_karyawan = $user[0]->p_karyawan_id;
    $id_jabatan = $user[0]->m_jabatan_id;
    $sqlberita = "
        SELECT * FROM hr_care where active=1 and to_char(tgl_posting,'YYYY-MM-DD') <='" . date('Y-m-d') . "' and to_char(tgl_posting_akhir,'YYYY-MM-DD')>='" . date('Y-m-d') . "' 
        
       ";
    $berita = DB::connection()->select($sqlberita);




    //print_r($sqlberita);die;
    $sqlloker = "SELECT a.*,b.nama as nmlokasi,c.nama as nmdepartemen,d.nama as nmjabatan, e.nama as nmstatus
FROM t_job_vacancy a
LEFT JOIN m_lokasi b on b.m_lokasi_id=a.m_lokasi_id
LEFT JOIN m_departemen c on c.m_departemen_id=a.m_departemen_id
LEFT JOIN m_jabatan d on d.m_jabatan_id=a.m_jabatan_id
LEFT JOIN m_status_pekerjaan e on e.m_status_pekerjaan_id=a.m_status_pekerjaan_id
WHERE a.active=1 and to_char(a.tgl_awal,'YYYY-MM-DD') <='" . date('Y-m-d') . "' and to_char(tgl_akhir,'YYYY-MM-DD')>='" . date('Y-m-d') . "' 
";
    $loker = DB::connection()->select($sqlloker);

    $today = array();

	$notifikasi_berita = DB::connection()->select("select * from notifikasi where p_karyawan_id=$id and terbaca=0");
    

                                            $sqlultah = "SELECT nama_Lengkap,tgl_lahir, EXTRACT(year FROM AGE(now(), tgl_lahir)) as usia FROM p_recruitment 
                                            left join p_karyawan on p_karyawan.p_recruitment_id = p_recruitment.p_recruitment_id
                                            WHERE to_char(tgl_lahir,'mm') = to_char(now(),'mm') and to_char(tgl_lahir,'dd') = to_char(now(),'dd') 
                                            and p_karyawan.active=1
                                            ORDER BY to_char(tgl_lahir,'%d %m'),tgl_lahir";
                                            $ultah = DB::connection()->select($sqlultah);

                                                        ?>
    <?php
    if (!isset($profile_power))
        $profile_power = false;
    if (count($required_field) and !$profile_power) { ?>
        <div class="card">
            <div class="card-body text-center">
                <img src="{!! asset('dist/img/logo/logo.png') !!}" alt="logo image" class="img-fluid" width="50" style="position: absolute;top: -90px">


                <div class="alert alert-danger" role="alert">
                    Mohon Maaf. Data profil anda belum lengkap. Silahkan mengisi data profile terlebih dahulu untuk mengakses fitur fitur HCMS lainnya.
                    <?= (stripos('profile', $_SERVER['REQUEST_URI'])) ?></div>
                <a href="{{route('fe.edit_profile')}}" class="btn btn-primary">Edit Profil</a>

            </div>
            <div class="card-footer">
                Total : <?= count($required_field) ?>
                <br>
                <ul>

                    <?php for ($i = 0; $i < count($required_field); $i++) { ?>
                        <li><?= $required_field[$i]; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    <?php } else { ?>
    <div class=""></div>
        <div class="inner-wrapper">

            <div id="loader-wrapper">
                <div class="loader">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>

            <header class="header">

                <div class="top-header-section">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-6">
                                <div class="logo my-3 my-sm-0">
                                    <a href="{!! route('home') !!}">
                                        <img src="{!! asset('dist/img/logo/logo.png') !!}" alt="logo image" class="img-fluid" width="50" style="position: absolute;top: -90px">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-6 text-right">
                                <div class="user-block d-none d-lg-block">
                                    <div class="row align-items-center">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="user-notification-block align-right d-inline-block">
                                                <div class="top-nav-search item-animated">

                                                </div>
                                            </div>

                                            <div class="user-notification-block align-right d-inline-block">
                                                <ul class="list-inline m-0">
                                                    <li class="list-inline-item item-animated" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lowongan Kerja">
                                                        <a href="#" data-toggle="modal" data-target="#loker" class="font-23 menu-style text-white align-middle " style="color:black">
                                                            <span class="la la-users position-relative" style="color:black"><span class="badge badge-<?= count($loker) ? 'light' : 'light'; ?>"><?= count($loker) ?></span></span>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item item-animated" data-toggle="tooltip" data-placement="top" title="" data-original-title="Menunggu Konfirmasi">
                                                        <a href="#" data-toggle="modal" data-target="#absen" class="font-23 menu-style text-white align-middle " style="color:black">
                                                            <span class="la la-calendar position-relative" style="color:black" id="today_count"></span>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item item-animated" data-toggle="tooltip" data-placement="top" title="" data-original-title="Berita">
                                                        <a href="#" onclick="openberita()" class="font-23 menu-style text-white align-middle ">
                                                        <!-- data-toggle="modal" data-target="#berita"-->
                                                            <span class="la la-comment position-relative" style="color:black" id="berita_count">
                                                            <span class="badge badge-<?= count($notifikasi_berita) ? 'danger' : 'light'; ?>"><?= count($notifikasi_berita) ?></span>
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item item-animated" data-toggle="tooltip" data-placement="top" title="" data-original-title="Berita">
                                                        <a href="#" data-toggle="modal" data-target="#ultah" class="font-23 menu-style text-white align-middle ">

                                                            <span class="fa fa-birthday-cake position-relative" style="color:black"><span class="badge badge-<?= count($ultah) ? 'light' : 'light'; ?>"><?= count($ultah) ?></span></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="user-info align-right dropdown d-inline-block header-dropdown">
                                                <a href="javascript:void(0)" data-toggle="dropdown" class=" menu-style dropdown-toggle">
                                                    <div class="user-avatar d-inline-block">
                                                        @if($user[0]->foto!=null)
                                                        <img src="{!! asset('dist/img/profile/'.$user[0]->foto) !!}" alt="User Avatar" class="img-fluid rounded-circle" width="55">
                                                        @else
                                                        <img src="{!! asset('dist/img/profile/user.png') !!}" class="img-fluid rounded-circle" width="55" alt="User Image">
                                                        @endif
                                                    </div>
                                                </a>

                                                <div class="dropdown-menu notification-dropdown-menu shadow-lg border-0 p-3 m-0 dropdown-menu-right">
                                                    <a class="dropdown-item p-2" href="{!!route('fe.edit_profile')!!}">
                                                        <span class="media align-items-center">
                                                            <span class="lnr lnr-user mr-3"></span>
                                                            <span class="media-body text-truncate">
                                                                <span class="text-truncate">Profile</span>
                                                            </span>
                                                        </span>
                                                    </a>

                                                    <a class="dropdown-item p-2" href="{!!route('logout')!!}">
                                                        <span class="media align-items-center">
                                                            <span class="lnr lnr-power-switch mr-3"></span>
                                                            <span class="media-body text-truncate">
                                                                <span class="text-truncate">Logout</span>
                                                            </span>
                                                        </span>
                                                    </a>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="d-block d-lg-none" style="margin-top: 20px;">
                                    <a href="javascript:void(0)">

                                        <span class="fa fa-list d-block display-5 text-white" id="open_navSidebar"></span>
                                    </a>

                                    <div class="offcanvas-menu" id="offcanvas_menu">
                                        <span class="lnr lnr-cross float-left display-6 position-absolute t-1 l-1 text-white" id="close_navSidebar"></span>
                                        <div class="user-info align-center bg-theme text-center">
                                            <a href="javascript:void(0)" class="d-block menu-style text-white" style="padding-bottom: 0">
                                                <div class="user-avatar d-inline-block mr-3">
                                                    @if($user[0]->foto!=null)
                                                    <img src="{!! asset('dist/img/profile/'.$user[0]->foto) !!}" alt="User Avatar" class="img-fluid rounded-circle" width="55">
                                                    @else
                                                    <img src="{!! asset('dist/img/profile/user.png') !!}" class="img-fluid rounded-circle" width="55" alt="User Image">
                                                    @endif
                                                </div>
                                            </a>
                                            <span class="text-white">{!!$user[0]->nama!!}</span>
                                        </div>
                                        <div class="user-notification-block align-center">
                                            <div class="top-nav-search item-animated">

                                            </div>
                                        </div>
                                        <hr>
                                        <div class="user-menu-items px-3 m-0">

                                            <?php
                                            $iduser = Auth::user()->id;

                                            function menuFunction($parent, $iduser)
                                            {
                                                $sqlmenu = "SELECT *,(select count(*) from m_menu b where b.parent = a.m_menu_id ) as count from m_menu a where a.parent = $parent and active=1 and type=2 and 
											(
											(select count(*) from users where role = -1 and id=$iduser)>=1
											or
											a.m_menu_id in (select users_admin.menu_id from users join users_admin on m_role_id = role and users_admin.active=1 where  id=$iduser)
											)  
											order  by urutan,nama_menu,m_menu_id";

                                                $return = '';
                                                $menuSide = DB::connection()->select($sqlmenu);
                                                if (count($menuSide)) {

                                                    $return .= '';
                                                    foreach ($menuSide as $menuSide) {
                                                        $link = $menuSide->link == '#' ? 'javascript:void(0)' : route($menuSide->link, $menuSide->link_sub);
                                                        if ($menuSide->parent == 0 and $menuSide->link == '#')
                                                            $menuSide->nama_menu = '<h3 style="font-weight:bold">' . $menuSide->nama_menu . '</h3>';
                                                        else if ($menuSide->parent == 0)
                                                            $menuSide->nama_menu = '' . $menuSide->nama_menu . '';
                                                        else
                                                            $menuSide->nama_menu = '<span style="margin-left:20px">' . $menuSide->nama_menu . '</span>';

                                                        $return .= '
											<a class="p-2" href="' . $link . '">
												<span class="media align-items-center">
													<span class="' . $menuSide->icon . ' mr-3"></span>
													<span class="media-body text-truncate text-left">
														<span class="text-truncate text-left"> ' . $menuSide->nama_menu . ' </span>
													</span>
												</span>
											</a>
										
										
											';
                                                        if ($menuSide->count)
                                                            $return .= menuFunction($menuSide->m_menu_id, $iduser);
                                                    }
                                                    $return .= '';
                                                }
                                                return $return;
                                            };
                                            echo menuFunction(0, $iduser);
                                            ?>


                                            <a class="p-2" href="{!!route('logout')!!}">
                                                <span class="media align-items-center">
                                                    <span class="lnr lnr-power-switch mr-3"></span>
                                                    <span class="media-body text-truncate text-left">
                                                        <span class="text-truncate text-left">Logout</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="header-wrapper d-none d-sm-none d-md-none d-lg-block">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="header-menu-list d-flex bg-white rt_nav_header horizontal-layout nav-bottom" id="myHeader">
                                    <div class="append mr-auto my-0 my-md-0 mr-auto">
                                        <ul class="list-group list-group-horizontal-md mr-auto">
                                            <?php

                                            function menuFunction2($parent, $iduser)
                                            {
                                                $sqlmenu = "SELECT *,(select count(*) from m_menu b where b.parent = a.m_menu_id ) as count from m_menu a where a.parent = $parent and active=1 and type=2 and 
											(
											(select count(*) from users where role = -1 and id=$iduser)>=1
											or
											a.m_menu_id in (select users_admin.menu_id from users join users_admin on m_role_id = role and users_admin.active=1 where  id=$iduser)
											)  
											order  by urutan,m_menu_id";
                                                $return = '';
                                                $menuSide = DB::connection()->select($sqlmenu);
                                                if (count($menuSide) and $parent != 0) {

                                                    $return .= ' <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                                    foreach ($menuSide as $menuSide) {
                                                        $link = $menuSide->link == '#' ? 'javascript:void(0)' : route($menuSide->link, $menuSide->link_sub);

                                                        $return .= '
												<a class="dropdown-item" href="' . $link . '" btn-ctm-space text-dark" href="manage.html">' . $menuSide->nama_menu . '</a>
												';
                                                    }
                                                    $return .= ' </div>';
                                                } else if (count($menuSide) and $parent == 0) {
                                                    $return .= ' ';
                                                    foreach ($menuSide as $menuSide) {
                                                        $link = $menuSide->link == '#' ? 'javascript:void(0)' : route($menuSide->link, $menuSide->link_sub);
                                                        //<a  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn-ctm-space text-dark" href="manage.html"><span class="lnr lnr-sync pr-0 pr-lg-2"></span><span class="d-none d-lg-inline">Informasi
                                                        ///<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        ///  <a class="dropdown-item" href="#">Pengumuman kepegawaian</a>
                                                        //  <a class="dropdown-item" href="#">File referensi</a>
                                                        //</div>
                                                        //</span></a></li>
                                                        if (empty(menuFunction2($menuSide->m_menu_id, $iduser))) {

                                                            $return .= '
											<li class="mr-1 "><a href="' . $link . '"  class="btn-ctm-space text-dark">
													<span class="' . $menuSide->icon . ' pr-0 pr-lg-2" ></span><span class="d-none d-lg-inline">' . $menuSide->nama_menu . '</span></a></li>
											';
                                                        } else {
                                                            $return .= '
											<li class="mr-1 "><a href="' . $link . '" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn-ctm-space text-dark">
													<span class="' . $menuSide->icon . ' pr-0 pr-lg-2" ></span><span class="d-none d-lg-inline">' . $menuSide->nama_menu . menuFunction2($menuSide->m_menu_id, $iduser) . '</span></a></li>
											';
                                                        }
                                                    }
                                                    $return .= ' ';
                                                }
                                                return $return;
                                            };
                                            echo menuFunction2(0, $iduser);

                                            ?>



                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </header>
            <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
        </div>
        </div>
        </div>

        </div>

        <div class="sidebar-overlay" id="sidebar_overlay"></div>



        <div class="modal fade" id="ultah" tabindex="-1" role="dialog" aria-labelledby="ultahTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ulang Tahun</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if(!empty($ultah))
                        <hr>
                        <?php foreach ($ultah as $ultah) { ?>
                            <a href="javascript:void(0)" class="dash-card text-dark">
                                <div class="dash-card-container">
                                    <div class="dash-card-icon text-danger">
                                        <i class="fa fa-birthday-cake" aria-hidden="true"></i>

                                    </div>
                                    <div class="dash-card-content">
                                        <h6 class="mb-0">
                                            <?= $ultah->nama_lengkap ?>
                                            <p>
                                                <?= date('d', strtotime($ultah->tgl_lahir)) . ' ' . $help->bulan(date('m', strtotime($ultah->tgl_lahir))) ?></b>
                                        </h6>
                                        </p>
                                        </h6>
                                    </div>
                                </div>
                                <hr>
                            </a>

                        <?php } ?>

                        @else
                        Tidak ada Yang Ulang Tahun Bulan ini
                        @endif

                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="berita" tabindex="-1" role="dialog" aria-labelledby="beritaTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Berita</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="content_berita" style="overflow-y: scroll;max-height:450px;">
                        
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="absen" tabindex="-1" role="dialog" aria-labelledby="absenTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Menunggu Konfirmasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="content_today">
                       
                    </div>

                </div>
            </div>
        </div>
        <style>
        	b{
        		font-weight:700;
        	}
        </style>
        <div class="modal fade" id="loker" tabindex="-1" role="dialog" aria-labelledby="lokerTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Lowongan Kerja</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if(!empty($loker))
                        @foreach($loker as $lok)
                        <a href="javascript:void(0)" class="dash-card text-dark" style="font-size:13px">
                            
                            <img src="{{url('dist/img/file/'.$lok->file)}}"/>
                            <p><b>Posisi</b> : {!! $lok->nmjabatan !!}</p>
                            <p><b>PT</b>: {!! $lok->nmlokasi !!} </p>
                            <p><b>Status Kerja</b>:{!! $lok->nmstatus !!}</p>
                            <p><b>Deskripsi</b> : {!! $lok->keterangan_indonesia !!}</p>
                            <p><b>Persyaratan</b> :{!! $lok->deskripsi_indonesia !!}</p>
                        </a>

                        <hr>
                        @endforeach

                        @else
                        Tidak Ada Loker
                        @endif
                    </div>

                </div>
            </div>
        </div>
        <?php

        if (isset($nojs)) {
        } else {
            echo      '<script src="' . url('plugins/dleohr/assets/js/jquery-3.2.1.min.js') . '"></script> ';
        } ?>


        <script src="<?= url('plugins/dleohr/assets/js/popper.min.js') ?>"></script>
        <script src="<?= url('plugins/dleohr/assets/js/bootstrap.min.js') ?>"></script>

        <script src="<?= url('plugins/dleohr/assets/plugins/theia-sticky-sidebar/ResizeSensor.js') ?>"></script>
        <script src="<?= url('plugins/dleohr/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') ?>"></script>


        <script src="<?= url('plugins/dleohr/assets/plugins/select2/moment.min.js"') ?>"></script>
        <script src="<?= url('plugins/dleohr/assets/js/bootstrap-datetimepicker.min.js"') ?>"></script>
        <script src="<?= url('plugins/dleohr/assets/js/Chart.min.js') ?>"></script>

        <link rel="stylesheet" href="{!! asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') !!}">

        <link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">
        <link rel="stylesheet" href="{!! asset('plugins/purple/assets/plugins/summernote/dist/summernote-bs4.css')!!}">
        <script src="{!! asset('plugins/purple/assets/plugins/summernote/dist/summernote-bs4.min.js')!!}"></script>

        <script src="{!! asset('plugins/datatables/jquery.dataTables.min.js') !!}"></script>
        <script src="{!! asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') !!}"></script>
        <script src="{!! asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') !!}"></script>
        <script src="{!! asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') !!}"></script>
        <script src="{!! asset('plugins/select2/js/select2.full.min.js') !!}"></script>
        <script src="<?= url('plugins/dleohr/assets/js/script2.js') ?>"></script>
        <link rel="stylesheet" href="{!! asset('plugins/purple/assets/plugins/summernote/dist/summernote-bs4.css')!!}">
        <script src="{!! asset('plugins/purple/assets/plugins/summernote/dist/summernote-bs4.min.js')!!}"> </script>

        <script src="{!! asset('plugins/summernote/summernote-bs4.min.js') !!}"> </script>
        <script>
            $('.summernote').summernote();
        </script>
</body>

<!-- Mirrored from dleohr.dreamguystech.com/template-1/dleohr-horizontal/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Jul 2022 01:58:12 GMT -->
<script>
    historis();
    optimasi_today_info()
    function optimasi_today_info(){
            $.ajax({
				type: 'get',
			
				url: '<?=route('optimasi_today_info');?>',
				dataType: 'json',
				success: function(data){
					$('#content_today').html(data.content);
					$('#today_count').html(data.today_count);
					
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
        }
    //optimasi_berita()
    function openberita(){
            $.ajax({
				type: 'get',
			
				url: '<?=route('optimasi_berita');?>',
				dataType: 'json',
				success: function(data){
					$('#content_berita').html(data.content);
					$('#berita').modal('show');	
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
        }
    function historis() {

        $.ajax({
            type: 'get',
            data: {
                "historis_page": '<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>'
            },
            url: '<?= route('historis'); ?>',
            dataType: 'json',
            success: function(data) {},
            error: function(error) {
                console.log('error; ' + eval(error));
                //alert(2);
            }
        });

    }
    if ($('.datetimepicker-input2').length > 0) {
        $('.datetimepicker-input2').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: 0,
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            },
            onSelect: function(dateText, inst) {

                var today = new Date();
                today = Date.parse(today.getMonth() + 1 + '/' + today.getDate() + '/' + today.getFullYear());

                var selDate = Date.parse(dateText);

                if (selDate < today) {

                    $('.datetimepicker-input2').val('');
                    $(inst).datepicker('show');
                }
            }
        });
    }

    if ($('.datetimepicker-input').length > 0) {
        $('.datetimepicker-input').datetimepicker({
            format: 'DD/MM/YYYY',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
        });
    }


    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $("#exam").DataTable({
            fixedColumns: {
                left: 2
            },
            "scrollY" : 500,
            "scrollX": true,
            "ordering": true,
            "lengthChange": true,
            "paging": false,
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
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })
</script>
<script>
    window.onscroll = function() {
        myFunction()
    };

    var header = document.getElementById("myHeader");
    var sticky = header.offsetTop;

    function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("stickyheader");
        } else {
            header.classList.remove("stickyheader");
        }
    }
</script>

</html>