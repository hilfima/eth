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
					<h1 class="m-0 text-dark">Rekap Cuti </h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
						<li class="breadcrumb-item active">Rekap Cuti</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	
	<div class="card">
		<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
			
		</div>
		<div class="card-body">
			 <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.rekap_cuti_karyawan') !!}">
                  
		
		
		
			<div class="row">
				
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;">
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($users AS $users){
                                        if($users->p_karyawan_id==$request->nama ){
                                            echo '<option selected="selected" value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                        else{
                                            echo '<option value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                        <div class="form-group">
							<label>Entitas</label>
							<select class="form-control select2" name="entitas" style="width: 100%;" >
								<option value="">Pilih Entitas</option>
								<?php
								foreach($entitas AS $entitas){
										$selected = '';
									if($entitas->m_lokasi_id== $request->get('entitas')){
										$selected = 'selected';
										
									}
									
										echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
								}
								?>
							</select>
						</div>
						</div>
			
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! route('be.ajuan') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            
                        </div>
                    </div>
                </form>
					<div id="table-scroll" class="table-scroll">
						<table id="main-table" class="main-table">
							<thead>
								<tr>
							
									<th scope="col" rowspan="2">Nama</th>
									<th scope="col" rowspan="2" >No </th>
									<th scope="col" rowspan="2" >NIK</th>
									<th scope="col" rowspan="2" >TANGAAL BERGABUNG</th>
									<?php 
									$tahun = date('Y');
									if($request->view=='rekapall'){
									for($i = 2020; $i <= date('Y'); $i++){
									echo ' <th scope="col" colspan="12">'.$i.'</th>
									<th scope="col" rowspan="2">Total Ajuan</th>
								        <th scope="col" rowspan="2">S Cuti '.($i-1).'</th>
								        <th scope="col" rowspan="2">+  Cuti Tahun</th>
								        <th scope="col" rowspan="2">Cuti Besar</th>
								        <th scope="col" rowspan="2">S Cuti '.($i-1).'</th>
								        <th scope="col" rowspan="2">Sisa Ajuan</th>
								        <th scope="col" rowspan="2">S Cuti '.($i).'</th>
								        <th scope="col" rowspan="2">Sisa</th>
								        <th scope="col" rowspan="2">S Cuti Besar</th>
								        <th scope="col"  rowspan="2">Total Sisa</th>
									';
									}
									}else{
										echo '
									<th scope="col" rowspan="2" >'.($tahun-1).'</th>
									<th scope="col" rowspan="2" >'.$tahun.'</th>
									<th scope="col" rowspan="2" >Cuti Besar</th>
									<th scope="col" rowspan="2" >TOTAL</th>';
									}
									?>
                       
                        
									
								</tr>
								<tr>
										<?php 
										if($request->view=='rekapall'){
									for($i = 2020; $i <= date('Y'); $i++){
									echo ' 
								        <td>Jan</td>
								        <td>Feb</td>
								        <td>Mar</td>
								        <td>Apr</td>
								        <td>Mei</td>
								        <td>Juni</td>
								        <td>Juli</td>
								        <td>Agu</td>
								        <td>Sep</td>
								        <td>Okt</td>
								        <td>Nov</td>
								        <td>Des</td>';
										}
										}
										?>
									</tr>
								</tr>
							</thead>
							<tbody>
								<?php 
								
								$no=0;
								$rekap_cuti = $cuti['rekap_cuti'];
								//print_r($rekap_cuti['all']['rekap_ajuan'][2022]['05']);
								?>
								@if(!empty($listkaryawan))
								@foreach($listkaryawan as $list_karyawan)
								<?php $no++ ?>
                         
							
								<tr>
									<th>{!! $list_karyawan->nama !!}</th>
									<td scope="col">{!! $no !!}</td>
							
									<td>{!! $list_karyawan->nik !!}</td>
									<td>{!! $list_karyawan->tgl_bergabung !!}</td>
									<?php
									//echo $list_karyawan->nama;
									//if(isset($rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_ajuan']))
									//print_r($rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_ajuan']);
									//if(isset($rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_cuti_tahunan']))
									//print_r($rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_cuti_tahunan']);
									//echo '<br>';echo '<br>';echo '<br>';
									$sisa_cuti[$list_karyawan->p_karyawan_id][2019] = 0;
									$sisa_cuti_besar[$list_karyawan->p_karyawan_id][2019] = 0;
										$cuti_besar[$list_karyawan->p_karyawan_id] = 0;
									$thnkemarin= 0;
									$thnsekarang= 0;
									$besar= 0;
									for($thn=2020;$thn<=date('Y');$thn++){
										$total_ajuan[$list_karyawan->p_karyawan_id][$thn] = 0;
										$sisa_cuti[$list_karyawan->p_karyawan_id][$thn] = 0;
										$cuti_tahun[$list_karyawan->p_karyawan_id][$thn] = 0;
										//echo $thn;
										for($bulan=1;$bulan<=12;$bulan++){
											$bln = sprintf('%02d',$bulan);
											$total_cuttah = 0;
											$total_cutber = 0;
											
												
											
											if(isset($rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_cuti_tahunan'][$thn][$bln])){
												
												$cuti_tahun[$list_karyawan->p_karyawan_id][$thn] += $rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_cuti_tahunan'][$thn][$bln];
												$total_cuttah += $rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_cuti_tahunan'][$thn][$bln];
											}
											if(isset($rekap_cuti[$list_karyawan->p_karyawan_id]['reset_cuti_besar'][$thn][$bln])){
												
												$cuti_besar[$list_karyawan->p_karyawan_id] -= $cuti_besar[$list_karyawan->p_karyawan_id];
												
											} 
											if(isset($rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_cuti_besar'][$thn][$bln])){
												
												$cuti_besar[$list_karyawan->p_karyawan_id] += $rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_cuti_besar'][$thn][$bln];
												$total_cutber += $rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_cuti_besar'][$thn][$bln];
											}
											if(date('Y-m-d',strtotime($thn.'-'.$bulan.'-1'))>$help->tambah_bulan($list_karyawan->tgl_bergabung,12)){
											
											$ajuan_cutber =  isset($rekap_cuti['all']['rekap_ajuan'][$thn][$bln])?
													$rekap_cuti['all']['rekap_ajuan'][$thn][$bln]:0;
											
											}else{
												$ajuan_permit = 0;
												$ajuan_cutber = 0;
												$ajuan_sinkron = 0;
											}
											$ajuan_permit =  isset($rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_ajuan'][$thn][$bln])?
													$rekap_cuti[$list_karyawan->p_karyawan_id]['rekap_ajuan'][$thn][$bln]:0;
											$ajuan_sinkron =  isset($rekap_cuti[$list_karyawan->p_karyawan_id]['sinkronisasi'][$thn][$bln])?$rekap_cuti[$list_karyawan->p_karyawan_id]['sinkronisasi'][$thn][$bln]:0;		
											//echo $ajuan_cutber;
											$total = $ajuan_permit+$ajuan_cutber+$ajuan_sinkron;	
											$keterangan = "<br><span style='font-size:2px'>
											$total_cuttah CT $total_cutber CB  $ajuan_permit A $ajuan_cutber CB  
											</span>";
											
											$total_ajuan[$list_karyawan->p_karyawan_id][$thn] +=	$total;
											if($request->view=='rekapall'){
												
											echo '<td>';
											echo  ($total?$total:"<font color='grey'>0</font>").$keterangan;	
											echo '</td>';
											}
										}
										if($request->view=='rekapall'){
										echo '<td>'.$total_ajuan[$list_karyawan->p_karyawan_id][$thn].'</td>';
										echo '<td>'.$sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1].'</td>';
										
										echo '<td>'.$cuti_tahun[$list_karyawan->p_karyawan_id][$thn].'</td>';
										echo '<td>'.$cuti_besar[$list_karyawan->p_karyawan_id].'</td>';
										}
										//=IF($sisacuti>$Totalajuan;sisacuti-Totalajuan;sisacuti-sisacuti)
										$sisathnkmr_TMP = $sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1];
										
										
										$sisa = $sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1];
										$total_aju = $total_ajuan[$list_karyawan->p_karyawan_id][$thn];
										$sisa = $sisa>$total_aju?$sisa-$total_aju:$sisa-$sisa;
										
										 $sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1] = $sisa;
										if($request->view=='rekapall')
											echo '<td>'.$sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1].'</td>';
										
										$sisac = $sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1];
										$total_aju = $total_ajuan[$list_karyawan->p_karyawan_id][$thn];
										
										$terpakai = $sisathnkmr_TMP-$sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1];
										
										$sisa = $total_aju  - $terpakai;
										$sisa_ajuan[$list_karyawan->p_karyawan_id][$thn] = $sisa;
										if($request->view=='rekapall') 
											echo '<td>'.$sisa_ajuan[$list_karyawan->p_karyawan_id][$thn].'</td>';
										//=IF(AL4>AO4;AL4-AO4;AL4-AL4) AL cuti tahunan a0 sisa ajuan
										$cutitahunan = $cuti_tahun[$list_karyawan->p_karyawan_id][$thn];
										$sisaaju = $sisa_ajuan[$list_karyawan->p_karyawan_id][$thn];
										
										$sisa = $cutitahunan>$sisaaju?$cutitahunan-$sisaaju:$sisaaju-$sisaaju;
										
										$sisa_cuti[$list_karyawan->p_karyawan_id][$thn]= $sisa;
										
										if($request->view=='rekapall')
										echo '<td>'.$sisa_cuti[$list_karyawan->p_karyawan_id][$thn].'</td>';
										//=IF(AL4>AO4;AL4-AL4;AO4-AL4)
										$cutitahunan = $cuti_tahun[$list_karyawan->p_karyawan_id][$thn];
										$sisaaju = $sisa_ajuan[$list_karyawan->p_karyawan_id][$thn];
										
										$sisa = $cutitahunan>$sisaaju?$cutitahunan-$cutitahunan:$sisaaju-$cutitahunan;
										$sisa_ajuan[$list_karyawan->p_karyawan_id][$thn] = $sisa;
										
										if($request->view=='rekapall')
										echo '<td>'.$sisa_ajuan[$list_karyawan->p_karyawan_id][$thn].'</td>';
										//sisa cuti besar + $cuti besar tambahan - sisa ajuan
										$cuti_besar[$list_karyawan->p_karyawan_id] = $cuti_besar[$list_karyawan->p_karyawan_id] - $sisa_ajuan[$list_karyawan->p_karyawan_id][$thn];
										if($request->view=='rekapall')
										echo '<td>'.$cuti_besar[$list_karyawan->p_karyawan_id].'</td>';
										
										$totalsisa = $cuti_besar[$list_karyawan->p_karyawan_id] + $sisa_cuti[$list_karyawan->p_karyawan_id][$thn]+$sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1];
										
										if($request->view=='rekapall')
										echo '<td>'.$totalsisa.'</td>';
										
										
											
										$thnkemarin= $sisa_cuti[$list_karyawan->p_karyawan_id][$thn-1];
										$thnsekarang= $sisa_cuti[$list_karyawan->p_karyawan_id][$thn];
										$besar= $cuti_besar[$list_karyawan->p_karyawan_id];
									}
									if($request->view!='rekapall'){
											echo '<td>'.$thnkemarin.'</td>';
											echo '<td>'.$thnsekarang.'</td>';
											echo '<td>'.$besar.'</td>';
											echo '<td>'.$totalsisa.'</td>';
										}
									?>
								</tr>
								@endforeach
								@endif
							
							</tbody>
						
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
