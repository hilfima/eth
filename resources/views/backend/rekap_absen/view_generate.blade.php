@extends('layouts.appsA')



@section('content')
<style>
	html {
		box-sizing: border-box;
	}
	*,
	*:before,
	*:after {
		box-sizing: inherit;
	}
	.intro {
		max-width: 1280px;
		margin: 1em auto;
	}
	.table-scroll {
		position: relative;
		width: 100%;
		z-index: 1;
		margin: auto;
		overflow: auto;
		height: 655px;
	}
	.table-scroll table {
		width: 100%;
		min-width: 1280px;
		margin: auto;
		border-collapse: separate;
		border-spacing: 0;
	}
	.table-wrap {
		position: relative;
	}
	.table-scroll th,
	.table-scroll td {
		padding: 5px 10px;
		border: 1px solid #000;
		background: #fff;
		vertical-align: top;
	}
	.table-scroll thead th {
		background: #333;
		color: #fff;
		position: -webkit-sticky;
		position: sticky;
		top: 0;
	}
	/* safari and ios need the tfoot itself to be position:sticky also */
	.table-scroll tfoot,
	.table-scroll tfoot th,
	.table-scroll tfoot td {
		position: -webkit-sticky;
		position: sticky;
		bottom: 0;
		background: #666;
		color: #fff;
		z-index: 4;
	}

	th:first-child {
		position: -webkit-sticky;
		position: sticky;
		left: 0;
		z-index: 2;
		background: #ccc;
	}
	thead th:first-child,
	tfoot th:first-child {
		z-index: 5;
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
					<h1 class="m-0 text-dark">Rekap Absen</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Rekap Absen</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
			<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.rekapabsen') !!}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-5">
						<div class="form-group">
							<label>Periode Absen</label>
							<select class="form-control select2" name="periode_gajian" style="width: 100%;" required>
								<option value="">Pilih Periode</option>
								<?php
								foreach($periode AS $periode){
								if($periode->periode_absen_id==$periode_absen){
								echo '<option selected="selected" value="'.$periode->periode_absen_id.'">'.ucfirst($periode->tipe_periode).' | '.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
								}
								else{
								echo '<option value="'.$periode->periode_absen_id.'">'.ucfirst($periode->tipe_periode).' | '.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
								}
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="form-group">
							<label>Rekap</label>
							<select class="form-control select2" name="rekapget" style="width: 100%;" required>
								<option value="Absen">Semua Absen</option>
								<?php
								$rekaplist[]='Rekap Lembur s/ Ajuan';
								$rekaplist[]='Rekap Lembur s/ Approve';
								$rekaplist[]='Rekap Izin';
								$rekaplist[]='Rekap Perdin';
								$rekaplist[]='Rekap Cuti';
								foreach($rekaplist AS $rekaplist){
								if($rekaplist==$rekapget){
									echo '<option selected="selected" value="'.$rekaplist.'">'.$rekaplist.'</option>';
								}
								else{
									echo '<option value="'.$rekaplist.'">'.$rekaplist.'</option>';
								}
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
								<label>Entitas</label>
								<select class="form-control select2" name="filterentitas"  id="filterentitas" style="width: 100%;" >
									<option value="">Pilih Entitas</option>
									<?php
									foreach($entitas AS $entitas){
									$selected = '';
									if($entitas->m_lokasi_id==$request->filterentitas)
										$selected = 'selected';
										echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
									
									}
									?>
								</select>
							</div>
					</div><div class="col-lg-3">
						<div class="form-group">
								<label>Jabatan</label>
								<select class="form-control select2" name="filterjabatan" style="width: 100%;" >
									<option value="">Pilih Jabatan</option>
									<?php
									foreach($jabatan AS $jabatan){
									$selected = '';
									if($jabatan->m_jabatan_id==$request->filterjabatan)
										$selected = 'selected';
										echo '<option value="'.$jabatan->m_jabatan_id.'" '.$selected.'>'.$jabatan->nama.'</option>';
									
									}
									?>
								</select>
							</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<label>Departement</label>
							<select class="form-control select2" name="departemen" style="width: 100%;" >
								<option value="">Semua Departemen</option>
								<?php
								foreach($departemen AS $departemen){
									echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
								}
								?>
							</select>
						</div>
					</div>
					
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<a href="{!! route('be.rekap_absen') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
						<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
						<button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-file-excel"></span> Excel</button>
						<button type="submit" name="Cari" class="btn btn-primary" value="Generate"><span class="fa fa-file-excel"></span> Generate</button>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body" id="content">
		    <div class="text-center">
		        Mulai Generate?<br>
		        <button type="button" onclick="generate(0,<?=$request->periode_gajian;?>)" class="btn btn-primary">Generate Semua</button>
		        <button type="button" onclick="generate(1,<?=$request->periode_gajian;?>)" class="btn btn-primary">Regenerate Yang belum</button>
		    </div>
		</div>
    		</div>
		</div>
		</div>
		</div>
		<script>
		    function hitung(periode_absen_id,waktu){
	        
    		var ajaxTime= new Date().getTime();	
    		$.ajax({
    				type: 'get',
    				data: {'waktu': waktu,'periode_absen_id': periode_absen_id,'entitas':$('#filterentitas').val()},
    				url: '<?=route('be.hitung_rekap_absen_tanggal');?>',
    				dataType: 'html',
    				success: function(data){
    					$('#content').html(data);
    					if($('#generate').val()>=100){
    						
    						
    					}else{
    							var totalTime = new Date().getTime()-ajaxTime;
    							hitung(periode_absen_id,totalTime);
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
		    function generate(existing,periode_absen_id){
	
    		var ajaxTime= new Date().getTime();	
    		$.ajax({
    				type: 'get',
    				data: {'existing': existing,'periode_absen_id': periode_absen_id,'entitas':$('#filterentitas').val()},
    				url: '<?=route('be.generate_rekap_absen_tanggal');?>',
    				dataType: 'html',
    				success: function(data){
    					$('#content').html("Sedang Proses Tunggu Sebentar");
    				
    							var totalTime = new Date().getTime()-ajaxTime;
    							hitung(periode_absen_id,totalTime);
    				
    				},
    				error: function (error) {
    				    console.log('error; ' + eval(error));
    				    //alert(2);
    				}
    			});
    		
    	}
		</script>
@endsection		
		  