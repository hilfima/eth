@extends('layouts.app2')

<link href="{!! asset('gallery/css/custom.css') !!}" rel="stylesheet">

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-12">
                                <img src="{!! asset('dist/img/gallery/'.$gallery[0]->nama) !!}" class="img-thumbnail img-responsive">
                            </div>
                            <!--<div class="col-md-4">
                                <table class="table">
                                    <tr>
                                        <th width="120">Nama File</th>
                                        <td>{!! $gallery[0]->nama !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Ukuran File</th>
                                        <td>{!! $size = filesize("dist/img/gallery/".$gallery[0]->nama) !!}
                                            {!! $size = $size / 1024 / 1024 !!}
                                            {!! number_format($size, 2).' MB' !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Ekstensi File</th>
                                        <td>{!! pathinfo($gallery[0]->nama, PATHINFO_EXTENSION) !!}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><a href="{!! asset('dist/img/gallery/'.$gallery[0]->gallery_id) !!}" class="btn btn-primary btn-block" target="_blank">Download</a></td>
                                    </tr>
                                </table>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

<script src="{!! asset('gallery/js/jquery.min.js') !!}"></script>
<script src="{!! asset('gallery/js/bootstrap.min.js') !!}"></script>
