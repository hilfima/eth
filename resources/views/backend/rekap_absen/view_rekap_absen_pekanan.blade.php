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
  z-index:4;
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
	<!-- /.content-header -->

	<!-- Main content -->
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
		<?=view('backend.rekap_absen.filter',compact('jabatan','departemen','periode','periode_absen','rekapget','entitas','request'));?>
		</div>
		<div class="card-body">
		<div class="row">
		<div class="col-sm-12">
		<Style>
				#ket td{
					border: 0;
				}
			</Style>
			<h5>Keterangan :</h5>
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
			</table>
		
			
<div id="table-scroll" class="table-scroll">
  <table id="main-table" class="main-table">
    <thead>
							<tr>
							
								<th scope="col" >Nama</th>
								<th scope="col" >No </th>
								<th scope="col" >NIK</th>
								<th scope="col" >Departemen</th>
								<?php 
								$date = $tgl_awal;
								for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
									echo ' <th>'.$date.'</th>';
									$date = $help->tambah_tanggal($date,1);
								}
								?>
                       
                        
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">IPD</th>
									<th scope="col"   style="width: 40px;min-width: 40px;max-width: 40px;font-size: 6px;text-align: center;">T Absen </th>
								
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">SAKIT</th>
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">IZIN</th>
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">ALPA</th>
							
								<th scope="col"   style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;text-align: center;">T Masuk</th>
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">T H Kerja</th>
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">Terlambat</th>
							</tr>
						</thead>
  <tbody>
							<?php 
							$listkaryawan = $list_karyawan;
							$no=0;?>
							@if(!empty($listkaryawan))
							@foreach($listkaryawan as $list_karyawan)
							<?php $no++ ?>
                         
							
                               	<tr>
								<th>{!! $list_karyawan->nama !!}</th>
								<td scope="col">{!! $no !!}</td>
							
								<td>{!! $list_karyawan->nik !!}</td>
								<td>{!! $list_karyawan->departemen !!}</td>
								<?php 
									$return = $help->total_rekap_absen_optimasi($rekap,$list_karyawan->p_karyawan_id);
									
									
									echo $return['all_content'];
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
		
									
									?> 
									
									
							
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$ipd;?></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$masuk+$ipd;?></td>
								
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$sakit;?></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$izin+$ipg;?></td>
								
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$alpha;?></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$masuk+$ipg+$izin+$ipd+$sakit;?></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$masuk+$ipg+$izin+$ipd+$sakit+$alpha;?></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?=$terlambat;?></td>
							</tr>
							@endforeach
							@endif
							
						</tbody>
    <tfoot>
    	<tr>
							
								<th scope="col" >Nama</th>
								<th scope="col" >No </th>
								<th scope="col" >NIK</th>
								<th scope="col" >Departemen</th>
								<?php 
								$date = $tgl_awal;
								for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
									echo ' <th>'.$date.'</th>';
									$date = $help->tambah_tanggal($date,1);
								}
								?>
                       
                        
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">IPD</th>
									<th scope="col"   style="width: 40px;min-width: 40px;max-width: 40px;font-size: 6px;text-align: center;">T Absen </th>
								
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">SAKIT</th>
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">IZIN</th>
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">ALPA</th>
							
								<th scope="col"   style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;text-align: center;">T Masuk</th>
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">T H Kerja</th>
								<th scope="col"  style="width: 40px;min-width: 40px;max-width: 40px;font-size: 10px;text-align: center;">Terlambat</th>
							</tr>
    </tfoot>
  </table>
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
