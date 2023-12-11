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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_loker') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Kode Loker</label>
                                <input type="text" class="form-control" placeholder="Kode Loker ..." id="kode" name="kode" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" required/>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" required/>
                                   
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
                                        echo '<option value="'.$lokasi->m_lokasi_id.'">'.$lokasi->nama.'</option>';
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
                                        echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
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
                                        echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama.'</option>';
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
                                        echo '<option value="'.$status->m_status_pekerjaan_id.'">'.$status->nama.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Brosur/Pamplet</label>
                                <input type="file" class="form-control " name="file" style="width: 100%;" required>
                                    
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea id="tujuan" name="tujuan" class="form-control " required></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Persyaratan</label>
                                <textarea id="persyaratan" name="persyaratan" class="form-control " required></textarea>
                            </div>
                        </div> <div class="col-sm-6">
                            <div class="form-group">
                                <label>Deskripsi -  Ingris</label>
                                <textarea id="tujuanen" name="tujuanen" class="form-control "></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Persyaratan -  Ingris</label>
                                <textarea id="persyaratanen" name="persyaratanen" class="form-control " ></textarea>
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
