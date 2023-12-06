
@extends('layouts.appsA')

@section('content')


<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"> Permintaan Surat </h4>


	</div>
</div>
<form action="{!!route('be.'.$type,$id)!!}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="card">
		@include('flash-message')
		<div class="card-body">
			
			<div class="form-group">
				<label>Karyawan </label>
				<select id="deskripsi_berita" name="karyawan" class="form-control select2" required>
					<option value="">- Karyawan -</option>
					<?php foreach($karyawan as $karyawan){?>
					
					<option value="<?=$karyawan->p_karyawan_id;?>" <?=$data['p_karyawan_id']==$karyawan->p_karyawan_id?'selected':''?>><?=$karyawan->nama;?></option>
					<?php }?>
					
				</select>
			</div>
			<div class="form-group">
				<label>Jenis Sanksi </label>
				<select id="deskripsi_berita" name="data[m_jenis_sanksi_id]" class="form-control " required>
					<option value="">- Jenis Surat -</option>
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
			
			<div class="form-group">
				<label>Status</label>
				<select  name="data[status]" class="form-control " required placeholder="Tindakan">
					<option value="3" <?=$data['status']==3?'selected':''; ?>">Sanksi di proses HC</option>
					<option value="4" <?=$data['status']==4?'selected':''; ?>">Sanksi ditolak</option>
					<option value="5" <?=$data['status']==5?'selected':''; ?>">Sanksi Selesai & Disetujui</option>
				
				</select>
			</div>
			

			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
	</div>

</form>

</div>
</div>

@endsection