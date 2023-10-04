@extends('layouts.app2')
<?php
date_default_timezone_set('Asia/Jakarta');
?>
<style type="text/css">
    .without_ampm::-webkit-datetime-edit-ampm-field {
        display: none;
    }
    input[type=time]::-webkit-clear-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        -o-appearance: none;
        -ms-appearance:none;
        appearance: none;
        margin: -10px;
    }
</style>

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Pengajuan Lembur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('fe.permit') !!}">Permit</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('fe.list_lembur') !!}">List Lembur</a></li>
                            <li class="breadcrumb-item active">Izin</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_lembur') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $kar[0]->nik !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $kar[0]->nama_lengkap !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" placeholder="Nama Jabatan ..." id="jabatan" name="jabatan" value="{!! $kar[0]->jabatan !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Departemen</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $kar[0]->departemen !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y') !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tanggal Awal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" value="" required>
                                    <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tanggal Akhir*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="" required>
                                    <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Lama (Jam)*</label>
                                <input type="text" class="form-control masking0" id="lama" name="lama" value="" placeholder="Lama Lembur..." required>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Awal*</label>
                                <input type="time" class="form-control without_ampm" id="jam_awal" name="jam_awal" value="16:30" required>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Akhir*</label>
                                <input type="time" class="form-control without_ampm" id="jam_akhir" name="jam_akhir" value="23:00" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tipe Lembur*</label>
                                <select class="form-control select2" name="tipe_lembur" style="width: 100%;" required>
                                    <option value="">Pilih Lembur</option>
                                    <option value="Lembur Hari Kerja">Lembur Hari Kerja</option>
                                    <option value="Lembur Hari Libur">Lembur Hari Libur</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Alasan Lembur*</label>
                                <textarea class="form-control" placeholder="Alasan Cuti..." id="alasan" name="alasan"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.list_lembur') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
