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
                "sertifikat" => "sertifikat",
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
                "Hamis" => "gamis",
                "Kemeja" => "kemeja",
                "Kaos" => "kaos",
                "Jaket" => "jaket",
                "Celana" => "celana",
                "Sepatu" => "sepatu",
            );
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
                <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <h4>Data Karyawan <small> </small></h4>
                        </div>
                    </div>
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
.avatar-upload .avatar-edit input + label {
  display: inline-block;
  width: 34px;
  height: 34px;
  margin-bottom: 0;
  border-radius: 100%;
  background: #FFFFFF;
  cursor: pointer;
  font-weight: normal;
  transition: all 0.2s ease-in-out;align-items: center;
text-align: center;
justify-items: center;
display: grid;
align-content: center;
align-self: center;
background: #6236ff;
color: white;
outline: none;
}
.avatar-upload .avatar-edit input + label:hover {
 
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
.avatar-upload .avatar-preview > div {
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
		
        <div class="avatar-preview">
                     @if($karyawan[0]->foto!=null)
                                                                
                                                            <div id="imagePreview" style="background-image: url('{!! asset('dist/img/profile/'.$karyawan[0]->foto) !!}');"></div>
                                                              
                                                            @else
                                                                <div id="imagePreview" style="background-image: url('{!! asset('dist/img/profile/user.png') !!}');"></div>
                                                            @endif  
           
        </div>
        <div id="button-pic" style="display: none">
        	<br>
        	
        	<!--
        	<button type="submit" class="btn btn-success mr-1 mb-1">SAVE</button>
        	<button type="reset" class="btn btn-danger mr-1 mb-1">BATAL</button>-->
        </div>
    </div>

</div>
</div> <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>NIK</label>
                                                        <input type="text" class="form-control" placeholder="NIK..." id="nik" name="nik" disabled="disabled" value="{!! $karyawan[0]->nik !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Lengkap</label>
                                                        <input type="text" class="form-control" placeholder="Nama Lengkap..." id="nama_lengkap" name="nama_lengkap" required disabled="disabled" value="{!! $karyawan[0]->nama_lengkap !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Panggilan</label>
                                                        <input type="text" class="form-control" placeholder="Nama Panggilan..." id="nama_panggilan" name="nama_panggilan" required disabled="disabled" value="{!! $karyawan[0]->nama_panggilan !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tempat Lahir</label>
                                                        <input type="text" class="form-control" placeholder="Tempat Lahir..." id="tempat_lahir" name="tempat_lahir" required disabled="disabled" value="{!! $karyawan[0]->tempat_lahir !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal Lahir</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! date('d-m-Y',strtotime($karyawan[0]->tgl_lahir)) !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jenis Kelamin</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->jenis_kelamin !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Alamat Sesuai KTP</label>
                                                        <input type="text" class="form-control" placeholder="Alamat Sesuai KTP..." disabled="disabled" value="{!! $karyawan[0]->alamat_ktp !!}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Domisili</label>
                                                        <input type="text" class="form-control" placeholder="Domisili..." id="domisili" name="domisili" required disabled="disabled" value="{!! $karyawan[0]->domisili !!}">
                                                    </div>
                                                    <!--<div class="form-group">-->
                                                    <!--    <label>Pendidikan</label>-->
                                                    <!--    <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->pendidikan !!}">-->
                                                    <!--</div>-->
                                                    <!--<div class="form-group">-->
                                                    <!--    <label>Jurusan</label>-->
                                                    <!--    <input type="text" class="form-control" placeholder="Jurusan..." id="jurusan" name="jurusan" required disabled="disabled" value="{!! $karyawan[0]->jurusan !!}">-->
                                                    <!--</div>-->
                                                    <!--<div class="form-group">-->
                                                    <!--    <label>Nama Sekolah/PT</label>-->
                                                    <!--    <input type="text" class="form-control" placeholder="Nama Sekolah/PT..." id="nama_sekolah" name="nama_sekolah" required disabled="disabled" value="{!! $karyawan[0]->nama_sekolah !!}">-->
                                                    <!--</div>-->
                                                    <div class="form-group">
                                                        <label>Status Pernikahan</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->status_pernikahan !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jumlah Anak</label>
                                                        <input type="text" class="form-control" placeholder="Jumlah Anak..." id="jumlah_anak" name="jumlah_anak" required disabled="disabled" value="{!! $karyawan[0]->jumlah_anak !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" placeholder="Email..." id="email" name="email" required disabled="disabled" value="{!! $karyawan[0]->email !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. HP</label>
                                                        <input type="text" class="form-control" placeholder="No. HP..." id="no_hp" name="no_hp" required disabled="disabled" value="{!! $karyawan[0]->no_hp !!}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-pekerjaan" role="tabpanel" aria-labelledby="custom-tabs-one-pekerjaan-tab">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                     <div class="form-group">
                                                    <label>Entitas</label>
                                                    <select class="form-control "  disabled name="lokasi" style="width: 100%;"  onchange="finddirectorat(this)">
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
                                                    <select class="form-control " disabled name="directorat" id="directorat" style="width: 100%;" onchange="finddivisi(this.value)">
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
                                                    <select class="form-control " disabled name="divisi_new" id="divisi_new" style="width: 100%;"  onchange="finddepartement(this.value)" >
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
                                                    <select class="form-control " disabled name="divisi" id="departemen" style="width: 100%;" onchange="findseksi(this.value)" >
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
                                                    <select class="form-control " disabled name="departemen" id="seksi" style="width: 100%;"  onchange="findjabatan(this.value)">
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
                                                    <select class="form-control " disabled name="jabatan" id="jabatan" style="width: 100%;" required>
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
                                                        <label>Tanggal Masuk</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! date('d-m-Y',strtotime($karyawan[0]->tgl_awal)) !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal Keluar</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! date('d-m-Y',strtotime($karyawan[0]->tgl_akhir)) !!}">
                                                    </div>
                                                   
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                    
                                                        <label>No. Absen</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->no_absen !!}">
                                                    </div>
                                                    <div class="form-group">
                                                    
                                                        <label>Mesin Seharusnya Absen</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->nama_mesin !!}">
                                                    </div>
                                                     <div class="form-group">
                                                        <label>Kantor</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->nama_kantor !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kota</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->kota !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Status Pekerjaan</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->nmstatus !!}">
                                                    </div>
                                                    
                                                   
                                                   
                                                    <div class="form-group">
                                                        <label>Bank</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->nama_bank !!}">
                                                    </div> <div class="form-group">
                                                        <label>No Rek</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->norek !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->status !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Periode Gajian</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->periode !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Keterangan</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->keterangan !!}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-kartuidentitas" role="tabpanel" aria-labelledby="custom-tabs-one-kartuidentitas-tab">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>No. KTP</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->ktp !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. NPWP</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->no_npwp !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. SIM A</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->no_sima !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. SIM C</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->no_simc !!}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>No. BPJS Ketenagakerjaan</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->no_bpjstk !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. BPJS Kesehatan</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->no_bpjsks !!}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. Pasport</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $karyawan[0]->no_pasport !!}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    $n = 1;
                                    $id = $karyawan[0]->p_karyawan_id;
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
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.karyawan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
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
@endsection
