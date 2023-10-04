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
                        <h1 class="m-0 text-dark">Jabatan Struktural</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jabatan Struktural</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_jabatan_struktural') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <select class="form-control select2" name="jabatan" style="width: 100%;" required>
                                    <option value="">Pilih Jabatan</option>
                                    <?php
                                    foreach($jabatan AS $jabatan2){
                                        echo '<option value="'.$jabatan2->m_jabatan_id.'">'.$jabatan2->nama_jabatan.'('.$jabatan2->namaentitas.')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Status</label>
                                <select type="text" class="form-control" placeholder="Nama ..." id="nama" name="status" required>
                                	<option value="">Pilih Status</option>
                                	<option value="1">Atasan</option>
                                	<option value="2">Bawahan</option>
                                	<option value="3">Sejajar</option>
                                </select>
                            </div>
                        </div><div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jabatan Terkait</label>
                                <select class="form-control select2" name="terkait" style="width: 100%;" required>
                                    <option value="">Pilih Jabatan Terkait</option>
                                    <?php
                                    foreach($jabatan AS $jabatan){
                                        echo '<option value="'.$jabatan->m_jabatan_id.'">'.$jabatan->nama_jabatan.'('.$jabatan->namaentitas.')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                   
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.mesin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
