@extends('layouts.app_fe')

@section('content')

                    

	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
         <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Tambah Penugasan Izin Perjalanan Dinas</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_perdin') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group  row">
                        <label class="col-sm-2 control-label">NIK</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $kar[0]->nik !!}" readonly>
                        </div>
                      </div>
                    <div class="form-group  row">
                        <label class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-10">
                           <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $kar[0]->nama_lengkap !!}" readonly>
                        </div>
                      </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Jabatan</label>
                        <div class="col-sm-10">
                           <input type="text" class="form-control" placeholder="Nama Jabatan ..." id="jabatan" name="jabatan" value="{!! $kar[0]->jabatan !!}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Departemen</label>
                        <div class="col-sm-10">
                           <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $kar[0]->departemen !!}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tanggal Pengajuan</label>
                        <div class="col-sm-10">
                                <input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y') !!}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Jenis Izin*</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="cuti" id="cuti" style="width: 100%;" readonly required>
                                    <option value="24">IZIN PERJALANAN DINAS </option>
                            </select>        
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tanggal Awal*</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control " id="tgl_awaldate" name="tgl_awal" value="<?=date('Y-m-d');?>" required  onchange="countdate();cek_min(this)" <?php if($tgl_cut_off) echo 'min="'.$tgl_cut_off.'"';?> onkeyup="change_tglawal()" >
                                    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tanggal Akhir*</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control " id="tgl_akhirdate" name="tgl_akhir" data-target="#tgl_akhir" value="<?=date('Y-m-d');?>" required onchange="countdate();cek_min(this)" data-date-format="DD-MMMM-YYYY" <?php if($tgl_cut_off) echo 'min="'.$tgl_cut_off.'"';?>>
                                       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Lama (Hari)*</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control masking0" id="lama" name="lama" value="1" placeholder="Lama Cuti/Izin..." required readonly="">       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">File</label>
                        <div class="col-sm-10">
                                <input type="file" class="form-control" id="filess" name="file" value="" >    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tipe Perdin*</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="tipe_perdin" style="width: 100%;" required>
                                    <option value="">Pilih Perdin</option>
                                     
                                    <option value="Perjalanan Dinas dalam Kota">Perjalanan Dinas dalam Kota</option>
                                    <option value="Perjalanan Dinas Luar kota">Perjalanan Dinas Luar kota</option>
                                    <option value="Perjalanan Dinas Luar Negeri">Perjalanan Dinas Luar Negeri</option>
                                </select>   
                        </div>
                    </div><div class="form-group row">
                        <label class="col-sm-2 control-label">Alat Transportasi*</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="alat_transportasi" style="width: 100%;" onchange="is_fasilitas(this)" required>
                                    <option value="">Pilih Alat Transportasi</option>
                                     <?php foreach($alat_transportasi as $alat){?>

                                    <option value="<?=$alat->m_alat_transportasi_id;?>"><?=$alat->nama_alat_transportasi;?></option>
                                    
                                     <?php }?>
                                     	
                                     </select> 
                                      <?php foreach($alat_transportasi as $alat){  
                                     if($alat->fasilitas){
                                     		echo '<input type="hidden" class="fasilitas_perusahaan" value="'.$alat->m_alat_transportasi_id.'">';
                                     	}
                                     }
                                     	?>
                        </div>
                    </div>
                   <script>
                   	function is_fasilitas(e){
                   		var is=0;
                   		$('.fasilitas_perusahaan').each(function(){
                   			if($(e).val()==$(this).val())
			                is += 1;  // Or this.innerHTML, this.innerText
			            });
			            
			            if(is){
			            	$('#fasilitas').show();
			            	
			            }else{
			            	$('#fasilitas').hide();
			            }
                   	}
                   </script>
                    <div class="form-group row " style="display: none" id="fasilitas">
                        <label class="col-sm-2 control-label">KM Awal</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control " id="tgl_akhirdate" name="km_awal"  value="0" >
                                       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tempat Tujuan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control " id="tgl_akhirdate" name="tempat_tujuan"  value="" >
                                       
                        </div>
                    </div>
                    
                       
                    <?php $visible = true;
                        if($atasan!=-1)
                            $visible=true;
                        else if($idkar[0]->m_pangkat_id==6)
                            $visible=false;
                        if( $visible){?>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Penanggung jawab sementara</label>
                        <div class="col-sm-10">
                                <select class="form-control select2" name="pjs" style="width: 100%;" >
                                    <option value="">Pilih Penanggung jawab sementara</option>
                                    <?php
                                  
                                    foreach($karyawan AS $karyawan){
                                       echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama_lengkap.'('.$karyawan->jabatan.')</option>';
                                    }
                                    ?>
                                </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Atasan*</label>
                        <div class="col-sm-10">
                                <select class="form-control select2" name="atasan" id="atasan" style="width: 100%;" required>
                                    <option value="">Pilih Atasan</option>
                                    <?php
                                    foreach($appr AS $appr){
                                        echo '<option value="'.$appr->p_karyawan_id.'">'.$appr->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                        </div>
                    </div>
                    <?php }?>
                    
                    <div id="JenisAlasanContent"></div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Keterangan Penugasan*</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" placeholder="Keterangan Penugasan..." id="alasan" name="alasan"  required=""></textarea>
                        </div>
                    </div>
                       
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('fe.list_perdin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
         // $('#jenis_ijin').val('');
         // change_jenis();
          cek_min_all();
          get_min_max_izin();
        });
        function get_min_max_izin(){
            val = $('#jenis_ijin').val();
                $('#tgl_awaldate').attr('min',$('#cut_off').val());
                $('#tgl_akhirdate').attr('min',$('#cut_off').val());
            $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "https://hcms.mafazaperforma.com/frontend/get_izin_detail", 
				data : {'val' : val},
				type : 'get',
				dataType : 'json',
				success : function(result){
                    if(result.absen){
                        if(result.batas_tipe=='-1'){
                            
                            $('#tgl_awaldate').attr('min',$('#cut_off').val());
                            $('#tgl_akhirdate').attr('min',$('#cut_off').val());
                        }else
                        if(result.min){
                            $('#tgl_awaldate').attr('min',result.min);
                            $('#tgl_akhirdate').attr('min',result.min);
                        }
                        if(result.alasan)
                        $('#JenisAlasanContent').html(result.kontent_alasan);
                        else
                        $('#JenisAlasanContent').html("");
                        if(result.batas_tipe=='+-'){
                            $('#kontentParameter').html(result.nama_parameter_input); 
                            //$('#tgl_awaldate').attr('disabled',true);
                            $//('#tgl_akhirdate').attr('disabled',true); 
                        }else{
                            $('#kontentParameter').html("");
                            $('#tgl_awaldate').attr('disabled',false);
                            $('#tgl_akhirdate').attr('disabled',false);
                        }
			            $('#filess').attr('required', result.require_file);  

                    }else{
                        alert(result.keterangan);
                    }
                    cek_min_all();
						//$('#lama').val(result.count);
						//console.log("===== " + result + " =====");
					
				}
				
			});
        }
        function cek_min_all(){
            min_awal = $('#tgl_awaldate').attr('min');
            min_akhir = $('#tgl_akhirdate').attr('min');
            
            val_awal = $('#tgl_awaldate').val();
            val_akhir = $('#tgl_akhirdate').val();
            if(val_awal<min_awal){
                 $('#tgl_awaldate').val(min_awal)
            }
            if(val_akhir<min_akhir){
                 $('#tgl_akhirdate').val(min_akhir)
            }
            
        }
    function cek_min(e){
            min  = $(e).attr('min');
            
            val = $(e).val();
            if(val<min){
                 $(e).val(min)
            }
        }
    	function countdate()
		{
			if( $("#tgl_akhirdate").val() &&  $("#tgl_awaldate").val()){
				
			$.ajax({
				headers: { 
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "{!! route('fe.hitung_hari_tanpa_libur') !!}", 
				data : {'tgl_awal' : $("#tgl_awaldate").val(),'tgl_akhir' : $("#tgl_akhirdate").val(),'cuti' : $("#cuti").val()},
				type : 'get',
				dataType : 'json',
				success : function(result){
						$('#lama').val(result.count); 
						//console.log("===== " + result + " =====");
					
				}
				
			});
			}
	
	 	}
    </script>
    <!-- /.content-wrapper -->
@endsection
