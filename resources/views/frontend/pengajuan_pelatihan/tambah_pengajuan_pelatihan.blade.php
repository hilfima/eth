
@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">



		<div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2"> @if($type!='lihat_agenda_perusahaan')Pengajuan Pelatihan @else Agenda Perusahaan @endif 
				
				
			
				</h4>

	<div class="text-right">
		
	</div>
			</div>
		</div>
		<form action="{!!route('fe.'.$type,$id)!!}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="card">
				@include('flash-message')
				<div class="card-body">
				<div class="row">

					<div class="col-md-12">
						<div class="form-group">
							<label>Nama  </label>
							<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama " value="<?=$data['nama']; ?>" <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?>>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Tanggal Awal</label>
							<input type="date" class="form-control" id="nama" name="tgl_awal" placeholder="Tanggal Awal" value="<?=$data['tgl_awal']; ?>" <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?>>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Tanggal Akhir</label>
							<input type="date" class="form-control" id="nama" name="tgl_akhir" placeholder="Nama Agenda Perusahaan" value="<?=$data['tgl_akhir']; ?>" <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?>>
						</div>
					</div><!--
					<div class="col-md-6">
						<div class="form-group">
							<label>Jam Mulai</label>
							<input type="time" class="form-control" id="nama" name="jam_mulai" placeholder="Nama Agenda Perusahaan" value="<?=$data['jam_mulai']; ?>" <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?>>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Jam Selesai </label>
							<input type="time" class="form-control" id="nama" name="jam_selesai" placeholder="Jam Selesai" value="<?=$data['jam_selesai']; ?>" <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?>>
						</div>
					</div>-->
					<div class="col-md-12">
						<div class="form-group">
							<label>Lokasi  </label>
							<input type="text" class="form-control" id="nama" name="lokasi" placeholder="Lokasi Diajukan" value="<?=$data['lokasi']; ?>" <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?>>
						</div>
					</div><div class="col-md-12">
						<div class="form-group">
							<label>Kontak Person </label>
							<input type="text" class="form-control" id="nama" name="cp" placeholder="Kontak Person"  <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?> value="<?=$data['cp']; ?>" >
						</div>
					</div>
					<!--<div class="col-md-12">
						<div class="form-group">
							<label>Deskripsi Pelatihan</label>
							<textarea type="number" class="form-control " id="nama" name="deskripsi" placeholder="Keterangan" value="" <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?>><?=$data['deskripsi']; ?></textarea>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Peserta</label>
							 <?php  if(in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))){
							 	echo '<ul>';
							 	for($i=0;$i<count($nama);$i++){
							 		echo '<li>'.$nama[$i].'</li>';
							 	}
							 	echo '</ul>';
							 	
							 }else{?>
							<select type="number" class="form-control select2" multiple="" id="nama" name="karyawan[]" placeholder="Keterangan" value=""><?=$data['deskripsi']; ?>
								<option value="">- Pilih Karyawan -</option>
								<?php foreach($karyawan as $karyawan){?>
								<option value="<?=$karyawan->p_karyawan_id?>" <?= in_array($karyawan->p_karyawan_id,$list_karyawan)?'selected':'';?>><?=$karyawan->nama?></option>
								<?php }?>
							</select>
							 <?php }?>
						</div>
					</div>-->
					<div class="col-md-12">
						<div class="form-group">
							<label>Brosur</label>
							<input  type="file" class="form-control " id="nama" name="brosur" placeholder="Keterangan" value="" disabled>
							@if(!empty($data['brosur']))
							<div class="text-center">
								
                                   <a href="{!! asset('dist/img/file/'.$data['brosur']	) !!}" target="_blank" title="Download" style="text-align:center">
                                   	<img src="{{asset('dist/img/file/'.$data['brosur'])}}" width="300px" >
                                   	
                                   </a>
							</div>
                                   @endif
						</div>
					</div>
					<div class="col-md-12">
					<?php 
					if(in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))){
						echo $data['deskripsi'];
					}else{?>
						
						<div class="form-group">
							<label>@if($type!='lihat_agenda_perusahaan')Alasan Pengajuan @else Deskripsi @endif</label>
							<textarea type="number" class="form-control " <?= in_array($type,array("baca_pengajuan_pelatihan","lihat_agenda_perusahaan"))?'disabled':'';?> id="nama" name="alasan" placeholder="" value=""><?=$data['alasan_pengajuan']; ?></textarea>
							
					<?php }?>

						</div>
					</div>





				</div>
					<?php if($type!='lihat_agenda_perusahaan'){?>
					<button type="submit" class="btn btn-primary">Simpan</button>
					<?php }else{?>
					<div class="d-flex text-center" style="justify-content: center;">
						
					@if(!in_array($data['konfirmasi_kehadiran'],array(1,2)))
	<a href="{!! route('fe.absen_kehadiran_agenda_konfirmasi_hadir',$data['t_agenda_perusahaan_karyawan_id']) !!}" title='Hadir' class="btn btn-primary mb-5 mt-3" data-toggle='tooltip' style="margin-right: 5px">
							<i class="fa fa-check" aria-hidden="true"></i> Hadir
						</a>
						<a href="{!! route('fe.absen_kehadiran_agenda_konfirmasi_tdkhadir',$data['t_agenda_perusahaan_karyawan_id']) !!}" title='Hadir' class="btn btn-primary mb-5 mt-3" data-toggle='tooltip'>
							<i class="fa fa-times" aria-hidden="true"></i> Tidak Hadir
						</a>
						
						@endif	
					</div>
					<?php }?>
				
			</div>

		</form>

	</div>
</div>

@endsection