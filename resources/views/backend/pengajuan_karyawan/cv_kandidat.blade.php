 @extends('layouts.appsA')



@section('content')
<!-- Page Title -->
<div class="content-wrapper">

@include('flash-message')


<!-- Main content -->
<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">List Proses Pengajuan Karyawan Baru </h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			
		</ul>
	</div>
</div>
<div class="card">
	<div class="card-body">
		 <form action="<?=route('be.database_kandidat');?>" method="get" enctype="multipart/form-data" class="row">  {{ csrf_field() }}
		<div class="col-md-3">
	            		<div class="form-group">
                                <label>Entitas</label>
                                <select type="date" class="form-control select2" id="tgl_awal" name="entitas" value="" placeholder="Tanggal Awal..." >
                                	<option value="">- Entitas -</option>
                                	<?php foreach($entitas as $entitas){?>
                                	<option value="<?=$entitas->m_lokasi_id;?>" <?=$request->get('entitas')==$entitas->m_lokasi_id?'selected':'';?>><?=$entitas->nama;?></option>
 									<?php }?>                               	
                                </select>
                            </div>	
	            	</div><div class="col-md-3">
	            		<div class="form-group">
                                <label>Departement</label>
                                <select type="date" class="form-control select2" id="tgl_awal" name="departemen" value="" placeholder="Tanggal Awal..." >
                                	<option value="">- Departement -</option>
                                	<?php 
                                	
                                	foreach($departemen as $dep){?>
                                	<option value="<?=$dep->m_departemen_id;?>" <?=$request->get('departemen')==$dep->m_departemen_id?'selected':'';?>><?=$dep->nama;?></option>
 									<?php }?>                               	
                                </select>
                            </div>	
	            	</div><div class="col-md-3">
	            		<div class="form-group">
                                <label>Tanggal Awal Masuk Ajuan</label>
                                <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?=$request->get('tgl_awal');?>" placeholder="Tanggal Awal..." >
                            </div>	
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group">
                                <label>Tanggal Akhir Masuk Ajuan</label>
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?=$request->get('tgl_akhir');?>" placeholder="Tanggal Akhir..." >
                            </div>
	            		
	            	</div>
	            	<div class="col-md-12">
            			<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
            		</div>
            </form>
			<table id="exam" class="table table-bordered table-striped text-nowrap">
			<thead>
				<tr>
					<th>No.</th>
					<th>No Pengajuan</th>	
					<th>Posisi Diajukan</th>
					<th>Tanggal Masuk Ajuan</th>
					<th>Hitunga Hari Ajuan</th>
					<th>Jumlah Kebutuhan</th>
					<th>Entitas</th>
					<th>Departemen</th>
					<th>Level</th>
					<th>Status Ajuan</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no =0;


			?>
			@if(!empty($tkaryawan))
			@foreach($tkaryawan as $tkaryawan)
			<?php $no++; ?>
			<tr>
				<td><?=$no; ?></td>
				<td><?=$tkaryawan->nomor_pengajuan; ?></td>
				<td><?=$tkaryawan->m_jabatan_id==-1?($tkaryawan->posisi):$tkaryawan->namaposisi; ?></td>

				<td><?=$tkaryawan->appr_keuangan_date; ?></td>
				<td><?=$tkaryawan->appr_keuangan_date?$help->hitungHari($tkaryawan->appr_keuangan_date,date('Y-m-d')):0; ?> Hari</td>
				<td>Total Diajukan : <b><?=$tkaryawan->jumlah_dibutuhkan;?> Orang</b><br>
                              
                                <br>TOTAL APPROVE   : <b style="color:red"><?=$tkaryawan->final_approval;?> Orang</b>
                                    </td>
				<td><?=$tkaryawan->nmlokasi; ?></td>
				<td><?=$tkaryawan->nmdept; ?></td>
				<td><?=$tkaryawan->nmlevel; ?></td>

				<td><?php
				 if($tkaryawan->status==1)
                               	echo 'Selesai ';
                               	else if($tkaryawan->status==0)
                               		echo 'Menunggu Approval Atasan'	;
                               	else if($tkaryawan->status==5)
                               		echo 'Menunggu Approval Keuangan'	;
								else if($tkaryawan->status==2)
								   echo 'Diproses HC';	
                               	else if($tkaryawan->status==3)
                               		echo 'Proses Interview';	
                               	else if($tkaryawan->status==41)
                               	echo 'Ditolak Atasan';	
                               	else if($tkaryawan->status==42)
                               	echo 'Ditolak Keuangan';
                               	else if($tkaryawan->status==4)
                               	echo 'Ditolak HC';
								   else
								   echo 'Pending'	;
				; ?></td>
				<td>
					<a href="{!! route('be.list_database_kandidat',$tkaryawan->t_karyawan_id) !!}" title='Upload' class="btn btn-primary" data-toggle='tooltip'>
						<i class="fa fa-upload" aria-hidden="true"></i>
					</a>
				</td>
			</tr>
			@endforeach
			@endif
		</table>
	</div>
</div>
@endsection