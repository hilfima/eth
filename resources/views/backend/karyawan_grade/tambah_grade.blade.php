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
                        <h1 class="m-0 text-dark">Grade</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Grade</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_karyawan_grade') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                          
                            <!--<div class="form-group">
                                <label>Group</label>
                                <select class="form-control select2" name="grup" style="width: 100%" required>
                                    <option selected="selected">Pilih Group</option>
                                    <option value="I">I</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Cluster</label>
                                <select class="form-control select2" name="gradecluster" style="width: 100%;" required>
                                     <option selected="selected">Pilih Cluster</option>
                                     <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>-->
                            <div class="form-group">
                                <label>Nama Grade</label>
                                 <input type="text" class="form-control" placeholder="Nama Grade..." id="nama" name="nama"  required>
                            </div> <div class="form-group">
                                <label>Job Weight Minimum</label>
                                 <input type="number" class="form-control" placeholder="Job Weight Minimum..." id="nama" name="min" value="0" required>
                            </div> <div class="form-group">
                                <label>Job Weight Maximum</label>
                                 <input type="number" class="form-control" placeholder="Job Weight Maximum..." id="nama" name="max" value="0" required>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.grup_jabatan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
