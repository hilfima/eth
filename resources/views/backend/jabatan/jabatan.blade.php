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
                        <h1 class="m-0 text-dark"> Jabatan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active"> Jabatan</li>
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
                <a href="{!! route('be.tambah_jabatan') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  Jabatan </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Entitas</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Job Weight</th>
                        <th>Pangkat</th>
                        <th>Seksi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($jabatan))
                        @foreach($jabatan as $jabatan)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $jabatan->nmlokasi !!}</td>
                                <td>{!! $jabatan->kode !!}</td>
                                <td>{!! $jabatan->nama !!}</td>
                                <td>{!! $jabatan->job !!}</td>
                                <td>{!! $jabatan->nmpangkat !!}</td>
                                <td>{!! $jabatan->nmdepartemen !!}</td>
                                <td style="text-align: center">
                                    @if($jabatan->active==1)
                                        <span class="fa fa-check-circle"></span>
                                    @else
                                        <span class="fa fa-window-close"></span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_jabatan',$jabatan->m_jabatan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_jabatan',$jabatan->m_jabatan_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
