
@extends('layouts.appsA')

@section('content')


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
						<label>Diterima Dari</label>
												
							<select type="text" class="form-control select2" id="nama" name="data[p_karyawan_dari]" >
								<option value="">- Pilih Karyawan - </option>
								<?php
						foreach ($karyawan as $karyawan) { ?>
								<option value="<?=$karyawan->p_karyawan_id ?>" ><?=$karyawan->nama ?></option>
								<?php
						} ?>

							</select>
						

					</div><div class="form-group">
						<label>Tipe Pemberian</label>
						<select type="text" class="form-control " id="nama" name="data[m_tipe_pemberian_id]" >
								<option value="">- Pilih Tipe Pemberian - </option>
								<?php
						foreach ($tipe_pemberian as $tipe_pemberian) { ?>
								<option value="<?=$tipe_pemberian->m_tipe_pemberian_id ?>" ><?=$tipe_pemberian->nama_tipe_pemberian ?></option>
								<?php
						} ?>

							</select>
						

					</div><div class="form-group">
						<label>Bukti</label>
						<input id="deskripsi_berita" name="file" class="form-control " type="file" ></input>


					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea id="deskripsi_berita" name="data[keterangan]" class="form-control summernote" required></textarea>


					</div>

					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>

		</form>

	</div>
</div>

@endsection