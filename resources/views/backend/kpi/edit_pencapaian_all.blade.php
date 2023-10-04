@extends('layouts.appsA')

@section('content')
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_kpi_pencapaian_all',[$id,$type]) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                            <!-- text input -->
                            <div class="row">
                            <?php foreach($kpi_detail as $kpi_detail){?>
                            	<div class="col-md-3">
                            		
	                            <div class="form-group " > 
	                            	 <label>Area Kerja</label>                               
	                            	 <textarea class="form-control" placeholder="Area kerja" id="alasan" name="sasaran_kerja" readonly="" >{{$kpi_detail->nama_area_kerja}}</textarea>
	                            </div>
                            	</div>
                            	<div class="col-md-3">
		                            <div class="form-group" > 
		                            	 <label>Sasaran Kerja</label>                               
		                            	 <textarea class="form-control" placeholder="Sasaran Kerja" id="alasan" name="sasaran_kerja" readonly="" >{{$kpi_detail->sasaran_kerja}}</textarea>
		                            </div>
                            	</div>
                            	<div class="col-md-3">
		                            <div class="form-group" > 
		                            	 <label>Definisi</label>                               
		                            	<textarea class="form-control" placeholder="Definisi" id="alasan" name="definisi"readonly="" >{{$kpi_detail->definisi}}</textarea>
		                            </div>
	                            </div>
	                            <div class="col-md-3">
	                            	<div class="form-group " > 
	                            	 <label class="">Pencapaian</label>                               
								     <?php 
								     	$row = 'pencapaian_tw'.$type;
								     ?>
	                            	<input class="form-control" placeholder="Pencapaian" id="alasan" name="pencapaian[<?=$kpi_detail->t_kpi_detail_id?>]" type="number" required="" value="{{$kpi_detail->$row}}">
	                            	<input  type="hidden" name="id[]" value="<?=$kpi_detail->t_kpi_detail_id?>" />
	                           
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
                        <a href="{!! route('be.kpi_detail',$id) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
				url : "{!! route('be.hitung_hari') !!}", 
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
    @endif
@endsection
