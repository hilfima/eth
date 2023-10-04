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
<h4 class="card-title float-left mb-0 mt-2">Tambah Pengajuan Pergantian Hari Libur</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_pergantian_hari_libur') !!}" enctype="multipart/form-data">
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
                            <div class="form-group">
                                <label>Tanggal Libur Awal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_awaldate" name="tgl_libur" data-target="#tgl_awal" value="" required  onchange="countdate()" <?php if($tgl_cut_off) echo 'min="'.$tgl_cut_off.'"';?>>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Libur Diajukan*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhirdate" name="tgl_ajuan" data-target="#tgl_akhir" value="" required onchange="countdate()" data-date-format="DD-MMMM-YYYY" <?php if($tgl_cut_off) echo 'min="'.$tgl_cut_off.'"';?>>
                                   
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" class="form-control" id="file" name="file" value="" >
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
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Alasan Pergantian hari libur*</label>
                                <textarea class="form-control" placeholder="Alasan Pergantian hari libur..." id="alasan" name="keterangan"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.pergantian_hari_libur') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
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
