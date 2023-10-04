@extends('layouts.app4')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 0px">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12" style="text-align: center">
                        <h1 class="m-2 text-dark"><b>FORM PERJALANAN DINAS</b></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
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
                <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        @include('flash-message')
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>No. Pengajuan *</label>
                                <input type="text" class="form-control" placeholder="No. Pengajuan..." id="no_pengajuan" name="no_pengajuan" readonly value="{!! $NoDokumen !!}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Perusahaan</label>
                                <select class="form-control select2" name="lokasi" style="width: 100%;" required>
                                    <option>Pilih Perusahaan</option>
                                    <?php
                                    foreach($lokasi AS $lokasi){
                                        echo '<option value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Unit Kerja</label>
                                <select class="form-control select2" name="departemen" style="width: 100%;" required>
                                    <option>Pilih Unit Kerja</option>
                                    <?php
                                    foreach($departemen AS $departemen){
                                        echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <div class="input-group date" id="tgl_pengajuan" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_pengajuan" name="tgl_pengajuan" data-target="#tgl_pengajuan" required/>
                                    <div class="input-group-append" data-target="#tgl_pengajuan" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Karyawan yang bertugas * :</label>
                                <select class="form-control select2" name="karyawan_bertugas" style="width: 100%;" required>
                                    <option value="">Pilih Karyawan</option>
                                    <?php
                                    foreach($karyawan AS $karyawan){
                                        echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jabatan * :</label>
                                <select class="form-control select2" name="jabatan" style="width: 100%;" required>
                                    <option value="">Pilih Jabatan</option>
                                    <?php
                                    foreach($jabatan AS $jabatan){
                                        echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Maksud Perjalan Dinas * :</label>
                                <input type="text" class="form-control" placeholder="Maksud Perjalan Dinas..." id="deskripsi" name="deskripsi" required>
                            </div>
                            <div class="form-group">
                                <label>Alat Transportasi * :</label>
                                <input type="text" class="form-control" placeholder="Transportasi..." id="transportasi" name="transportasi" required>
                            </div>
                            <div class="form-group">
                                <label>Dipergunakan * :</label>
                                <input type="text" class="form-control" placeholder="Dipergunakan..." id="dipergunakan" name="dipergunakan" required>
                            </div>
                            <div class="form-group">
                                <label>Tempat Tujuan * :</label>
                                <input type="text" class="form-control" placeholder="Tempat Tujuan..." id="tujuan" name="tujuan" required>
                            </div>
                            <div class="form-group">
                                <label>Lama Perjalanan Dinas * :</label>
                                <input type="text" class="form-control" placeholder="Lama Perjalanan Dinas..." id="lama" name="lama" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Berangkat * :</label>
                                <div class="input-group date" id="tgl_berangkat" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_berangkat" name="tgl_berangkat" data-target="#tgl_berangkat" required/>
                                    <div class="input-group-append" data-target="#tgl_berangkat" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Kembali * :</label>
                                <div class="input-group date" id="tgl_kembali" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_kembali" name="tgl_kembali" data-target="#tgl_kembali" required/>
                                    <div class="input-group-append" data-target="#tgl_kembali" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Pengikut *</label>
                                <select class="form-control select2" name="pengikut" style="width: 100%;" required>
                                    <option value="">Pilih Pengikut</option>
                                    <?php
                                    foreach($pengikut AS $pengikut){
                                        echo '<option value="'.$pengikut->p_karyawan_id.'">'.$pengikut->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan Ajuan</button>
                        <a href="{!! route('ga') !!}" class="btn btn-default"><span class="fa fa-backward"></span> Kembali</a>
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
