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
                        <h1 class="m-0 text-dark">Grup Jabatan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Grup Jabatan</li>
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
                <a href="{!! route('be.tambah_grup_jabatan') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Grup Jabatan </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($grupjabatan))
                        @foreach($grupjabatan as $grupjabatan)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $grupjabatan->nama !!}</td>
                                <td>{!! $grupjabatan->nmlokasi !!}</td>
                                <td style="text-align: center">
                                    @if($grupjabatan->active==1)
                                        <span class="fa fa-check-circle"></span>
                                    @else
                                        <span class="fa fa-window-close"></span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.edit_grup_jabatan',$grupjabatan->m_grupjabatan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_grup_jabatan',$grupjabatan->m_grupjabatan_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
