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
                        <h1 class="m-0 text-dark">Jabatan Struktural</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jabatan Struktural</li>
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
                <a href="{!! route('be.tambah_jabatan_struktural') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Jabatan Struktural </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Jabatan </th>
                        <th>Status </th>
                        <th>Jabatan Terkait</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($jabatan_struktrular))
                        @foreach($jabatan_struktrular as $jabatan_struktrular)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $jabatan_struktrular->nm_jabatan !!} ({!! $jabatan_struktrular->namaentitas !!})</td>
                                <td><?php 
                                	if($jabatan_struktrular->tipe_struktural==1) echo 'Atasan';
                                	else if($jabatan_struktrular->tipe_struktural==2) echo 'Bawahan';
                                	else if($jabatan_struktrular->tipe_struktural==3) echo 'Sejajar';
                                ?></td>
                                <td>{!! $jabatan_struktrular->jabatan_terkait !!}</td>
                                <td style="text-align: center">
                                  
                                    <a href="{!! route('be.hapus_jabatan_struktural',$jabatan_struktrular->m_jabatan_struktural_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
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
