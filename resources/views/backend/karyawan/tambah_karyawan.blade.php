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

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_karyawan') !!}" enctype="multipart/form-data">
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
		<div class="avatar-edit">
            <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
            <label for="imageUpload"><ion-icon name="camera"></ion-icon></label>
        </div>
        <div class="avatar-preview">
                    <div id="imagePreview" style="background-image: url(http://localhost/_kerja/es-agen-absen/public/dist/img/profile-no-image.png);">
            </div>
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
                                                        <input type="text" class="form-control" placeholder="NIK..." id="nik" name="nik" value="AUTO" disabled="disabled">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Lengkap</label>
                                                        <input type="text" class="form-control" placeholder="Nama Lengkap..." id="nama_lengkap" name="nama_lengkap" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nama Panggilan</label>
                                                        <input type="text" class="form-control" placeholder="Nama Panggilan..." id="nama_panggilan" name="nama_panggilan" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tempat Lahir</label>
                                                        <input type="text" class="form-control" placeholder="Tempat Lahir..." id="tempat_lahir" name="tempat_lahir" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal Lahir</label>
                                                        <div class="input-group date" id="tgl_lahir" data-target-input="nearest">
                                                            <input type="date" class="form-control -input" id="tgl_lahir" name="tgl_lahir" data-target="#tgl_lahir" />
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jenis Kelamin</label>
                                                        <select class="form-control select2" name="jk" style="width: 100%;" required>
                                                            <?php
                                                            foreach($jk AS $jk){
                                                                echo '<option value="'.$jk->m_jenis_kelamin_id.'">'.$jk->nama.'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Alamat Sesuai KTP</label>
                                                        <input type="text" class="form-control" placeholder="Alamat Sesuai KTP..." id="alamat_ktp" name="alamat_ktp">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Domisili</label>
                                                        <input type="text" class="form-control" placeholder="Domisili..." id="domisili" name="domisili" >
                                                    </div>
                                                    <!--<div class="form-group">-->
                                                    <!--    <label>Pendidikan</label>-->
                                                    <!--    <select class="form-control select2" name="pendidikan" style="width: 100%;" >-->
                                                    <!--        <option value="">Pilih Pendidikan</option>-->
                                                    <!--        <option value="SD">SD</option>-->
                                                    <!--        <option value="SMP">SMP</option>-->
                                                    <!--        <option value="SMA">SMA</option>-->
                                                    <!--        <option value="D1">D1</option>-->
                                                    <!--        <option value="D2">D2</option>-->
                                                    <!--        <option value="D3">D3</option>-->
                                                    <!--        <option value="S1">S1</option>-->
                                                    <!--        <option value="S2">S2</option>-->
                                                    <!--        <option value="S3">S3</option>-->
                                                    <!--    </select>-->
                                                    <!--</div>-->
                                                    <!--<div class="form-group">-->
                                                    <!--    <label>Jurusan</label>-->
                                                    <!--    <input type="text" class="form-control" placeholder="Jurusan..." id="jurusan" name="jurusan" >-->
                                                    <!--</div>-->
                                                    <!--<div class="form-group">-->
                                                    <!--    <label>Nama Sekolah/PT</label>-->
                                                    <!--    <input type="text" class="form-control" placeholder="Nama Sekolah/PT..." id="nama_sekolah" name="nama_sekolah" >-->
                                                    <!--</div>-->
                                                    <div class="form-group">
                                                        <label>Status Pernikahan</label>
                                                        <select class="form-control select2" name="status_pernikahan" style="width: 100%;" >
                                                            <option value="0">Belum Menikah</option>
                                                            <option value="1">Menikah</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jumlah Anak</label>
                                                        <input type="text" class="form-control" placeholder="Jumlah Anak..." id="jumlah_anak" name="jumlah_anak" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" placeholder="Email..." id="email" name="email" >
                                                    </div>

                                                    <div class="form-group">
                                                        <label>No. HP</label>
                                                        <input type="text" class="form-control" placeholder="No. HP..." id="no_hp" name="no_hp" >
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
                                                            <?php
                                                            foreach($lokasi AS $lokasi){
                                                                echo '<option value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                    <label>Directorat</label>
                                                    <select class="form-control select2" name="directorat" id="directorat" style="width: 100%;" onchange="finddivisi(this.value)" required>
                                                        <option value="">- Directorat -</option>
                                                        
                                                    </select>
                                                </div>
                                                 <div class="form-group">
                                                    <label>Divisi</label>
                                                    <select class="form-control select2" name="divisi_new" id="divisi_new" style="width: 100%;"  onchange="finddepartement(this.value)" required>
                                                        <option value="">- Divisi -</option>
                                                        
                                                    </select>
                                                </div>
                                                    <div class="form-group">
                                                        <label>Departemen</label>
                                                        <select class="form-control select2" name="divisi" id="departemen"  style="width: 100%;" required  onchange="findseksi(this.value)">
                                                            <option value="">- Departement -</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Seksi</label>
                                                        <select class="form-control select2" name="departemen" id="seksi" style="width: 100%;" required   onchange="findjabatan(this.value)">
                                                            <option value="">- Seksi -</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jabatan/Pangkat</label>
                                                        <select class="form-control select2" name="jabatan"  id="jabatan" style="width: 100%;" required>
                                                            <?php
                                                            foreach($jabatan AS $jabatan){
                                                                echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.' - '.$jabatan->nmpangkat.' - '.$jabatan->nmlokasi.'</option>';
                                                            }
                                                            ?>
                                                        </select>
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
                                                    <div class="form-group">
                                                        <label>Tanggal Masuk</label>
                                                        <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                                            <input type="date" class="form-control -input" id="tgl_awal" name="tgl_awal" data-target="#tgl_awal"/>
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal Keluar</label>
                                                        <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                                            <input type="date" class="form-control -input" id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir"/>
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal Bergabung</label>
                                                        <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                                            <input type="date" class="form-control -input" id="tgl_awal" name="tgl_bergabung" data-target="#tgl_awal" value="<?=date("Y-m-d");?>" required/>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label>No. Absen</label>
                                                        <input type="text" class="form-control" placeholder="No. Absen..." id="no_absen" name="no_absen" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">

                                                    <div class="form-group">
                                                        <label>Status Pekerjaan</label>
                                                        <select class="form-control select2" name="status_pekerjaan" style="width: 100%;" required>
                                                            <?php
                                                            foreach($stspekerjaan AS $stspekerjaan){
                                                                echo '<option value="'.$stspekerjaan->m_status_pekerjaan_id.'">'.$stspekerjaan->nama.'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kota</label>
                                                        <input type="text" class="form-control" placeholder="Kota..." id="kotakerja" name="kotakerja">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kantor</label>
                                                         <select class="form-control select2" name="kantor" style="width: 100%;" required>
                                                            <option value="1">- Pilih Kantor</option>
                                                            <?php
                                                            foreach($kantor AS $kantor){
                                                                echo '<option value="'.$kantor->m_office_id.'">'.$kantor->nama.'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                       <!-- <input type="text" class="form-control" placeholder="Kantor..." id="kantor" name="kantor" required>-->
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select class="form-control select2" name="status" style="width: 100%;" required>
                                                            <option value="1">Active</option>
                                                            <option value="0">Non Active</option>
                                                        </select>
                                                    </div>
													<div class="form-group">
                                                        <label>Karyawan Shift</label>
                                                        <select class="form-control select2" name="is_shift" style="width: 100%;" required>
                                                            <option value="0">Non Shift</option>
                                                            <option value="1">Shift</option>
                                                        </select>
                                                    </div>
													<div class="form-group">
                                                        <label>No Rekening</label>
                                                       <input type="text" class="form-control" placeholder="No Rekening..." id="norek" name="norek" >
                                                    </div><div class="form-group">
                                                        <label>Bank</label>
                                                        <select type="text" class="form-control select2" id="bank"  style="width: 100%;" name="bank" placeholder="Nama bank" value="">
                                <option value="">- Pilih Bank - </option>
								<?php

								foreach($bank as $bank){
								$selected ='';
								
								echo "
								<option value='".$bank->m_bank_id."' $selected>".$bank->nama_bank."</option>";
								} 
								?>
                                
                                </select>
                                                    </div>
                                                     <div class="form-group">
                                                        <label>Periode Gajian</label>
                                                        <select class="form-control select2" name="periode_gajian" style="width: 100%;" required>
                                                            <option value="1">Bulanan</option>
                                                            <option value="0">Pekanan</option>
                                                        </select>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Pajak</label>
                                                        <select class="form-control select2" name="pajakonoff" style="width: 100%;" required="">	
                                    <option value="OFF"  >OFF</option>
                                    <option value="ON" >ON</option>
                                    
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>BPJS KANTOR</label>
                                                        <select class="form-control select2" name="bpjs_kantor" style="width: 100%;" >
                                                            <option value="0">Belum</option>
                                                            <option value="1">Sudah</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal BPJS Kantor</label>
                                                       <input class="form-control" type="date" placeholder="Tanggal BPJS Kantor..." id="tgl_bpjs_kantor" name="tgl_bpjs_kantor" value="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Keterangan</label>
                                                        <input type="text" class="form-control" placeholder="Keterangan..." id="keterangan" name="keterangan">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-kartuidentitas" role="tabpanel" aria-labelledby="custom-tabs-one-kartuidentitas-tab">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>No. KK</label>
                                                        <input type="text" class="form-control" placeholder="KK" id="kk" name="kk" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. KTP</label>
                                                        <input type="text" class="form-control" placeholder="KTP..." id="ktp" name="ktp" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. NPWP</label>
                                                        <input type="text" class="form-control" placeholder="NPWP..." id="npwp" name="npwp" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. SIM A</label>
                                                        <input type="text" class="form-control" placeholder="SIM A..." id="sima" name="sima" >
                                                    </div>

                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>No. SIM C</label>
                                                        <input type="text" class="form-control" placeholder="SIM C..." id="simc" name="simc" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. BPJS Ketenagakerjaan</label>
                                                        <input type="text" class="form-control" placeholder="BPJS Ketenagakerjaan..." id="bpjstk" name="bpjstk">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. BPJS Kesehatan</label>
                                                        <input type="text" class="form-control" placeholder="BPJS Kesehatan..." id="bpjsks" name="bpjsks">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No. Passport</label>
                                                        <input type="text" class="form-control" placeholder="Pasport..." id="pasport" name="pasport">
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
                        <a href="{!! route('be.karyawan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
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
		            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
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
     </script>
    <!-- /.content-wrapper -->
@endsection
