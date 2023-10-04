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
                        <h1 class="m-0 text-dark">Shift Kerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Shift Kerja</li>
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
                
                <a href="{!! route('be.tambah_shift') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Shift Kerja Satuan</a>
                <a href="{!! route('be.tambah_shift_excel') !!}" title='Tambah Excel' data-toggle='tooltip'><span class='fa fa-plus'></span> Excel</a>
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
                    @if(!empty($shift))
                        @foreach($shift as $shift)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! date('d-m-Y',strtotime($shift->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y',strtotime($shift->tgl_akhir)) !!}</td>
                                <td>{!! $shift->nama !!}</td>
                                <td>{!! $shift->jam_masuk !!}</td>
                                <td>{!! $shift->jam_keluar !!}</td>
                                <td>{!! $shift->keterangan !!}</td>
                                
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_shift',$shift->absen_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_shift',$shift->absen_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
