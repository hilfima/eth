 @extends('layouts.app_fe')



@section('content')
					<!-- Page Title -->
					 <div class="content-wrapper">
					 <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
    @include('flash-message')
   
    
	<!-- Main content -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Pengajuan Karyawan Baru</h4>
<ul class="nav nav-tabs float-right border-0 tab-list-emp">

</ul>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.update_karyawan_baru',$tkaryawan[0]->t_karyawan_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                       
                        
                           
                                    
                                    <div class="col-sm-12" id="karyawanKonten">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Posisi Yang Dibutuhkan</label>
                                
								 <select class="form-control " name="jabatan"  readonly  style="width: 100%;"  onchange="changeposisi(this)" readonly>
                                    <option value="" >Pilih Jabatan</option>
                                   <option value="-1"  <?=$tkaryawan[0]->m_jabatan_id==-1?'selected':''?>>Lainnya</option>
                                    <?php foreach($jabatan as $kar){?>
                                    <option value="<?=$kar->m_jabatan_id;?>" <?=$tkaryawan[0]->m_jabatan_id==$kar->m_jabatan_id?'selected':''?>><?=$kar->nama;?></option>
									<?php }?>
                                </select>
                                
                        </div>
                           <?php if($tkaryawan[0]->m_jabatan_id==-1){?>
                          
                            <div class="form-group" >
                                <label>Posisi Lainnya</label>
                              <input class="form-control " id="tgl_absen" name="posisi"  value="<?=$tkaryawan[0]->posisi?>" placeholder="Posisi Lainnya"  readonly>
                              	
                              </div>
                            <div class="form-group">
                                <label>Dapartement</label>
                              <select class="form-control " name="dept"  style="width: 100%;"   readonly>
                                   
                                    <?php 
                                    if(isset($pekerjaan[0]->m_departemen_id)){
                                    foreach($departemen as $dept){
                                      
                                    	if($dept->m_departemen_id==$pekerjaan[0]->m_departemen_id){?>
                                    <option value="<?=$dept->m_departemen_id;?>" ><?=$dept->nama;?></option>
									<?php }
                }?>
									<?php }?>
                                </select>
                            </div><div class="form-group">
                                <label>Level</label>
                               <select class="form-control " name="level" id="karyawan"  style="width: 100%;"  readonly>
                                    <option value="" >Pilih Level</option>
                                    <?php foreach($pangkat as $p){?>
                                    <option value="<?=$p->m_pangkat_id;?>" <?=$tkaryawan[0]->m_pangkat_id==$p->m_pangkat_id?'selected':''?>><?=$p->nama;?></option>
									<?php }?>
                                </select>
                            </div><div class="form-group">
                                <label>Entitas</label>
                               <select class="form-control " name="lokasi" id="karyawan"  style="width: 100%;"  readonly>
                                   
                                    <?php 
                                    if(isset($pekerjaan[0]->m_departemen_id)){
                                      foreach($lokasi as $lokasi){
                                    	if($lokasi->m_lokasi_id==$pekerjaan[0]->m_lokasi_id){?>
                                    <option value="<?=$lokasi->m_lokasi_id;?>"  <?=$tkaryawan[0]->m_lokasi_id==$lokasi->m_lokasi_id?'selected':''?>><?=$lokasi->nama;?></option>
									<?php }}?>
									<?php }?>
                                </select>
                            </div>
                            <?php }?>
                            <div class="form-group">
                                <label>Lokasi Penempatan</label>
                              <input class="form-control"  name="kebutuhan"  value="<?=$tkaryawan[0]->jumlah_dibutuhkan?>" placeholder="Jumlah Kebutuhan..."  readonly>    
                            </div>
                            <div class="form-group">
                                <label>Jumlah Kebutuhan</label>
                              <input class="form-control"  name="kebutuhan"  value="<?=$tkaryawan[0]->jumlah_dibutuhkan?>" placeholder="Jumlah Kebutuhan..."  readonly>    
                            </div> <div class="form-group">
                                <label>Tanggal Diperlukan</label>
                              <input class="form-control" type="date" name="tangal_diperlukan"  value="<?=$tkaryawan[0]->tgl_diperlukan?>" placeholder="Jumlah Kebutuhan..."  readonly>    
                            </div>
                            <div class="form-group">
                                <label>Alasan Permintaan</label>
                              <select class="form-control " id="tgl_absen" name="alasan"  value="" placeholder="Keterangan" onclick="changealasan(this)"  readonly>
                                    	
                                    	<option value="">- Pilih Alasan Permintaan -</option>
                                    	<option value="Pergantian Karyawan" <?=$tkaryawan[0]->alasan=='Pergantian Karyawan'?'selected':''?>>Pergantian Karyawan</option>
                                    	<option value="Penambahan Karyawan"<?=$tkaryawan[0]->alasan=='Penambahan Karyawan'?'selected':''?>>Penambahan Karyawan</option>
                                    	<option value="Penambahan & Pergantian Karyawan"<?=$tkaryawan[0]->alasan=='Penambahan & Pergantian Karyawan'?'selected':''?>>Penambahan & Pergantian Karyawan</option>
                                    </select>
                            </div>
                            
                             <div id="tambahkaryawan" style="display: none">
	                             <div class="form-group">
	                                <label>Alasan Penambahan Karyawan</label>
	                              <textarea class="form-control " id="tgl_absen" name="alasantambah"   readonly value="" placeholder="Deskripsi"><?=$tkaryawan[0]->penambahan_karyawan?></textarea>
	                            </div>
                             </div><div id="gantikaryawan"  style="display: none">
	                             <div class="form-group">
	                                <label>Nama Karyawan yang diganti</label>
	                              <select class="form-control " id="tgl_absen" name="alasanganti"  value="" placeholder="Deskripsi" readonly>
	                              	<option value="">- Pilih Karyawan -</option>
	                              	<?php foreach($karyawan as $karyawan){?>
	                              	<option value="<?=$karyawan->p_karyawan_id;?>"<?=$tkaryawan[0]->pergantian_karyawan_id==$karyawan->p_karyawan_id?'selected':''?> ><?=$karyawan->nama;?></option>
	                              	<?php }?>
	                              </select>
	                            </div>
                             </div>
                             <div class="form-group">
                                <label>Gambarkan Posisi dalam Struktur Organisasi</label>
                              <input type="file" class="form-control " name="struktur"  value="" placeholder="Deskripsi"  readonly>
                            </div><div class="form-group">
                                <label>Uraian Pekerjaan</label>
                              <?=$tkaryawan[0]->uraian_pekerjaan?>
                            </div>
                           <div class="form-group ">
                                <label class="">Kualifikasi Usia</label>
                                <div class="row">
                                	<div class="col-6">
                                		
                              <input type="number" class="form-control  " id="tgl_absen" name="usia_dari"  value="<?=$tkaryawan[0]->kualifikasi_usia_dari?>" placeholder="Usia dari.." readonly>
                                	</div>
                                	<div class="col-6">
                              <input type="number" class="form-control  " id="tgl_absen" name="usia_ke"  value="<?=$tkaryawan[0]->kualifikasi_usai_sampai?>"  placeholder="Usia Sampai.." readonly>
                                	</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Kualifikasi Jenis Kelamin</label>
                                 <select class="form-control " id="tgl_absen" name="jk"  value="" readonly >
                                    	
                                    	<option value="">- Pilih Kualifikasi Jenis Kelamin -</option>
                                    	<option value="Laki Laki" <?=$tkaryawan[0]->kualifikasi_pendidikan=='Laki Laki'?'selected':''?>>Laki Laki</option>
                                    	<option value="Wanita" <?=$tkaryawan[0]->kualifikasi_pendidikan=='Wanita'?'selected':''?>>Wanita</option>
                                    	<option value="Pria dan Wanita" <?=$tkaryawan[0]->kualifikasi_pendidikan=='Pria dan Wanita'?'selected':''?>>Pria dan Wanita</option>
                                    </select>
                             
                            </div>
                           <div class="form-group">
                                <label>Kualifikasi Minimal Pendidikan dan Jurusan</label>
                              <input class="form-control "  name="k_pendidikan"  value="<?=$tkaryawan[0]->kualifikasi_pendidikan?>" placeholder="Kualifikasi Minimal Pendidikan dan Jurusan" readonly>
                            </div>
                        <div class="form-group">
                                <label>Kualifikasi Pengalaman Kerja</label>
                              <input class="form-control "  name="k_pengalaman"  value="<?=$tkaryawan[0]->kualifikasi_pengalaman?>" placeholder="KualifikasiPengalaman Kerja" readonly>
                            </div><div class="form-group">
                                <label>Kualifikasi Keahlian</label>
                              <input class="form-control "  name="k_keahlian"  value="<?=$tkaryawan[0]->kualifikasi_keahlian?>" placeholder="Kualifikasi Kompetensi Kerja" readonly>
                            </div><div class="form-group">
                                <label>Kompetensi lainnya</label>
                              <textarea class="form-control "  name="k_kompetensi"  value="" placeholder="Kompetensi lainnya" readonly><?=$tkaryawan[0]->kualifikasi_kompetisi?></textarea>
                            </div>
                           
                        </div>
                           
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.karyawan_baru') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
