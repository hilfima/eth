@extends('layouts.appsA')

@section('content')


        <div class="card shadow-sm ctm-border-radius">
            <div class="card-body align-center">
                <h4 class="card-title float-left mb-0 mt-2">Tambah Mutasi Demosi Promosi </h4>


            </div>
        </div>
        <form action="{!!route('be.update_mudepro',$id)!!}" method="post" enctype="multipart/form-data">
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
                    <!--<div class="form-group">
                        <label>Perpindahan Dapartement</label>
                        <select class="form-control select2 " name="data[perpindahan_departement_id]" style="width: 100%;"   disabled>

                            <option value="">Pilih Dapartement</option>
                            <?php foreach ($departemen as $dept) { ?>

                                <option value="<?= $dept->m_departemen_id; ?>"
                            	<?= $mudepro[0]->perpindahan_departement_id==$dept->m_departemen_id?'selected':'';?>><?= $dept->nama; ?></option>

                            <?php } ?>
                        </select>
                    </div>-->
                   <!-- <div class="form-group">
                        <label>Perpindahan Level</label>
                        <select class="form-control select2" name="data[perpindahan_pangkat_id]" id="karyawan"   disabled style="width: 100%;">
                            <option value="">Pilih Level</option>
                            <?php foreach ($pangkat as $p) { ?>
                                <option value="<?= $p->m_pangkat_id; ?>"
                                <?= $mudepro[0]->perpindahan_pangkat_id== $p->m_pangkat_id?'selected':'';?>><?= $p->nama; ?></option>
                            <?php } ?>
                        </select>
                    </div>-->
                    <div class="form-group">
                        <label>Tujuan Entitas</label>
                        <select class="form-control select2 " name="data[perpindahan_entitas_id]" id="karyawan"  disabled style="width: 100%;">

                            <option value="">Pilih Entitas</option>
                            <?php foreach ($lokasi as $lokasi) { ?>

                                <option value="<?= $lokasi->m_lokasi_id; ?>"
                                <?= $mudepro[0]->perpindahan_entitas_id==$lokasi->m_lokasi_id?'selected':'';?>><?= $lokasi->nama; ?></option>
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

					<div class="form-group">
                        <label>Appproval Direksi*</label>
                        <select class="form-control select2" name="data[appr_by]" style="width: 100%;" disabled>
                            <option value="">Pilih Atasan</option>
                            <?php
                            foreach ($karyawan as $appr) {
                                echo '
								<option value="' . $appr->p_karyawan_id . '"
                                '. ($mudepro[0]->appr_by==$appr->p_karyawan_id ?'selected':'').'>' . $appr->nama . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <hr>
                    <div id="listkaryawan">
                    <?php 
                    foreach($mudepro_karyawan as $mk){
                    ?>
                        <div class="form-group">
                            <label>Karyawan</label>
                            <select class="form-control select2 " name="karyawan[]" id="karyawan" style="width: 100%;" disabled>

                                <option value="">Pilih Karyawan</option>
                                <?php foreach ($karyawan as $karyawans) { ?>

                                    <option value="<?= $karyawans->p_karyawan_id; ?>" 
                                    <?= $karyawans->p_karyawan_id==$mk->p_list_karyawan_id?'selected':''; ?>
                                    ><?= $karyawans->nama; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>

                            <textarea class="form-control " id="tgl_absen" name="keterangan[]" value="" placeholder="Deskripsi" disabled><?=$mk->deskripsi;?></textarea>
                        </div>
                        
                        
                        
                        <div class="form-group">
                            <label>Deskripsi Asesment HC</label>

                            <textarea class="form-control " id="tgl_absen" name="asesmen_hc[<?=$mk->t_mudepro_karyawan_id?>]" value="" placeholder="Asesment HC"  required=""><?=$mk->deskripsi_asesmen_hc;?></textarea>
                        </div>
                        
                        <div class="form-group">
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
                            <label>File Asesment HC</label>

                            <input type="file" class="form-control " id="tgl_absen" name="asesmen_file_hc_<?=$mk->t_mudepro_karyawan_id?>" value="" placeholder="Asesment HC"   >
                        </div>
                        
                        <div class="form-group">
                            <label>Status Asesment HC</label>

                            <select type="file" class="form-control " id="tgl_absen" name="asesmen_status_hc[<?=$mk->t_mudepro_karyawan_id?>]" value="" placeholder="Asesment HC" required="">
                           		<option value="">- Pilih Status -</option>
                           		<option value="1" <?=$mk->status_asesmen_hc==1?'selected':''?>>Direkomendasikan</option>
                           		<option value="2" <?=$mk->status_asesmen_hc==2?'selected':''?>>Dipertimbangkan</option>
                           		<option value="3" <?=$mk->status_asesmen_hc==3?'selected':''?>>Tidak direkomendasikan</option>
                            </select>
                        </div>
                        
                        
                        
                        <hr>
                    <?php }?>
                    </div>
                   

                    <hr>
                        <div class="form-group">
                            <label>Status</label>

                            <select type="file" class="form-control " id="tgl_absen" name="status" value="" placeholder="Asesment HC" required="">
                           		<option value="">- Pilih Status -</option>
                           		<option value="11">Masih terdapat perubahan</option>
                           		<option value="2">Selesai</option>
                            </select>
                        </div>
                    


                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>

        </form>

    </div>
</div>
<script>
    function changeposisi(e) {

        if ($(e).val() == '-1') {
            $('#jabatan_lain').show();
        } else {
            $('#jabatan_lain').hide();

        }
    }
</script>
<script>
    function addKaryawan(e) {
        $(e).before('<div class="form-group"><label>Karyawan</label><select class="form-control select3 " name="karyawan[]" id="karyawan"  style="width: 100%;" >                                                                        <option value="" >Pilih Karyawan</option>                                     <?php foreach ($karyawan as $karyawans) { ?><option value="<?= $karyawans->p_karyawan_id; ?>" ><?= str_replace("'", "", $karyawans->nama); ?></option><?php } ?>                                 </select>                             </div>                             <div class="form-group"> 		                        <label>Deskripsi</label>  		                        <textarea class="form-control " id="tgl_absen" name="keterangan[]" value="" placeholder="Deskripsi" required=""></textarea> 		                    </div><hr>');
    }
</script>
@endsection