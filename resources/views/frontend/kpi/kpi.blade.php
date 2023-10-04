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
            <h4 class="card-title float-left mb-0 mt-2">Key Perfomance Index</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

				<li class="nav-item pl-3">
					<a href="{!! route('fe.tambah_kpi') !!}" title='Tambah'  class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah KPI</a>
				</li>
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
                        <th>Jabatan</th>
                        <th>Tanggal Pembuatan</th>
                        <th>Periode </th>
                        <th>Atasan 1</th>
                        <th>Status Approval 1</th>
                        <th>Atasan 2</th>
                        <th>Status Approval 2</th>
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
                        <td>{!! $kpi->jabatan !!}</td>
                        <td>{!! $kpi->create_date !!}</td>
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
                        <td>{!! $kpi->appr_1 !!}</td>
                        <td style="text-align: center">
                            @if($kpi->appr_1)
                                @if($kpi->status_appr_1==1)
                                <span class="fa fa-check-circle"> Disetujui</span>
                                @elseif($kpi->status_appr_1==2)
                                <span class="fa fa-window-close"> Ditolak</span>
                                @else
                                <span class="fa fa-edit"> Pending</span>
                                @endif
                            @endif
                        </td>
                        <td>{!! $kpi->appr_2 !!}</td>
                        <td style="text-align: center">
                            @if($kpi->status_appr_2==1)
                            <span class="fa fa-check-circle"> Disetujui</span>
                            @elseif($kpi->status_appr_2==2)
                            <span class="fa fa-window-close"> Ditolak</span>
                            @else
                            <span class="fa fa-edit"> Pending</span>
                            @endif
                        </td>
                        <td style="text-align: center; display: flex;">
                           
                            <div class="d-flex">
                            	
                            <a href="{!! route('fe.edit_kpi',$kpi->t_kpi_id) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 mr-2 add-person ctm-btn-padding" title='Edit' data-toggle='tooltip'><span class='fa fa-edit'></span> Edit</a>
                            <a href="{!! route('fe.kpi_detail',$kpi->t_kpi_id) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 mr-2 add-person ctm-btn-padding" title='Detail Capaian' data-toggle='tooltip'><span class='fa fa-eye'></span> Capaian</a>
                            <a href="{!! route('fe.evaluasi_tahunan',$kpi->t_kpi_id).'?Cari=View' !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 mr-2 add-person ctm-btn-padding" title='Evaluasi Tahunan' data-toggle='tooltip'><span class='fa fa-file'></span> Evaluasi</a>
                            <a href="{!! route('fe.mentoring_kpi',$kpi->t_kpi_id).'?Cari=View' !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 mr-2  add-person ctm-btn-padding" title='Coaching & Mentoring' data-toggle='tooltip'><span class='fa fa-user '></span>Mentoring</a>
                            <a href="{!! route('fe.hapus_kpi',$kpi->t_kpi_id).'?Cari=View' !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash '></span> Hapus</a>
                            
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