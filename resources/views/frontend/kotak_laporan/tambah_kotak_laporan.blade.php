
@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">



		<div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2"> Kotak Laporan </h4>


			</div>
		</div> 	
		<form action="{!!route('fe.simpan_kotak_laporan')!!}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="card">
				@include('flash-message')
				<div class="card-body">
					<!--<div class="form-group">
						<label>Karyawan yang dilaporkan</label>
												
							<select type="text" class="form-control select2" id="nama" name="p_karyawan_id" >
								<option value="">- Pilih Karyawan - </option>
								<?php
						foreach ($karyawan as $karyawan) { ?>
								<option value="<?=$karyawan->p_karyawan_id ?>" <?=$data['p_karyawan_id']==$karyawan->p_karyawan_id?'selected':''; ?>><?=$karyawan->nama ?></option>
								<?php
						} ?>

							</select>
						

					</div>--><div class="form-group">
						<label>Judul laporan</label>
						<input id="deskripsi_berita" name="judul" class="form-control " required></input>
					</div><div class="form-group">
						<label>Tgl Kejadian</label>
						<input id="deskripsi_berita" name="tgl" class="form-control " type="date" required></input>


					</div><div class="form-group">
						<label>Lampiran</label>
						<input id="deskripsi_berita" name="file" class="form-control " type="file" ></input>


					</div>
					<div class="form-group">
						<label>Penjelasan</label>
						<textarea id="deskripsi_berita" name="penjelasan" class="form-control summernote" required></textarea>


					</div>

					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>

		</form>

	</div>
</div>

@endsection