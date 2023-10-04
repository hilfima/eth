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
                        <h1 class="m-0 text-dark">Pangkat</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Pangkat</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_pangkat') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Kode</label>
                                <input type="text" class="form-control" placeholder="Kode ..." id="kode" name="kode" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" required>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Uang Makan</label>
                                <input type="text" class="form-control" placeholder="Uang Makan ..." id="nama" name="uangmakan" required onkeypress="handleNumber(event, 'Rp {15,3}')">
                            </div>
                        </div><div class="col-sm-6">
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Uang Saku <24 jam</label>
                                <input type="text" class="form-control" placeholder="Uang Makan ..." id="nama" name="uangsaku1" required onkeypress="handleNumber(event, 'Rp {15,3}')">
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Uang Saku >24 jam</label>
                                <input type="text" class="form-control" placeholder="Uang Makan ..." id="nama" name="uangsaku2" required onkeypress="handleNumber(event, 'Rp {15,3}')">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.pangkat') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
