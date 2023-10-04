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
                            <li class="breadcrumb-item"><a href="{!! route('fe.ptest') !!}">Performance & Test</a></li>
                            <li class="breadcrumb-item active">RMIB</li>
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
                <a href="{!! route('fe.tambah_rmib') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> RMIB </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Usia Kerja</th>
                        <th>Tanggal Test</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($data))
                        @foreach($data as $rmib)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $rmib->nik !!}</td>
                                <td>{!! $rmib->nama_lengkap !!}</td>
                                <td>{!! $rmib->umur_kerja !!}</td>
                                <td>{!! date('d-m-Y', strtotime($rmib->tanggal)) !!}</td>
                                <td style="text-align: center">
                                    <a href="{!! route('fe.view_rmib',$rmib->t_rmib_id) !!}" title='lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    <!--<a href="{!! route('fe.edit_rmib',$rmib->t_rmib_id) !!}" title='Approve' data-toggle='tooltip'><span class='fa fa-check'></span></a>
                                    <a href="{!! route('fe.hapus_rmib',$rmib->t_rmib_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>-->
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
