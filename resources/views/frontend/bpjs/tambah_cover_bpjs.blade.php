@extends('layouts.app_fe')

@section('content')
<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    
			
<div class="content" style="min-height: 150%">
<div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
         <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Pengajuan Penambahan Anggota Keluarga BPJS Kesehatan</h4>

</div>
</div>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
               <h3 class="card-title mb-0 pb-0">Tambah</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_cover_bpjs') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                       <div class="col-sm-12" id="karyawanKonten">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Anggota Keluarga Yang Diajukan</label>
                                
								 <select class="form-control select2" name="keluarga" id="karyawan"  style="width: 100%;" required>
                                    <option value="" >Pilih Anggota Keluarga</option>
                                   
                                    <?php foreach($keluarga as $kar){
                                    	if($kar->hubungan=='Suami/Istri' or $kar->hubungan=='Anak'){
											
                                    	?>
                                    <option value="<?=$kar->p_karyawan_keluarga_id;?>"><?=$kar->hubungan;?> - <?=$kar->nama;?></option>
									<?php }?>
									<?php }?>
                                </select>
                            </div><div class="form-group">
                                <label>Alamat</label>
                              <textarea class="form-control"  name="alamat"  value="" placeholder="Alamat"></textarea>
                            </div><div class="form-group">
                                <label>Tanggal Lahir</label>
                              <input class="form-control"  name="tanggal_lahir"  type="date" placeholder="">
                            </div><div class="form-group">
                                <label>NIK</label>
                              <input class="form-control"  name="nik"  type="text" placeholder="NIK">
                            </div><div class="form-group">
                                <label>File KK pengaju</label>
                              <input class="form-control"  name="kk"  type="file" >
                            </div><div class="form-group">
                                <label>File KTP pengaju</label>
                              <input class="form-control"  name="ktp"  type="file" >
                            </div><div class="form-group">
                                <label>File BPJS Karyawan</label>
                              <input class="form-control"  name="bpjs_karyawan"  type="file" >
                            </div><div class="form-group">
                                <label>File  BPJS Induk(BPJS Suami/Istri)</label>
                              <input class="form-control"  name="bpjs_induk"  type="file" >
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('admin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    </div>
  
    <!-- /.content-wrapper -->
@endsection
