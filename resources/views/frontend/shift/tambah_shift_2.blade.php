@extends('layouts.app_fe')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side',compact('help'));?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
         <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Shift Kerja</h4>

</div>
</div>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
            <div class="card-body">
                <!-- form start -->
                   
                   
                       <div class="row">
                       <div class="col-md-6">
                       
                       <div class="form-group">
                                <label>Dari Tanggal</label>
                                <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value=""/>
								 
                        	        
                        </div>
                        </div><div class="col-md-6">
                        	<div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value=""/>
								 
                        	        
                        </div>
                        </div>
                        </div>
                        <button type="button" onclick="view_bulan()" class="btn btn-info pull-right"><span class="fa fa-check"></span> Cari</button> </div> </div>
          <div id="content"></div>
            <!-- /.card-body -->
        </div>
        <!-- /.content<select class="form-control select2" id="bulan"  style="width: 100%;" required >
                                    <option value="">Pilih Bulan</option>
                                  
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktobe</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select> -->
    </div>
    </div>
  <script>
  	function karyawan_shift(e,id){
		if($(e).is(":checked")){
			//alert(1)
			$('#kontent-tanggal-'+id).show();
		}else{
			//alert(2)
			$('#kontent-tanggal-'+id).hide();
			
		}
	}
  	function check(e){
  		if ($(e).is(':checked')) {
			$('#karyawanKonten').show();
		}else
			$('#karyawanKonten').hide();
			
		
		$('#karyawan').val('');
		changeentitas();
	}
  	function view_bulan(){
		var tgl_awal = $('#tgl_awal').val();
		var tgl_akhir = $('#tgl_akhir').val();
		
		
  		
  			$.ajax({
				type: 'get',
				
				url: '<?= route('fe.get_template_shift');?>?tgl_awal='+tgl_awal+'&tgl_akhir='+tgl_akhir,
				dataType: 'html',
				success: function(data){
					//alert(data.respon)
					$('#content').html(data);
				}
			});
		
	}function changeentitas(){
		var entitas = $('#entitas').val();
		
		$('#karyawan').val('');
  		
  			$.ajax({
				type: 'get',
				
				url: 'daftarKaryawan/'+entitas+'/',
				dataType: 'json',
				success: function(data){
					//alert(data.respon)
					$('#karyawan').html(data.respon);
				}
			});
		
	}
  	 
  </script>
    <!-- /.content-wrapper -->
@endsection
