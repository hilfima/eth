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
                        <h1 class="m-0 text-dark">Foto Kegiatan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Foto Kegiatan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
            <div class="card-body">
           
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_foto_kegiatan_club',[$id,$id_kegiatan]) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                   
                        
                        <div class="form-group">
                        	<label>Nama Kegiatan Foto Kegiatan</label>
                            <input class="form-control "  type="file" name="file[]"  multiple="" placeholder="Nama Kegiatan Foto Kegiatan">
                        </div>
                        
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.club') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
