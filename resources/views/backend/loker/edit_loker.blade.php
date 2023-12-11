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
                        <h1 class="m-0 text-dark">Lowongan Pekerjaan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Lowongan Pekerjaan</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_loker',$loker[0]->t_job_vacancy_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Kode Loker</label>
                                <input type="text" class="form-control" placeholder="Kode Loker ..." id="kode" name="kode" value="{!! $loker[0]->kode !!}" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" value="{!! $loker[0]->tgl_awal !!}" required/>
                                    <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="{!! $loker[0]->tgl_akhir !!}" required/>
                                    <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Lokasi</label>
                                <select class="form-control select2" name="lokasi" style="width: 100%;" required>
                                    <option value="">Pilih Lokasi</option>
                                    <?php
                                    foreach($lokasi AS $lokasi){
                                        if($lokasi->m_lokasi_id==$loker[0]->m_lokasi_id){
                                            echo '<option selected="selected" value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Departemen</label>
                                <select class="form-control select2" name="departemen" style="width: 100%;" required>
                                    <option value="">Pilih Departemen</option>
                                    <?php
                                    foreach($departemen AS $departemen){
                                        if($departemen->m_departemen_id==$loker[0]->m_departemen_id){
                                            echo '<option selected="selected" value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <select class="form-control select2" name="jabatan" style="width: 100%;" required>
                                    <option value="">Pilih Jabatan</option>
                                    <?php
                                    foreach($jabatan AS $jabatan){
                                        if($jabatan->m_jabatan_id==$loker[0]->m_jabatan_id){
                                            echo '<option selected="selected" value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Status Pekerjaan</label>
                                <select class="form-control select2" name="status" style="width: 100%;" required>
                                    <option value="">Pilih Status Pekerjaan</option>
                                    <?php
                                    foreach($status AS $status){
                                        if($status->m_status_pekerjaan_id==$loker[0]->m_status_pekerjaan_id){
                                            echo '<option selected="selected" value="'.$status->m_status_pekerjaan_id.'">'.$status->nama.'</option>';
                                        }
                                        else{
                                            echo '<option value="'.$status->m_status_pekerjaan_id.'">'.$status->nama.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tujuan Jabatan</label>
                                <textarea id="tujuan" name="tujuan" required>{!! $loker[0]->keterangan_indonesia !!}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Persyaratan</label>
                                <textarea id="persyaratan" name="persyaratan" required>{!! $loker[0]->deskripsi_indonesia !!}</textarea>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Tujuan Jabatan - Inggris</label>
                                <textarea id="tujuanrn" name="tujuanrn" required>{!! $loker[0]->keterangan_english !!}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Persyaratan - Inggris</label>
                                <textarea id="persyaratanrn" name="persyaratanen" required>{!! $loker[0]->deskripsi_english !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.loker') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
