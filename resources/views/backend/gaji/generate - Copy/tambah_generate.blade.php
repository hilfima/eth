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
					<h1 class="m-0 text-dark">Generate Gaji</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Generate Gaji</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-body">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
			<form id="cari_absen" class="form-horizontal" method="post" action="{!! route('be.post_generate') !!}">
				{{ csrf_field() }}
				<div class="row">
				<div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tahun</label>
                               	<input type="text" class="form-control" placeholder="Tahun ..." id="tahun" name="tahun" required 
                               	onkeyup="cariPeriode()"
                               	>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select class="form-control select2" name="bulan" style="width: 100%;" required id="bulan"	onchange="cariPeriode()">
                                    <option value="">Pilih</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div> <div class="col-sm-4">
                            <div class="form-group">
                                <label>Periode Gajian</label>
                                <select class="form-control select2" name="periode_gajian" id="periode_gajian" style="width: 100%;" required 	onchange="cariPeriode()">
                                    <option value="">Pilih</option>
                                    <option value="1">Bulanan</option>
                                    <option value="0">Pekanan</option>
                                </select>
                            </div>
                        </div><!--<div class="col-sm-6">
                            <div class="form-group">
                                <label>Entitas</label>
                                <select class="form-control select2" name="entitas" id="periode_gajian" style="width: 100%;" required 	>
                                    <option value="">Pilih</option>
                                    <option value="-1">Semua Entitas</option>
                                    <?php 
                                    foreach($lokasi as $lokasi){
                                    ?>
                                    <option value="<?=$lokasi->m_lokasi_id;?>"><?=$lokasi->nama;?></option>
                                	<?php }?>
                                </select>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Pajak On Of</label>
                                <select class="form-control " name="pajak_on_off" id="periode_gajian" style="width: 100%;" required 	>
                                    <option value="">Pilih</option>
                                    <option value="ON & OFF">ON & OFF</option>
                                    <option value="ON">ON</option>
                                    <option value="OFF">OFF</option>
                                    
                                </select>
                            </div>
                        </div>-->
					<div class="col-lg-12">
					
						<div class="form-group">
							<label>Periode Absen</label>
							<select class="form-control " name="periode_absen" id="periode_absen" style="width: 100%;" required>
								<option value="">- Isi Terlebih Dahulu Periode - </option>
							</select>
						</div><div class="form-group">
							<label>Periode Lembur</label>
							<select class="form-control " name="periode_lembur" id="periode_lembur" style="width: 100%;" required>
								<option value="">- Isi Terlebih Dahulu Periode - </option>
							</select>
						</div>
						<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Generate</button>
					</div>
				</div>
				<div class="col-sm-12" id="generatecontent	">
				</div>
			</form>
		</div>
		
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
	function cariPeriode(){
		var bulan = $('#bulan').val();	 	
		var tahun =  $('#tahun').val();	 	
		var periode_gajian =  $('#periode_gajian').val();	 
		//alert();
		if(bulan &&  tahun && periode_gajian)	{
			 $.ajax({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
	            url : "{{ route('be.cariPeriode') }}",
	            data : {'bulan' : bulan,'tahun' : tahun,'periode_gajian' : periode_gajian},
	            type : 'get',
	            dataType : 'json',
	            success : function(result){

	                //console.log("===== " + result + " =====");
	                $('#periode_absen').html(result.periode_absen)
	                $('#periode_lembur').html(result.periode_lembur)
	                $('#lokasi').html(result.lokasi)
	            }

	        });
			
		}
	}
</script>
@endsection
