
@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">



		<div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2"> Kotak Laporan Gratifikasi </h4>


			</div>
		</div> 	
		<form action="{!!route('fe.simpan_laporan_gratifikasi')!!}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="card">
				@include('flash-message')
				<div class="card-body">
				<div class="form-group">
						<label>Tanggal Diterima</label>
						<input id="deskripsi_berita" type="date" name="data[tgl_diterima]" class="form-control " readonly value="<?=$gratifikasi[0]->tgl_diterima?>"></input>
					</div><div class="form-group">
						<label>Nama lembaga/perusahaan/nama pemberi</label>
												
							<input type="text" class="form-control " id="nama" name="data[dari]" placeholder="Nama lembaga/perusahaan/nama pemberi" readonly value="<?=$gratifikasi[0]->dari?>">
								
					</div><div class="form-group">
						<label>Nama Barang</label>
						<input type="text" class="form-control " id="nama" name="data[nama_tipe_pemberian]" placeholder="Nama Barang" readonly value="<?=$gratifikasi[0]->nama_tipe_pemberian?>">
							
						

					</div><div class="form-group">
						<label>Perkiraan Harga </label>
						<input type="text" class="form-control " id="nama" name="data[perkiraan_harga]" placeholder="Perkiraan Harga" readonly value="<?=$gratifikasi[0]->perkiraan_harga?>">
							
						

					</div><div class="form-group">
						<label>Bukti</label>
						<input id="deskripsi_berita" name="file" class="form-control " type="file" ></input>


					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea id="deskripsi_berita" name="data[keterangan]" class="form-control " readonly><?=$gratifikasi[0]->keterangan?></textarea>


					</div>

					
				</div>
			</div>

		</form>

	</div>
</div>

@endsection