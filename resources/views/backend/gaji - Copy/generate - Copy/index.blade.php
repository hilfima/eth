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
                        <h1 class="m-0 text-dark"> <?=ucwords('Generate Gaji');?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#"><?=ucwords('Generate Gaji');?></a></li>
                            <li class="breadcrumb-item active"> <?=ucwords('Generate Gaji');?></li>
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
                <a href="{!! route('be.tambah_generate') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span>  <?=ucwords('Generate Gaji');?> </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                      
                        <th>Periode Gaji</th>
                        <th>Type Gaji</th>
                        <th>Periode Absen</th>
                        <th>Periode Lembur</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($generate))
                        @foreach($generate as $generate)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $generate->bulan !!} - {!! $generate->tahun !!}</td>
                                <td>{!! $generate->tipe !!}</td>
                                <td>{!! $generate->tgl_awal_absen !!} - {!! $generate->tgl_akhir_absen !!}</td>
                                <td>{!! $generate->tgl_awal_lembur !!} - {!! $generate->tgl_akhir_lembur !!}</td>
                                <td style="text-align: center">
								<?php if(!$generate->appr_status){?>
								
                                    <a href="{!! route('be.regenerate',$generate->prl_generate_id) !!}" class="btn btn-primary btn-sm" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span> Regenerate</a> 
									<a href="{!! route('be.regenerate_pajak',$generate->prl_generate_id) !!}" class="btn btn-primary btn-sm" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span> Regenerate Pajak</a>
									<a href="{!! route('be.regenerate_field',$generate->prl_generate_id) !!}" class="btn btn-primary btn-sm" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span> Regenerate Field</a>
                                    <a href="{!! route('be.revisi_generate',$generate->prl_generate_id) !!}" class="btn btn-primary btn-sm" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span> Revisi Data</a>
                                   
                                    <a href="{!! route('be.nonaktifgenerate',$generate->prl_generate_id) !!}"class="btn btn-primary btn-sm"  title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span> Non Aktifkan</a>
								<?php }?>
                                </td>
                               
                            </tr>
                    @endforeach
                    @endif
                </table>
            </div>
            <?php
           // print_r($generate);
            ?>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
