
@extends('layouts.app_fe')

@section('content')


<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2"> Keluh Kesah </h4>


			</div>
		</div>
		<form action="{!!route('fe.simpan_keluhkesah')!!}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="card">
				@include('flash-message')
				<div class="card-body">
					<div class="form-group">
						<label>Judul Keluh Kesah</label>
					<input id="deskripsi_berita" name="judul" class="form-control" placeholder="Judul" required>
					

					</div><div class="form-group">
						<label>Sampaikan Keluh Kesah</label>
					<textarea id="deskripsi_berita" name="isi" class="form-control summernote" required></textarea>
					

					</div>

					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>

		</form>

	</div>
</div>

@endsection