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
					<h1 class="m-0 text-dark">Revisi Data Generate Gaji</h1>
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
			<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.revisi_data_generate',$id_generate) !!}">
				{{ csrf_field() }}
				<div class="row">
				
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Generate Data </label>
                                <select class="form-control select2" name="generate" style="width: 100%;"  required>
                                    <?php 
                                    
                                    foreach($generate AS $periode){
                                        if($periode->prl_generate_id==$id_generate){
                                            echo '<option selected="selected" value="'.$periode->prl_generate_id.'">'.$periode->tipe.' - Periode: '.$periode->tahun_gener.' Bulan: '.$periode->bulan_gener.' | Absen:'.$periode->tgl_awal.' - '.$periode->tgl_akhir.' | Lembur:'.$periode->tgl_awal_lembur.' - '.$periode->tgl_akhir_lembur.'</option>';
                                        }
                                        
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Karyawan </label>
                                <select class="form-control select2" name="karyawan" style="width: 100%;"  required>
                                    <option value="">Pilih</option>
                                    <?php foreach($karyawan as $karyawan){
                                    	$visible = true;
                                    	if($karyawan->pajak_onoff){
											
                                    	 if($sudah_appr[$karyawan->m_lokasi_id][$karyawan->pajak_onoff] ){
                                    		$visible = false;
										 }
										 }	
										 if($visible){
										 	
                                    ?>
                                   		<option value="<?=$karyawan->p_karyawan_id;?>"><?=$karyawan->nama;?></option>
                                    <?php }?>
                                    <?php }?>
                                    
                                </select>
                            </div>
                        </div> 
				</div>
				<div>
					<button type="submit" class="btn btn-primary">Revisi Data</button>
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
	
</script>
@endsection
