@extends('layouts.appsA')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    
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
                  
                {{ csrf_field() }}
                    <div class="row">
                       
                       
                        <div class="col-sm-12" >
                           <div class="form-group" >
                                <label>Nomor Pengajuan</label>
                              <input class="form-control " id="nomor_pengajuan" name="nomor_pengajuan"  value="<?=$tkaryawan[0]->nomor_pengajuan;?>" placeholder="Nomor Pengajuan" readonly>
                              	
                              </div>
                              </div>
                                    
                                    <div class="col-sm-12" id="karyawanKonten">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Posisi Yang Dibutuhkan</label>
                                <?php
                                
                                if($tkaryawan[0]->m_jabatan_id==-1)
                                    $posisi = 'Posisi Baru';
                                else{
                                    foreach($jabatan as $kar){
                                    if($tkaryawan[0]->m_jabatan_id==$kar->m_jabatan_id)
                                    $posisi = $kar->nama;
                                    }
                                }?>
                             
                             
								 <input value="<?=$posisi;?>" class="form-control " name="jabatan"  readonly  style="width: 100%;"  onchange="changeposisi(this)" readonly>
                                   
                                
                        </div> 
                           <?php if($tkaryawan[0]->m_jabatan_id==-1){?>
                            <div class="form-group" >
                                <label>Nama Posisi</label>
                              <input class="form-control " id="tgl_absen" name="posisi"  value="<?=$tkaryawan[0]->posisi?>" placeholder="Posisi Lainnya"  readonly>
                              	
                              </div>
                            <div class="form-group">
                                <label>Dapartement</label>
                                <?php 
                                   $valuedept = '';
                                    foreach($departemen as $dept){
                                      
                                    	if($dept->m_departemen_id==$tkaryawan[0]->m_departemen_id){
                                    	$valuedept = $dept->nama;;
                                    }
                }?>
                              <input value="<?=$valuedept?>" class="form-control " name="dept"  style="width: 100%;"   readonly>
                                   
                                    
								
                                </select>
                            </div><div class="form-group">
                                <label>Level</label>
                               <?php 
                               $valuepangkat ="";
                               foreach($pangkat as $p){
                                   if($tkaryawan[0]->m_pangkat_id==$p->m_pangkat_id)
                                   $valuepangkat = $p->nama;
								 }?>
                                <input value="<?=$valuepangkat?>" class="form-control " name="level" id="karyawan"  style="width: 100%;"  readonly>
                                 
                            </div><div class="form-group">
                                <label>Entitas</label>
                                 <?php 
                                    $valueentitas = "";
                                      foreach($lokasi as $lokasi){
                                    	if($lokasi->m_lokasi_id==$tkaryawan[0]->m_lokasi_id){
                                    	$valueentitas = $lokasi->nama;
                                    	}
                                    }?>
                               <input value="<?=$valueentitas;?>" class="form-control " name="lokasi" id="karyawan"  style="width: 100%;"  readonly>
                                   
                                   
                            </div>
                            <?php }?>
                            <div class="form-group">
                                <label>Lokasi Penempatan</label>
                              <input class="form-control"  name="kebutuhan"  value="<?=$tkaryawan[0]->lokasi_penempatan?>" placeholder="Lokasi Penempatan..."  readonly>    
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
                              <select class="form-control " id="tgl_absen" name="alasan"  value="" placeholder="Keterangan" onclick="changealasan(this)"  disabled>
                                    	
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
	                              <select class="form-control " id="tgl_absen" name="alasanganti"  value="" placeholder="Deskripsi" disabled>
	                              	<option value="">- Pilih Karyawan -</option>
	                              	<?php foreach($karyawan as $karyawan){?>
	                              	<option value="<?=$karyawan->p_karyawan_id;?>"<?=$tkaryawan[0]->pergantian_karyawan_id==$karyawan->p_karyawan_id?'selected':''?> ><?=$karyawan->nama;?></option>
	                              	<?php }?>
	                              </select>
	                            </div>
                             </div>
                             
                             <div class="form-group">
                                <label>Gambarkan Posisi dalam Struktur Organisasi</label>
                                <br>
                                <div >
                                @if(!empty($datas->foto))
                                    <a href="{!! asset('dist/img/file/'.$datas->file_gambaran_struktur) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    -
                                @endif
                                </div>
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
                                 <select class="form-control " id="tgl_absen" name="jk"  value="" disabled >
                                    	
                                    	<option value="">- Pilih Kualifikasi Jenis Kelamin -</option>
                                    	<option value="Laki Laki" <?=$tkaryawan[0]->kualifikasi_jenis_kelamin=='Laki Laki'?'selected':''?>>Pria</option>
                                    	<option value="Wanita" <?=$tkaryawan[0]->kualifikasi_jenis_kelamin=='Wanita'?'selected':''?>>Wanita</option>
                                    	<option value="Pria dan Wanita" <?=$tkaryawan[0]->kualifikasi_jenis_kelamin=='Pria dan Wanita'?'selected':''?>>Pria dan Wanita</option>
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
                        <br>
                        <div >
                        <?php if($view =='approve_atasan' or $view =='approve_keuangan' or $view =='approve_direksi' or $view ==''  or $view=='edit'){?>
                        <form class="form-horizontal" method="POST" action="{!! route('be.simpan_approval_karyawan_baru',$tkaryawan[0]->t_karyawan_id) !!}" enctype="multipart/form-data" onsubmit="return confirm('Apakah anda yakin?');">
                            {{ csrf_field() }}
                        <input type="hidden" name="view" value="<?=$view;?>">
                        <h4>APPROVE ATASAN</h4>
                        </div>
                        <br>
                         <div class="form-group">
                                        <label>Status Approval</label>
                                         <select class="form-control " id="tgl_absen" name="approve_atasan" <?php if($view!='approve_atasan'){echo 'disabled';}?>  >
                                            	
                                            	<option value="">- Pilih Status Approval -</option>
                                            	<option value="1" <?=$tkaryawan[0]->appr_status==1?'selected':''?>>Disetujui</option>
                                            	
        										<option value="2" <?=$tkaryawan[0]->appr_status==2?'selected':''?>>Ditolak</option>
        										<option value="3" <?=$tkaryawan[0]->appr_status==3?'selected':''?>>Pending</option>
                                            </select>
                                     
                                    </div>
                           
                            <div class="form-group">
                                <label>Approvel Jumlah Rekutmen Karyawan</label>
                              <input class="form-control "  name="karyawan_approval_atasan" <?php if($view!='approve_atasan'){echo 'disabled';}?>  value="<?php 
                              if($view=='approve_atasan')
                              echo ($tkaryawan[0]->final_approval?$tkaryawan[0]->final_approval:$tkaryawan[0]->jumlah_dibutuhkan);
                              else
                              echo $tkaryawan[0]->karyawan_approve_atasan;
                              ?>" placeholder="Jumlah karyawan yang disetujui" >
                            </div>
                           
                                  
                             <div class="form-group">
                              <label>Keterangan</label>
                              <textarea class="form-control " <?php if($view!='approve_atasan'){echo 'disabled';}?>   name="keterangan_atasan"  value="" placeholder="Keterangan" ><?=$tkaryawan[0]->keterangan_atasan?></textarea>
                            </div>  
                               <br>
                        
                        <?php }?>
                       
                        <?php if($view =='approve_keuangan' or $view =='approve_direksi' or $view =='' or $view=='edit' ){?>
                        <div >
                        <h4>APPROVE KEUANGAN</h4>
                        </div>
                        <br>     
                               
                                      <div class="form-group">
                                        <label>Status Approval</label>
                                         <select class="form-control " id="tgl_absen" name="approve_keuangan" <?php if($view!='approve_keuangan'){echo 'disabled';}?>   >
                                            	
                                            	<option value="">- Pilih Status Approval -</option>
                                            	<option value="1" <?=$tkaryawan[0]->appr_keuangan_status==1?'selected':''?>>Disetujui</option>
                                            	
        										<option value="2" <?=$tkaryawan[0]->appr_keuangan_status==2?'selected':''?>>Ditolak</option>
        										<option value="3" <?=$tkaryawan[0]->appr_keuangan_status==3?'selected':''?>>Pending</option>
                                            </select>
                                     
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Approvel Jumlah Rekutmen Karyawan</label>
                                        <input class="form-control "  name="karyawan_approval_keuangan" <?php if($view!='approve_keuangan'){echo 'disabled';}?> value="<?php 
                                              if($view=='approve_keuangan')
                                              echo ($tkaryawan[0]->final_approval?$tkaryawan[0]->final_approval:$tkaryawan[0]->jumlah_dibutuhkan);
                                              else
                                              echo $tkaryawan[0]->karyawan_approve_keuangan;
                                              ?>" placeholder="Jumlah karyawan yang disetujui" >
                                    </div>
                                          
                                            <div class="form-group">
                                        <label>Keterangan</label>
                                      <textarea class="form-control "  name="keterangan_keuangan" <?php if($view!='approve_keuangan'){echo 'disabled';}?> value="" placeholder="Keterangan" ><?=$tkaryawan[0]->keterangan_keuangan?></textarea>
                                    </div>  
                                            
                                     <br>
                                     <?php }?>
                        <?php if($view =='approve_direksi' or $view ==''  or $view=='edit'){?>
                        <div >
                            
                        <h4>APPROVE DIREKSI</h4>
                        </div>
                        <br>     
                               
                            
                                  
                             <div class="form-group">
                                        <label>Status Approval</label>
                                         <select class="form-control " id="tgl_absen" name="approve_direksi" <?php if($view!='approve_direksi'){echo 'disabled';}?>   >
                                            	
                                            	<option value="">- Pilih Status Approval -</option>
                                            	<option value="1" <?=$tkaryawan[0]->appr_direksi_status==1?'selected':''?>>Disetujui</option>
                                            	
        										<option value="2" <?=$tkaryawan[0]->appr_direksi_status==2?'selected':''?>>Ditolak</option>
        										<option value="3" <?=$tkaryawan[0]->appr_direksi_status==3?'selected':''?>>Pending</option>
                                            </select>
                                     
                                    </div>
                                    
                                    <div class="form-group">
                                <label>Approvel Jumlah Rekutmen Karyawan</label>
                              <input class="form-control "  name="karyawan_approval_direksi" <?php if($view!='approve_direksi'){echo 'disabled';}?>  value="<?php 
                                              if($view=='approve_direksi')
                                              echo ($tkaryawan[0]->final_approval?$tkaryawan[0]->final_approval:$tkaryawan[0]->jumlah_dibutuhkan);
                                              else
                                              echo $tkaryawan[0]->karyawan_approve_direksi;
                                              ?>" placeholder="Jumlah karyawan yang disetujui" >
                            </div>
                                  
                             <div class="form-group">
                              <label>Keterangan</label>
                              <textarea class="form-control "  name="keterangan_direksi" <?php if($view!='approve_direksi'){echo 'disabled';}?>  value="" placeholder="Keterangan" ><?=$tkaryawan[0]->keterangan_direksi?></textarea>
                            </div>  
                                    
                             
                            <?php }?>
                            <?php if($view=='edit'){?>
                             <div >
                            
                        <h4>APPROVE HC</h4>
                        </div>
                        <br>     
                            <div class="form-group">
                                <label>Status</label>
                                 <select class="form-control " id="tgl_absen" name="status"  value=""  >
                                    	
                                    	<option value="">- Pilih Status -</option>
                                    	<option value="15" <?=$tkaryawan[0]->status==15?'selected':''?>>Hold</option>
										<option value="2" <?=$tkaryawan[0]->status==2?'selected':''?>>Diproses</option><!--
                                    	<option value="3" <?=$tkaryawan[0]->status==3?'selected':''?>>Proses Interview</option>-->
                                    	<option value="1" <?=$tkaryawan[0]->status==1?'selected':''?>>Selesai</option>
                                    	<option value="4" <?=$tkaryawan[0]->status==4?'selected':''?>>Ditolak</option>
                                    	
                                    </select>
                             
                            </div>
                       <?php }?>
                        <?php if($view =='approve_atasan' or $view =='approve_keuangan' or $view =='approve_direksi' or $view=='edit' ){?>
                                  
                            </div>
                            <div class="box-footer p-3">
                                <a href="{!! route('fe.approval_karyawan_baru') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                                <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                            </div>
                            <?php }?>
                            
                            
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
