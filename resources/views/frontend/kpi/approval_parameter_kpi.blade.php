@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Persetujuan Parameter Capaian KPI</h4>

</div>
</div>
<style>
strong{
	font-weight: 900;
}
</style>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Approval</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
          
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Periode</th>
                        <th>Tahun </th>
                        <th>TW</th>
                        <th>Status Approval</th>
                        <th>Action </th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($kpi))
                          @foreach($kpi as $kpi)
                    <?php $no++ ?>
                    <tr>
                        <td>{!! $no !!}</td>
                        <td>{!!$kpi->nama!!}</td>
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
                        <td>{!! $kpi->tahun !!}</td>
                       
                        <td>{!! $kpi->tw !!}</td>
                        
                        <?php 
                        if($kpi->atasan_1==$id){
                            $appr = 1;
                        }else if($kpi->atasan_2==$id){
                            $appr = 2;
                        }
                        $status = 'status_appr_pengajuan'.$appr;
                        ?>
                               
	                                <td>
									@if($kpi->$status==1)
	                                        <span class="fa fa-check-circle"> Disetujui</span>
	                                        @elseif($kpi->$status==2)
	                                            <span class="fa fa-window-close"> Ditolak</span>
	                                    @else
	                                        <span class="fa fa-edit"> Pending</span>
	                                    @endif
									</td>
                                
								
                               
                                
                               
                                
                                <td style="text-align: center">
                                    <a href="{!! route('fe.kpi_review',[$kpi->t_kpi_id,'tahun_tw='.$kpi->tahun.'-'.$kpi->tw.'&appr=1']) !!}" title='Lihat' data-toggle='tooltip' class="btn btn-primary"><span class='fa fa-search'></span> Lihat</a><Br>
									
                      				          
                                </td>
                                
                                
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div> 
       
        </div>-->
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    </div>
    </div>
    </div>
    <!-- /.content-wrapper -->
@endsection
