
@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">



<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"> Pengajuan Resign </h4>


	</div>
</div>
<form action="{!!route('fe.'.$type,$id)!!}" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<div class="card">
		@include('flash-message')
		<div class="card-body">
			
			<?php if($page!='karyawan'){?>
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
			<?php }?>
			<div class="form-group">
				<label>Tanggal Terakhir Kerja</label>
				<input type="date" id="deskripsi_berita" name="data[tanggal_terakhir_kerja]" class="form-control " required placeholder="Tanggal Terkhir Kerja" value="<?=$data['tanggal_terakhir_kerja']; ?>" />
				
			</div>
			<div class="form-group">
				<label>Alasan Mengundurkan Diri</label>
				<textarea id="deskripsi_berita" name="data[alasan_mengundurkan_diri]" class="form-control " required placeholder="Alasan Mengundurkan Diri"><?=$data['alasan_mengundurkan_diri']; ?></textarea>
				
			</div>
			<?php if($page=='karyawan'){?>
			<div class="form-group ">
                        <label class="">Atasan Layer 1</label>
                        <div class="">
                            <select class="form-control select2" name="data[appr_atasan]" style="width: 100%;" >
                                    <option value="">Pilih Atasan</option>
                                    <?php
                                    foreach($appr1 AS $appr){
                                        echo '<option value="'.$appr->p_karyawan_id.'">'.$appr->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                                       
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="">Atasan Layer 2*</label>
                        <div class="">
                            <select class="form-control select2" name="data[appr_direksi]" style="width: 100%;" required>
                                    <option value="">Pilih Atasan</option>
                                    <?php
                                    foreach($appr2 AS $appr){
                                        echo '<option value="'.$appr->p_karyawan_id.'">'.$appr->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                        </div>
                    </div>
			<?php }else if($page=='atasan'){?>
			 	<div class="form-group ">
                        <label class="">Approve Atasan</label>
                        <div class="">
                            <select class="form-control select2" onchange="change_appr_atasan(this)" name="data[status_appr_atasan]" style="width: 100%;" required>
                                    <option value="">Pilih Approve atasan</option>
                                    <option value="2">Tolak</option>
                                    <option value="1">Setuju</option>
                                   
                                </select>
                        </div>
                    </div>
                    <input  type="hidden"  name="data[tgl_appr_atasan]" value="<?=date('Y-m-d');?>"/>
                    <input  type="hidden" id="status_atasan" name="data[status]" value="2"/>
				<div class="form-group">
					<label>Keterangan Atasan</label>
					<textarea id="deskripsi_berita" name="data[keterangan_atasan]" class="form-control " required placeholder="Keterangan Atasan"><?=$data['keterangan_atasan']; ?></textarea>
					
				</div>
				<script>
					function change_appr_atasan(e){
						val = $(e).val();
						if(val==1)
						$('#status_atasan').val(2);
						else
						$('#status_atasan').val(11);
					}
				</script>
			<?php }else if($page=='direksi'){?>
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
					<textarea id="deskripsi_berita" class="form-control " disabled placeholder="Keterangan Atasan"><?=$data['keterangan_atasan']; ?></textarea>
					
				</div>
				<hr>
				<div class="form-group ">
                        <label class="">Approve Direksi</label>
                        <div class="">
                            <select class="form-control select2" onchange="change_appr_direksi(this)" name="data[status_appr_atasan]" style="width: 100%;" required>
                                    <option value="">Pilih Approve Direksi</option>
                                    <option value="2">Tolak</option>
                                    <option value="1">Setuju</option>
                                   
                                </select>
                        </div>
                    </div>
                    <input  type="hidden" name="data[tgl_appr_direksi]" value="<?=date('Y-m-d');?>"/>
                    <input  type="hidden" id="status_direksi" name="data[status]" value="3"/>
				<div class="form-group">
					<label>Keterangan Direksi</label>
					<textarea id="deskripsi_berita" name="data[keterangan_direksi]" class="form-control " required placeholder="Keterangan Direksi"></textarea>
					
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
			<?php }?>
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
	</div>

</form>

</div>
</div>

@endsection