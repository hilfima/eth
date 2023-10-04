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
                <form class="form-horizontal" method="POST" action="{!! route('fe.update_kpi_detail',[$id,$id2]) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                            <!-- text input -->
                            <div class="form-group">
                                <label>Area Kerja</label>
                                
                                <select class="form-control" id="alasan" name="area_kerja" onchange="change(this)"required="">
                                	<option value="">- Area Kerja -</option>
                                	<option value="Lainnya">Lainnya</option>
                                	<?php foreach($area as $area){
                                		
$selected = '';                                			
                                		if($area->t_kpi_area_kerja_id==$detail[0]->t_kpi_area_kerja_id)
$selected = 'selected';                                			
                                		?>
                                	
                                	<option value="<?=$area->t_kpi_area_kerja_id?>" <?=$selected?>><?=$area->nama_area_kerja?></option>
                                	<?php }?>
                                </select>
                            </div>
                            <div class="form-group" style="display: none;" id="area_kerja_kontent">                                
                            	<input class="form-control" placeholder="Area Kerja Lainnya" id="alasan" name="lainnya_area_kerja" >
                            </div>
                            
                            <div class="form-group" > 
                            	 <label>Sasaran Kerja</label>                               
                            	<textarea class="form-control" placeholder="Sasaran Kerja" id="alasan" name="sasaran_kerja" required="" ><?=$detail[0]->sasaran_kerja?></textarea>
                            </div>
                            <div class="form-group" > 
                            	 <label>Definisi</label>                               
                            	<textarea class="form-control" placeholder="Definisi" id="alasan" name="definisi"required="" ><?=$detail[0]->definisi?></textarea>
                            </div>
                            <div class="form-group row" > 
                            <div class="col-md-6" > 
                            	 <label class="">Target</label>                               
                            	<input class="form-control" placeholder="Target" id="alasan" name="target" type="text"required="" value="<?=$detail[0]->target?>">
                           
                            </div>
                            <div class="col-md-6" > 
                            	 <label>Satuan</label>                               
                            	<select class="form-control" placeholder="Satuan" id="alasan" name="satuan" required="">
                            		<option value="persen" <?php if($detail[0]->satuan=='persen') echo 'selected'?>>Persen</option>
                            		<option value="poin" <?php if($detail[0]->satuan=='poin') echo 'selected'?>>Poin</option>
                            		<option value="nominal" <?php if($detail[0]->satuan=='nominal') echo 'selected'?>>Nominal</option>
                            	</select>
                            </div>
                            </div>
                            <div class="form-group" > 
                            	 <label>Prioritas</label>                               
                            	<select class="form-control" placeholder="Area Kerja Lainnya" id="alasan" name="prioritas" required="" >
                            		<option value="">Prioritas</option>
                            		<option value="1" <?php if($detail[0]->prioritas==1) echo 'selected'?>>Sangat Rendah</option>
                            		<option value="3" <?php if($detail[0]->prioritas==3) echo 'selected'?>>Rendah</option>
                            		<option value="5" <?php if($detail[0]->prioritas==5) echo 'selected'?>>Sedang</option>
                            		<option value="7" <?php if($detail[0]->prioritas==7) echo 'selected'?>>Tinggi</option>
                            		<option value="9" <?php if($detail[0]->prioritas==9) echo 'selected'?>>Sangat Tinggi</option>
                            	</select>
                            </div>
                             <div class="card">
                            <div class="card-body">
                            	<h4>Breakdown Target</h4>
                            	<br>
                            
                       	<?php 
						$kpi = $kpi[0];
						$no = 0;
						$kpi_detail = $detail[0];
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
	                        echo "Tahun ".$i;
	                        echo '<div class="row">';
                       		for($j=$tw;$j<=$tw_akhir;$j++){
                       			$no++;$tahun = $i;
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
	                            	 }?>
							<div class="col-md-3" > 
							<div class="form-group" > 
                            	 <label>Rencana TW-<?=$j;?> Tahun <?=$i;?></label>                               
                            	<input class="form-control rencana" placeholder="Rencana" value="<?=$rencana;?>" type="text" id="alasan" name="rencana[<?=$i;?>][<?=$j;?>]" onkeyup="totalRENCANA()" required="" onkeypress="return ( 
                key == 8 ||
                key == 9 ||
                key == 13 ||
                key == 46 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105))" value="0" data-literasi="<?=$no;?>">
                            	<input class="form-control " placeholder="Rencana" value="<?=$t_kpi_pencapaian_id;?>" type="hidden" id="alasan" name="id_pencapaian[<?=$i;?>][<?=$j;?>]" >
                            </div>
                            </div>
							<?php }
							echo '</div>';
						}
                       	?>
                       	<div class="">
                       		<h4>Total Rencana</h4>
                       		<div id="totalRencanaContent"></div>
                       	</div>
                       	<div id="pesan_submit"></div>
                       	<input type="hidden" id="totalrencana">
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
    	function change_jenis(e){
			var val = e.value;
			if(val==20)
			$('#file').attr('required', true);   
			else			
			$('#file').attr('required', false);   
		}
		function countdate()
		{
			if( $("#tgl_akhirdate").val() &&  $("#tgl_awaldate").val()){
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "{!! route('fe.hitung_hari') !!}", 
				data : {'tgl_awal' : $("#tgl_awaldate").val(),'tgl_akhir' : $("#tgl_akhirdate").val(),'cuti' : $("#cuti").val()},
				type : 'get',
				dataType : 'json',
				success : function(result){
						$('#lama').val(result.count);
						//console.log("===== " + result + " =====");
					
				}
				
			});
	
	 		}
	 	}
	 	
		function countdate2(){
			var start = new Date($("#tgl_awaldate").val());
			var end = new Date($("#tgl_akhirdate").val());

//alert($("#tgl_awaldate").val());
			var loop = new Date(start);
			var count = 0;
			while(loop <= end){
			   var newDate = loop.setDate(loop.getDate() + 1);
			   loop = new Date(newDate);
			   //if()
			   count+=1;
			}
        	///alert(count);
          
            
           // $("#x_Date_Difference").val(diffDays);
       
		}
    
    </script>
@endsection
