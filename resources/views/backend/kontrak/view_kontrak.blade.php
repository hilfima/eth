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
                        <h1 class="m-0 text-dark">Kontrak</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Kontrak</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_karyawan',$kontrak[0]->p_karyawan_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <h4>Data Kontrak <small> </small></h4>
                        </div>
                    </div>
                    <!-- ./row -->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-kontrak-tab" data-toggle="pill" href="#custom-tabs-one-kontrak" role="tab" aria-controls="custom-tabs-one-kontrak" aria-selected="false">Kontrak</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-one-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-one-kontrak" role="tabpanel" aria-labelledby="custom-tabs-one-kontrak-tab">
                                            <div class="row">

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>NIK</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $kontrak[0]->nik !!}" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Entitas</label>
                                                        <select class="form-control select2" name="lokasi" style="width: 100%;" disabled>
                                                            <?php
                                                            foreach($lokasi AS $lokasi){
                                                                if($lokasi->m_lokasi_id==$kontrak[0]->m_lokasi_kontrak_id){
                                                                    echo '<option selected="selected" value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                                                }
                                                                else{
                                                                    echo '<option value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jabatan/Pangkat</label>
                                                        <select class="form-control select2" name="jabatan" style="width: 100%;" disabled>
                                                            <?php
                                                            foreach($jabatan AS $jabatan){
                                                                if($jabatan->m_jabatan_id==$kontrak[0]->m_jabatan_kontrak__id){
                                                                    echo '<option selected="selected" value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.' - '.$jabatan->nmpangkat.'</option>';
                                                                }
                                                                else{
                                                                    echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.' - '.$jabatan->nmpangkat.'</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal Masuk</label>
                                                        <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input" id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" required value="{!! date('d-m-Y',strtotime($kontrak[0]->tgl_awal)) !!}" disabled/>
                                                            <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Status Pekerjaan</label>
                                                        <select class="form-control select2" name="status_pekerjaan" style="width: 100%;" disabled>
                                                            <?php
                                                            foreach($stspekerjaan AS $stspekerjaan){
                                                                if($stspekerjaan->m_status_pekerjaan_id==$kontrak[0]->m_status_pekerjaan_id){
                                                                    echo '<option selected="selected" value="'.$stspekerjaan->m_status_pekerjaan_id.'">'.$stspekerjaan->nama.'</option>';
                                                                }
                                                                else{
                                                                    echo '<option value="'.$stspekerjaan->m_status_pekerjaan_id.'">'.$stspekerjaan->nama.'</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div><div class="form-group">
                                                        <label>Kantor</label>
                                                        <select class="form-control select2" name="kantor" style="width: 100%;" disabled>
                                                            <?php
                                                            foreach($kantor AS $stspekerjaan){
                                                                if($stspekerjaan->m_office_id==$kontrak[0]->m_kantor_kontrak__id){
                                                                    echo '<option selected="selected" value="'.$stspekerjaan->m_office_id.'">'.$stspekerjaan->nama.'</option>';
                                                                }
                                                                else{
                                                                    echo '<option value="'.$stspekerjaan->m_office_id.'">'.$stspekerjaan->nama.'</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                   
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select class="form-control select2" name="status" style="width: 100%;" disabled>
                                                            <option value="1" <?php if($kontrak[0]->active==1){ echo 'selected="selected" ';} ?>>Active</option>
                                                            <option value="0" <?php if($kontrak[0]->active==0){ echo 'selected="selected" ';} ?>>Non Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Nama</label>
                                                        <input type="text" class="form-control" disabled="disabled" value="{!! $kontrak[0]->nama_lengkap !!}" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Departemen</label>
                                                        <select class="form-control select2" name="departemen" style="width: 100%;" disabled>
                                                            <?php
                                                            foreach($departemen AS $departemen){
                                                                if($departemen->m_departemen_id==$kontrak[0]->m_departemen_kontrak__id){
                                                                    echo '<option selected="selected" value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
                                                                }
                                                                else{
                                                                    echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Divisi</label>
                                                        <select class="form-control select2" name="divisi" style="width: 100%;" disabled>
                                                            <?php
                                                            foreach($divisi AS $divisi){
                                                                if($divisi->m_divisi_id==$kontrak[0]->m_divisi_kontrak__id){
                                                                    echo '<option selected="selected" value="'.$divisi->m_divisi_id.'">'.$divisi->nama.'</option>';
                                                                }
                                                                else{
                                                                    echo '<option value="'.$divisi->m_divisi_id.'">'.$divisi->nama.'</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal Keluar</label>
                                                        <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                                            <input type="text" class="form-control datetimepicker-input" id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="{!! date('d-m-Y',strtotime($kontrak[0]->tgl_akhir)) !!}" disabled/>
                                                            <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="form-group">
                                                        <label>Keterangan</label>
                                                        <input type="text" class="form-control" placeholder="Keterangan..." id="keterangans" name="keterangans" value="{!! $kontrak[0]->keterangan !!}" disabled>
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
                        <a href="{!! route('be.kontrak') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
