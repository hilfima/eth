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
        <form action="{!!route('fe.simpan_mudepro')!!}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card">
                @include('flash-message')
                <div class="card-body">
                    <div class="form-group">
                                <label>Jenis</label>
                              <select class="form-control select2 " name="data[jenis]"  style="width: 100%;"  >
                                   
                                    <option value="" >Pilih Jenis</option>
                                    <option value="1" >Mutasi</option>
                                    <option value="2" >Demosi</option>
                                    <option value="3" >Promosi</option>
                                </select>
                            </div><div class="form-group">
                            	<label>Tujuan Entitas</label>
                               	<select class="form-control select4" name="data[perpindahan_entitas_id]" id="karyawan"  style="width: 100%;" onclick="jabatan(this)">
                                   
                                    <option value="" >Pilih Entitas</option>
                                    <?php foreach($lokasi as $lokasi){?>
                                    	
                                    <option value="<?=$lokasi->m_lokasi_id;?>" ><?=$lokasi->nama;?></option>
									<?php }?>
                                </select>
                            </div>
                            <div class="form-group">
                            	<label>Tujuan Jabatan</label>
								<select class="form-control select2" name="data[perpindahan_jabatan_id]"   style="width: 100%;" required onchange="changeposisi(this)" id="list_jabatan">
                                    <option value="" >Pilih Jabatan</option>
                                  <!-- <option value="-1">Posisi Baru</option>-->
                                    <?php foreach($jabatan as $kar){?>
                                    <option value="<?=$kar->m_jabatan_id;?>"><?=$kar->nama;?></option>
									<?php }?>
                                </select>
                                
                        </div>
                        <div id="jabatan_lain" style="display: none" >
                            <div class="form-group" >
                                <label>Nama Posisi</label>
                              <input class="form-control " id="tgl_absen" name="posisi"  value="" placeholder="Nama Posisi">
                              	
                              </div>
                              </div>
                            
                            
                           <hr> 
                           <div id="listkaryawan">
                           <div class="form-group">
                                <label>Karyawan</label>
                               	<select class="form-control select2 colect" name="karyawan[]" id="karyawan"  style="width: 100%;" multiple="">
                                   
                                    <option value="" >Pilih Karyawan</option>
                                    <?php foreach($karyawan as $karyawans){?>
                                    	
                                    <option value="<?=$karyawans->p_karyawan_id;?>" ><?=$karyawans->nama;?></option>
									<?php }?>
                                </select>
                            </div>
		                    </div>
		                    
		                     
                           <hr> 
                           <div class="form-group"> 		                        <label>Deskripsi</label>  		                        <textarea class="form-control " id="tgl_absen" name="keterangan" value="" placeholder="Deskripsi" required=""></textarea> 		                    </div>
                    <div class="form-group">
                        <label>Atasan*</label>
                        <select class="form-control select2" name="data[appr_by]" style="width: 100%;" required>
                            <option value="">Pilih Atasan</option>
                            <?php
                            foreach ($appr as $appr) {
                                echo '
								<option value="' . $appr->p_karyawan_id . '">' . $appr->nama_lengkap . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    

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
	$('.select4').select2();
	</script>
	<script>
	
	function colectkaryawan(e){
		var count =0;
		$('.colect').each(function(){
		    if($(this).val() == $(e).val()){
		    	count+=1;
		    }
		});
		//alert(count);
		if(count>=2){
			//alert('Data Karyawan Sudah Ada');
			$(e).val('').trigger("change");
		}
	}
	
		                     	function jabatan(e){
									$.ajax({
										type: 'get',
										data: {'entitas': $(e).val()},
										url: '<?=route('fe.jabatan_entitas');?>',
										dataType: 'html',
										success: function(data){
											$('#list_jabatan').html(data)
										},
										error: function (error) {
										    console.log('error; ' + eval(error));
										    //alert(2);
										}
									});
								}
		                     	function addKaryawan(e){
		                     		$(e).before('<div class="form-group"><label>Karyawan</label><select class="form-control select3 colect" onchange="colectkaryawan(this)" name="karyawan[]" id="karyawan"  style="width: 100%;" >                                                                        <option value="" >Pilih Karyawan</option>                                     <?php foreach($karyawan as $karyawans){?><option value="<?=$karyawans->p_karyawan_id;?>" ><?=str_replace("'","",$karyawans->nama);?></option><?php }?>                                 </select>                             </div>                             <hr>');
		                     	$('.select3').select2();
		                     	}
		                     	
		                     </script>
@endsection