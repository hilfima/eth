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
                        <h1 class="m-0 text-dark">Jam Kerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jam Kerja</li>
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
                
                <a href="{!! route('be.tambah_jam_kerja') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Jam Kerja Satuan</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir </th>
                        <th>Entitas</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($jam_kerja))
                        @foreach($jam_kerja as $jam_kerja)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! date('d-m-Y',strtotime($jam_kerja->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y',strtotime($jam_kerja->tgl_akhir)) !!}</td>
                                <td>{!! $jam_kerja->nama !!}</td>
                                <td>{!! $jam_kerja->jam_masuk !!}</td>
                                <td>{!! $jam_kerja->jam_keluar !!}</td>
                                <td>{!! $jam_kerja->keterangan !!}</td>
                                
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_jam_kerja',$jam_kerja->absen_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_jam_kerja',$jam_kerja->absen_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
