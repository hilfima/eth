@extends('layouts.appsA')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Input Absen</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Input Absen</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                 <a href="{!! route('be.export_absen') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Export Kehadiran </a>
                 <!--<h3 class="card-title">DataTable with default features</h3>-->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_input_absen',$karyawan[0]->no_absen) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>No. Absen</label>
                                <input type="text" class="form-control" placeholder="Nama ..." value="{!! $karyawan[0]->no_absen !!}" id="no_absen" name="no_absen" readonly>
                                <input type="hidden" class="form-control" placeholder="Mesin ..." value="{!! $karyawan[0]->mesin_id !!}" id="mesin" name="mesin">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." value="{!! $karyawan[0]->nama !!}" id="nama" name="nama" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_absen" name="tgl_absen" data-target="#tgl_absen" value="{!! date('Y-m-d') !!}"/>
                                    <div class="input-group-append" data-target="#tgl_absen" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div><div class="col-sm-3">
                            <div class="form-group">
                                <label>Jam Masuk</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="time" class="form-control timepicker-input" id="tgl_absen" name="jam_masuk" data-target="#tgl_absen" value="00:00:00" required=""/>
                                    <div class="input-group-append" data-target="#tgl_absen" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('admin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
  <script src='https://code.jquery.com/jquery-1.11.0.min.js'></script>
    <script src='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js'></script>
    <script  >
    	$("input[name=time]").clockpicker({       
  placement: 'bottom',
  align: 'left',
  autoclose: true,
  default: 'now',
  donetext: "Select",
  init: function() { 
                            console.log("colorpicker initiated");
                        },
                        beforeShow: function() {
                            console.log("before show");
                        },
                        afterShow: function() {
                            console.log("after show");
                        },
                        beforeHide: function() {
                            console.log("before hide");
                        },
                        afterHide: function() {
                            console.log("after hide");
                        },
                        beforeHourSelect: function() {
                            console.log("before hour selected");
                        },
                        afterHourSelect: function() {
                            console.log("after hour selected");
                        },
                        beforeDone: function() {
                            console.log("before done");
                        },
                        afterDone: function() {
                            console.log("after done");
                        }
});
    	
    </script>
    <!-- /.content-wrapper -->
@endsection
