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
	.table-scroll td {
		min-width: 120px
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
	.table-scroll thead tr.secondline th {
		top: 35px;
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

	th.statictable:first-child {
		position: -webkit-sticky;
		position: sticky;
		left: 0;
		z-index: 2;
		background: #ccc;
	}

	thead th.statictable:first-child{
		background: #333;
		position: sticky;
		left: 0;
		z-index: 3;
	}
	thead th:first-child,
	tfoot th:first-child {
		z-index: 5;
	}

</style>

<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Generate, Preview, Aproval, dan Ajuan  Gaji</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Generate, Preview, Aproval, dan Ajuan Gaji</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.previewgaji',$data['page']) !!}">
		<div class="card">
			<div class="card-header">
				<!--<h3 class="card-title">DataTable with default features</h3>-->
				<a href="{!! route('be.tambah_generate') !!}" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah Generate Gaji </a>
				<a href="{!! route('be.generategaji') !!}" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-plus'></span> Edit Data  </a>
			</div>
			<?php 
			echo  view('backend.gaji.preview.filter',compact('data','entitas','user','id_prl','help','periode','periode_absen','request','list_karyawan'));
			?>
           
			@if(!empty($list_karyawan))
			<div class="card-body">
           
				<div id="table-scroll" class="table-scroll">
					<table id="main-table" class="main-table">
						<thead>
							<tr>
							
							

								<th rowspan="2" class="statictable" >Nama</th>
								<th rowspan="2" style="width: 30px;max-width: 30px;min-width: 30px">No </th>
								
								<th rowspan="2">NIP</th>
								<th rowspan="2">Perusahaan</th>
								<th rowspan="2">Pajak</th>
								<th rowspan="2">JABATAN</th>
								<th rowspan="2">TGL MASUK</th>
								<th rowspan="2">MASA KERJA</th>
								<th rowspan="2">Grade</th>
								
								GAJI TOTAL PER BULAN 
	

                        
								<th colspan="2" >DATA ABSENSI</th>
								<th rowspan="2">UPAH HARIAN</th>
								<th rowspan="2">GAJI TOTAL PER BULAN </th>
								<th colspan="3"> PENDAPATAN 	</th>
								<th colspan="3">  TUNJANGAN</th>
								<th colspan="9">   POTONGAN </th>
								
											  		
		


								<th rowspan="2" style="font-size: 12px">TOTAL POTONGAN</th>
								<th rowspan="2" style="font-size: 12px">TOTAL PENDAPATAN</th>
								<th rowspan="2">THP</th>
							</tr>
							<tr class="secondline">
							
							

                        
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;z-index:1">H. ABSEN</th>
								<th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">J. LEMBUR</th>
								
								<th>Total Pendapatan</th>
								<th> UPAH LEMBUR</th>
								<th> JUMLAH</th>
								<th>KOST</th>
								<th>KOREKSI (+)</th>
								<th>JUMLAH</th>
								<th>P.TELAT </th>
								<th>SEWA KOST</th>
								<th>TIDAK FINGERPRINT</th>
								<th>PULANG MENDAHULUI</th>
								<th>KOPERASI KKB</th>
								<th>KOPERASI ASA</th>
								<th>ZAKAT</th>
								<th>INFAQ</th>
								<th>KOREKSI (-)</th>
							</tr>
						</thead>
						<tbody>
							<?php $no=0;
							$gaji_entitas = array();
							$total['UPAH HARIAN'] = 0;
                            $total['Total Pendapatan']=0;
								$total['GAJI TOTAL PER BULAN']=0;
								$total['UPAH LEMBUR']=0;
								$total['JUMLAH PENDAPATAN']=0;
								$total['KOST']=0;
								$total['KOREKSI (+)']=0;
								$total['JUMLAH TUNJANGAN']=0;
								$total['P.TELAT']=0;
								$total['SEWA KOST']=0;
								$total['TIDAK FINGERPRINT']=0;
								$total['PM']=0;
								$total['KOPERASI KKB']=0;
								$total['KOPERASI ASA']=0;
								$total['ZAKAT']=0;
								$total['INFAQ']=0;
								$total['KOREKSI (-)']=0;
								$total['JUMLAH POTONGAN']=0;
								$total['TOTAL PENDAPATAN']=0;
								$total['THP']=0;
							?>
							@foreach($list_karyawan as $list_karyawan)
							<?php $no++;	
							 $color = '';
							 if($list_karyawan->pajak_onoff){
							 	
                            	if($sudah_appr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                            		$color = 'background: orange;color: white;';
                            	if($sudah_appr_keuangan[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
	                            	$color = 'background: green;color: white;';
							 }
							?>
							<tr>
								<th style="font-size: 11px;<?=$color?>" class="statictable">{!! $list_karyawan->nama_lengkap !!}</th>
								<td style="width: 50px;max-width: 50px;min-width: 50px">{!! $no !!}</td> 
								<td>{!! $list_karyawan->nik !!}</td>
								<td>{!! $list_karyawan->nmlokasi !!} </td>
								<td>{!! $list_karyawan->pajak_onoff !!}</td>
								<td style="font-size: 10px">{!! $list_karyawan->nmjabatan !!}</td>
								<td>{!! $list_karyawan->tgl_awal !!}</td>
								<td style="font-size: 10px">{!! $list_karyawan->umur !!}</td>
								<td>{!! $list_karyawan->grade !!}</td>
                                
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $ha= isset($data[$list_karyawan->p_karyawan_id]['Hari Absen'])?$data[$list_karyawan->p_karyawan_id]['Hari Absen']:0;?></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Jam Lembur'])?$data[$list_karyawan->p_karyawan_id]['Jam Lembur']:0;?></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"><?= $uh=isset($data[$list_karyawan->p_karyawan_id]['Upah Harian'])?$data[$list_karyawan->p_karyawan_id]['Upah Harian']:0;
								
                                $nama_row = 'upah_harian';
                                if(!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                		$data[$list_karyawan->p_karyawan_id][$nama_row]['master']=0;
                                 if($uh!=$data[$list_karyawan->p_karyawan_id][$nama_row]['master']){
                                	echo '<div style="color:red"><a href="'.route('be.update_nominal',[$id_prl,$data[$list_karyawan->p_karyawan_id]['id']['Infaq'],$data[$list_karyawan->p_karyawan_id][$nama_row]['master']]).'"><i class="fa fa-lightbulb-o "></i></a> '.$help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']).'</div>';
                                }?></td>
								<td ><?=  $help->rupiah2($uh*22)?></td>
								<td ><?=  $help->rupiah2($gapok = $ha*$uh);?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Lembur'])?$lembur=$data[$list_karyawan->p_karyawan_id]['Lembur']:$lembur=0);?></td>
								<td><?=  $help->rupiah2($gapok+$lembur);?></td>
                                
                                
                                
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost'])?$tunkost=$data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']	:$tunkost=0);
                                $nama_row = 'tunjangan_kost';
                                if(!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                		$data[$list_karyawan->p_karyawan_id][$nama_row]['master']=0;
                                 if($tunkost!=$data[$list_karyawan->p_karyawan_id][$nama_row]['master']){
                                	echo '<div style="color:red"><a href="'.route('be.update_nominal',[$id_prl,$data[$list_karyawan->p_karyawan_id]['id']['Infaq'],$data[$list_karyawan->p_karyawan_id][$nama_row]['master']]).'"><i class="fa fa-lightbulb-o "></i></a> '.$help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']).'</div>';		
}
								?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)'])?$korekplus=$data[$list_karyawan->p_karyawan_id]['Koreksi(+)']:$korekplus=0);?></td>
								<td  style="font-size:13px"><?=  $help->rupiah2($tunjangan = $tunkost+$korekplus);?></td>
                                
                                
                               
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat'])?$telat= $data[$list_karyawan->p_karyawan_id]['Potongan Telat']:$telat=0);?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost'])?$sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost']:$sewakost =0);
                                $nama_row = 'tunjangan_kost';
                                if(!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                		$data[$list_karyawan->p_karyawan_id][$nama_row]['master']=0;
                                 if($tunkost!=$data[$list_karyawan->p_karyawan_id][$nama_row]['master']){
                                	echo '<div style="color:red"><a href="'.route('be.update_nominal',[$id_prl,$data[$list_karyawan->p_karyawan_id]['id']['Infaq'],$data[$list_karyawan->p_karyawan_id][$nama_row]['master']]).'"><i class="fa fa-lightbulb-o "></i></a> '.$help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']).'</div>';
                                	
}?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'])?$finger= $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']:$finger=0);?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'])?$potpm= $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']:$potpm=0);?></td>
                               
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'])?$kkp = $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']:$kkp =0);
								$nama_row = 'KKB';
                                if(!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                		$data[$list_karyawan->p_karyawan_id][$nama_row]['master']=0;
                                 if($kkp!=$data[$list_karyawan->p_karyawan_id][$nama_row]['master']){
                                	echo '<div style="color:red"><a href="'.route('be.update_nominal',[$id_prl,$data[$list_karyawan->p_karyawan_id]['id']['Simpanan Wajib KKB'],$data[$list_karyawan->p_karyawan_id][$nama_row]['master']]).'"><i class="fa fa-lightbulb-o "></i></a> '.$help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']).'</div>';
                                }
                                ?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'])?$asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']:$asa =0);
								$nama_row = 'ASA';
                                if(!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                		$data[$list_karyawan->p_karyawan_id][$nama_row]['master']=0;
                                 if($asa!=$data[$list_karyawan->p_karyawan_id][$nama_row]['master']){
                                	echo '<div style="color:red"><a href="'.route('be.update_nominal',[$id_prl,$data[$list_karyawan->p_karyawan_id]['id']['Potongan Koperasi Asa'],$data[$list_karyawan->p_karyawan_id][$nama_row]['master']]).'"><i class="fa fa-lightbulb-o "></i></a> '.$help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']).'</div>';
                                }?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Zakat'])?$zakat = $data[$list_karyawan->p_karyawan_id]['Zakat']:$zakat =0);$nama_row = 'zakat';
                                if(!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                		$data[$list_karyawan->p_karyawan_id][$nama_row]['master']=0;
                                 if($zakat!=$data[$list_karyawan->p_karyawan_id][$nama_row]['master']){
                                	echo '<div style="color:red"><a href="'.route('be.update_nominal',[$id_prl,$data[$list_karyawan->p_karyawan_id]['id']['Zakat'],$data[$list_karyawan->p_karyawan_id][$nama_row]['master']]).'"><i class="fa fa-lightbulb-o "></i></a> '.$help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']).'</div>';
                                }
                                ?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Infaq'])?$infaq = $data[$list_karyawan->p_karyawan_id]['Infaq']:$infaq =0);
                                $nama_row = 'infaq';
                                if(!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                		$data[$list_karyawan->p_karyawan_id][$nama_row]['master']=0;
                                 if($infaq!=$data[$list_karyawan->p_karyawan_id][$nama_row]['master']){
                                	echo '<div style="color:red"><a href="'.route('be.update_nominal',[$id_prl,$data[$list_karyawan->p_karyawan_id]['id']['Infaq'],$data[$list_karyawan->p_karyawan_id][$nama_row]['master']]).'"><i class="fa fa-lightbulb-o "></i></a> '.$help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']).'</div>';
                                }
                                ?>?></td>
								<td><?=  $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)'])?$korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)']:$korekmin =0);?></td>
                               
								<td style="font-size:13px"><?=  $help->rupiah2($potongan = $telat+$finger+$sewakost+$zakat+$infaq+$korekmin+$kkp+$asa+$potpm);?></td>
								<td style="font-size:13px"><?=  $help->rupiah2(($gapok+$tunjangan+$lembur));?></td>
								<td style="font-size:13px"><?=  $help->rupiah2(($gapok+$tunjangan+$lembur)-$potongan);?></td>
								<?php 
								
								
								$total['UPAH HARIAN']+=$uh; 
								$total['Total Pendapatan']+=$ha*$uh;
								$total['GAJI TOTAL PER BULAN']+=$uh*22;
								$total['UPAH LEMBUR']+=$lembur;
								$total['JUMLAH PENDAPATAN']+=$gapok+$lembur;
								$total['KOST']+=$tunkost;
								$total['KOREKSI (+)']+=$korekplus;
								$total['JUMLAH TUNJANGAN']+=$tunjangan;
								$total['P.TELAT']+=$telat;
								$total['SEWA KOST']+=$sewakost;
								$total['TIDAK FINGERPRINT']+=$finger;
								$total['PM']+=$potpm;
								$total['KOPERASI KKB']+=$kkp;
								$total['KOPERASI ASA']+=$asa;
								$total['ZAKAT']+=$zakat;
								$total['INFAQ']+=$infaq;
								$total['KOREKSI (-)']+=$korekmin;
								$total['JUMLAH POTONGAN']+=$potongan;
								$total['TOTAL PENDAPATAN']+=($gapok+$tunjangan+$lembur);
								$total['THP']+=($gapok+$tunjangan+$lembur)-$potongan;
								
								
								if(isset($gaji_entitas[$list_karyawan->nmlokasi])){
								$gaji_entitas[$list_karyawan->nmlokasi] += ($gapok+$tunjangan+$lembur)-$potongan+$korekmin-$korekplus;
								}else{
									$gaji_entitas[$list_karyawan->nmlokasi] = ($gapok+$tunjangan+$lembur)-$potongan+$korekmin-$korekplus;

								}
								;if(isset($data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(+)'])){
								$nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(+)'];
								if(isset($gaji_entitas[$nmlok])){
								$gaji_entitas[$nmlok] += $korekplus;
								}else{
								$gaji_entitas[$nmlok] = $korekplus;

								}
								}
								if(isset($data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(-)'])){
									$nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(-)'];
									if(isset($gaji_entitas[$nmlok])){
									$gaji_entitas[$nmlok] -= $korekmin;
									}else{
										$gaji_entitas[$nmlok] = -$korekmin;

									}
								}
								
								?>
								
								
							</tr>
							@endforeach
							<tr>
								<th style="font-size: 11px" class="statictable">Total</th>
								<td style="width: 50px;max-width: 50px;min-width: 50px"></td> 
								<td></td>
								<td></td>
								<td style="font-size: 10px"></td>
								<td></td>
								<td style="font-size: 10px"></td>
								<td></td>
                                
								<td style="width: 40px;min-width: 40px;max-width: 40px;"></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"></td>
								<td style="width: 40px;min-width: 40px;max-width: 40px;"></td>
								<td style="font-size:13px"><?=$help->rupiah2($total['UPAH HARIAN'])?></td>
								<td style="font-size:13px"><?=  $help->rupiah2($total['GAJI TOTAL PER BULAN']);?></td>
								<td style="font-size:13px"><?=$help->rupiah2($total['Total Pendapatan'])?></td>
								<td style="font-size:13px"><?=  $help->rupiah2($total['UPAH LEMBUR']);?></td>
								<td style="font-size:13px"><?=  $help->rupiah2($total['JUMLAH PENDAPATAN']);?></td>
								<td><?=  $help->rupiah2($total['KOST']);?></td>
								<td><?=  $help->rupiah2($total['KOREKSI (+)']);?></td>
								<td  style="font-size:13px"><?=  $help->rupiah2($total['JUMLAH TUNJANGAN']);?></td>
								<td><?=  $help->rupiah2($total['P.TELAT']);?></td>
								<td><?=  $help->rupiah2($total['SEWA KOST']);?></td>
								<td><?= $help->rupiah2($total['TIDAK FINGERPRINT']);?></td>
								<td><?= $help->rupiah2($total['PM']);?></td>
                               
								<td><?=  $help->rupiah2($total['KOPERASI KKB']);?></td>
								<td><?=  $help->rupiah2($total['KOPERASI ASA']);?></td>
								<td><?=  $help->rupiah2($total['ZAKAT']);?></td>
								<td><?=  $help->rupiah2($total['INFAQ']);?></td>
								<td><?=  $help->rupiah2($total['KOREKSI (-)']);?></td>
                               
								<td style="font-size:13px"><?=  $help->rupiah2($total['JUMLAH POTONGAN']);?></td>
								<td style="font-size:13px"><?=  $help->rupiah2($total['TOTAL PENDAPATAN']);?></td>
								<td style="font-size:13px"><?=  $help->rupiah2($total['THP']);?></td>
								
							</tr>
							
						</tbody>
  
					</table>
					
					
				</div>

            	
TOTAL GAJI PER ENTITAS
					<table style="width: 100%" class="table table-striped">
            			<tbody>
            			<?php 
						$t = 0;
						foreach($gaji_entitas as $key => $value){
						$t += $value;
            				
						 	echo '<tr>
            				<th style="width:10%">'.$key.'</th>
            				<td>'.$help->rupiah($value).'</td>
            			</tr>';
						}
            			?>
						<tr>
							<td style="width:10%"> TOTAL</td>
							<td > <?=$help->rupiah($t)?></td>
						</tr>
            			
            			
            		</tbody></table>              
					@endif
			</div>
			<!-- /.card-body -->
		</div>
	</form>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
