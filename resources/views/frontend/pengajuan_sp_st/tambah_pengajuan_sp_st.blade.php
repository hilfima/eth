
@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">



<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"> Pengajuan Surat Teguran dan peringatan </h4>


	</div>
</div>
<form action="{!!route('fe.'.$type,$id)!!}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="card">
		@include('flash-message')
		<div class="card-body">
			
			<div class="form-group">
				<label>Karyawan </label>
				<select id="deskripsi_berita" name="data[p_karyawan_id]" class="form-control select2" required>
					<option value="">- Karyawan -</option>
					<?php foreach($karyawan as $karyawan){?>
					
					<option value="<?=$karyawan->p_karyawan_id;?>" <?=$data['p_karyawan_id']==$karyawan->p_karyawan_id?'selected':''?>><?=$karyawan->nama;?></option>
					<?php }?>
					
				</select>
			</div>
			<div class="form-group">
				<label>Jenis Sanksi </label>
				<select id="deskripsi_berita" name="data[m_jenis_sanksi_id]" class="form-control " required>
					<option value="">- Jenis Sanksi -</option>
					<?php foreach($jenis_sanksi as $sanksi){?>
					
					<option value="<?=$sanksi->m_jenis_sanksi_id;?>" <?=$data['m_jenis_sanksi_id']==$sanksi->m_jenis_sanksi_id?'selected':''?>><?=$sanksi->nama_sanksi;?></option>
					<?php }?>
					
				</select>
			</div>
			
			<div class="form-group">
				<label>Alasan Sanksi</label>
				<textarea id="deskripsi_berita" name="data[alasan_sanksi]" class="form-control " required placeholder="Alasan Sanksi"><?=$data['alasan_sanksi']; ?></textarea>
				
			</div>
			<div class="form-group">
				<label>Tindakan Yang disarankan</label>
				<textarea id="deskripsi_berita" name="data[tindakan]" class="form-control " required placeholder="Tindakan"><?=$data['tindakan']; ?></textarea>
				
			</div>

			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
	</div>

</form>

</div>
</div>

@endsection