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
                        <h1 class="m-0 text-dark">Pengajuan Lembur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('home') !!}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('fe.permit') !!}">Permit</a></li>
                            <li class="breadcrumb-item active">Lembur</li>
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
                <a href="{!! route('fe.tambah_lembur') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah Lembur </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Lembur</th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>Approve</th>
                        <th>Status Approve</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($lembur))
                        @foreach($lembur as $lembur)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $lembur->nik !!}</td>
                                <td>{!! $lembur->nama !!}</td>
                                <td>{!! $lembur->nama_lembur !!}</td>
                                <td>{!! date('d-m-Y', strtotime($lembur->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($lembur->tgl_akhir)) !!}</td>
                                <td>{!! $lembur->nama_appr !!}</td>
                                <td style="text-align: center">
                                    @if($lembur->status_appr_1==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                    @elseif($lembur->status_appr_1==2)
                                        <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('fe.lihat_lembur',$lembur->t_form_exit_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    @if($lembur->status_appr_1==3)
                                        <a href="{!! route('fe.hapus_lembur',$lembur->t_form_exit_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
