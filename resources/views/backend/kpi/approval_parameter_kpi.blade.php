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
                        <th>Tahun</th>
                        <th>Sasaran kerja</th>
                        <th>Definisi </th>
                        <th>Target</th>
                        <th>Satuan</th>
                        <th>Pencapaian</th>
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
                        <td>{!! $kpi->tahun !!}</td>
                        <td>{!! $kpi->sasaran_kerja !!}</td>
                        <td>{!! $kpi->definisi !!}</td>
                        <td>{!! $kpi->target !!}</td>
                        <td>{!! $kpi->satuan !!}</td>
                        <td><b style="font-weight: bold; color:red "><?php $tw = 'pencapaian_tw'.$kpi->tw_ke;?>{!! $kpi->$tw !!}</b></td>
                               
	                                <td>
									@if($kpi->appr_status==1)
	                                        <span class="fa fa-check-circle"> Disetujui</span>
	                                        @elseif($kpi->appr_status==2)
	                                            <span class="fa fa-window-close"> Ditolak</span>
	                                    @else
	                                        <span class="fa fa-edit"> Pending</span>
	                                    @endif
									</td>
                                
								
                               
                                
                               
                                
                                <td style="text-align: center">
                                    <a href="{!! route('fe.kpi_detail',$kpi->t_kpi_id) !!}" title='Lihat' data-toggle='tooltip' class="btn btn-primary"><span class='fa fa-search'></span> Lihat</a><Br>
									
                      				          
                                </td>
                                
                                <td style="text-align: center;">
                                   <div class="d-flex">
									@if($kpi->appr_status==3)
                                        <a href="{!! route('fe.acc_kpi_parameter',[$kpi->t_kpi_detail_id,$kpi->t_kpi_appr_id,$kpi->tw_ke]) !!}" class="btn btn-success btn-sm"  title='Approve - 1 ' data-toggle='tooltip'> 
                                    	Approve  
                                    </a><a href="{!! route('fe.dec_kpi_parameter',[$kpi->t_kpi_detail_id,$kpi->t_kpi_appr_id,$kpi->tw_ke]) !!}" class="btn btn-danger btn-sm" title='Tolak - 1' data-toggle='tooltip'> 
                                    	Tolak    
                                    </a> 
                                    @endif
                                   </div>
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
