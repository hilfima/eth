
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
		<form action="{!!route('fe.simpan_laporan_gratifikasi')!!}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="card">
				@include('flash-message')
				<div class="card-body">
				<div class="form-group">
						<label>Tanggal Diterima</label>
						<input id="deskripsi_berita" type="date" name="data[tgl_diterima]" class="form-control " required></input>
					</div><div class="form-group">
						<label>Nama lembaga/perusahaan/nama pemberi</label>
												
							<input type="text" class="form-control " id="nama" name="data[dari]" placeholder="Nama lembaga/perusahaan/nama pemberi">
								
					</div><div class="form-group">
						<label>Nama Barang</label>
						<input type="text" class="form-control " id="nama" name="data[nama_tipe_pemberian]" placeholder="Nama Barang">
							
						

					</div><div class="form-group">
						<label>Perkiraan Harga </label>
						<input type="number" class="form-control " id="nama" name="data[perkiraan_harga]" placeholder="Perkiraan Harga" onkeypress="return isNumber(event)">
							
						

					</div><div class="form-group">
						<label>Bukti</label>
						<input id="deskripsi_berita" name="file" class="form-control " type="file" ></input>


					</div>
					<div class="form-group">
						<label>Keterangan*</label>
						<textarea id="deskripsi_berita" name="data[keterangan]" class="form-control summernote" required></textarea>


					</div>

					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>

		</form>

	</div>
</div>
<script>
	function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
@endsection