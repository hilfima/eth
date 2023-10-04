
@extends('layouts.appsA')

@section('content')


		<div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2"> Kotak Laporan Gratifikasi </h4>


			</div>
		</div> 	
		<form action="{!!route('be.update_laporan_gratifikasi',$id)!!}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="card">
				@include('flash-message')
				<div class="card-body">
				<div class="form-group">
						<label>Tanggal Diterima</label>
						<input id="deskripsi_berita" type="date" name="data[tgl_diterima]" class="form-control " readonly value="<?=$gratifikasi[0]->tgl_diterima?>"></input>
					</div><div class="form-group">
						<label>Yang Melaporkan</label>
												
							<select type="text" class="form-control select2" id="nama" name="data[p_karyawan_dari]" disabled>
								<option value="">- Pilih Karyawan - </option>
								<?php
									foreach ($karyawan as $karyawan) { ?>
											<option value="<?=$karyawan->p_karyawan_id ?>" <?=$gratifikasi[0]->p_karyawan_yg_melaporkan==$karyawan->p_karyawan_id?'selected':''?>><?=$karyawan->nama ?></option>
											<?php
									} ?>

							</select>
						

					</div>
					<div class="form-group">
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
					<?php if($type=='edit'){ ?>
					<div class="form-group">
						<label>Status</label>
						<select id="deskripsi_berita" name="data[status]" class="form-control " >
							<option value="">Pilih Status</option>
							<option value="2">Milik Karyawan</option>
							<option value="3">Diserahkan kepada perusahaan</option>
						</select>


					</div>
					<button type="submit" class="btn btn-primary">Simpan</button>
					<?php }?>
					</div>

					
				</div>
			</div>

		</form>

	</div>
</div>

@endsection