@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">KPI Tahun <?=$kpi->tahun?></h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

				<li class="nav-item pl-3">
					<a href="{!! route('be.tambah_kpi_detail',$kpi->t_kpi_id) !!}" title='Tambah'  class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Parameter Kerja</a>
				</li>
			</ul>

        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card">
       
        <!-- /.card-header -->
        <div class="card-body">
           
           
			<Style>
				.table thead tr td{
					vertical-align:middle;
					text-align:center;
					font-size: 17px;
					font-weight: 700;
				}
			</Style>
			<div style="overflow-x:auto;">
            <table id="" class="table table-bordered table-striped" >
                <thead><?php 
                    $total = 0;
                    $tahun_awal = date('Y',strtotime(date($kpi->tanggal_awal)));
                        $bulan_awal = date('m',strtotime(date($kpi->tanggal_awal)));
                        $tahun_akhir = date('Y',strtotime(date($kpi->tanggal_akhir)));
                        $bulan_akhir = date('m',strtotime(date($kpi->tanggal_akhir)));
                    for($i=$tahun_awal;$i<=$tahun_akhir;$i++){
                        $tw = 1;
                        $tw_akhir = 4;
                        if($i==$tahun_awal){
                            if(in_array($bulan_awal,array(1,2,3))){
                                $tw=1;
                            }else if(in_array($bulan_awal,array(4,5,6))){
                                $tw=2;
                            }else if(in_array($bulan_awal,array(7,8,9))){
                                $tw=3;
                            }else if(in_array($bulan_awal,array(10,11,12))){
                                $tw=4;
                            }
                        }elseif($i==$tahun_akhir){
                           if(in_array($bulan_akhir,array(1,2,3))){
                                $tw_akhir=1;
                            }else if(in_array($bulan_akhir,array(4,5,6))){
                                $tw_akhir=2;
                            }else if(in_array($bulan_akhir,array(7,8,9))){
                                $tw_akhir=3;
                            }else if(in_array($bulan_akhir,array(10,11,12))){
                                $tw_akhir=4;
                            }
                        }
                        for($j=$tw;$j<=$tw_akhir;$j++){
                            $total += 1;
                          }
                         }?>
                    <tr>
                        <td rowspan="3">No.</td>
                        <td colspan="7">INDIKATOR KINERJA</td>
                        <td colspan="<?=$total;?>">PENCAPAIAN KINERJA</td>
                        <td rowspan="3">Action</td>
                    </tr>
                    <tr>
                    
                        <td rowspan="2">AREA  KERJA</td>
                        <td colspan="2">SASARAN DAN PARAMETER</td>
                        <td rowspan="2">TARGET</td>
                        <td rowspan="2">SATUAN</td>
                        <td>PRIORITAS</td>
                        <td>BOBOT</td>
                        <?php 
                        
                        for($i=$tahun_awal;$i<=$tahun_akhir;$i++){?>
                        <td 
                            @if($i==$tahun_awal) colspan="<?php 
                                if(in_array($bulan_awal,array(1,2,3))){
                                        echo 4;
                                    }else if(in_array($bulan_awal,array(4,5,6))){
                                        echo 3;
                                    }else if(in_array($bulan_awal,array(7,8,9))){
                                        echo 2;
                                    }else if(in_array($bulan_awal,array(10,11,12))){
                                        echo 1;
                                    }?>" 
                            @elseif($i==$tahun_akhir)
                                colspan="<?php if(in_array($bulan_akhir,array(1,2,3))){
                                        echo 1;
                                    }else if(in_array($bulan_akhir,array(4,5,6))){
                                        echo 2;
                                    }else if(in_array($bulan_akhir,array(7,8,9))){
                                        echo 3;
                                    }else if(in_array($bulan_akhir,array(10,11,12))){
                                        echo 4;
                                    }?>"
                            @else
                            colspan=4
                            @endif
    ><?=$i;?></td>
                            
                        <?php }?>
                        
                    <tr>
                        <td>SASARAN KERJA
