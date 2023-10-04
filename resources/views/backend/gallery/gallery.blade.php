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
                <a href="{!! route('be.tambah_gallery') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Gallery </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama </th>
                        <th>Foto </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($gallery))
                        @foreach($gallery as $gallery)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $gallery->nama !!}</td>
                                <td><img src="{!! asset('dist/img/gallery/'.$gallery->nama) !!}" style="height: 75px"></td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_gallery',$gallery->gallery_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_gallery',$gallery->gallery_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
