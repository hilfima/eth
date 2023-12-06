@extends('layouts.appsA')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Jam Kerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jam Kerja</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_jam_kerja',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                       
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Entitas</label>
                                
								 <select class="form-control select2" name="entitas" style="width: 100%;" required>
                                    <option value="">Pilih Entitas</option>
                                    <?php
                                    foreach($lokasi AS $entitas){
                                    	if($entitas->m_lokasi_id == $jam_kerja->m_lokasi_id)
                                    		$selected = 'Selected';
                                    	else
                                    		$selected = '';
                                        echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
                                    }
                                    ?>
                                </select>
                                
                        </div>
                            </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_absen" name="tgl_awal" data-target="#tgl_absen" value="{!! $jam_kerja->tgl_awal !!}"/>
                                   
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="{!! $jam_kerja->tgl_akhir !!}"/>
                                   
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Jam Masuk</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="time" class="form-control timepicker-input" id="jam_masuk" name="jam_masuk" data-target="#tgl_absen" value="{!! $jam_kerja->jam_masuk !!}" required=""/>
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Jam Keluar</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="time" class="form-control timepicker-input" id="jam_keluar" name="jam_keluar" data-target="#tgl_absen" value="{!! $jam_kerja->jam_keluar !!}" required=""/>
                                    
                                </div>
                            </div>
                        </div><div class="form-group col-md-6">
	                                <label>Batas Jam Masuk</label>
	                                <input type="time" class="form-control" placeholder="Nama  ..." id="judul" name="data[jam_batas_masuk]" required>
	                            </div>
	                            <div class="form-group col-md-6 ">
	                                <label>Batas Jam Keluar</label>
	                                <input type="time" class="form-control" placeholder="Nama  ..." id="judul" name="data[jam_batas_keluar]" required>
	                            </div>
	                            <div class="form-group  col-md-3">
                                <label>Masuk Senin</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_senin]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk selasa</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_selasa]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Rabu</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_rabu]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Kamis</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_kamis]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Jumat</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_jumat]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Sabtu</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_sabtu]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Ahad</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_ahad]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div><div class="col-sm-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <textarea class="form-control " id="tgl_absen" name="keterangan" data-target="#tgl_absen" value="" placeholder="Keterangan">{!! $jam_kerja->keterangan !!}</textarea>
                                    
                                </div>
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
  <script>
  	 $('#tgl_absen').daterangepicker({
        
        locale: {
            format: 'YYYY-MM-DD'
        }
    })$('#tgl_akhir').daterangepicker({
        
        locale: {
            format: 'YYYY-MM-DD'
        }
    })
  </script>
    <!-- /.content-wrapper -->
@endsection
