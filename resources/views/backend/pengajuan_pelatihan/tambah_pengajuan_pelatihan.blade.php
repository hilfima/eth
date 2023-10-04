
@extends('layouts.appsA')

@section('content')


<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"> Pengajuan Pelatihan</h4>


	</div>
</div>
<form action="{!!route('be.'.$type,$id)!!}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="card">
	@include('flash-message')
	<div class="card-body">
		<div class="row">

			<div class="col-md-12">
				<div class="form-group">
					<label>Nama Pelatihan </label>
					<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pelatihan" value="<?=$data['nama']; ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Tanggal Awal</label>
					<input type="date" class="form-control" id="nama" name="tgl_awal" placeholder="Tanggal Awal" value="<?=$data['tgl_awal']; ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Tanggal Akhir</label>
					<input type="date" class="form-control" id="nama" name="tgl_akhir" placeholder="Nama Agenda Perusahaan" value="<?=$data['tgl_akhir']; ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Jam Mulai</label>
					<input type="time" class="form-control" id="nama" name="jam_mulai" placeholder="Nama Agenda Perusahaan" value="<?=$data['jam_mulai']; ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Jam Selesai </label>
					<input type="time" class="form-control" id="nama" name="jam_selesai" placeholder="Jam Selesai" value="<?=$data['jam_selesai']; ?>">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label>Lokasi Diajukan </label>
					<input type="text" class="form-control" id="nama" name="lokasi" placeholder="Lokasi Diajukan" value="<?=$data['lokasi']; ?>">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label>Kontak Person </label>
					<input type="text" class="form-control" id="nama" name="cp" placeholder="Kontak Person" value="<?=$data['cp']; ?>">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label>Deskripsi Pelatihan</label>
					<textarea type="number" class="form-control summernote" id="nama" name="deskripsi" placeholder="Keterangan" value=""><?=$data['deskripsi']; ?></textarea>
					<?=$data['deskripsi']; ?>				</div>
			</div>

			<div class="col-md-12">
				<div class="form-group">
					<label>Status</label>
					<select type="text" class="form-control" id="nama" name="status" placeholder="Kontak Person">
						<option value="1" <?=$data['status']==1?'selected':''; ?>>Disetujui</option>
						<option value="2" <?=$data['status']==2?'selected':''; ?>>Pending</option>
					</select>
				</div>
			</div>




		</div>

		<button type="submit" class="btn btn-primary">Simpan</button>

	</div>

</form>

</div>
</div>

@endsection