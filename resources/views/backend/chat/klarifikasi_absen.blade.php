@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	@include('flash-message')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"> Klarifikasi Absen</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{!! route('be.klarifikasi_absen') !!}">Klarifikasi Absen</a></li>
						<li class="breadcrumb-item active"> Klarifikasi Absen</li>
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
			
		</div>
		<!-- /.card-header -->
		<div class="card-body">
			  <form action="<?=route('be.klarifikasi_absen');?>" method="get" enctype="multipart/form-data">
            	 {{ csrf_field() }}
            	<div class="row mb-3">
	            	<div class="col-md-3">
	            		<div class="form-group">
                                <label>Nama Karyawan</label>
                                 <select class="form-control select2" id="tgl_absen" name="nama" value=""  >
                                    <option value="">- Nama Karyawan -</option>
                                    <?php foreach($karyawan as $karyawan){?>
                                        <option value="<?=$karyawan->p_karyawan_id;?>"><?=$karyawan->nama;?></option>
                                    <?php }?>
                                 </select>
                                
                            </div>	
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group">
                                <label>Tanggal Awal</label>
                                <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?=$tgl_awal;?>" placeholder="Tanggal Awal..." required>
                            </div>	
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group">
                                <label>Tanggal Awal</label>
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?=$tgl_akhir;?>" placeholder="Tanggal Awal..." required>
                            </div>
	            		
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group">
                                <label>Pengajuan*</label>
                                <select class="form-control " id="tgl_absen" name="tujuan" value=""  >
									<option value="">Pilih Masalah</option>
									<option value="1" <?=$request->get('tujuan')==1?'selected':''; ?>>Absensi - Finger tidak terbaca</option>
									<option value="2" <?=$request->get('tujuan')==2?'selected':''; ?>>Absensi - Izin</option>
									<option value="3" <?=$request->get('tujuan')==3?'selected':''; ?>>Absensi - Sakit</option>
									<option value="4" <?=$request->get('tujuan')==4?'selected':''; ?>>Absensi - Mesin absen Error</option>
									<option value="5" <?=$request->get('tujuan')==5?'selected':''; ?>>Absensi - Lainnya</option>
									<option value="6" <?=$request->get('tujuan')==6?'selected':''; ?>>Gaji -  Kelebihan bayar</option>
									<option value="7" <?=$request->get('tujuan')==7?'selected':''; ?>>Gaji -  Kekurangan bayar</option>
									<option value="8" <?=$request->get('tujuan')==8?'selected':''; ?>>Gaji -  Lainnya</option>
									<option value="9" <?=$request->get('tujuan')==9?'selected':''; ?>>Lainnya</option>
								</select>
                            </div>
	            	</div><div class="col-md-3">
	            		<div class="form-group">
                                <label>Approve HR*</label>
                               <select class="form-control " id="tgl_absen" name="appr_hr_status"  >
									<option value="">Pilih Approval HR</option>
									<option value="3" <?=$request->get('appr_hr_status')==3?'selected':''; ?>>Pending</option>
									<option value="1" <?=$request->get('appr_hr_status')==1?'selected':''; ?>>Setujui dan Telah Dirubah</option>
									<option value="2" <?=$request->get('appr_hr_status')==2?'selected':''; ?>>Tolak(Perlu Approve Atasan)</option>
									<option value="4" <?=$request->get('appr_hr_status')==4?'selected':''; ?>>Perlu Konfirmasi Karyawan </option>
								</select>
                            </div>
	            	</div><div class="col-md-3">
	            		<div class="form-group">
                                <label>Status*</label>
                                
                               <select class="form-control " id="tgl_absen" name="selesai"  >
									<option value="">Semua</option>
									<option value="-1" <?=$request->get('selesai')==3?'selected':''; ?>>Proses HR</option>
									<option value="1" <?=$request->get('selesai')==1?'selected':''; ?>>Proses Atasan</option>
									<option value="2" <?=$request->get('selesai')==2?'selected':''; ?>>Entry Perubahan Absen</option>
									<option value="3" <?=$request->get('selesai')==3?'selected':''; ?>>Selesai</option>
									<option value="4" <?=$request->get('selesai')==3?'selected':''; ?>>Karyawan Selesai Konfirmasi</option>
									<option value="5" <?=$request->get('selesai')==3?'selected':''; ?>>Klarifikasi tidak bisa ditindak lanjuti</option>
									<option value="6" <?=$request->get('selesai')==3?'selected':''; ?>>Proses Karyawan</option>
								</select>
                            </div>
	            	</div>
            		<div class="col-md-12">
            			<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
            		</div>
            	</div>
            </form>
			<table id="example1" class="table table-striped custom-table mb-0">
				<thead>
					<tr>
						<th>No.</th>
						<th>Tanggal Pengajuan</th>
						<th>Tanggal</th>

						<th>Nama Karyawan</th>
						<th>Topik</th>
						<th>Klarifikasi</th>
						<th>Deskripsi</th>
						<th>Status</th>
						<th>Approval HR</th>
						<th>Atasan</th>
						<th>Approval Atasan</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $no=0 ?>
				@if(!empty($chat_list))
				@foreach($chat_list as $chat_room)
				<?php $no++ ?>
				<tr>
					<td>{!! $no !!}</td>
					<td><?= $chat_room->created_date; ?></td>
					<td><?= $chat_room->tanggal?$chat_room->tanggal:str_replace('Klarifikasi Absen Tanggal','',$chat_room->topik); ?></td>
					<td><?= $chat_room->nama; ?></td>
					<td><?= $chat_room->topik; ?><?php if(!empty($chat_room->file))
						echo '
						<a href="'.asset('dist/img/file/'.$chat_room->file) .'" target="_blank" title="Download"><span class="fa fa-download"></span></a>';?></td>
					
					<td><?php
					if ($chat_room->tujuan==1)
						echo 'Absensi - Finger tidak terbaca'; else if ($chat_room->tujuan==2)
						echo 'Absensi - Izin'; else if ($chat_room->tujuan==3)
						echo 'Absensi - Sakit'; else if ($chat_room->tujuan==4)
						echo 'Absensi - Mesin absen Error'; else if ($chat_room->tujuan==5)
						echo 'Absensi - Lainnya'; else if ($chat_room->tujuan==6)
						echo 'Gaji -  Kelebihan bayar'; else if ($chat_room->tujuan==7)
						echo 'Gaji -  Kekurangan bayar'; else if ($chat_room->tujuan==8)
						echo 'Gaji -  Lainnya'; else if ($chat_room->tujuan==9)
						echo 'Lainnya'; ?></td>
						<td><?= $chat_room->deskripsi; ?></td>
						<td><?php
						if ($chat_room->selesai==0) {
							echo 'Proses HR';
						}else if ($chat_room->selesai==1) {
							echo 'Proses Atasan';
						}else if ($chat_room->selesai==2) {
							echo 'Entry Perubahan Absen';
						}else if ($chat_room->selesai==3) {
							echo 'Selesai';
						}else if ($chat_room->selesai==4) {
							echo 'Karyawan Selesai Konfirmasi';
						}else if ($chat_room->selesai==5) {
							echo 'Klarifikasi tidak bisa ditindak lanjuti';
						}else if ($chat_room->selesai==6) {
							echo 'Proses Karyawan';
						}; ?></td><td><?php
						if ($chat_room->appr_hr_status==3) {
							echo 'Pending';
						} else if ($chat_room->appr_hr_status==1) {
							echo 'Setuju dan Telah Dirubah';
						} else if ($chat_room->appr_hr_status==2) {
							echo 'Perlu Approve Atasan';
						}else if ($chat_room->appr_hr_status==4) {
							echo 'Perlu Konfirmasi Karyawan';
						}; ?></td>
						<td><?= $chat_room->nama_atasan; ?></td>
						
						<td><?php
						if ($chat_room->appr_status==3) {
							echo 'Pending';
						} else if ($chat_room->appr_status==1) {
							echo 'Setuju';
						} else if ($chat_room->appr_status==2) {
							echo 'Tolak';
						}; ?></td>
					

					<td style="text-align: center">
						
						
						<a href="{!!route('be.edit_chat_room',$chat_room->chat_room_id)!!}" class="btn btn-theme button-1 ctm-border-radiusfloat-right">
							<i class="fa fa-edit"></i></a>

						
					</td>

				</tr>
				@endforeach
				@endif
			</table>
		</div>
		<?php
		// print_r($agenda_perusahaan);
		?>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->
	<!-- /.card -->
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
