@extends('layouts.app_fe')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
         <div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2">Pengajuan Karyawan Baru</h4>
			</div>
		</div>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
               <h3 class="card-title mb-0 pb-0">Tambah</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.update_karyawan_baru',$tkaryawan[0 ]->t_karyawan_id) !!}" enctype="multipart/form-data" onsubmit="return confirm('Apakah anda yakin?');">
                    {{ csrf_field() }}
                    <div class="row">
                       <div class="col-sm-12" id="karyawanKonten">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Posisi Yang Dibutuhkan</label>
                                
								 <select class="form-control select2" name="jabatan"   style="width: 100%;" required onchange="changeposisi(this)">
                                    <option  value="">Pilih Jabatan</option>
                                   <option value="-1" <?= $tkaryawan[0]->m_jabatan_id==-1?'selected':'';?>
                                    	>Posisi Baru</option>
                                    <?php foreach($jabatan as $kar){?>
                                    <option value="<?=$kar->m_jabatan_id;?>"
                                    <?= $tkaryawan[0]->m_jabatan_id==$kar->m_jabatan_id?'selected':'';?>
                                    	><?=$kar->nama;?></option>
									<?php }?>
                                </select>
                                
                        </div>
                            <div id="jabatan_lain" <?= $tkaryawan[0]->m_jabatan_id==-1?'':'style="display: none"'?> >
                            <div class="form-group" >
                                <label>Nama Posisi</label>
                              <input class="form-control " id="tgl_absen" name="posisi"   placeholder="Nama Posisi" value="<?= $tkaryawan[0]->posisi?>">
                              	
                              </div>
                            <div class="form-group">
                                <label>Dapartement</label>
                              <select class="form-control " name="dept"  style="width: 100%;"  >
                                   
                                    <?php foreach($departemen as $dept){
                                    	if($dept->m_departemen_id==$pekerjaan[0]->m_departemen_id){?>
                                    <option value="<?=$dept->m_departemen_id;?>"  <?= $tkaryawan[0]->m_departemen_id==$dept->m_departemen_id?'selected':'';?>
                                    	><?=$dept->nama;?></option>
									<?php }?>
									<?php }?>
                                </select>
                            </div><div class="form-group">
                                <label>Level</label>
                               <select class="form-control select2" name="level" id="karyawan"  style="width: 100%;" >
                                    <option  value="">Pilih Level</option>
                                    <?php foreach($pangkat as $p){?>
                                    <option value="<?=$p->m_pangkat_id;?>" <?= $tkaryawan[0]->m_pangkat_id==$p->m_pangkat_id?'selected':'';?>
                                    	><?=$p->nama;?></option>
									<?php }?>
                                </select>
                            </div><div class="form-group">
                                <label>Entitas</label>
                               <select class="form-control " name="lokasi" id="karyawan"  style="width: 100%;" >
                                   
                                    <?php foreach($lokasi as $lokasi){
                                    	if($lokasi->m_lokasi_id==$tkaryawan[0]->m_lokasi_id){?>
                                    <option value="<?=$lokasi->m_lokasi_id;?>" <?= $tkaryawan[0]->m_lokasi_id==$lokasi->m_lokasi_id?'selected':'';?>
                                    	><?=$lokasi->nama;?></option>
									
									<?php }
									 }?>
                                </select>
                            </div>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Penempatan</label> 
                              <input class="form-control"  name="lokasi_penempatan"   placeholder="Lokasi Penempatan..." value="<?=$tkaryawan[0]->lokasi_penempatan?>">    
                            </div> 
                            <div class="form-group">
                                <label>Jumlah Kebutuhan</label>
                              <input class="form-control"  name="kebutuhan"   placeholder="Jumlah Kebutuhan..." required="" value="<?=$tkaryawan[0]->jumlah_dibutuhkan?>">    
                            </div> 
                            <div class="form-group">
                                <label>Tanggal Diperlukan</label>
                              <input class="form-control" type="date" name="tangal_diperlukan"   placeholder="Jumlah Kebutuhan..."  value="<?=$tkaryawan[0]->tgl_diperlukan?>">    
                            </div>
                            <div class="form-group">
                                <label>Alasan Permintaan</label>
                              <select class="form-control " id="tgl_absen" name="alasan" required=""  placeholder="Keterangan" onclick="changealasan(this)">
                                    	
                                    	<option value="">- Pilih Alasan Permintaan -</option>
                                    	<option value="Pergantian Karyawan"
                                    	<?= $tkaryawan[0]->alasan=='Pergantian Karyawan'?'selected':'';?>
                                    	>Pergantian Karyawan</option>
                                    	<option value="Penambahan Karyawan"
                                    	<?= $tkaryawan[0]->alasan=='Penambahan Karyawan'?'selected':'';?>
                                    	>Penambahan Karyawan</option>
                                    	<option value="Penambahan & Pergantian Karyawan"
                                    	<?= $tkaryawan[0]->alasan=='Penambahan & Pergantian Karyawan'?'selected':'';?>
                                    	>Penambahan & Pergantian Karyawan</option>
                                    </select>
                            </div>
                            
                             <div id="tambahkaryawan" style="display: none">
	                             <div class="form-group">
	                                <label>Alasan Penambahan Karyawan</label>
	                              <textarea class="form-control " id="tgl_absen" name="alasantambah"   placeholder="Deskripsi"><?=$tkaryawan[0]->penambahan_karyawan?></textarea>
	                            </div>
                             </div>
                             <div id="gantikaryawan"  style="display: none">
	                             <div class="form-group">
	                                <label>Nama Karyawan yang diganti</label>
	                              <select class="form-control select2" id="tgl_absen" name="alasanganti"   placeholder="Deskripsi" style="width: 100%">
	                              	<option value="" >- Pilih Karyawan -</option>
	                              	<?php foreach($karyawan as $karyawan){?>
	                              	<option value="<?=$karyawan->p_karyawan_id;?>"
                                    	<?= $tkaryawan[0]->pergantian_karyawan_id==$karyawan->p_karyawan_id?'selected':'';?>
                                    	><?=$karyawan->nama;?></option>
	                              	<?php }?>
	                              </select>
	                            </div>
	                            <!--<div class="form-group">
                                	<label>Alasan Resign</label>
	                              	<textarea class="form-control " id="tgl_absen" name="resign"   placeholder="Alasan Resign"></textarea>
	                            </div>-->
                             </div>
                             <div class="form-group">
                                <label>Gambarkan Posisi dalam Struktur Organisasi</label>
                              <input type="file" class="form-control" name="struktur"   placeholder="Deskripsi"   value="<?=$tkaryawan[0]->file_gambaran_struktur?>">
                            </div><div class="form-group">
                                <label>Uraian Pekerjaan</label>
                              <textarea class="form-control summernote" id="tgl_absen" name="uraian"   required="" placeholder="Deskripsi"><?=$tkaryawan[0]->uraian_pekerjaan?></textarea>
                            </div>
                           <div class="form-group ">
                                <label class="">Kualifikasi Usia</label>
                                <div class="row">
                                	<div class="col-6">
                                		
                              <input type="number" class="form-control  " id="tgl_absen" name="usia_dari"   placeholder="Usia dari.." required=""  value="<?=$tkaryawan[0]->kualifikasi_usia_dari?>">
                                	</div>
                                	<div class="col-6">
                              <input type="number" class="form-control  " id="tgl_absen" name="usia_ke"    placeholder="Usia Sampai.." required="" value="<?=$tkaryawan[0]->kualifikasi_usai_sampai?>" >
                                	</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Kualifikasi Jenis Kelamin</label>
                                 <select class="form-control " id="tgl_absen" name="jk"   required="" >
                                    	
                                    	<option value="">- Pilih Kualifikasi Jenis Kelamin -</option>
                                    	<option value="Laki Laki"
                                    	<?= $tkaryawan[0]->kualifikasi_jenis_kelamin=='Laki Laki'?'selected':'';?>
                                    	>Laki Laki</option>
                                    	<option value="Wanita"
                                    	<?= $tkaryawan[0]->kualifikasi_jenis_kelamin=='Wanita'?'selected':'';?>
                                    	>Wanita</option>
                                    	<option value="Pria dan Wanita"
                                    	<?= $tkaryawan[0]->kualifikasi_jenis_kelamin=='Pria dan Wanita'?'selected':'';?>
                                    	>Pria dan Wanita</option>
                                    </select>
                             
                            </div><div class="form-group">
                                <label>Kualifikasi Keahlian</label>
                              <input class="form-control "  name="k_keahlian"   required=""  placeholder="Kualifikasi Kompetensi Kerja" value="<?=$tkaryawan[0]->kualifikasi_keahlian?>" >
                            
                            </div>
                           <div class="form-group">
                                <label>Kualifikasi Minimal Pendidikan dan Jurusan</label>
                              <input class="form-control "  name="k_pendidikan"   placeholder="Kualifikasi Minimal Pendidikan dan Jurusan" value="<?=$tkaryawan[0]->kualifikasi_pendidikan?>" >
                            </div>
                        <div class="form-group">
                                <label>Kualifikasi Pengalaman Kerja</label>
                              <input class="form-control "  name="k_pengalaman"   placeholder="KualifikasiPengalaman Kerja" value="<?=$tkaryawan[0]->kualifikasi_pengalaman?>" >
                            </div><div class="form-group">
                                <label>Kompetensi lainnya</label>
                              <textarea class="form-control "  name="k_kompetensi"   placeholder="Kompetensi lainnya"><?=$tkaryawan[0]->kualifikasi_kompetisi?></textarea>
                            </div>
                            <hr>
                            <div class="form-group">
	                                <label>Atasan 1(Atasan Langsung)</label>
	                              <select class="form-control select2" id="tgl_absen" name="atasan"   required="" style="width: 100%">
	                              	<option value="">- Pilih Atasan 1-</option>
	                              	<?php foreach($appr as $atasan){?> 
	                              	<option value="<?=$atasan->p_karyawan_id;?>" <?=$tkaryawan[0]->kualifikasi_kompetisi?>><?=$atasan->nama_lengkap;?></option>
	                              	<?php }?>
	                              </select>
	                            </div>
                            <div class="form-group">
	                                <label>Atasan 2(Direksi)</label>
	                              <select class="form-control select2" id="tgl_absen" name="atasan2"   required="" style="width: 100%">
	                              	<option value="">- Pilih Atasan 2-</option>
	                              	<?php foreach($apprdireksi as $atasan){?>
	                              	<option value="<?=$atasan->p_karyawan_id;?>"><?=$atasan->nama_lengkap;?></option>
	                              	<?php }?>
	                              </select>
	                            </div>
                        </div>
                           
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.karyawan_baru') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    </div>
  <script>
  	function changeposisi(e){
  		
  		if($(e).val()=='-1'){
			$('#jabatan_lain').show();
		}else{
			$('#jabatan_lain').hide();
			
		}
	}function changealasan(e){
  		
  		if($(e).val()=='Penambahan Karyawan'){
			$('#tambahkaryawan').show();
			$('#gantikaryawan').hide();
		}else if($(e).val()=='Pergantian Karyawan'){
			$('#tambahkaryawan').hide();
			$('#gantikaryawan').show();
		}else{
			$('#tambahkaryawan').show();
			$('#gantikaryawan').show();
			
		}
	}
  	function check(e){
  		if ($(e).is(':checked')) {
			$('#karyawanKonten').show();
		}else
			$('#karyawanKonten').hide();
			
		
		$('#karyawan').val('');
		changeentitas();
	}
  	function changeentitas(){
		var entitas = $('#entitas').val();
		
		$('#karyawan').val('');
  		
  			$.ajax({
				type: 'get',
				
				url: 'daftarKaryawan/'+entitas+'/',
				dataType: 'json',
				success: function(data){
					//alert(data.respon)
					$('#karyawan').html(data.respon);
				}
			});
		
	}
  	 
  </script>
    <!-- /.content-wrapper -->
@endsection
