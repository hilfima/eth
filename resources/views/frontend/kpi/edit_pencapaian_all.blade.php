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
<h4 class="card-title float-left mb-0 mt-2">Tambah Parameter</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                @if(!count($kpi_detail))
                	Data parameter capaian sudah terapprove semua
                @else
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_kpi_pencapaian_all',[$id,$tahun,$type]) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                            <!-- text input -->
                            <?php foreach($kpi_detail as $kpi_detail){?>
                            
                            <div class="card ">
                            <div class="card-body row">
                            	<div class="col-md-6">
                            	<div class="">
                            		
	                            <div class="form-group " > 
	                            	 <label>Area Kerja</label>                               
	                            	 <textarea class="form-control" placeholder="Area kerja" id="alasan" name="sasaran_kerja" readonly="" >{{$kpi_detail->nama_area_kerja}}</textarea>
	                            </div>
                            	</div>
                            	<div class="">
		                            <div class="form-group" > 
		                            	 <label>Sasaran Kerja</label>                               
		                            	 <textarea class="form-control" placeholder="Sasaran Kerja" id="alasan" name="sasaran_kerja" readonly="" >{{$kpi_detail->sasaran_kerja}}</textarea>
		                            </div>
                            	</div>
                            	<div class="">
		                            <div class="form-group" > 
		                            	 <label>Definisi</label>                               
		                            	<textarea class="form-control" placeholder="Definisi" id="alasan" name="definisi"readonly="" >{{$kpi_detail->definisi}}</textarea>
		                            </div>
	                            </div>
	                            </div>
	                            
	                            <div class="col-md-6">
	                            <div class="">
	                            	<div class="form-group " > 
	                            	 <label class="">Rencana</label>     
	                            	 <?php 
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
	                            	 $inline = $appr_status==3?'':'readonly';
	                            	 $hasil_akhir=0;
	                            	 $tahun_before=$tahun;
	                            	 $type_before=$type-1;
	                            	 if($type==1){
	                            	 	$type_before=4;
	                            	 	$tahun_before=$tahun-1;
	                            	 	
	                            	 }
	                            	  if(isset( $capaian[$kpi_detail->t_kpi_detail_id][$tahun_before][$type_before])){
	                            	  	 $hasil_akhir = $capaian[$kpi_detail->t_kpi_detail_id][$tahun_before][$type_before]['hasil'];
									  }
	                            	;
			?>                          
								     
	                            	<input class="form-control" placeholder="Rencana" id="rencana-<?=$kpi_detail->t_kpi_detail_id?>" name="rencana[<?=$kpi_detail->t_kpi_detail_id?>]" type="number" required="" <?=$inline?> value="<?=$rencana?>" onkeyup="change_pencapaian(<?=$kpi_detail->t_kpi_detail_id?>)" readonly>
	                            	<input  type="hidden" name="id[]" value="<?=$kpi_detail->t_kpi_detail_id?>" />
	                            	<input  type="hidden" name="ada[<?=$kpi_detail->t_kpi_detail_id?>]" value="<?=$ada?>" />
	                            	<input  type="hidden" name="capai_id[<?=$kpi_detail->t_kpi_detail_id?>]" value="<?=$t_kpi_pencapaian_id?>" />
	                           
	                            </div>
	                            </div>
	                           
	                            <div class="">
	                            	<div class="form-group " > 
	                            	 <label class="">Realisasi</label>                               
								     
	                            	<input class="form-control" placeholder="Realisasi" id="realisasi-<?=$kpi_detail->t_kpi_detail_id?>"  value="<?=$realisasi?>" name="realisasi[<?=$kpi_detail->t_kpi_detail_id?>]" <?=$inline?> type="number" required="" onkeyup="change_pencapaian(<?=$kpi_detail->t_kpi_detail_id?>)">
	                            	
	                            </div>
	                            </div>
	                            <div class="">
	                            	<div class="form-group " > 
	                            	 <label class="">Pencapaian</label>                               
								     
	                            	<input class="form-control" placeholder="Pencapaian" id="pencapaian-<?=$kpi_detail->t_kpi_detail_id?>" value="<?=$pencapaian?>" name="pencapaian[<?=$kpi_detail->t_kpi_detail_id?>]" <?=$inline?> type="text" required="" onkeyup="change_pencapaian(<?=$kpi_detail->t_kpi_detail_id?>)">
	                            	
	                            </div>
	                            </div><div class="">
	                            	<div class="form-group " > 
	                            	 <label class="">Hasil Akhir</label>                               
								     
	                            	<input class="form-control" placeholder="Pencapaian" id="hasil-sebelumnya-<?=$kpi_detail->t_kpi_detail_id?>"  value="<?=$hasil_akhir?>"  type="hidden" >
	                            	<input class="form-control" <?=$inline?> placeholder="Pencapaian" id="hasil-<?=$kpi_detail->t_kpi_detail_id?>" value="<?=$hasil?>" name="hasil[<?=$kpi_detail->t_kpi_detail_id?>]" type="text" required="" onkeyup="change_pencapaian(<?=$kpi_detail->t_kpi_detail_id?>)">
	                           
	                            </div>
	                            </div>
	                            </div>
                            </div>
                            </div>
                            <?php }?>
                            
                            <div class="col-md-12">
                            	<div class="form-group">
                            		<label class="">Approval Atasan</label>  
                            		<select class="form-control select2" name="atasan" style="width: 100%;" required="">
                                    <option value="">Pilih Atasan</option>
                                    <?php
                                    foreach($appr AS $appr1){
                                        echo '<option value="'.$appr1->p_karyawan_id.'">'.$appr1->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                            	</div>
                            </div>
                            	</div>
                            	
                            
                       
                    </div>
                    <script>
                    	function change(e){
                    		if($(e).val()=='Lainnya'){
                    			$('#area_kerja_kontent').show();	
                    		}else{
                    			$('#area_kerja_kontent').hide();	
                    			
                    		}
                    	}
                    </script>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.kpi_detail',$id) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
	    function change_pencapaian(id){
	    		rencana = parseInt($('#rencana-'+id).val());
	    		realisasi = parseInt($('#realisasi-'+id).val());
	    		hasilS = parseFloat($('#hasil-sebelumnya-'+id).val());
	    		pencapaian = parseFloat(realisasi/rencana*100);
	    		$('#pencapaian-'+id).val(pencapaian);
	    		$('#hasil-'+id).val(pencapaian+hasilS);
	    		
	    		
	    }
    </script>
    @endif
@endsection
