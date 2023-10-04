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
                        <h1 class="m-0 text-dark"> <?=ucwords('Jenis Surat');?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('be.jenis_surat') !!}"><?=ucwords('Jenis Surat ');?></a></li>
                            <li class="breadcrumb-item active"> <?=ucwords('Jenis Surat');?></li>
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
                <a href="{!! route('be.tambah_jenis_surat') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  <?=ucwords('Jenis Surat');?> </a>
            </div>
            <!-- /.card-header --> 
            <div class="card-body">
            
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                      
                        <th>Nama Jenis Surat</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($jenis_surat))
                        @foreach($jenis_surat as $jenis_surat)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $jenis_surat->nama_jenis_surat !!}</td>
                                <td style="text-align: center">
                                   
                                    <a href="{!! route('be.edit_jenis_surat',$jenis_surat->m_jenis_surat_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_jenis_surat',$jenis_surat->m_jenis_surat_id) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>
                               
                            </tr>
                    @endforeach
                    @endif
                </table>
            </div>
            <?php
           // print_r($jenis_surat);
            ?>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
