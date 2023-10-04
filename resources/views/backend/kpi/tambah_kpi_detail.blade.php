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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_kpi_detail',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                            <!-- text input -->
                            <div class="form-group">
                                <label>Area Kerja</label>
                                
                                <input class="form-control" id="alasan" name="area_kerja" required="">
                                	
                            </div>
                           
                            
                            <div class="form-group" > 
                            	 <label>Sasaran Kerja</label>                               
                            	<textarea class="form-control" placeholder="Sasaran Kerja" id="alasan" name="sasaran_kerja" required="" ></textarea>
                            </div>
                            <div class="form-group" > 
                            	 <label>Definisi</label>                               
                            	<textarea class="form-control" placeholder="Definisi" id="alasan" name="definisi"required="" ></textarea>
                            </div>
                            <div class="form-group row" > 
                            <div class="col-md-6" > 
                            	 <label class="">Target</label>                               
                            	<input class="form-control" placeholder="Target" id="alasan" name="target" type="number"required="">
                           
                            </div>
                            <div class="col-md-6" > 
                            	 <label>Satuan</label>                               
                            	<select class="form-control" placeholder="Satuan" id="alasan" name="satuan" required="">
                            		<option value="persen">Persen</option>
                            		<option value="poin">Poin</option>
                            	</select>
                            </div>
                            </div>
                            <div class="form-group" > 
                            	 <label>Prioritas</label>                               
                            	<select class="form-control" placeholder="Area Kerja Lainnya" id="alasan" name="prioritas" required="" >
                            		<option value="">Prioritas</option>
                            		<option value="1">Sangat Rendah</option>
                            		<option value="3">Rendah</option>
                            		<option value="5">Sedang</option>
                            		<option value="7">Tinggi</option>
                            		<option value="9">Sangat Tinggi</option>
                            	</select>
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
@endsection
