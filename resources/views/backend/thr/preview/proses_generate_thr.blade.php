@extends('layouts.appsA')



@section('content')
<style>
	.trr {
		background-color: #0099FF;
		color: #ffffff;
		align: center;
		padding: 10px;
		height: 20px;
	}
	
	tr.odd > td {
		background-color: #E3F2FD;
	}

	tr.even > td {
		background-color: #BBDEFB;
	}
	.fixedTable .table {
  background-color: white;
  width: auto;
  display: table;
}
.fixedTable .table tr td,
.fixedTable .table tr th {
  min-width: 100px;
  width: 100px;
  min-height: 20px;
  height: 20px;
  padding: 5px;
  max-width: 100px;
}
.fixedTable-header {
  width: 100%;
  height: 60px;
  /*margin-left: 150px;*/
  overflow: hidden;
  border-bottom: 1px solid #CCC;
}
.fixedTable-sidebar {
  width: 0;
  height: 510px;
  float: left;
  overflow: hidden;
  border-right: 1px solid #CCC;
}
@media screen and (max-height: 700px) {
 .fixedTable-body {
  overflow: auto;
  width: 100%;
  height: 410px;
  float: left;
}
}
@media screen and (min-height: 700px) {
 .fixedTable-body {
  overflow: auto;
  width: 100%;
  height: 510px;
  float: left;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Proses Generate</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Proses Generate</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-body">
			
			
				<h3>Proses Generate THR</h3>
				<div class="">
				<strong>THR Tahun </strong>: <?=$generate[0]->tahun;?><br>
				<strong>Tanggal Patokan</strong>: <?=$help->tgl_indo($generate[0]->tgl_patokan);?>
				
				<div class="row">
				<div class="col-sm-3">
                            <div class="form-group">
                                <label>Entitas</label>
                                    
                                <select class="form-control select2" name="periode_gajian" id="entitas" style="width: 100%;" required 	>
                                   
                                    <option value="All">Semua Entitas</option>
                                    <?php foreach($entitas as $entitas){?>
                                    	<option value="<?=$entitas->m_lokasi_id;?>"><?=$entitas->nama;?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
				<div class="col-sm-3">
                            <div class="form-group">
                                <label>Periode Gajian</label>
                                <select class="form-control select2" name="periode_gajian" id="periode_gajian" style="width: 100%;" required>
                                   
                                    <option value="All">Semua</option>
                                    <option value="1">Bulanan</option>
                                    <option value="0">Pekanan</option>
                                </select>
                            </div>
                        </div>
				</div>
				
				
				</div>
				<div class="" id="button">
					<button type="button" name="Cari" class="btn btn-primary" value="Cari" onclick="getkaryawandata()"><span class="fa fa-search"></span> Generate</button>
					<button type="button" name="Cari" class="btn btn-primary" value="Cari" onclick="hitung(1000,'All','All')"><span class="fa fa-search"></span> Generate Yang belum</button>
					<button type="button" name="Cari" class="btn btn-primary" value="Cari" onclick="lihat_hasil()"><span class="fa fa-search"></span> Lihat Hasil</button>
					
				</div>
				</div>
				
			
		</div>
		<div class="col-sm-12" id="contentGenerate">
		</div>
		
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js">
</script><script>
function getkaryawandata(time){
		//alert();
		var periode_gajian = $('#periode_gajian').val();
		var entitas = $('#entitas').val();
		var ajaxTime= new Date().getTime();	
		$.ajax({
				type: 'get',
				data: {'periode_gajian': periode_gajian,'entitas': entitas},
				url: '<?=route('be.getkaryawanprosesgenerate_thr',$id);?>',
				dataType: 'html',
				success: function(data){
					hitung(1000,entitas,periode_gajian);
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
		
	}function lihat_hasil(){
		
		var periode_gajian = $('#periode_gajian').val();
		var entitas = $('#entitas').val();
		window.location.href = "<?=route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id . '&menu=Gaji&Cari=Cari']);?>&entitas="+entitas+"&periode_gajian="+periode_gajian;
	}
	function hitung(time,entitas,periode_gajian){
		//alert();
		var ajaxTime= new Date().getTime();	
		$.ajax({
				type: 'get',
				//data: {'id': id},
				data: {'time': time,'periode_gajian': periode_gajian,'entitas': entitas},
				
				url: '<?=route('be.hitung_thr',$id);?>',
				dataType: 'html',
				success: function(data){
					$('#contentGenerate').html(data);
					if($('#generate').val()>=100){
						
						
					}else{
							var totalTime = new Date().getTime()-ajaxTime;
							hitung(totalTime,entitas,periode_gajian);
					}
					//var tgl_cicilan = myDate.addMonths(cicilan);
						
					
					
				    //console.log(data);
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
		
	}
	
	$(document).ready(function(){
  		//hitung();
  		//setInterval(hitung, 300);
  		
	});
</script>
@endsection
