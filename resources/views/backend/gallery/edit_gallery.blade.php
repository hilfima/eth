@extends('layouts.appsA')

<link href="{!! asset('gallery/css/bootstrap.css') !!}" rel="stylesheet">
<link href="{!! asset('gallery/css/jasny-bootstrap.css') !!}" rel="stylesheet">
<link href="{!! asset('gallery/css/custom.css') !!}" rel="stylesheet">

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
    <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Gallery</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Gallery</li>
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
                <h1>Upload</h1>
                <form method="POST" action="{!! route('be.update_gallery',$gallery[0]->gallery_id) !!}"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                            @if(empty($gallery))
                                <img data-src="holder.js/100%x100%" alt="">
                            @else
                                <img data-src="holder.js/100%x100%" src="{!! asset('dist/img/gallery/'.$gallery[0]->nama) !!}" alt="">
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div>
                            <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="foto" required></span>
                            <input type="submit" class="btn btn-primary" name="submit" value="Upload">
                            <a href="{!! route('be.gallery') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

<script src="{!! asset('gallery/js/jquery.min.js') !!}"></script>
<script src="{!! asset('gallery/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('gallery/js/jasny-bootstrap.js') !!}"></script>