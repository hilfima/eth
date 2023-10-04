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
                        <h1 class="m-0 text-dark">Pengajuan Izin</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('fe.permit') !!}">Permit</a></li>
                            <li class="breadcrumb-item active">Izin</li>
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
                <a href="{!! route('fe.tambah_izin') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah Izin </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Izin</th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>Approval</th>
                        <th>File</th>
                        <th>Status Approve</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($izin))
                        @foreach($izin as $izin)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $izin->nik !!}</td>
                                <td>{!! $izin->nama !!}</td>
                                <td>{!! $izin->nama_ijin !!}</td>
                                <td>{!! date('d-m-Y', strtotime($izin->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($izin->tgl_akhir)) !!}</td>
                                <td>{!! $izin->nama_appr !!}</td>
                                @if(!empty($izin->foto))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/file/'.$izin->foto) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                <td style="text-align: center">
                                    @if($izin->status_appr_1==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                    @elseif($izin->status_appr_1==2)
                                        <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('fe.lihat_izin',$izin->t_form_exit_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    @if($izin->status_appr_1==3)
                                        <a href="{!! route('fe.hapus_izin',$izin->t_form_exit_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                    @endif
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