</td>
                        <td>DEFINISI</td>
                        
                        <td>'1-3-5-7-9</td>
                        <td> (%)</td>
                        <?php 
                        
                        for($i=$tahun_awal;$i<=$tahun_akhir;$i++){
                        $tw = 1;
                        $tw_akhir = 4;
                        if($i==$tahun_awal){
                        
                            if(in_array($bulan_awal,array(1,2,3))){
                                $tw=1;
                            }else if(in_array($bulan_awal,array(4,5,6))){
                                $tw=2;
                            }else if(in_array($bulan_awal,array(7,8,9))){
                                $tw=3;
                            }else if(in_array($bulan_awal,array(10,11,12))){
                                $tw=4;
                            }
                        
                            // if($kpi->triwulan_awal==1) $tw =  4;
                            // else if($kpi->triwulan_awal==2) $tw =  3;
                            // else if($kpi->triwulan_awal==3) $tw =  2;
                            // else if($kpi->triwulan_awal==4) $tw =  1;
                            
                            
                            
                        }elseif($i==$tahun_akhir){
                            if(in_array($bulan_akhir,array(1,2,3))){
                                $tw_akhir=1;
                            }else if(in_array($bulan_akhir,array(4,5,6))){
                                $tw_akhir=2;
                            }else if(in_array($bulan_akhir,array(7,8,9))){
                                $tw_akhir=3;
                            }else if(in_array($bulan_akhir,array(10,11,12))){
                                $tw_akhir=4;
                            }
                        }
                        ?>
                            
                            <?php for($j=$tw;$j<=$tw_akhir;$j++){?>
                                <td ><?php 
                                if($kpi->periode_kpi==1){
                                    echo 'Bulan';
                                }else if($kpi->periode_kpi==2){
                                    echo 'Bulan';
                                }else if($kpi->periode_kpi==3){
                                    echo 'TW';
                                }else if($kpi->periode_kpi==4){
                                    echo 'tahun';
                                }
                                ?><?=$j?>  
                                @if($kpi->status_appr_2 ==1) 
                                	<a href="{{ route('fe.edit_all_tw',[$kpi->t_kpi_id,$i,$j]) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>Edit</a>
                                @endif
                                </td>
                            
                            <?php }?>
                        <?php }?>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0;
                    // echo '<pre>';
                    // print_r($capaian);
                    ?>
                    @if(!empty($kpi_detail))
                    <?php $total_tw1=0;;?>
                    <?php $total_tw2=0;?>
                    <?php $total_tw3=0;?>
                    <?php $total_tw4=0;?>
                    @foreach($kpi_detail as $kpi_detail)
                    <?php $no++ ?>
                   
                    <tr>
                        <td>{!! $no !!}</td>
                        <td>{!! $kpi_detail->nama_area_kerja !!}</td>
                        <td>{!! $kpi_detail->sasaran_kerja !!}</td>
                        <td>{!! $kpi_detail->definisi !!}</td>
                        <td>
                            @if($kpi_detail->satuan=='nominal')
                            {!! number_format($kpi_detail->target,0,',','.') !!}
                            @else
                            {!! ($kpi_detail->target) !!}
                            @endif
                            </td>
                        <td>{!! ucwords($kpi_detail->satuan) !!}</td>
                        <td>{!! $kpi_detail->prioritas !!}</td>
                        <td>{!! round($bobot = $kpi_detail->prioritas/$kpi_detail->sum*100,2) !!}%</td>
                        <?php for($i=$tahun_awal;$i<=$tahun_akhir;$i++){
                        $tw = 1;
                        $tw_akhir = 4;
                        if($i==$tahun_awal){
                            if(in_array($bulan_awal,array(1,2,3))){
                                $tw=1;
                            }else if(in_array($bulan_awal,array(4,5,6))){
                                $tw=2;
                            }else if(in_array($bulan_awal,array(7,8,9))){
                                $tw=3;
                            }else if(in_array($bulan_awal,array(10,11,12))){
                                $tw=4;
                            }
                        }elseif($i==$tahun_akhir){
                           if(in_array($bulan_akhir,array(1,2,3))){
                                $tw_akhir=1;
                            }else if(in_array($bulan_akhir,array(4,5,6))){
                                $tw_akhir=2;
                            }else if(in_array($bulan_akhir,array(7,8,9))){
                                $tw_akhir=3;
                            }else if(in_array($bulan_akhir,array(10,11,12))){
                                $tw_akhir=4;
                            }
                        }
                          for($j=$tw;$j<=$tw_akhir;$j++){
                            	$tahun = $i;
                            	$type = $j;
                            	if(isset( $capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type])){
	                            	 	$realisasi=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['realisasi'];
	                            	 	$rencana=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['rencana'];
	                            	 	$pencapaian=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['pencapaian'];
	                            	 	$hasil=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['hasil'];
	                            	 	$appr_status=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['appr_status'];
	                            	 	$t_kpi_pencapaian_id=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['t_kpi_pencapaian_id'];
	                            	 	$ada=1;
	                            	 }else{
	                            	 	$t_kpi_pencapaian_id=0;
	                            	 	$ada=0;
	                            	 	$realisasi=0;
	                            	 	$rencana=0;
	                            	 	$pencapaian=0;
	                            	 	$hasil=0;
	                            	 	$appr_status=3;
	                            	 }
                            	?>
                                <td <?=$appr_status==3?'style="color:red"':''?>><?php
                                if($kpi_detail->satuan=='persen')
                                echo $rencana.'%';
                                else 
                                if($kpi_detail->satuan=='nominal')
                                echo number_format($rencana,0,',','.');
                                else 
                                if($kpi_detail->satuan=='poin')
                                echo $rencana. ' Poin';
                                 
                                ?></td>
                             
                            <?php }?>
                        <?php }?>
                        <td>
                        	@if($kpi->status_appr_2 ==3)
                        	<div class="d-flex">
                        		
                        <a href="{{ route('fe.edit_kpi_detail',[$id,$kpi_detail->t_kpi_detail_id]) }}" class="btn btn-primary btn-sm mr-2"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('fe.hapus_kpi_detail',[$id,$kpi_detail->t_kpi_detail_id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-trash"></i></a>
                        	</div>
                        	@endif
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