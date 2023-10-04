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
                        <h1 class="m-0 text-dark">Lowongan Pekerjaan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Lowongan Pekerjaan</li>
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
                <a href="{!! route('be.tambah_loker') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Lowongan Pekerjaan </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode </th>
                        <th>Tgl Awal </th>
                        <th>Tgl Akhir</th>
                        <th>Lokasi</th>
                        <th>Departemen</th>
                        <th>Jabatan</th>
                        <th>Status Pekerjaan</th>
                        <th>Status Loker</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($loker))
                        @foreach($loker as $loker)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $loker->kode !!}</td>
                                <td>{!! date('Y-m-d',strtotime($loker->tgl_awal)) !!}</td>
                                <td>{!! date('Y-m-d',strtotime($loker->tgl_akhir)) !!}</td>
                                <td>{!! $loker->nmlokasi !!}</td>
                                <td>{!! $loker->nmdept !!}</td>
                                <td>{!! $loker->nmjabatan !!}</td>
                                <td>{!! $loker->nmstatus !!}</td>
                                <td style="text-align: center">
                                    @if($loker->active==1)
                                        <span class="fa fa-check-circle"></span>
                                    @else
                                        <span class="fa fa-window-close"></span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_loker',$loker->t_job_vacancy_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_loker',$loker->t_job_vacancy_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
