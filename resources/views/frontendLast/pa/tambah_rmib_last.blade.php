@extends('layouts.app2')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Penilaian Kinerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item">Performance & Test</li>
                            <li class="breadcrumb-item">RMIB Grup {!! $grup !!}</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_rmib_last') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" id="nik" name="nik" value="{!! $datakar[0]->nik !!}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" id="nama" name="nama" value="{!! $datakar[0]->nama_lengkap !!}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Umur Kerja</label>
                                <input type="text" id="umur" name="umur" value="{!! $datakar[0]->umur_kerja !!}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" id="tanggal" name="tanggal" value="{!! date('d-m-Y') !!}" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <p>Tulislah dibawah ini tiga (3) macam pekerjaan yang paling ingin anda lakukan atau yang paling anda sukai (tidak harus pekerjaan yang tercantum di dalam daftar yangg ada) :</p>
                            <input type="text" id="alt1" name="alt1" class="form-control" placeholder="(1)" required>
                            <input type="text" id="alt2" name="alt2" class="form-control" placeholder="(2)" required>
                            <input type="text" id="alt3" name="alt3" class="form-control" placeholder="(3)" required>
                            <hr>
                        </div>
                    </div>
                    <input type="hidden" id="idkar" name="idkar" value="{!! $datakar[0]->p_karyawan_id !!}" class="form-control">
                    <input type="hidden" id="grup" name="grup" value="{!! $grup !!}" class="form-control">
                    <input type="hidden" id="idrmib" name="idrmib" value="{!! $idrmib !!}" class="form-control">
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <!--<a href="{!! route('fe.ptest') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>-->
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-forward"></span> Selesai</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection