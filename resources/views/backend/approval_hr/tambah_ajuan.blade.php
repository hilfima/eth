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
                        <h1 class="m-0 text-dark">Pengajuan {!!ucwords($type)!!}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6"> 
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">admin</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('be.list_ajuan',$type) !!}">List Permit {!!ucwords($type)!!}</a></li>
                            <li class="breadcrumb-item active">Tambah {!!ucwords($type)!!}</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_pengajuan',$type) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama Karyawan*</label>
                                <select class="form-control select2" name="nama" style="width: 100%;" required>
                                    <option value="">Pilih Karywan</option>
                                    <?php
                                    foreach($karyawan AS $karyawan){
                                        echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <div class="input-group date" id="tgl_pengajuan" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_pengajuan" name="tgl_pengajuan" data-target="#tgl_pengajuan" value="" required>
                                    <div class="input-group-append" data-target="#tgl_pengajuan" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Jenis {!!ucwords($type)!!}*</label>
                                <select class="form-control select2" name="jenis" style="width: 100%;" required>
                                    <option value="">Pilih {!!ucwords($type)!!}</option>
                                    <?php
                                    foreach($jeniscuti AS $jeniscuti){
                                        echo '<option value="'.$jeniscuti->m_jenis_ijin_id.'">'.$jeniscuti->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tanggal Awal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" value="" required>
                                    <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tanggal Akhir*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="" required>
                                    <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Atasan*</label>
                                <select class="form-control select2" name="atasan" style="width: 100%;" required>
                                    <option value="">Pilih Atasan</option>
                                    <?php
                                    foreach($appr AS $appr){
                                        echo '<option value="'.$appr->p_karyawan_id.'">'.$appr->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Lama (Hari)*</label>
                                <input type="number" class="form-control" id="lama" name="lama" value="" placeholder="Lama {!!ucwords($type)!!}..." required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" class="form-control" id="file" name="file" value="" >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Alasan {!!ucwords($type)!!}*</label>
                                <textarea class="form-control" placeholder="Alasan {!!ucwords($type)!!}..." id="alasan" name="alasan"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.list_ajuan',$type) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
