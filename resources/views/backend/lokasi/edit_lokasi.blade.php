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
                        <h1 class="m-0 text-dark">Entitas</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Entitas</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_lokasi',$lokasi[0]->m_lokasi_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Kode</label>
                                <input type="text" class="form-control" placeholder="Kode ..." id="kode" name="kode" value="{!! $lokasi[0]->kode !!}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $lokasi[0]->nama !!}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" placeholder="Email ..." id="email" name="email" value="{!! $lokasi[0]->email !!}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>No. Telp</label>
                                <input type="text" class="form-control" placeholder="No. Telp ..." id="no_telp" name="no_telp" value="{!! $lokasi[0]->no_telp !!}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" class="form-control" placeholder="Alamat ..." id="alamat" name="alamat" value="{!! $lokasi[0]->alamat !!}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fax</label>
                                <input type="text" class="form-control" placeholder="Fax ..." id="fax" name="fax" value="{!! $lokasi[0]->fax !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>WhatApps</label>
                                <input type="text" class="form-control" placeholder="WhatApps +62..." id="wa" name="wa" value="{!! $lokasi[0]->wa !!}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" placeholder="Title ..." id="title" name="title" value="{!! $lokasi[0]->title !!}">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.lokasi') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Ubah</button>
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
