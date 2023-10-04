@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Profile</h4>

</div>
</div>
        
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    @if($karyawan[0]->foto!=null)
                                        <img src="{!! asset('dist/img/profile/'.$karyawan[0]->foto) !!}" alt="User Avatar" class="profile-user-img img-fluid img-thumbnail">
                                    @else
                                        <img src="{!! asset('dist/img/profile/user.png') !!}" class="profile-user-img img-fluid img-circle" alt="User Image">
                                    @endif
                                </div>

                                <h3 class="profile-username text-center">{!! $karyawan[0]->nama_lengkap !!}</h3>

                                <p class="text-muted text-center">{!! $karyawan[0]->nik !!}</p>

                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Entitas</b> <a class="float-right">{!! $karyawan[0]->nmlokasi !!}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Departemen</b> <a class="float-right">{!! $karyawan[0]->nmdept !!}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Divisi</b> <a class="float-right">{!! $karyawan[0]->nmdivisi !!}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Jabatan</b> <a class="float-right">{!! $karyawan[0]->nmjabatan !!}</a>
                                    </li>
                                </ul>

                                <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    
                    <div class="col-md-6">
                        <div class="card">
                            <!-- form start -->
                            <form class="form-horizontal" method="POST" action="{!! route('fe.update_profile',$karyawan[0]->p_karyawan_id) !!}" enctype="multipart/form-data">
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
                                                        <a class="nav-link" id="custom-tabs-one-kartuidentitas-tab" data-toggle="pill" href="#custom-tabs-one-kartuidentitas" role="tab" aria-controls="custom-tabs-one-kartuidentitas" aria-selected="false">Kartu Identitas</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="custom-tabs-one-password-tab" data-toggle="pill" href="#custom-tabs-one-password" role="tab" aria-controls="custom-tabs-one-password" aria-selected="false">Password</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                                    <div class="tab-pane fade show active" id="custom-tabs-one-profil" role="tabpanel" aria-labelledby="custom-tabs-one-profil-tab">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>NIK</label>
                                                                    <input type="text" class="form-control" placeholder="NIK..." id="nik" name="nik" value="{!! $karyawan[0]->nik !!}" disabled="disabled">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Nama Lengkap *</label>
                                                                    <input type="text" class="form-control" placeholder="Nama Lengkap..." id="nama_lengkap" name="nama_lengkap" required value="{!! $karyawan[0]->nama_lengkap !!}" disabled="disabled">
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
                                                                        <input type="date" class="form-control " id="tgl_lahir" name="tgl_lahir" data-target="#tgl_lahir" value="{!! $karyawan[0]->tgl_lahir !!}">
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Jenis Kelamin</label>
                                                                    <select class="form-control select2" name="jk" style="width: 100%;" required>
                                                                        <?php
                                                                        foreach($jk AS $jk){
                                                                            if($jk->m_jenis_kelamin_id==$karyawan[0]->m_jenis_kelamin_id){
                                                                                echo '<option selected="selected" value="'.$jk->m_jenis_kelamin_id.'">'.$jk->nama.'</option>';
                                                                            }
                                                                            else{
                                                                                echo '<option value="'.$jk->m_jenis_kelamin_id.'">'.$jk->nama.'</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Alamat Sesuai KTP</label>
                                                                    <textarea class="form-control" placeholder="Alamat Sesuai KTP..." id="alamat_ktp" name="alamat_ktp">{!! $karyawan[0]->alamat_ktp !!}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Upload Foto</label><br>
                                                                    @if($karyawan[0]->foto!=null)
                                                                        <img src="{!! asset('dist/img/profile/'.$karyawan[0]->foto) !!}" alt="User Avatar" class="img-size-64 mr-3 img-thumbnail" style="max-width: 151px;">
                                                                    @else
                                                                        <img src="{!! asset('dist/img/profile/user.png') !!}" class="img-size-64 mr-3 img-circle elevation-2" alt="User Image"style="max-width: 151px;">
                                                                    @endif
                                                                    <input type="file" class="form-control" id="image" name="image" value="{!! $karyawan[0]->foto !!}">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Domisili</label>
                                                                    <input type="text" class="form-control" placeholder="Domisili..." id="domisili" name="domisili" value="{!! $karyawan[0]->domisili !!}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Pendidikan</label>
                                                                    <select class="form-control select2" name="pendidikan" style="width: 100%;" >
                                                                        <option value="">Pilih Pendidikan</option>
                                                                        <option value="SD" <?php if($karyawan[0]->pendidikan=='SD'){ echo 'selected="selected" ';} ?>>SD</option>
                                                                        <option value="SMP" <?php if($karyawan[0]->pendidikan=='SMP'){ echo 'selected="selected" ';} ?>>SMP</option>
                                                                        <option value="SMA" <?php if($karyawan[0]->pendidikan=='SMA'){ echo 'selected="selected" ';} ?>>SMA</option>
                                                                        <option value="D1" <?php if($karyawan[0]->pendidikan=='D1'){ echo 'selected="selected" ';} ?>>D1</option>
                                                                        <option value="D2" <?php if($karyawan[0]->pendidikan=='D2'){ echo 'selected="selected" ';} ?>>D2</option>
                                                                        <option value="D3" <?php if($karyawan[0]->pendidikan=='D3'){ echo 'selected="selected" ';} ?>>D3</option>
                                                                        <option value="S1" <?php if($karyawan[0]->pendidikan=='S1'){ echo 'selected="selected" ';} ?>>S1</option>
                                                                        <option value="S2" <?php if($karyawan[0]->pendidikan=='S2'){ echo 'selected="selected" ';} ?>>S2</option>
                                                                        <option value="S3" <?php if($karyawan[0]->pendidikan=='S3'){ echo 'selected="selected" ';} ?>>S3</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Jurusan</label>
                                                                    <input type="text" class="form-control" placeholder="Jurusan..." id="jurusan" name="jurusan" value="{!! $karyawan[0]->jurusan !!}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Nama Sekolah/PT</label>
                                                                    <input type="text" class="form-control" placeholder="Nama Sekolah/PT..." id="nama_sekolah" name="nama_sekolah" value="{!! $karyawan[0]->nama_sekolah !!}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Status Pernikahan *</label>
                                                                    <select class="form-control select2" name="status_pernikahan" style="width: 100%;" required >
                                                                        <option value="0" <?php if($karyawan[0]->m_status_id==0){ echo 'selected="selected" ';} ?> >Belum Menikah</option>
                                                                        <option value="1" <?php if($karyawan[0]->m_status_id==1){ echo 'selected="selected" ';} ?> >Menikah</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Jumlah Anak *</label>
                                                                    <input type="text" class="form-control" placeholder="Jumlah Anak..." id="jumlah_anak" name="jumlah_anak" value="{!! $karyawan[0]->jumlah_anak !!}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Email * </label>
                                                                    <input type="email" class="form-control" placeholder="Email..." id="email" name="email" value="{!! $karyawan[0]->email_perusahaan !!}" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>No. HP *</label>
                                                                    <input type="text" class="form-control" placeholder="No. HP..." id="no_hp" name="no_hp" value="{!! $karyawan[0]->no_hp !!}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="custom-tabs-one-kartuidentitas" role="tabpanel" aria-labelledby="custom-tabs-one-kartuidentitas-tab">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>No. KK *</label>
                                                                    <input type="text" class="form-control" placeholder="KK..." id="kk" name="kk" value="{!! $karyawan[0]->kartu_keluarga !!}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>No. KTP *</label>
                                                                    <input type="text" class="form-control" placeholder="KTP..." id="ktp" name="ktp" value="{!! $karyawan[0]->ktp !!}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>No. NPWP</label>
                                                                    <input type="text" class="form-control" placeholder="NPWP..." id="npwp" name="npwp" value="{!! $karyawan[0]->no_npwp !!}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>No. SIM A</label>
                                                                    <input type="text" class="form-control" placeholder="SIM A..." id="sima" name="sima" value="{!! $karyawan[0]->no_sima !!}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>No. SIM C</label>
                                                                    <input type="text" class="form-control" placeholder="SIM C..." id="simc" name="simc" value="{!! $karyawan[0]->no_simc !!}">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>No. BPJS Ketenagakerjaan</label>
                                                                    <input type="text" class="form-control" placeholder="BPJS Ketenagakerjaan..." id="bpjstk" name="bpjstk" value="{!! $karyawan[0]->no_bpjstk !!}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>No. BPJS Kesehatan</label>
                                                                    <input type="text" class="form-control" placeholder="BPJS Kesehatan..." id="bpjsks" name="bpjsks" value="{!! $karyawan[0]->no_bpjsks!!}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>No. Passport</label>
                                                                    <input type="text" class="form-control" placeholder="Pasport..." id="pasport" name="pasport" value="{!! $karyawan[0]->no_pasport !!}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>No. Absen</label>
                                                                    <input type="text" class="form-control" placeholder="No. Absen..." id="no_absen" name="no_absen" required value="{!! $karyawan[0]->no_absen !!}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="custom-tabs-one-password" role="tabpanel" aria-labelledby="custom-tabs-one-password-tab">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Password</label>
                                                                    <input type="password" class="form-control" placeholder="Password ..." id="password" name="password" value="" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <a href="{!! route('home') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                                    <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Ubah</button>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
