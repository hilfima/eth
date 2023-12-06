
@extends('layouts.appsA')

@section('content')
	 



<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"> Pengajuan Resign </h4>


	</div>
</div>
<form action="{!!route('be.'.$type,$id)!!}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="card">
		@include('flash-message')
		<div class="card-body">
			
				<div class="form-group">
					<label>NIK</label>
					<input type="text"  class="form-control " disabled placeholder="Tanggal Terkhir Kerja" value="<?=$data['nik']; ?>" />
					
				</div><div class="form-group">
					<label>Nama Karyawan</label>
					<input type="text"  class="form-control " disabled placeholder="Tanggal Terkhir Kerja" value="<?=$data['nama_karyawan']; ?>" />
					
				</div><div class="form-group">
					<label>Jabatan</label>
					<input type="text" class="form-control " disabled placeholder="Tanggal Terkhir Kerja" value="<?=$data['nama_jabatan']; ?>" />
					
				</div>
			<div class="form-group">
				<label>Tanggal Terakhir Kerja</label>
				<input type="date" id="deskripsi_berita" name="data[tanggal_terakhir_kerja]" class="form-control " required placeholder="Tanggal Terkhir Kerja" value="<?=$data['tanggal_terakhir_kerja']; ?>" />
				
			</div>
			<div class="form-group">
				<label>Alasan Mengundurkan Diri</label>
				<textarea id="deskripsi_berita" name="data[alasan_mengundurkan_diri]" class="form-control " required placeholder="Alasan Mengundurkan Diri"><?=$data['alasan_mengundurkan_diri']; ?></textarea>
				
			</div>
			
			<div class="form-group ">
                        <label class="">Approve Atasan</label>
                        <div class="">
                            <select class="form-control select2" style="width: 100%;" disabled>
                                    <option value="">Pilih Approve atasan</option>
                                    <option value="2" <?=$data['status_appr_atasan']==2?'selected=""':'';?>>Tolak</option>
                                    <option value="1" <?=$data['status_appr_atasan']==1?'selected=""':'';?>>Setuju</option>
                                   
                                </select>
                        </div>
                    </div>
                   
				<div class="form-group">
					<label>Keterangan Atasan</label>
					<textarea id="deskripsi_berita" class="form-control " disabled placeholder="Keterangan Atasan" disabled><?=$data['keterangan_atasan']; ?></textarea>
					
				</div>
				<hr>
				<div class="form-group ">
                        <label class="">Approve Direksi</label>
                        <div class="">
                            <select class="form-control select2"  style="width: 100%;" disabled>
                                    <option value="">Pilih Approve Direksi</option>
                                    <option value="2" <?=$data['status_appr_direksi']==2?'selected=""':'';?>>Tolak</option>
                                    <option value="1" <?=$data['status_appr_direksi']==1?'selected=""':'';?>>Setuju</option>
                                   
                                </select>
                        </div>
                    </div>
				<div class="form-group">
					<label>Keterangan Direksi</label>
					<textarea id="deskripsi_berita" class="form-control " disabled placeholder="Keterangan Direksi"><?=$data['keterangan_direksi']; ?></textarea>
					
				</div>
				
				<hr>
				<div class="form-group ">
                        <label class="">Proses HC</label>
                        <div class="">
                            <select class="form-control select2" name="data[status]" style="width: 100%;" >
                                    <option value="">Pilih Proses HC</option>
                                    <option value="33" <?=$data['status_appr_direksi']==33?'selected=""':'';?>>Ditolak HC</option>
                                    <option value="4" <?=$data['status_appr_direksi']==4?'selected=""':'';?>>Pengajuan resign disetujui</option>
                                   
				
                                </select>
                        </div>
                    </div>
				<div class="form-group">
					<label>Periode Terakhir Penggajian</label>
					<select id="deskripsi_berita" class="form-control " name="data[m_periode_terakhir_gajian]"  required >
						<option value="">Pilih Periode</option>
						<?php foreach($periode as $periode){
							echo '<option value="'.$periode->periode_absen_id.'">'.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';}
							?>
					</select>
				</div>
				
				<script>
					function change_appr_direksi(e){
						val = $(e).val();
						if(val==1)
						$('#status_direksi').val(3);
						else
						$('#status_direksi').val(22);
					}
				</script>
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
	</div>

</form>

</div>
</div>

@endsection