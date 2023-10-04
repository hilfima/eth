@extends('layouts.app2')

<link href="{!! asset('gallery/css/custom.css') !!}" rel="stylesheet">

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Informasi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item active">Informasi</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Image/File</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($fasilitas))
                        @foreach($fasilitas as $fasilitas)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $fasilitas->nama !!}</td>
                                <td>{!! $fasilitas->file !!}</td>
                                <td style="text-align: center">
                                    <a href="{!! route('fe.download_fasilitas',$fasilitas->m_fasilitas_id) !!}" title='Download' data-toggle='tooltip'><span class='fa fa-download'></span></a>
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

<script src="{!! asset('gallery/js/jquery.min.js') !!}"></script>
<script src="{!! asset('gallery/js/bootstrap.min.js') !!}"></script>
