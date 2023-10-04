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
                        <h1 class="m-0 text-dark"> <?=ucwords('Jenis Sanksi');?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('be.jenis_sanksi') !!}"><?=ucwords('Jenis Sanksi ');?></a></li>
                            <li class="breadcrumb-item active"> <?=ucwords('Jenis Sanksi');?></li>
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
                <a href="{!! route('be.tambah_jenis_sanksi') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  <?=ucwords('Jenis Sanksi');?> </a>
            </div>
            <!-- /.card-header --> 
            <div class="card-body">
            
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                      
                        <th>Nama Jenis Sanksi</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($jenis_sanksi))
                        @foreach($jenis_sanksi as $jenis_sanksi)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $jenis_sanksi->nama_sanksi !!}</td>
                                <td style="text-align: center">
                                   
                                    <a href="{!! route('be.edit_jenis_sanksi',$jenis_sanksi->m_jenis_sanksi_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_jenis_sanksi',$jenis_sanksi->m_jenis_sanksi_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>
                               
                            </tr>
                    @endforeach
                    @endif
                </table>
            </div>
            <?php
           // print_r($jenis_sanksi);
            ?>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
