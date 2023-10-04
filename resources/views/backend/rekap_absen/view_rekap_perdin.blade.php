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
	td {
		border: 1px solid #040404;
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
					<h1 class="m-0 text-dark">Rekap Izin</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Rekap Izin</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
			<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.rekapabsen') !!}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-6">
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
					</div><div class="col-lg-6">
						<div class="form-group">
							<label>Rekap</label>
							<select class="form-control select2" name="rekapget" style="width: 100%;" required>
								<option value="Absen">Semua Absen</option>
								<?php
								$rekaplist[]='Rekap Lembur';
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
					
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<a href="{!! route('be.rekap_absen') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
						<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
						<button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-file-excel"></span> Excel</button>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body">
		<div class="row">
		<div class="col-sm-12">
		
		
			<div class="fixedTable" id="demo">
				<header class="fixedTable-header">
					<table class="table table-bordered">
						<thead>
							<tr>
							
								<th>No </th>
								<th>Nama</th>
								<th>NIK</th>
								<th>Departemen</th>
								<?php 
								$date = $tgl_awal;
								for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
									echo ' <th>'.$date.'</th>';
									$date = $help->tambah_tanggal($date,1);
								}
								?>
                       
                        
								<th>Total</th>
							</tr>
						</thead>
					</table>
				</header>
				<aside class="fixedTable-sidebar" style="display: none">
					<table class="table table-bordered">
						<tbody>
							<?php 
							$listkaryawan = $list_karyawan;
							$no=0;?>
							@if(!empty($listkaryawan))
							@foreach($listkaryawan as $list_karyawan)
							<?php $no++ ?>
                         
							
							@endforeach
							@endif 
						</tbody> 
					</table>
				</aside>
				<div class="fixedTable-body">
				
					<table class="table table-bordered">
						<tbody>
							<?php $no=0;?>
							@if(!empty($listkaryawan))
							@foreach($listkaryawan as $list_karyawan)
							<?php $no++ ?>
                         
							<tr>
                               	<tr>
								<td>{!! $no !!}</td>
								<td>{!! $list_karyawan->nama !!}</td>
							
								<td>{!! $list_karyawan->nik !!}</td>
								<td>{!! $list_karyawan->departemen !!}</td>
								<?php 
								
								$date = $tgl_awal;
										$rekap[$list_karyawan->p_karyawan_id]['total']['cuti'] =0;
			                        
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['sakit'] = 0;
										$rekap[$list_karyawan->p_karyawan_id]['total']['alpha'] = 0;
								for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
									
									$content = "<td style='background-color: STR;SRT2'>";
									$warna = '';
									
									if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
										
										if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
											if(in_array($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'],array(4,7,12,13,14,15,16,17))){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ihk'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==1){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipg'] += 1;
										}else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['m_jenis_ijin_id']==24){
											$rekap[$list_karyawan->p_karyawan_id]['total']['ipd'] += 1;
										}	
										if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==3)
											$warna = 'green';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==4)
											$warna = 'blue';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']=='IZIN DATANG TERLAMBAT')
											$warna = 'red';
											else if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']==1)
											$warna = 'purple';
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												!= 'IZIN DATANG TERLAMBAT' and $rekap[$list_karyawan->p_karyawan_id][$date]['ci']['tipe']== 4)
														
											$content .= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'].'';	
											if($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin']
												== 'IZIN PERJALANAN DINAS'){
											
											}
										}
													
									}
										
											
									if($content=="<td style='background-color: STR;SRT2'>"){
										
								
									$warna = '';
									$content .= '</td>';
								
									}
									else{
											
										$content .= '</td>';
									} 
											
									$content = str_ireplace('STR',$warna,$content);
									 if($warna!='' and $warna!='yellow' ){
										//$content = str_ireplace('STR','orang',$content);
										$content = str_ireplace('SRT2','color: white;',$content);
											
									}
											
											
									echo $content;
									$date = $help->tambah_tanggal($date,1);
								}
								?> 
								<?php $ipd = isset($rekap[$list_karyawan->p_karyawan_id]['total']['ipd'])?$rekap[$list_karyawan->p_karyawan_id]['total']['ipd']:0;?>
								
								<td><?=$ipd;?></td>
								
							</tr>
							@endforeach
							@endif
							
						</tbody>
					</table>
				</div>
			</div>

			
		</div>
		</div>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
