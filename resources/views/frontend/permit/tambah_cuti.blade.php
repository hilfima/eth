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
<h4 class="card-title float-left mb-0 mt-2">Tambah Pengajuan Cuti</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <!-- /.card-header -->
            <div class="card-body">
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_cuti') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    
            <?php 
            if(($kar[0]->periode_gajian==1)  ){
				
			
            ?>
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
                        <label class="col-sm-2 control-label">Jenis Cuti*</label>
                        <div class="col-sm-10">
                                <select class="form-control select2" name="cuti" style="width: 100%;" required>
                                    <option value="">Pilih Cuti</option>
                                    <?php
                                    foreach($jeniscuti AS $jeniscuti){
                                    	if($jeniscuti->nama=='CUTI TAHUNAN')
                                    		$jeniscuti->satuan =$totalcuti;
                                        echo '<option value="'.$jeniscuti->m_jenis_ijin_id.'">'.$jeniscuti->nama.' ('.$jeniscuti->satuan.' Hari)'.'</option>';
                                    }
                                    ?>
                                </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tanggal Awal*</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control " id="tgl_awaldate" name="tgl_awal" value="<?=date('Y-m-d');?>" required  onchange="countdate();cek_min(this)" min="<?=$help->tambah_tanggal(date('Y-m-d'),5)?>" onkeyup="change_tglawal()" >
                                    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tanggal Akhir*</label>   
                        <div class="col-sm-10"> 
                            <input type="date" class="form-control " id="tgl_akhirdate" name="tgl_akhir" data-target="#tgl_akhir" value="<?=date('Y-m-d');?>" required onchange="countdate();cek_min(this)" data-date-format="DD-MMMM-YYYY" min="<?=$help->tambah_tanggal(date('Y-m-d'),5)?>" >
                                       
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
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Keterangan Cuti*</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" placeholder="Keterangan Cuti..." id="alasan" name="alasan"  required=""></textarea>
                        </div>
                    </div>
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('fe.list_cuti') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            <?php }else{?>
				<div class="card-body">
					<h3>Maaf, Untuk Karyawan Pekanan tidak bisa mengajukan Cuti</h3>
				</div>
			<?php }?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
         // $('#jenis_ijin').val('');
         // change_jenis();
          cek_min_all();
        });
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
				url : "{!! route('fe.hitung_hari') !!}", 
				data : {'tgl_awal' : $("#tgl_awaldate").val(),'tgl_akhir' : $("#tgl_akhirdate").val()},
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
