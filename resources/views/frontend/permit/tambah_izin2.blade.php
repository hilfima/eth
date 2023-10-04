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
<h4 class="card-title float-left mb-0 mt-2">Tambah Pengajuan Izin</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
            </div>
            <!-- /.card-header -->
           
             
              <div class="card-body">   <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_izin') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $kar[0]->nik !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $kar[0]->nama_lengkap !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" placeholder="Nama Jabatan ..." id="jabatan" name="jabatan" value="{!! $kar[0]->jabatan !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Departemen</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $kar[0]->departemen !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y') !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Jenis Izin*</label>
                                <select class="form-control select2" name="cuti" id="cuti" style="width: 100%;" onchange="change_jenis(this)" required>
                                    <option value="">Pilih Izin</option>
                                    <?php
                                    
                                    foreach($jenisizin AS $jenisizin){
                                    	$dis = '';
                                    	if($jenisizin->m_jenis_ijin_id==3 and $tolcut>0 and $dw[0]->m_status_pekerjaan_id!=5)
                                    	$dis = 'disabled';
                                    	if($jenisizin->nama=='IZIN POTONG CUTI')
                                    		$jenisizin->jumlah =$totalcuti;
                                    	if(!($totalcuti<=0 and $jenisizin->nama=='IZIN POTONG CUTI' ))
                                        echo '<option value="'.$jenisizin->m_jenis_ijin_id.'" '.$dis.'>'.$jenisizin->nama.' - ('.$jenisizin->jumlah.' Hari )'.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Awal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_awaldate" name="tgl_awal" data-target="#tgl_awal" value="" required  onchange="countdate()" <?php if($tgl_cut_off) echo 'min="'.$tgl_cut_off.'"';?>>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Akhir*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhirdate" name="tgl_akhir" data-target="#tgl_akhir" value="" required onchange="countdate()" data-date-format="DD-MMMM-YYYY" <?php if($tgl_cut_off) echo 'min="'.$tgl_cut_off.'"';?>>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Lama (Hari)*</label>
                                <input type="text" class="form-control masking0" id="lama" name="lama" value="" placeholder="Lama Cuti/Izin..."  readonly="">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" class="form-control" id="file" name="file" value="" >
                            </div>
                        </div>
                        <?php if($idkar[0]->m_pangkat_id!=6){?>
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Penanggung jawab sementara</label>
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
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Atasan*</label>
                                <select class="form-control select2" name="atasan" style="width: 100%;" required>
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
                        <div class="col-sm-<?=($idkar[0]->m_pangkat_id!=6)?'12':'6';?>">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Alasan Izin*</label>
                                <textarea class="form-control" placeholder="Alasan Izin..." id="alasan" name="alasan" required=""></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.list_izin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form> </div>
           
            
           
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
    	function change_jenis(e){
			var val = e.value;
			if(val==20)
			$('#file').attr('required', true);   
			else			
			$('#file').attr('required', false);   
		}
		function countdate()
		{
			if( $("#tgl_akhirdate").val() &&  $("#tgl_awaldate").val()){
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "{!! route('fe.hitung_hari') !!}", 
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
	 	
		function countdate2(){
			var start = new Date($("#tgl_awaldate").val());
			var end = new Date($("#tgl_akhirdate").val());

//alert($("#tgl_awaldate").val());
			var loop = new Date(start);
			var count = 0;
			while(loop <= end){
			   var newDate = loop.setDate(loop.getDate() + 1);
			   loop = new Date(newDate);
			   //if()
			   count+=1;
			}
        	///alert(count);
          
            
           // $("#x_Date_Difference").val(diffDays);
       
		}
    
    </script>
@endsection
