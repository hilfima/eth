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
                        <h1 class="m-0 text-dark">Kegiatan Club</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Kegiatan Club</li>
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
                <a href="{!! route('be.tambah_kegiatan_club',$id) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Kegiatan Club </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Waktu </th>
                        <th>Nama Kegiatan Club </th>
                        <th>Deksripsi </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($club))
                        @foreach($club as $club)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $help->tgl_indo($club->tgl) !!}<br>
                                	{!! date('H:i',strtotime($club->jam_awal)) !!} s/d {!! date('H:i',strtotime($club->jam_akhir)) !!} 
                                </td>
                                <td>{!! $club->nama_kegiatan_club !!}</td>
                                <td>{!! $club->deskripsi !!}</td>
                               
                                <td style="text-align: center">
                                   
                                    <a href="{!! route('be.foto_kegiatan_club',[$club->club_id,$club->club_kegiatan_id]) !!}" title='foto' data-toggle='tooltip'><span class='fa fa-picture-o'></span></a>
                                    <a href="{!! route('be.hapus_anggota_club',[$club->club_id,$club->club_kegiatan_id]) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
