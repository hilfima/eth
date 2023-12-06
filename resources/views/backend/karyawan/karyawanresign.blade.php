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
                        <h1 class="m-0 text-dark">Karyawan Resign</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Karyawan Resign</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Kantor</th>
                        <th>Divisi</th>
                        <th>Tgl Masuk</th>
                        <th>Tgl Keluar</th>
                        <th>Status Pekerjaan</th>
                        <th>Tanggal Resign</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($karyawan))
                        @foreach($karyawan as $karyawan)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $karyawan->nik !!}</td>
                                <td>{!! $karyawan->nama_lengkap !!}</td>
                                <td>{!! $karyawan->kantor !!}</td>
                                <td>{!! $karyawan->nmdivisi !!}</td>
                                <td>{!! date('d-m-Y',strtotime($karyawan->tgl_awal)) !!}</td>
                                @if(!empty($karyawan->tgl_akhir))
                                <td>{!! date('d-m-Y',strtotime($karyawan->tgl_akhir)) !!}</td>
                                @else
                                    <td></td>
                                @endif
                                <td>{!! $karyawan->nmstatus !!}</td>
                                <td>{!! $karyawan->update_date !!}</td>
                                <td> <a href="{!! route('be.view_karyawan',$karyawan->p_karyawan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    <a href="{!! route('be.aktif',$karyawan->p_karyawan_id) !!}" title='Aktifkan' data-toggle='tooltip'><span class='fa fa-check'></span></a></td>
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