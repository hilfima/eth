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
                        <h1 class="m-0 text-dark">Batas Pengajuan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Batas Pengajuan</li>
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
                <a href="{!! route('be.tambah_batas_pengajuan') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Batas Pengajuan </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama </th>
                        <th>Tipe</th>
                        <th>Hari</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($batas_pengajuan))
                        @foreach($batas_pengajuan as $batas_pengajuan)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $batas_pengajuan->nama_batas !!}</td>
                                <td>{!! (($batas_pengajuan->batas_tipe)) !!}</td>
                                <td>{!! (($batas_pengajuan->batas_hari)) !!}</td>
                                
                                <td style="text-align: center">
                                    <!-- <a href="{!! route('be.edit_batas_pengajuan',$batas_pengajuan->m_batas_pengajuan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a> -->
                                    <a href="{!! route('be.hapus_batas_pengajuan',$batas_pengajuan->m_batas_pengajuan_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
