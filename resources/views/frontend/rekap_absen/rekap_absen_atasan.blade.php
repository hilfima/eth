@extends('layouts.app_fe')



@section('content')
<!--<style>
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
  width:100%;
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
  /*background: #fff;*/
  vertical-align: top;
}
.table-scroll thead th {
 /* background: #333;*/
  background: #b00;
  background: #fff;
  color: #fff;
  color: #000;
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
 /* background: #666;
  color: #fff;*/
  z-index:4;
}

th:first-child {
  position: -webkit-sticky;
  position: sticky;
  left: 0;
  z-index: 2;
 /* background: #ccc;*/
  background: #dfff00;
  background: #fff;
}
thead th:first-child,
tfoot th:first-child {
  z-index: 5;
}
</style>-->
<div class="row">
			
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">	
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Tinjauan Kehadiran Tim</h4>

</div>
</div>

	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
			<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('fe.kehadiran') !!}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label>Tanggal Awal</label>
							<input class="form-control " type="date" name="tgl_awal" style="width: 100%;" required  value="<?=$tgl_awal;?>">
							</div>
					</div><div class="col-lg-6">
						<div class="form-group">
							<label>Tanggal Akhir</label>
							<input class="form-control " type="date" name="tgl_akhir" style="width: 100%;" required value="<?=$tgl_akhir;?>">
							</div>
					</div> 
					<!--<div class="col-lg-6">
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
					</div>-->
					
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<a href="{!! route('fe.kehadiran') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
						<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
						<button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-file-excel"></span> Excel</button>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body">
		<div class="row">
		<div class="col-sm-12">
		<Style>
				#ket td{
					border: 0;
				}
			</Style>
			<!--<h5>Keterangan :</h5>
			<table id="ket" border="0" style="width: 100%; border:0">
				<tr>
					<td style="background: orange;width: 100%; display: block;">&nbsp;</td>
					<td>Kesiangan</td>
					
					<td style="background: yellow;width: 100%; display: block;"><span style="background: ">&nbsp;</span></td>
					<td>TIdak Ada Absen</td>
					<td style="background: red;width: 100%; display: block;"><span style="background: ">&nbsp;</span></td>
					<td>Libur</td>
				</tr>	<tr>
					
					
					<td style="background: purple;width: 100%; display: block;"><span style="background: ">&nbsp;</span></td>
					<td>Perjalanan Dinas</td>
			
					
					<td style="background: pink;width: 100%; display: block;"><span style="background: ">&nbsp;</span></td>
					<td>IZIN IHK</td>
					<td style="background: blue;width: 100%; display: block;"><span style="background: ">&nbsp;</span></td>
					<td>IZIN IPG</td>
				</tr><tr>
					
					<td style="background: green;width: 100%; display: block;"><span style="background: ">&nbsp;</span></td>
					<td>Semua Cuti</td>
				</tr>	
			</table>-->
			<div style="overflow-x:auto;">
			<table id="example1" class="table table-bordered table-striped" style="width: 100%" >
										<tr>
											<th style="text-align: center;vertical-align: middle">Nama</th>
											<th style="text-align: center;vertical-align: middle">No </th>
											<th style="text-align: center;vertical-align: middle">NIK</th>
											<th style="text-align: center;vertical-align: middle">Departemen</th>
											<th style="text-align: center;vertical-align: middle">Absen </th>
								<th style="text-align: center;vertical-align: middle">Cuti</th>
								<th style="text-align: center;vertical-align: middle">IPG</th>
								<th style="text-align: center;vertical-align: middle">IHK</th>
								<th style="text-align: center;vertical-align: middle">IPD</th>
								<th style="text-align: center;vertical-align: middle">SAKIT</th>
								<th style="text-align: center;vertical-align: middle">ALPA</th>
								<th style="text-align: center;vertical-align: middle">TP</th>
								<th style="text-align: center;vertical-align: middle">IDT</th>
								<th style="text-align: center;vertical-align: middle">IPM</th>
								<th style="text-align: center;vertical-align: middle">PM</th>
								<th style="text-align: center;vertical-align: middle">Total Absen </th>
								<th style="text-align: center;vertical-align: middle">Total Masuk</th>
								<th style="text-align: center;vertical-align: middle">Total  Kerja</th>
								<th style="text-align: center;vertical-align: middle">Terlambat</th>
								<th style="text-align: center;vertical-align: middle">Total Jam Lembur</th>
										</tr>
											<?php $no=0;
							$listkaryawan = $list_karyawan;
							
							?>
							@if(!empty($listkaryawan))
							@foreach($listkaryawan as $list_karyawan)
							
							<?php $no++;
							$return = $help->total_rekap_absen($rekap,$list_karyawan->p_karyawan_id);
									
									
									//echo $return['all_content'];
									//if($no==5)
									//break;
									$masuk 	= $return['total']['masuk'] ;
									$cuti = $return['total']['cuti'] ;
									$fingerprint = $return['total']['fingerprint'] ;
									$ipg = $return['total']['ipg'] ;
									$izin = $return['total']['izin'] ;
									$ipd = $return['total']['ipd'] ;
									$ipc = $return['total']['ipc'] ;
									$sakit = $return['total']['sakit'] ;
									$alpha = $return['total']['alpha'] ;
									$terlambat = $return['total']['terlambat'] ;
									$idt = $return['total']['idt'] ;
									$ipm = $return['total']['ipm'] ;
									$pm = $return['total']['pm'] ;
									$tabsen = $return['total']['Total Absen'] ;
									$tmasuk = $return['total']['Total Masuk'] ;
									$tkerja = $return['total']['Total Hari Kerja'] ;
									$lembur = $return['total']['total_all'] ;
										?>
                         
						
                               	<tr>
								<td>{!! $no !!}</td>
								<th style="min-width: 300px;">{!! $list_karyawan->nama !!}</th>
							
								<td>{!! $list_karyawan->nik !!}</td>
								<td style="min-width: 200px;">{!! $list_karyawan->departemen !!}</td>
									<?php $ipd;?>
								<td  style=""><?=$masuk;?></td>
								<td  style=""><?=$cuti?></td>
								<td style=""><?=$ipg;?></td>
								<td style=""><?=$izin;?></td>
								<td style=""><?=$ipd;?></td>
								<td style=""><?=$sakit;?></td>
								<td style=""><?=$alpha;?></td>
								<td style=""><?=$fingerprint;?></td>
								<td style=""><?=$idt;?></td>
								<td style=""><?=$ipm;?></td>
								<td style=""><?=$pm;?></td>
								<td style=""><?=$masuk+$ipd+$fingerprint;?></td>
								<td style=""><?=$masuk+$cuti+$ipg+$izin+$ipd+$sakit+$fingerprint;?></td>
								<td style=""><?=$masuk+$cuti+$ipg+$izin+$ipd+$sakit+$alpha+$fingerprint;?></td>
								<td style=""><?=$terlambat;?></td>
								<td style=""><?=$lembur;?></td>
							</tr>
							@endforeach
							@endif			
											
										</table>
			</div><div id="table-scroll" class="table-scroll ">
 
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
</div>
</div>
<!-- /.content-wrapper -->
@endsection
