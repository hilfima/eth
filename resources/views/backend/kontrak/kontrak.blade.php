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
                        <h1 class="m-0 text-dark">Kontrak</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Kontrak</li>
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
                <a href="{!! route('be.tambah_kontrak') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Kontrak </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Departemen</th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($kontrak))
                        @foreach($kontrak as $kontrak)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $kontrak->nik !!}</td>
                                <td>{!! $kontrak->nama !!}</td>
                                <td>{!! $kontrak->nmdivisi !!}</td>
                                <td>{!! $kontrak->nmdept !!}</td>
                                <td>{!! date('d-m-Y',strtotime($kontrak->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y',strtotime($kontrak->tgl_akhir)) !!}</td>
                                <td>{!! $kontrak->file_kontrak_kerja?'<a href="'.url('dist/img/file/'.$kontrak->file_kontrak_kerja).'" download>View File</a>':''!!}</td>
                                <td style="text-align: center">
                                    <a href="{!! route('be.view_kontrak',$kontrak->p_karyawan_kontrak_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    <a href="{!! route('be.tambah_kontrak',['id='.$kontrak->p_karyawan_id]) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
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