@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Penilaian KPI</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

			
			</ul>

        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card">
       
        <!-- /.card-header -->
        <div class="card-body">
           

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Jenis Penilaian</th>
                        <th>Total Point </th>
                       
                        <th>Periode </th>
                      
                        <th>Action </th>
                    </tr>
                    
                </thead>
                <tbody>
                    <?php $no = 0 ?>
                    @if(!empty($kpi))
                    @foreach($kpi as $kpi)
                    <?php $no++ ?>
                    <tr>
                        <td>{!! $no !!}</td>
                        <td>{!! $kpi->nama !!}</td>
                        <td>{!! $kpi->nama_penilaian !!}</td>
                        <td>{!! $kpi->point !!}</td>
                        <td>Periode :<?php 
                        if($kpi->periode_kpi==1){
                            echo 'Costum';
                        }else if($kpi->periode_kpi==2){
                            echo 'Bulanan';
                        }else if($kpi->periode_kpi==3){
                            echo 'Triwulan';
                        }else if($kpi->periode_kpi==4){
                            echo 'Tahunan';
                        }
                        
                        echo $kpi->tanggal_awal.' s/d '.$kpi->tanggal_akhir;
                        ?></td>
                        
                        <td style="text-align: center; display: flex;">
                           
                            
                            <div class="d-flex">
                            	
                            <a href="{!! route('fe.form_penilaian_kpi',$kpi->t_kpi_penilaian_karyawan_id) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 mr-2 add-person ctm-btn-padding btn-sm" title='Edit' data-toggle='tooltip'><span class='fa fa-edit'></span><?php if($kpi->penilaian==1){?> Beri Penilaian<?php }else{ echo 'Lihat';}?></a>
                           <!---->
                            </div>
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