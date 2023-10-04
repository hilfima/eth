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
<h4 class="card-title float-left mb-0 mt-2">Tambah Key Perfomance Indicator</h4>

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
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_kpi') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <!--<div class="row">-->
                        <!--<div class="col-sm-2">-->
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $kar[0]->nik !!}" readonly>
                            </div>
                        <!--</div>-->
                        <!--<div class="col-sm-3">-->
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $kar[0]->nama_lengkap !!}" readonly>
                            </div>
                        <!--</div>-->
                        <!--<div class="col-sm-3">-->
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" placeholder="Nama Jabatan ..." id="jabatan"  value="{!! $kar[0]->jabatan !!}" readonly>
                                <input type="hidden" class="form-control" placeholder="Nama Jabatan ..." name="jabatan"  value="{!! $kar[0]->m_jabatan_id !!}" readonly>
                               
                            </div>
                        <!--</div>-->
                        <!--<div class="col-sm-4">-->
                            <!-- text input -->
                            <div class="form-group">
                                <label>Unit Kerja</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $kar[0]->departemen !!}" readonly>
                            </div>
                        <!--</div>-->
                        
                     
                        
                        <!--<div class="col-sm-3">-->
                            <!-- text input -->
                            <div class="form-group">
                                <label>Periode*</label>
                                <select class="form-control" placeholder="Tahun..." id="tahun" name="periode" onchange="change_periode(this)">
                                    <!--<option value="">Pilih Periode</option>-->
                                    <!--<option value="1">Costum</option>-->
                                    <!--<option value="2">Bulanan</option><!-- 1 bulan-->-->
                                    <option value="3">Triwulan</option><!-- 3 bulan-->
                                    <!--<option value="4">Tahunan</option><!-- 4 triwulan-->-->
                                </select>
                            </div>
                            <script>
                                function change_periode(e){
                                    value = parseInt($(e).val());
                                    //if(value==1){
                                      //  $('#costum_periode').show();
                                    //}else{
                                      //  $('#costum_periode').hide();
                                    //}
                                }
                            </script>
                            <div class="form-group">
                                <label>Tanggal Awal*</label>
                                <input class="form-control" type="date" placeholder="Tahun..." id="tahun" name="tanggal_awal" value="{{date('Y-m-d')}}"></input>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Akhir*</label>
                                <input class="form-control" type="date" placeholder="Tahun..." id="tahun" name="tanggal_akhir" value="{{date('Y-m-d')}}"></input>
                            </div>
                            <div class="form-group">
                                <label>Tipe Pencapaian*</label>
                                <select class="form-control" type="date" placeholder="Tahun..." id="tahun" name="tipe_pencapaian">
                                    <option value="">Pilih Tipe Pencapaian</option>
                                    <option>Individu</option>
                                    <option>Tim</option>
                                </select>
                            </div>
                            <div id="costum_periode" style="display:none">
                            
                        <!--    <div class="form-group">-->
                        <!--        <label>Tahun Awal*</label>-->
                        <!--        <input class="form-control" placeholder="Tahun..." id="tahun" name="tahun_awal" value="{{date('Y')}}"></input>-->
                        <!--    </div>-->
                        
                        <!--</div>-->
                        <!--<div class="col-sm-3">-->
                        <!--    <div class="form-group">-->
                        <!--        <label>Triwulan Awal*</label>-->
                        <!--        <select class="form-control" id="tahun" name="triwulan_awal" >-->
                        <!--            <option value="">- Pilih -</option>-->
                        <!--            <option value="1">Triwulan 1</option>-->
                        <!--            <option value="2">Triwulan 2</option>-->
                        <!--            <option value="3">Triwulan 3</option>-->
                        <!--            <option value="4">Triwulan 4</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!--<div class="col-sm-3">-->
                            <!-- text input -->
                        <!--    <div class="form-group">-->
                        <!--        <label>Tahun Akhir*</label>-->
                        <!--        <input class="form-control" placeholder="Tahun..." id="tahun" name="tahun_akhir" value="{{date('Y')}}"></input>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!--<div class="col-sm-3">-->
                        <!--    <div class="form-group">-->
                        <!--        <label>Triwulan Akhir*</label>-->
                        <!--        <select class="form-control" id="tahun" name="triwulan_akhir" >-->
                        <!--            <option value="">- Pilih -</option>-->
                        <!--            <option value="1">Triwulan 1</option>-->
                        <!--            <option value="2">Triwulan 2</option>-->
                        <!--            <option value="3">Triwulan 3</option>-->
                        <!--            <option value="4">Triwulan 4</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--    </div>-->
                        </div>
                        @if($kar[0]->m_pangkat_id!=6)
                        <!--<div class="col-sm-6">-->
                            <!-- text input -->
                            <div class="form-group">
                                <label>Atasan 1</label>
                                <select class="form-control select2" name="atasan" style="width: 100%;" >
                                    <option value="">Pilih Atasan 1</option>
                                    <?php
                                    foreach($appr1 AS $appr1){
                                        echo '<option value="'.$appr1->p_karyawan_id.'">'.$appr1->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        <!--</div><div class="col-sm-6">-->
                            <!-- text input -->
                            <div class="form-group">
                                <label>Atasan 2*</label>
                                <select class="form-control select2" name="atasan2" style="width: 100%;" required>
                                    <option value="">Pilih Atasan 2</option>
                                    <?php
                                    foreach($appr2 AS $appr){
                                        echo '<option value="'.$appr->p_karyawan_id.'">'.$appr->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        <!--</div>-->
                        @endif
                       
                    <!--</div>-->
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
