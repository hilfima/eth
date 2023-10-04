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
        <form action="{!!route('be.update_mudepro',$id)!!}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card">
                @include('flash-message')
                <div class="card-body">
                    <div class="form-group">
                        <label>Jenis</label>
                        <select class="form-control select2 " name="data[jenis]" style="width: 100%;"   >

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
                        <label>Perpindahan Dapartement</label>
                        <select class="form-control select2 " name="data[perpindahan_departement_id]" style="width: 100%;"   >

                            <option value="">Pilih Dapartement</option>
                            <?php foreach ($departemen as $dept) { ?>

                                <option value="<?= $dept->m_departemen_id; ?>"
                            	<?= $mudepro[0]->perpindahan_departement_id==$dept->m_departemen_id?'selected':'';?>><?= $dept->nama; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Perpindahan Level</label>
                        <select class="form-control select2" name="data[perpindahan_pangkat_id]" id="karyawan"    style="width: 100%;">
                            <option value="">Pilih Level</option>
                            <?php foreach ($pangkat as $p) { ?>
                                <option value="<?= $p->m_pangkat_id; ?>"
                                <?= $mudepro[0]->perpindahan_pangkat_id== $p->m_pangkat_id?'selected':'';?>><?= $p->nama; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Perpindahan Entitas</label>
                        <select class="form-control select2 " name="data[perpindahan_entitas_id]" id="karyawan"   style="width: 100%;">

                            <option value="">Pilih Entitas</option>
                            <?php foreach ($lokasi as $lokasi) { ?>

                                <option value="<?= $lokasi->m_lokasi_id; ?>"
                                <?= $mudepro[0]->perpindahan_entitas_id==$lokasi->m_lokasi_id?'selected':'';?>><?= $lokasi->nama; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Perpindahan Jabatan</label>
                        <select class="form-control select2" name="data[perpindahan_jabatan_id]" style="width: 100%;"   onchange="changeposisi(this)">
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
                        <select class="form-control select2" name="data[appr_by]" style="width: 100%;" >
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
                            <select class="form-control select2 " name="karyawan[]" id="karyawan" style="width: 100%;" >

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

                            <textarea class="form-control " id="tgl_absen" name="keterangan[]" value="" placeholder="Deskripsi" ><?=$mk->deskripsi;?></textarea>
                        </div>
                        
                        <hr>
                    <?php }?>
                    </div>
                   

                    <hr>
                        
                    


                    <button type="submit" class="btn btn-primary">Simpan</button>
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