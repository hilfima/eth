@extends('layouts.app_fe')

@section('content')


<div class="row">
    <div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
        <?= view('layouts.app_side'); ?>
    </div>
    <div class="col-xl-9 col-lg-8 col-md-12">
        <div class="card shadow-sm ctm-border-radius">
            <div class="card-body align-center">
                <h4 class="card-title float-left mb-0 mt-2">Tambah Mutasi Demosi Promosi </h4>


            </div>
        </div>
        <form action="{!!route('fe.update_appr_mudepro',$id)!!}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card">
                @include('flash-message')
                <div class="card-body">
                    <div class="form-group">
                        <label>Jenis</label>
                        <select class="form-control select2 " name="data[jenis]" style="width: 100%;"   disabled>

                            <option value="">Pilih Jenis</option>
                            <option value="1" 
                            	<?= $mudepro[0]->jenis==1?'selected':'';?>
                            >Mutasi</option>
                            <option value="2" 
                            	<?= $mudepro[0]->jenis==2?'selected':'';?>>Demosi</option>
                            <option value="3" 
                            	<?= $mudepro[0]->jenis==3?'selected':'';?>>Promosi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tujuan Entitas</label>
                        <select class="form-control select2 " name="data[perpindahan_entitas_id]" id="karyawan"  disabled style="width: 100%;">

                            <option value="">Pilih Entitas</option>
                            <?php foreach ($lokasi as $lokasis) { ?>

                                <option value="<?= $lokasis->m_lokasi_id; ?>"
                                <?= $mudepro[0]->perpindahan_entitas_id==$lokasis->m_lokasi_id?'selected':'';?>><?= $lokasis->nama; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tujuan Jabatan</label>
                        <select class="form-control select2" name="data[perpindahan_jabatan_id]" style="width: 100%;"  disabled onchange="changeposisi(this)">
                            <option value="">Pilih Jabatan</option>
                            <!-- <option value="-1">Posisi Baru</option>-->
                            <?php foreach ($jabatan as $kar) { ?>
                                <option value="<?= $kar->m_jabatan_id; ?>"
                                <?= $mudepro[0]->perpindahan_jabatan_id==$kar->m_jabatan_id?'selected':'';?>><?= $kar->nama; ?></option>
                            <?php } ?>
                        </select>

                    </div>
                    <div id="jabatan_lain" style="display: none">
                        <div class="form-group">
                            <label>Nama Posisi</label>
                            <input class="form-control " id="tgl_absen" name="posisi" value="" placeholder="Nama Posisi">

                        </div>
                    </div>

                    <hr>
                    </div>
                    </div>
                    <div id="listkaryawan">
                    
                    <?php 
                    
                    foreach($mudepro_karyawan as $mk){
                    ?>
                    	<div class="card">
                    	<div class="card-body">
                    	<div class="row">
                        <div class="form-group col-md-12">
                            <label>Karyawan</label>
                            <select class="form-control select2 " name="karyawan[]" id="karyawan" style="width: 100%;" disabled>

                                <option value="">Pilih Karyawan</option>
                                <?php 
                                $nama = '';
                                foreach ($karyawan as $karyawans) { 
                                	if($karyawans->p_karyawan_id==$mk->p_list_karyawan_id)
                                	$nama = $karyawans->nama;
                                	?>

                                    <option value="<?= $karyawans->p_karyawan_id; ?>" 
                                    <?= $karyawans->p_karyawan_id==$mk->p_list_karyawan_id?'selected':''; ?>
                                    ><?= $karyawans->nama; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        
                        
                        <div class="form-group col-md-4">
                            <label>Deskripsi Asesment HC <?=$nama?></label>

                            <textarea class="form-control " id="tgl_absen" name="asesmen_hc[<?=$mk->t_mudepro_karyawan_id?>]" value="" placeholder="Asesment HC"   disabled><?=$mk->deskripsi_asesmen_hc;?></textarea>
                        </div>
                        
                        <div class="form-group col-md-4">
                        	@if(!empty($mk->file_asesmen_hc))
                        	<a href="{!! asset('dist/img/file/'.$mk->file_asesmen_hc) !!}" target="_blank">
								<?php
									$info = pathinfo($mk->file_asesmen_hc);
									if(isset($info["extension"])){
									if ($info["extension"] == "jpg" or $info["extension"] == "png" or $info["extension"] == "jpeg" ) {
								?>
								<img src="{!! asset('dist/img/file/'.$mk->file_asesmen_hc) !!}"  class="" height="100px">
<?php }else{?>
								<i class="fa fa-download"></i>
								<?php }?><?php }?></a>
                        	<br>
                        	@endif
                            <label>File Asesment HC  <?=$nama?></label>

                            <input type="file" class="form-control " id="tgl_absen" name="asesmen_file_hc_<?=$mk->t_mudepro_karyawan_id?>" value="" placeholder="Asesment HC"    disabled>
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label>Status Asesment HC - <?=$nama?></label>

                            <select type="file" class="form-control " id="tgl_absen" name="asesmen_status_hc[<?=$mk->t_mudepro_karyawan_id?>]" value="" placeholder="Asesment HC" disabled>
                           		<option value="">- Pilih Status -</option>
                           		<option value="1" <?=$mk->status_asesmen_hc==1?'selected':''?>>Direkomendasikan</option>
                           		<option value="2" <?=$mk->status_asesmen_hc==2?'selected':''?>>Dipertimbangkan</option>
                           		<option value="3" <?=$mk->status_asesmen_hc==3?'selected':''?>>Tidak direkomendasikan</option>
                            </select>
                        </div>
                        
                        
                     	<div class="form-group col-md-12">
                            <label>Status Tujuan Perpindahan <?=$nama?></label>

                            <select type="file" class="form-control " id="tgl_absen" name="data[status_perpindahan][<?=$mk->t_mudepro_karyawan_id?>]" value="" placeholder="Asesment HC" required="" onchange="view_perpindahan(this,<?=$mk->t_mudepro_karyawan_id?>)">
                           		<option value="">- Pilih Status Tujuan-</option>
                           		<option value="1" <?=$mk->status_perpindahan==1?'selected':'';?>>Setuju</option>
                           		<option value="2" <?=$mk->status_perpindahan==2?'selected':'';?>>Tolak</option>
                           		<option value="3" <?=$mk->status_perpindahan==3?'selected':'';?>>Pending</option>
                            </select>
                        </div>
                        </div>
                        
                        <div id="view-pindah-<?=$mk->t_mudepro_karyawan_id?>" >
                        
                        <div class="row">
                        <div class="form-group col-md-4">
                        <label>Finalisasi Jenis  <?=$nama?></label>
                        <select class="form-control select2 val-input-<?=$mk->t_mudepro_karyawan_id?>" name="data[jenis][<?=$mk->t_mudepro_karyawan_id?>]" style="width: 100%;"   >

                            <option value="">Pilih Jenis</option>
                            <option value="1" 
                            
                                <?php
                                if($mudepro[0]->status==2){
                                	echo $mudepro[0]->jenis==1?'selected':'';
                                }else{
                                	echo $mk->fiksasi_jenis==1?'selected':'';
                                }
                                 
                                 ?>
                                 
                            	
                            >Mutasi</option>
                            <option value="2"
                            
                                <?php
                                if($mudepro[0]->status==2){
                                	echo $mudepro[0]->jenis==2?'selected':'';
                                }else{
                                	echo $mk->fiksasi_jenis==2?'selected':'';
                                }
                                 
                                 ?> >Demosi</option>
                            <option value="3" 
                            
                            
                                <?php
                                if($mudepro[0]->status==2){
                                	echo $mudepro[0]->jenis==3?'selected':'';
                                }else{
                                	echo $mk->fiksasi_jenis==3?'selected':'';
                                }
                                 
                                 ?> 
                            	>Promosi</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Finalisasi Entitas <?=$nama?></label>
                        <select class="form-control select2 val-input-<?=$mk->t_mudepro_karyawan_id?>" name="data[fiksasi_entitas_id][<?=$mk->t_mudepro_karyawan_id?>]" id="karyawan"   style="width: 100%;">

                            <option value="">Pilih Entitas</option>
                            <?php foreach ($lokasi as $lokasis) { ?>

                                <option value="<?= $lokasis->m_lokasi_id; ?>"
                                
                                <?php
                                if($mudepro[0]->status==2){
                                	echo $mudepro[0]->perpindahan_entitas_id==$lokasis->m_lokasi_id?'selected':'';
                                }else{
                                	echo $mk->fiksasi_entitas_id==$lokasis->m_lokasi_id?'selected':'';
                                }
                                 
                                 ?>
                                ><?= $lokasis->nama; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Finalisasi Jabatan <?=$nama?></label>
                        <select class="form-control select2 val-input-<?=$mk->t_mudepro_karyawan_id?>" name="data[fiksasi_jabatan_id][<?=$mk->t_mudepro_karyawan_id?>]" style="width: 100%;"   onchange="changeposisi(this)">
                            <option value="">Pilih Jabatan</option>
                            <!-- <option value="-1">Posisi Baru</option>-->
                            <?php foreach ($jabatan as $kar) { ?>
                                <option value="<?= $kar->m_jabatan_id; ?>"
                                <?php
                                if($mudepro[0]->status==2){
                                	echo $mudepro[0]->perpindahan_jabatan_id==$kar->m_jabatan_id?'selected':'';
                                }else{
                                	echo $mk->fiksasi_jabatan_id==$kar->m_jabatan_id?'selected':'';
                                }
                                 ?>><?= $kar->nama; ?>(<?= $kar->nama_entitas; ?>)</option>
                            <?php } ?>
                        </select>

                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                        
                        
                    <?php }?>
                    
                        <script>
                        	function view_perpindahan(e,id){
                        		if ($(e).val()=='1') {
                        			$('#view-pindah-'+id).show();
								}else{
                        			$('#view-pindah-'+id).hide();
                        			//$('.val-input-'+id).val('');
									
								}
                        	}
                        </script>
                    </div>
                   

                        <div class="form-group">
                            
                            <input type="hidden" type="file" class="form-control " id="tgl_absen" name="status" value="3" placeholder="Asesment HC" required="">
                           		
                        </div>
                     <div class="form-group">
                           
                            <input type="hidden"class="form-control " id="tgl_absen" name="appr" value="1" placeholder="Asesment HC" required="">
                           		
                        </div>
                    


                     <div class="card">
                     <div class="card-body">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </div>
                </div>
            </div>

        </form>

    </div>
</div>
<script>
  	function changeposisi(e){
  		
  		if($(e).val()=='-1'){
			$('#jabatan_lain').show();
		}else{
			$('#jabatan_lain').hide();
			
		}
	}
	</script>
	<script>
		                     	function addKaryawan(e){
		                     		$(e).before('<div class="form-group"><label>Karyawan</label><select class="form-control select3 " name="karyawan[]" id="karyawan"  style="width: 100%;" >                                                                        <option value="" >Pilih Karyawan</option>                                     <?php foreach($karyawan as $karyawans){?><option value="<?=$karyawans->p_karyawan_id;?>" ><?=str_replace("'","",$karyawans->nama);?></option><?php }?>                                 </select>                             </div>                             <div class="form-group"> 		                        <label>Deskripsi</label>  		                        <textarea class="form-control " id="tgl_absen" name="keterangan[]" value="" placeholder="Deskripsi" required=""></textarea> 		                    </div><hr>');
		                     	}
		                     	
		                     </script>
@endsection