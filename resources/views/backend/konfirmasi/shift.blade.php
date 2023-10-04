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
                        <h1 class="m-0 text-dark">Konfirmasi {!!ucwords($type)!!}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Konfirmasi {!!ucwords($type)!!}</li>
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
                <a href="{!! route('be.pengajuan',$type) !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah Pergantian Shift </a>
            </div> 
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pengaju</th>
                        <th>Nama Pengganti</th>
                        <th>Tanggal Absen</th>
                        <th>Shift Awal</th>
                        <th>Tukar Shift</th>
                       
                        <th>Approve</th>
                        <th>File</th>
                        <th>Status Approve</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($data))
                        @foreach($data as $data)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data->nama_pengaju!!}</td>
                                <td>{!! $data->nama_pengganti!!}</td>
                                <td><?= date('d-m-Y', strtotime($data->tanggal_absen));?></td>
                                <td>Jam <?= date('H:i', strtotime($data->jam_masuk_pengaju));?> s/d <?= date('H:i', strtotime($data->jam_keluar_pengaju));?></td>
                                <td>Jam <?= date('H:i', strtotime($data->jam_masuk_pengganti));?> s/d <?= date('H:i', strtotime($data->jam_keluar_pengganti));?></td>
                                
                                <td>{!! $data->nama_appr !!}</td>
                                @if(!empty($data->foto))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/file/'.$data->foto) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                <td style="text-align: center">
                                    @if($data->status_appr==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($data->status_appr==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                               
                                <td style="text-align: center">
                                    <a href="{!!route('be.lihat_konfirmasi',[$data->absen_permit_id,'type='.$type])!!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
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
