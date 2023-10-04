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
</style>
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
  height: 70px;
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
table th{
text-align: center;
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
                        <h1 class="m-0 text-dark">Rekap Lembur</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Rekap Lembur</li>
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
                        </div>
<div class="col-lg-6">
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
            	<div class="fixedTable" id="demo">
				<header class="fixedTable-header">
					<table class="table table-bordered">
						<thead>
							<tr>
							
								<th rowspan="2">No </th>
								<th rowspan="2">Nama</th>
								
								<th rowspan="2">Entitas</th>
								<th rowspan="2">Jabatan</th>
								<?php 
								$date = $tgl_awal;
								for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
									echo ' <th>'.$help->nama_hari($date).'
								
									</th>';
									$date = $help->tambah_tanggal($date,1);
								}
								?>
                       
                        
								<th colspan="4" style="text-align: center;">HARI KERJA</th>
								<th colspan="6" style="text-align: center;">HARI LIBUR</th>
								<th rowspan="2">TOTAL</th>
								<th rowspan="2"  style="text-align: center; font-size:12px">TOTAL PENGAJUAN</th>
								<th rowspan="2" style="text-align: center; font-size:12px">TOTAL APPROVE</th>
								<th rowspan="2" style="text-align: center; font-size:12px">TOTAL PENDING</th>
								<th rowspan="2" style="text-align: center; font-size:12px">TOTAL TOLAK</th>
							</tr><tr>
							
								
								<?php 
								$date = $tgl_awal;
								for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
									echo ' <th>'.$help->tgl_indo_short_no_tahun	($date).'</th>';
									$date = $help->tambah_tanggal($date,1);
								}
								?>
                       
                        
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">1 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">>=2 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">COUNT</th>
								<th>SUM</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px"><8 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">8 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">9 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">>=10 jam</th>
								<th style="width: 30px;min-width: 30px;max-width: 30px;font-size: 8px">COUNT</th>
								<th>SUM</th>
							</tr>
						</thead>
					</table>
				</header>
				<aside class="fixedTable-sidebar" style="display: none">
					<table class="table table-bordered">
						<tbody>
							
						</tbody>
					</table>
				</aside>
				<div class="fixedTable-body">
					<table class="table table-bordered">
						<tbody>
							 <?php $no=0 ?>
                    @if(!empty($list_karyawan))
                        @foreach($list_karyawan as $list_karyawan)
                            <?php $no++;	 
                            $total['<8 jam'] =0;
                            $total['8 jam'] =0;
			                $total['9 jam'] =0;
			                $total['>=10 jam'] =0;
                            $total['COUNT Libur'] =0;
							$total['SUM Libur'] =0;
							$total['1jam'] =0;
							$total['>=2jam'] =0;
							$total['COUNT Kerja'] =0;
							$total['SUM Kerja'] =0;
                            ?>
                            <tr>
                                <td>{!! $no !!}</td>
                               
                                <td>{!! $list_karyawan->nama !!}</td>
                                <td>{!! $list_karyawan->nmlokasi !!}</td>
                                <td>{!! $list_karyawan->nmjabatan !!}</td>
                               	<?php 
			                        $date = $tgl_awal;
			                        $total_all = 0;
			                        for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
			                        	if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
			                        				in_array(($date),$hari_libur	))
											$color = 'Style="background:red;color:white"';
			                        	else
			                        		$color='';
										
										if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['lama'])){
			                        			$total_all += $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
			                        			if(in_array($help->nama_hari($date),array('Sabtu','Minggu')) or 
			                        				in_array(($date),$hari_libur)){
			                        				$lama = $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
			                        				if($lama>8){
														$total['8 jam'] +=8; $lama-=8;
													}else if($lama<=8){
														$total['8 jam'] +=$lama; $lama-=$lama;
													}
													if($lama){
														
													 $total['9 jam'] +=1;$lama-=1;
													}
													if($lama)
													 $total['>=10 jam'] +=$lama;
														
													
													$total['COUNT Libur'] +=1;
													$total['SUM Libur'] +=$rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
												}
					                        	else{
					                        		$lama = $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
													$total['1jam'] +=1;$lama-=1;
			                        				if($lama)
													$total['>=2jam'] +=$lama;
													
								                   
													$total['COUNT Kerja'] +=1;
													$total['SUM Kerja'] +=$rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
												}
												echo ' <td '.$color.'>
												'.$rekap[$list_karyawan->p_karyawan_id][$date]['tgl_awal'].'
												s/d  '.$rekap[$list_karyawan->p_karyawan_id][$date]['tgl_akhir'].'
												<br>
												'.$rekap[$list_karyawan->p_karyawan_id][$date]['lama'].' Jam
													<br> '.$rekap[$list_karyawan->p_karyawan_id][$date]['jam_awal'].' 
														s/d  '.$rekap[$list_karyawan->p_karyawan_id][$date]['jam_akhir'].'
												</td >';
											
										}else{
											
											echo '<td '.$color.'>-</td>';
										}
										$date = $help->tambah_tanggal($date,1);
									}
                        		?>
                        		 
                                <td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['1jam']!!}</td>
                                <td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['>=2jam']!!}</td>
                                <td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['COUNT Kerja']!!}</td>
                                <td>{!!$total['SUM Kerja']!!} Jam </td>
                                <td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['<8 jam']!!}</td>
                                <td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['8 jam']!!}</td>
                                <td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['9 jam']!!}</td>
                                <td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['>=10 jam']!!}</td>
                                <td style="width: 30px;min-width: 30px;max-width: 30px;">{!!$total['COUNT Libur']!!}</td>
                                <td>{!!$total['SUM Libur']!!} Jam</td>
                                <td>{!!$total_all!!} Jam</td>
                                <td><?=isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_pengajuan']:0;?> Jam</td>
                                <td><?=isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_approve']:0;?> Jam</td>
                                <td><?=isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_pending']:0;?> Jam</td>
                                <td><?=isset($rekap[$list_karyawan->p_karyawan_id]['lembur'])? $rekap[$list_karyawan->p_karyawan_id]['lembur']['total_tolak']:0;?> Jam</td>
                               
                                
                            </tr>
                        @endforeach
                    @endif
							
						</tbody>
					</table>
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
