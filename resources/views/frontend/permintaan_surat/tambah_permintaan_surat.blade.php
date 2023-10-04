
@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">



<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"> Permintaan Surat </h4>


	</div>
</div>
<form action="{!!route('fe.'.$type,$id)!!}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="card">
		@include('flash-message')
		<div class="card-body">
			
			<div class="form-group">
				<label>Jenis Surat </label>
				<select id="deskripsi_berita" name="jenis_surat" class="form-control " required>
					<option value="">- Jenis Surat -</option>
					<option value="Surat Pengantar" <?=$data['jenis_surat']=='Surat Pengantar'?'selected':'' ?>>Surat pengatar</option>
					<option value="Keterangan Kerja" <?=$data['jenis_surat']=='Keterangan Kerja'?'selected':'' ?>>Keterangan Kerja</option>
					<option value="Paklaring" <?=$data['jenis_surat']=='Paklaring'?'selected':'' ?>>Paklaring </option>
					<option value="Lainnya" <?=$data['jenis_surat']=='Lainnya'?'selected':'' ?>>Lainnya</option>
				</select>
			</div>
			
			<div class="form-group">
				<label>Rincian Lengkap</label>
				<textarea id="deskripsi_berita" name="keterangan_surat" class="form-control " required><?=$data['keterangan_surat']; ?></textarea>
				<Span class="help-block">Sertakan rincian ditujukan kesiapa, tujuan, dan hal yang lain yang perlu disertakan</Span>

			</div>

			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
	</div>

</form>

</div>
</div>

@endsection