@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Karyawan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                        <li class="breadcrumb-item active">Karyawan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <?php

    $view_array = array(
        "kontrak_kerja" => "Kontrak Kerja",
        "riwayat_pekerjaan" => "Riwayat Pekerjaan",
        "pendidikan" => "Pendidikan",
        "kursus" => "Kursus dan Pelatihan",
        "keluarga" => "Keluarga",
        "pakaian" => "Pakaian",
        "award" => "Award ",
        "sanksi" => "Sanksi ",
    );
    $view_panel = function ($type, $id) {
        $where = 'and p_karyawan.p_karyawan_id = ' . $id;
        $join = ' ';


        if ($type == 'riwayat_pekerjaan') {
            $sql = "SELECT * FROM p_karyawan_riwayat_pekerjaan
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_riwayat_pekerjaan.p_karyawan_id
			$join
			WHERE p_karyawan_riwayat_pekerjaan.active=1 $where ORDER BY p_karyawan_riwayat_pekerjaan.p_karyawan_id,awal_periode ASC ";
            $data = DB::connection()->select($sql);
            $array = array(
                "NIK" => "nik",
                "Nama Karyawan" => "nama",
                "Nama Perusahaan" => "nama_perusahaan",
                "Awal Periode" => "awal_periode",
                "Akhir Periode" => "akhir_periode",
                "Posisi Kerja" => "posisi_kerja",
                "Deskripsi Kerja" => "deskripsi_kerja",
            );
        } else if ($type == 'kontrak_kerja') {
            $sql = "SELECT * FROM p_karyawan_kontrak 
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_kontrak.p_karyawan_id
			$join
			WHERE 1=1  $where   ";

            $data = DB::connection()->select($sql);

            $array = array(
                "Tanggal Input" => "create_date",
                "Tanggal Awal" => "tgl_awal",
                "Tanggal Akhir" => "tgl_akhir",
                "File" => "file_kontrak_kerja",
            );
        } else if ($type == 'pendidikan') {
            $sql = "SELECT *,p_karyawan_pendidikan.nama_sekolah as nmsekolah,p_karyawan_pendidikan.jurusan as nmjurusan FROM p_karyawan_pendidikan 
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_pendidikan.p_karyawan_id
			$join
			WHERE p_karyawan_pendidikan.active=1  $where  ORDER BY p_karyawan_pendidikan.p_karyawan_id,tahun_lulus,p_karyawan_pendidikan.nama_sekolah ASC ";

            $data = DB::connection()->select($sql);

            $array = array(
                "NIK" => "nik",
                "Nama Karyawan" => "nama",
                "Jenjang" => "jenjang",
                "Nama Sekolah" => "nmsekolah",
                "Jurusan" => "nmjurusan",
                "Tahun Lulus" => "tahun_lulus",
                "Alamat Sekolah" => "alamat_sekolah",
                "Kota Sekolah" => "kota_sekolah",
            );
        } else if ($type == 'kursus') {
            $sql = "SELECT *,case sertifikat when 1 then 'Ya' else 'Tidak' end as sertifikat FROM p_karyawan_kursus
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_kursus.p_karyawan_id
			$join
			WHERE p_karyawan_kursus.active=1  $where  ORDER BY p_karyawan_kursus.p_karyawan_id,p_karyawan_kursus.nama_kursus ASC ";

            $data = DB::connection()->select($sql);

            $array = array(
                "NIK" => "nik",
                "Nama Karyawan" => "nama",

                "Nama Kursus" => "nama_kursus",
                "Penyelenggara" => "penyelenggara",
                "Tanggal Awal Pelatihan" => "tanggal_awal_pelatihan",
                "Tanggal Akhir Pelatihan" => "tanggal_akhir_pelatihan",
                "Sertifikat" => "sertifikat",
            );
        } else if ($type == 'keluarga') {
            $sql = "SELECT *, p_karyawan.nama as nama_lengkap, p_karyawan_keluarga.nama as nama_keluarga FROM p_karyawan_keluarga
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_keluarga.p_karyawan_id
			$join
			WHERE p_karyawan_keluarga.active=1  $where  ORDER BY p_karyawan_keluarga.p_karyawan_id,p_karyawan_keluarga.tgl_lahir ASC ";

            $data = DB::connection()->select($sql);

            $array = array(
                "NIK" => "nik",
                "Nama Karyawan" => "nama_lengkap",

                "Hubungan" => "hubungan",
                "Nama" => "nama_keluarga",
                "No Hp" => "no_hp",
                "Tanggal Lahir" => "tgl_lahir",
           
            );
            /*
            Hubungan
            Nama
            No Hp
            Tanggal Lahir
            */
        } else if ($type == 'pakaian') {
            $sql = "SELECT *
	    , p_karyawan.nama as nama_lengkap, c.nama as nama_keluarga,CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN p_karyawan.nama
	   				ELSE c.nama END as nama
	   				,
	   				CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN 'Diri Sendiri'
	   				ELSE c.hubungan END as hubungan
	   				
	     FROM p_karyawan_pakaian 
	     	left join p_karyawan  on p_karyawan_pakaian.p_karyawan_id = p_karyawan.p_karyawan_id 
	     	left join p_karyawan_keluarga c on p_karyawan_pakaian.p_karyawan_keluarga_id = c.p_karyawan_keluarga_id 
	     	$join
	     WHERE  tipe=2 and p_karyawan_pakaian.active=1
	      $where 
	      ORDER BY p_karyawan_pakaian.p_karyawan_id ASC ";

            $data = DB::connection()->select($sql);

            $array = array(
                "NIK" => "nik",
                "Nama Karyawan" => "nama_lengkap",

                "Nama Hubungan" => "nama_keluarga",
                "Hubungan" => "hubungan",
                "Gamis" => "gamis",
                "Kemeja" => "kemeja",
                "Kaos" => "kaos",
                "Jaket" => "jaket",
                "Celana" => "celana",
                "Sepatu" => "sepatu",
            );
             /*
            Nama Hubungan
            hubungan
            gamis
            kameja
            kaos
            jaket
            celana
            sepatu
            
            */
        } else if ($type == 'award') {
            $sql = "SELECT * FROM p_karyawan_award 
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_award.p_karyawan_id
			left join m_jenis_reward on m_jenis_reward.m_jenis_reward_id = p_karyawan_award.m_jenis_reward_id
			$join
	     WHERE   p_karyawan_award.active=1
	      $where 
	      ORDER BY p_karyawan_award.p_karyawan_id,tgl_award ASC ";

            $data = DB::connection()->select($sql);

            $array = array(
                "NIK" => "nik",
                "Nama Karyawan" => "nama",

                "Nama Jenis Reward" => "nama_jenis_reward",
                "Hadiah" => "hadiah",
                "Tangal Award" => "tgl_award",
            );
             /*
            Nama Jenis Reward
            Hadiah
            Tanggal Reward
            */
        } else if ($type == 'sanksi') {
            $sql = "SELECT * FROM p_karyawan_sanksi 
			left join m_jenis_sanksi on p_karyawan_sanksi.m_jenis_sanksi_id = m_jenis_sanksi.m_jenis_sanksi_id 
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_sanksi.p_karyawan_id
			$join 
			WHERE p_karyawan_sanksi.active=1  $where 
			ORDER BY tgl_awal_sanksi ASC ";


            $data = DB::connection()->select($sql);

            $array = array(
                "NIK" => "nik",
                "Nama Karyawan" => "nama",

                "Nama Sanksi" => "nama_sanksi",
                "Tanggal Awal Sanksi" => "tgl_awal_sanksi",
                "Tanggal Akhir Sanksi" => "tgl_akhir_sanksi",
                "Alasan Sanksi" => "alasan_sanksi",
            );
            /*
        	Nama Sanksi
        	Tanggal Awal Sanksi
        	Tanggal Akhir Sanksi
        	Alasan Sanksi
        	*/
        }
        $return['data'] = $data;
        $return['array'] = $array;
        return $return;
    };


    ?>
    <!-- Main content -->
    <div class="card">
        <div class="card-header">
            <!--<h3 class="card-title">DataTable with default features</h3>-->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="{!! route('be.update_karyawan',$karyawan[0]->p_karyawan_id) !!}" enctype="multipart/form-data">
                {{ csrf_field() }}
                
                <!-- ./row -->
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-profil-tab" data-toggle="pill" href="#custom-tabs-one-profil" role="tab" aria-controls="custom-tabs-one-profil" aria-selected="true">Profil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-pekerjaan-tab" data-toggle="pill" href="#custom-tabs-one-pekerjaan" role="tab" aria-controls="custom-tabs-one-pekerjaan" aria-selected="false">Pekerjaan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-kartuidentitas-tab" data-toggle="pill" href="#custom-tabs-one-kartuidentitas" role="tab" aria-controls="custom-tabs-one-kartuidentitas" aria-selected="false">Kartu Identitas</a>
                                    </li>
                                    <?php foreach ($view_array as $key => $value) { ?>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-<?= $key ?>-tab" data-toggle="pill" href="#custom-tabs-one-<?= $key ?>" role="tab" aria-controls="custom-tabs-one-<?= $key ?>" aria-selected="false">Data <?= $value ?></a>
                                        </li>
                                    <?php } ?>

                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-profil" role="tabpanel" aria-labelledby="custom-tabs-one-profil-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <style>
                                                    .avatar-upload {
                                                        position: relative;
                                                        max-width: 205px;
                                                        margin: 50px auto;
                                                    }

                                                    .avatar-upload .avatar-edit {
                                                        position: absolute;
                                                        right: 12px;
                                                        z-index: 1;
                                                        top: 10px;
                                                    }

                                                    .avatar-upload .avatar-edit input {
                                                        display: none;
                                                    }

                                                    .avatar-upload .avatar-edit input+label {
                                                        display: inline-block;
                                                        width: 34px;
                                                        height: 34px;
                                                        margin-bottom: 0;
                                                        border-radius: 100%;
                                                        background: #FFFFFF;
                                                        cursor: pointer;
                                                        font-weight: normal;
                                                        transition: all 0.2s ease-in-out;
                                                        align-items: center;
                                                        text-align: center;
                                                        justify-items: center;
                                                        display: grid;
                                                        align-content: center;
                                                        align-self: center;
                                                        background: #6236ff;
                                                        color: white;
                                                        outline: none;
                                                    }

                                                    .avatar-upload .avatar-edit input+label:hover {

                                                        border-color: #d6d6d6;
                                                    }

                                                    .avatar-upload .avatar-preview {
                                                        width: 192px;
                                                        height: 192px;
                                                        position: relative;
                                                        border-radius: 100%;
                                                        border: 6px solid #F8F8F8;
                                                        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
                                                    }

                                                    .avatar-upload .avatar-preview>div {
                                                        width: 100%;
                                                        height: 100%;
                                                        border-radius: 100%;
                                                        background-size: cover;
                                                        background-repeat: no-repeat;
                                                        background-position: center;
                                                    }
                                                </style>
                                                <div class="section mt-3 text-center">
                                                    <div class="avatar-upload">
                                                        <div class="avatar-edit">
                                                            <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                                            <label for="imageUpload">
                                                                <ion-icon name="camera"></ion-icon>
                                                            </label>
                                                        </div>
                                                        <div class="avatar-preview">
                                                             @if($karyawan[0]->foto!=null)
                                                                
                                                            <div id="imagePreview" style="background-image: url('{!! asset('dist/img/profile/'.$karyawan[0]->foto) !!}');"></div>
                                                              
                                                            @else
                                                                <div id="imagePreview" style="background-image: url('{!! asset('dist/img/profile/user.png') !!}');"></div>
                                                              
                                                            @endif
                                                        </div>
                                                        <div id="button-pic" style="display: none">
                                                          
                                                        </div>
                                                    </div>

                                                </div>
                                                </div>
                                                 <div class="col-sm-6"><div class="form-group">
                                                    <label>NIK</label>
                                                    <input type="text" class="form-control" placeholder="NIK..." id="nik" name="nik" value="{!! $karyawan[0]->nik !!}" disabled="disabled">
                                                </div>
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" class="form-control" placeholder="Nama Lengkap..." id="nama_lengkap" name="nama_lengkap" value="{!! $karyawan[0]->nama_lengkap !!}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nama Panggilan</label>
                                                    <input type="text" class="form-control" placeholder="Nama Panggilan..." id="nama_panggilan" name="nama_panggilan" required value="{!! $karyawan[0]->nama_panggilan !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tempat Lahir</label>
                                                    <input type="text" class="form-control" placeholder="Tempat Lahir..." id="tempat_lahir" name="tempat_lahir" value="{!! $karyawan[0]->tempat_lahir !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Lahir</label>
                                                    <div class="input-group date" id="tgl_lahir" data-target-input="nearest">
                                                        <input type="text" class="form-control " id="tgl_lahir" name="tgl_lahir" data-target="#tgl_lahir" value="{!! $karyawan[0]->tgl_lahir !!}">
                                                        <div class="input-group-append" data-target="#tgl_lahir" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <select class="form-control select2" name="jk" style="width: 100%;" required>
                                                        <?php
                                                        foreach ($jk as $jk) {
                                                            if ($jk->m_jenis_kelamin_id == $karyawan[0]->m_jenis_kelamin_id) {
                                                                echo '<option selected="selected" value="' . $jk->m_jenis_kelamin_id . '">' . $jk->nama . '</option>';
                                                            } else {
                                                                echo '<option value="' . $jk->m_jenis_kelamin_id . '">' . $jk->nama . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alamat Sesuai KTP</label>
                                                    <textarea class="form-control" placeholder="Alamat Sesuai KTP..." id="alamat_ktp" name="alamat_ktp">{!! $karyawan[0]->alamat_ktp !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Domisili</label>
                                                    <input type="text" class="form-control" placeholder="Domisili..." id="domisili" name="domisili" value="{!! $karyawan[0]->domisili !!}">
                                                </div>
                                                
                                                <!--<div class="form-group">-->
                                                <!--    <label>Pendidikan</label>-->
                                                <!--    <select class="form-control select2" name="pendidikan" style="width: 100%;">-->
                                                <!--        <option value="">Pilih Pendidikan</option>-->
                                                <!--        <option value="SD" <?php if ($karyawan[0]->pendidikan == 'SD') {echo 'selected="selected" ';} ?>>SD</option>-->
                                                <!--        <option value="SMP" <?php if ($karyawan[0]->pendidikan == 'SMP') {  echo 'selected="selected" ';} ?>>SMP</option>-->
                                                <!--        <option value="SMA" <?php if ($karyawan[0]->pendidikan == 'SMA') { echo 'selected="selected" ';} ?>>SMA</option>-->
                                                <!--        <option value="D1" <?php if ($karyawan[0]->pendidikan == 'D1') {   echo 'selected="selected" ';    } ?>>D1</option>-->
                                                <!--        <option value="D2" <?php if ($karyawan[0]->pendidikan == 'D2') {   echo 'selected="selected" '; } ?>>D2</option>-->
                                                <!--        <option value="D3" <?php if ($karyawan[0]->pendidikan == 'D3') {    echo 'selected="selected" ';  } ?>>D3</option>-->
                                                <!--        <option value="S1" <?php if ($karyawan[0]->pendidikan == 'S1') { echo 'selected="selected" '; } ?>>S1</option>-->
                                                <!--        <option value="S2" <?php if ($karyawan[0]->pendidikan == 'S2') {  echo 'selected="selected" '; } ?>>S2</option>-->
                                                <!--        <option value="S3" <?php if ($karyawan[0]->pendidikan == 'S3') { echo 'selected="selected" ';    } ?>>S3</option>-->
                                                <!--    </select>-->
                                                <!--</div>-->
                                                <!--<div class="form-group">-->
                                                <!--    <label>Jurusan</label>-->
                                                <!--    <input type="text" class="form-control" placeholder="Jurusan..." id="jurusan" name="jurusan" value="{!! $karyawan[0]->jurusan !!}">-->
                                                <!--</div>-->
                                                <!--<div class="form-group">-->
                                                <!--    <label>Nama Sekolah/PT</label>-->
                                                <!--    <input type="text" class="form-control" placeholder="Nama Sekolah/PT..." id="nama_sekolah" name="nama_sekolah" value="{!! $karyawan[0]->nama_sekolah !!}">-->
                                                <!--</div>-->
                                                <div class="form-group">
                                                    <label>Status Pernikahan</label>
                                                    <select class="form-control select2" name="status_pernikahan" style="width: 100%;">
                                                        <option value="0" <?php if ($karyawan[0]->m_status_id == 0) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Belum Menikah</option>
                                                        <option value="1" <?php if ($karyawan[0]->m_status_id == 1) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Menikah</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jumlah Anak</label>
                                                    <input type="text" class="form-control" placeholder="Jumlah Anak..." id="jumlah_anak" name="jumlah_anak" value="{!! $karyawan[0]->jumlah_anak !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" placeholder="Email..." id="email" name="email" value="{!! $karyawan[0]->email !!}">
                                                </div>

                                                <div class="form-group">
                                                    <label>No. HP</label>
                                                    <input type="text" class="form-control" placeholder="No. HP..." id="no_hp" name="no_hp" value="{!! $karyawan[0]->no_hp !!}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-pekerjaan" role="tabpanel" aria-labelledby="custom-tabs-one-pekerjaan-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Entitas</label>
                                                    <select class="form-control select2" name="lokasi" style="width: 100%;" required onchange="finddirectorat(this)">
                                                        <option value="">- Entitas -</option>
                                                        <?php
                                                        foreach ($lokasi as $lokasi) {
                                                            if ($lokasi->m_lokasi_id == $karyawan[0]->m_lokasi_id) {
                                                                echo '<option selected="selected" value="' . $lokasi->m_lokasi_id . '">' . $lokasi->nama . '</option>';
                                                            } else {
                                                                echo '<option value="' . $lokasi->m_lokasi_id . '">' . $lokasi->nama . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Directorat</label>
                                                    <select class="form-control select2" name="directorat" id="directorat" style="width: 100%;" onchange="finddivisi(this.value)">
                                                        <option value="">- Directorat -</option>
                                                        <?php
                                                        foreach ($directorat as $directorat) {
                                                            if ($directorat->m_directorat_id == $karyawan[0]->m_directorat_id) {
                                                                echo '
															<option selected="selected" value="' . $directorat->m_directorat_id . '">' . $directorat->nama_directorat . '</option>';
                                                            } 
                                                           else {
                                                            echo '
                                                            <option value="' . $directorat->m_directorat_id . '">' . $directorat->nama_directorat . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Divisi</label>
                                                    <select class="form-control select2" name="divisi_new" id="divisi_new" style="width: 100%;"  onchange="finddepartement(this.value)" >
                                                        <option value="">- Divisi -</option>
                                                        <?php
                                                        foreach ($divisi_new as $divisi_new) {
                                                            if ($divisi_new->m_divisi_new_id == $karyawan[0]->m_divisi_new_id) {
                                                                echo '
															<option selected="selected" value="' . $divisi_new->m_divisi_new_id . '">' . $divisi_new->nama_divisi . '</option>';
                                                            } 
                                                            else {
                                                                echo '
															<option value="' . $divisi_new->m_divisi_new_id . '">' . $divisi_new->nama_divisi . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Departemen</label>
                                                    <select class="form-control select2" name="divisi" id="departemen" style="width: 100%;" onchange="findseksi(this.value)" >
                                                        <option value="">- Departement -</option>
                                                        <?php
                                                        foreach ($divisi as $divisi) {
                                                            if ($divisi->m_divisi_id == $karyawan[0]->m_divisi_id) {
                                                                echo '
															<option selected="selected" value="' . $divisi->m_divisi_id . '">' . $divisi->nama . '</option>';
                                                            }
                                                            else {
                                                                echo '
															<option value="' . $divisi->m_divisi_id . '">' . $divisi->nama . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Seksi</label>
                                                    <select class="form-control select2" name="departemen" id="seksi" style="width: 100%;"  onchange="findjabatan(this.value)">
                                                        <option value="">- Seksi -</option>
                                                        
                                                        
                                                        <?php
                                                        foreach ($departemen as $departemen) {
                                                            if ($departemen->m_departemen_id == $karyawan[0]->m_departemen_id) {
                                                                echo '
															<option selected="selected" value="' . $departemen->m_departemen_id . '">' . $departemen->nama . '</option>';
                                                            }
                                                            else {
                                                                echo '
															<option value="' . $departemen->m_departemen_id . '">' . $departemen->nama . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jabatan/Pangkat</label>
                                                    <select class="form-control select2" name="jabatan" id="jabatan" style="width: 100%;" required>
                                                        <option value="">- Jabatan -</option>
                                                        <?php
                                                        foreach ($jabatan as $jabatan) {
                                                            if ($jabatan->m_jabatan_id == $karyawan[0]->m_jabatan_id) {
                                                                echo '<option selected="selected" value="' . $jabatan->m_jabatan_id . '">' . $jabatan->nama . ' - ' . $jabatan->nmpangkat . ' - ' . $jabatan->nmlokasi . '</option>';
                                                            } 
                                                            else {
                                                                echo '<option value="' . $jabatan->m_jabatan_id . '">' . $jabatan->nama . ' - ' . $jabatan->nmpangkat . ' - ' . $jabatan->nmlokasi . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Awal Kontrak</label>
                                                    <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                                        <input type="date" class="form-control " id="tgl_awal" name="" data-target="#tgl_awal" required value="{!! $karyawan[0]->tgl_awal !!}" readonly />

                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Akhir Kontrak</label>
                                                    <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                                        <input type="date" class="form-control " id="tgl_akhir" name="" data-target="#tgl_akhir" value="{!! $karyawan[0]->tgl_akhir !!}" readonly/>

                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                        <label>Tanggal Bergabung</label>
                                                        <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                                            <input type="date" class="form-control -input" id="tgl_awal" name="tgl_bergabung"  value="{!! $karyawan[0]->tgl_bergabung !!}"required/>
                                                            
                                                        </div>
                                                    </div>
                                               
                                            </div>
                                             <script>
                                                        function finddirectorat(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.finddirectorat') }}",
                                                                data : {
                                                                    id:$(e).val(),
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#directorat').html(msg);
                                                                    
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                        function finddivisi(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.finddivisi') }}",
                                                                data : {
                                                                    id:e,
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#divisi_new').html(msg);
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                        function finddepartement(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.finddepartement') }}",
                                                                data : {
                                                                    id:e,
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#departemen').html(msg);
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                        function findseksi(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.findseksi') }}",
                                                                data : {
                                                                    id:e,
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#seksi').html(msg);
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                        function findjabatan(e){
                                                            $.ajax({
                                                                type : 'get',
                                                                url : "{{ route('be.findjabatan') }}",
                                                                data : {
                                                                    id:e,
                                                                   
                                                                    
                                                                },
                                                                cache : false,
                                                                headers: {
                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function(msg){
                                                                    //console.log(msg);
                                                                    $('#jabatan').html(msg);
                                                                },
                                                                error: function(data){
                                                                    console.log('error:', data)
                                                                },
                                                            })
                                                        }
                                                    </script>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                                    <label>Kantor</label>
                                                    <select class="form-control select2" name="kantor" style="width: 100%;" required>
                                                        <option value="1">- Pilih Kantor</option>
                                                        <?php
                                                        foreach ($kantor as $kantor) {
                                                            $selected = '';
                                                            if ($karyawan[0]->m_kantor_id == $kantor->m_office_id)
                                                                $selected = 'selected';
                                                            echo '<option value="' . $kantor->m_office_id . '" ' . $selected . '>' . $kantor->nama . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <!-- <input type="text" class="form-control" placeholder="Kantor..." id="kantor" name="kantor" required>-->
                                                </div>
                                                <div class="form-group">
                                                    <label>No. Absen</label>
                                                    <input type="text" class="form-control" placeholder="No. Absen..." id="no_absen" name="no_absen" required value="{!! $karyawan[0]->no_absen !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Lokasi Absen </label>
                                                    <select class="form-control  " readonly placeholder="No. Absen..." id="no_absen" name="lokasi_absen" style="width:100%" >
                                                        <option value="">Pilih</option>
                                                        <?php foreach($absen as $absen){?>
                                                        <option value="<?=$absen->mesin_id;?>" <?=$absen->mesin_id==$karyawan[0]->m_mesin_absen_id?>><?=$absen->nama;?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Status Pekerjaan</label>
                                                    <select class="form-control select2" name="status_pekerjaan" style="width: 100%;" required>
                                                        <?php
                                                        foreach ($stspekerjaan as $stspekerjaan) {
                                                            if ($stspekerjaan->m_status_pekerjaan_id == $karyawan[0]->m_status_pekerjaan_id) {
                                                                echo '<option selected="selected" value="' . $stspekerjaan->m_status_pekerjaan_id . '">' . $stspekerjaan->nama . '</option>';
                                                            } else {
                                                                echo '<option value="' . $stspekerjaan->m_status_pekerjaan_id . '">' . $stspekerjaan->nama . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Kota</label>
                                                    <input type="text" class="form-control" placeholder="Kota..." id="kotakerja" name="kotakerja" value="{!! $karyawan[0]->kota !!}">
                                                </div>
                                                <!--<div class="form-group">
                                                        <label>Kantor</label>
                                                        <input type="text" class="form-control" placeholder="Kantor..." id="kantor" name="kantor" required value="{!! $karyawan[0]->kantor !!}">
                                                    </div>-->
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control select2" name="status" style="width: 100%;" required>
                                                        <option value="1" <?php if ($karyawan[0]->active == 1) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Active</option>
                                                        <option value="0" <?php if ($karyawan[0]->active == 0) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Non Active</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Karyawan Shift</label>
                                                    <select class="form-control select2" name="is_shift" style="width: 100%;" required>
                                                        <option value="1" <?php if ($karyawan[0]->is_shift == 1) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Shift</option>
                                                        <option value="0" <?php if ($karyawan[0]->is_shift == 0) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Non Shift</option>
                                                    </select>
                                                </div>
                                                
                                                <?php 
                                                $editing_3 =(in_array(date('d'),array(10,11,12,13,25,26,27,28)))?true:false;
                                                ?>
                                                <div class="form-group">
                                                    <label>No Rekening</label>
                                                    <input type="text" class="form-control" placeholder="No Rekening..." id="norek" name="norek" value="{!! $karyawan[0]->norek !!}" 
                                                <?php if($editing_3) echo 'disabled';?>>
                                                </div>
                                                <div class="form-group">
                                                    <label>Bank</label>
                                                    <select type="text" class="form-control <?php if(!$editing_3) echo 'select2';?>" id="bank" style="width: 100%;" name="bank" placeholder="Nama bank" <?php if($editing_3) echo 'disabled';?>>
                                                        <option value="">- Pilih Bank - </option>
                                                        <?php

                                                        foreach ($bank as $bank) {
                                                            $selected = '';
                                                            if ($karyawan[0]->m_bank_id == $bank->m_bank_id)
                                                                $selected = 'selected';

                                                            echo "
										<option value='" . $bank->m_bank_id . "' $selected>" . $bank->nama_bank . "</option>";
                                                        }
                                                        ?>

                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Pajak</label>
                                                    <select class="form-control <?php if(!$editing_3) echo 'select2';?>" name="pajakonoff" style="width: 100%;"  <?php if($editing_3) echo 'disabled';?>>
                                                        <option value="">Pilih Pajak</option>
                                                        <option value="ON" <?= $karyawan[0]->pajak_onoff == 'ON' ? 'selected' : ''; ?>>ON</option>
                                                        <option value="OFF" <?= $karyawan[0]->pajak_onoff == 'OFF' ? 'selected' : ''; ?>>OFF</option>

                                                    </select>
                                                </div>
                                                <?php if($editing_3){?>
                                                	
                                                	<input  type="hidden" name="pajakonoff" value="<?= $karyawan[0]->pajak_onoff;?>"/>
                                                	<input  type="hidden" name="bank" value="<?= $karyawan[0]->m_bank_id;?>"/>
                                                	<input  type="hidden" name="norek" value="<?= $karyawan[0]->norek;?>"/>
												<?php	};?>
                                                <div class="form-group">
                                                    <label>Periode Gajian</label>
                                                    <select class="form-control select2" name="periode_gajian" style="width: 100%;" required>
                                                        <option value="1" <?php if ($karyawan[0]->periode_gajian == 1) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Bulanan</option>
                                                        <option value="0" <?php if ($karyawan[0]->periode_gajian == 0) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Pekanan</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>BPJS KANTOR</label>
                                                    <select class="form-control select2" name="bpjs_kantor" style="width: 100%;" required>
                                                        <option value="0" <?php if ($karyawan[0]->bpjs_kantor == 0) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Belum</option>
                                                        <option value="1" <?php if ($karyawan[0]->bpjs_kantor == 1) {
                                                                                echo 'selected="selected" ';
                                                                            } ?>>Sudah</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal BPJS Kantor</label>
                                                    <input class="form-control" type="date" placeholder="Tanggal BPJS Kantor..." id="tgl_bpjs_kantor" name="tgl_bpjs_kantor" value="{!! $karyawan[0]->tgl_bpjs_kantor !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Keterangan</label>
                                                    <textarea class="form-control" placeholder="Keterangan..." id="keterangan" name="keterangan">{!! $karyawan[0]->keterangan !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $n = 1;
                                    foreach ($view_array as $key1 => $value1) { ?>
                                        <div class="tab-pane fade" id="custom-tabs-one-<?= $key1 ?>" role="tabpanel" aria-labelledby="custom-tabs-one-<?= $key1 ?>-tab">
                                            <?php
                                            $return = $view_panel($key1, $id);
                                            ?>
                                            <table id="example<?= $n; ?>" class="table table-striped custom-table mb-0">
                                                <thead>
                                                    <th>No</th>
                                                    <?php foreach ($return['array'] as $key => $value) { ?>

                                                        <th><?= $key; ?></th>
                                                    <?php } ?>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no = 0;
                                                    $nominal = 0;
                                                    foreach ($return['data'] as $data) {
                                                        echo '<tr>';
                                                        $no++;
                                                        echo '<td>' . $no . '</td>';
                                                        foreach ($return['array'] as $key => $value) {
                                                            if($value=='file_kontrak_kerja')
                                                            echo '<td>'.($data->$value?'<a href="'.url('dist/img/file/'.$data->$value).'" download>View File</a>':'').'</td>';
                                                            else
                                                            echo '<td>' . $data->$value . '</td>';
                                                        }
                                                        echo '</tr>';
                                                    }
                                                    $n++;
                                                    ?>
                                                </tbody>
                                            </table>


                                        </div>
                                    <?php } ?>
                                    <div class="tab-pane fade" id="custom-tabs-one-kartuidentitas" role="tabpanel" aria-labelledby="custom-tabs-one-kartuidentitas-tab">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>No. KK</label>
                                                    <input type="text" class="form-control" placeholder="KK..." id="kk" name="kk" value="{!! $karyawan[0]->kartu_keluarga !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>No. KTP</label>
                                                    <input type="text" class="form-control" placeholder="KTP..." id="ktp" name="ktp" value="{!! $karyawan[0]->ktp !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>No. NPWP</label>
                                                    <input type="text" class="form-control" placeholder="NPWP..." id="npwp" name="npwp" value="{!! $karyawan[0]->no_npwp !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>No. SIM A</label>
                                                    <input type="text" class="form-control" placeholder="SIM A..." id="sima" name="sima" value="{!! $karyawan[0]->no_sima !!}">
                                                </div>

                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>No. SIM C</label>
                                                    <input type="text" class="form-control" placeholder="SIM C..." id="simc" name="simc" value="{!! $karyawan[0]->no_simc !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>No. BPJS Ketenagakerjaan</label>
                                                    <input type="text" class="form-control" placeholder="BPJS Ketenagakerjaan..." id="bpjstk" name="bpjstk" value="{!! $karyawan[0]->no_bpjstk !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>No. BPJS Kesehatan</label>
                                                    <input type="text" class="form-control" placeholder="BPJS Kesehatan..." id="bpjsks" name="bpjsks" value="{!! $karyawan[0]->no_bpjsks !!}">
                                                </div>
                                                <div class="form-group">
                                                    <label>No. Passport</label>
                                                    <input type="text" class="form-control" placeholder="Pasport..." id="pasport" name="pasport" value="{!! $karyawan[0]->no_pasport !!}">
                                                </div>
                                            </div>

                                        </div>
                                        <ul class="personal-info">
                                            <li>

                                                <div class="title">File KK</div>

                                                <div class="text">: @if($karyawan[0]->file_kk!=null)
                                                    <a href="{!! asset('dist/img/file/'.$karyawan[0]->file_kk) !!}" target="_blank">
                                                        <?php
                                                        $info = pathinfo($karyawan[0]->file_kk);
                                                         if(isset($info["extension"])){
                                                         	if ($info["extension"] == "jpg" or $info["extension"] == "png" or $info["extension"] == "jepg") {
                                                        ?>

                                                            <img src="{!! asset('dist/img/file/'.$karyawan[0]->file_kk) !!}" alt="File Terupload" class="">
                                                        <?php } else { ?>
                                                            <i class="fa fa-download"></i>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </a>
                                                    @else
                                                    <img src="{!! asset('dist/img/noimage.png') !!}" class="" alt="File Belum Diupload">
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">File KTP</div>
                                                <div class="text">: @if($karyawan[0]->file_ktp!=null)
                                                    <a href="{!! asset('dist/img/file/'.$karyawan[0]->file_ktp) !!}" target="_blank">
                                                        <?php
                                                        $info = pathinfo($karyawan[0]->file_ktp);
                                                        if(isset($info["extension"])){
                                                        if ($info["extension"] == "jpg" or $info["extension"] == "png" or $info["extension"] == "jepg") {
                                                        ?>
                                                            <img src="{!! asset('dist/img/file/'.$karyawan[0]->file_ktp) !!}" alt="File Terupload" class="">
                                                        <?php } else { ?>
                                                            <i class="fa fa-download"></i>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </a>
                                                    @else
                                                    <img src="{!! asset('dist/img/noimage.png') !!}" class="" alt="File Belum Diupload">
                                                    @endif
                                                </div>
                                            </li>
                                            <li>
                                                <div class="title">File BPJS</div>
                                                <div class="text">: @if($karyawan[0]->file_bpjs_karyawan!=null)
                                                    <a href="{!! asset('dist/img/file/'.$karyawan[0]->file_bpjs_karyawan) !!}" target="_blank">
                                                        <?php
                                                        $info = pathinfo($karyawan[0]->file_bpjs_karyawan);
                                                        if(isset($info["extension"])){
                                                        	if ($info["extension"] == "jpg" or $info["extension"] == "png" or $info["extension"] == "jepg") {
                                                        ?>
                                                            <img src="{!! asset('dist/img/file/'.$karyawan[0]->file_bpjs_karyawan) !!}" alt="File Terupload" class="">
                                                        <?php } else { ?>
                                                            <i class="fa fa-download"></i>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </a>
                                                    @else
                                                    <img src="{!! asset('dist/img/noimage.png') !!}" class="" alt="File Belum Diupload">
                                                    @endif
                                                </div>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
        </div>
        <!-- /.box-body -->
        <div class="card-footer">
            <a href="{!! route('be.karyawan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
            <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Ubah</button>
        </div>
        <!-- /.box-footer -->
        </form>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.content -->
</div>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
        $('#button-pic').show();
    });

    $("#example3").DataTable({
        "responsive": true,
        "autoWidth": false,
    });
    $("#example4").DataTable({
        "responsive": true,
        "autoWidth": false,
    });
    $("#example5").DataTable({
        "responsive": true,
        "autoWidth": false,
    });
    $("#example6").DataTable({
        "responsive": true,
        "autoWidth": false,
    });
    $("#example7").DataTable({
        "responsive": true,
        "autoWidth": false,
    });
</script>
<!-- /.content-wrapper -->
@endsection