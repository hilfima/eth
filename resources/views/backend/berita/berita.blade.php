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
                        <h1 class="m-0 text-dark">Berita</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Berita</li>
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
                <a href="{!! route('be.tambah_berita') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Berita </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Judul </th>
                        <th>Deskripsi </th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($berita))
                        @foreach($berita as $berita)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $berita->judul !!}</td>
                                <td>{!! $berita->deskripsi !!}</td>
                                <td>{!! date('Y-m-d',strtotime($berita->tgl_posting)) !!}</td>
                                <td>{!! date('Y-m-d',strtotime($berita->tgl_posting_akhir)) !!}</td>
                                <td style="text-align: center">
                                    @if($berita->active==1)
                                        <span class="fa fa-check-circle"></span>
                                    @else
                                        <span class="fa fa-window-close"></span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_berita',$berita->hr_care_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_berita',$berita->hr_care_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
