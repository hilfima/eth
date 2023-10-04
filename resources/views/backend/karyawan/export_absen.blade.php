@extends('layouts.appsA')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Export Absen Hr</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Export Absen Hr</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
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
                <?php if($user[0]->p_karyawan_id==174 or $user[0]->p_karyawan_id==269 or $user[0]->p_karyawan_id==22){?>
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_input_absen_hr') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                 <?php 
                            	//174
                            	
									$sqlkaryawans="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap
FROM p_karyawan a
                    					WHERE 1=1 and a.active=1 order by a.nama";
        								$karyawans=DB::connection()->select($sqlkaryawans);?>
								 <select class="form-control select2" name="nama[]" style="width: 100%;" id="nama" onchange="load_data()" multiple=" " placeholder="Pilih Karyawan" required>
                                    <option value="" disabled="">Pilih Karyawan</option>
                                    <?php
                                    foreach($karyawans AS $karyawans){
                                        echo '<option value="'.$karyawans->p_karyawan_id.'">'.$karyawans->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                               
                           
                               
                            
                        </div>
                            </div>
                        
                          <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_absen" name="tgl_absen" data-target="#tgl_absen" value="{!! date('Y-m-d') !!}"   />
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-4">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_absen" name="tgl_absen" data-target="#tgl_absen" value="{!! date('Y-m-d') !!}"    />
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('admin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info mr-2 me-2"><span class="fa fa-check"></span> Download Template</button>
                    </div>
                </form>
                    </div>
                    </div>
                    <!-- /.box-footer -->
                     <div class="card">
                    	<div class="card-body">
                    	<form class="form-horizontal" method="POST" action="{!! route('be.simpan_export_absen_hr') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    		<div class="form-group">
                                <label>Data Excel</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="file" class="form-control" id="gapok" name="excel"     />
                                    
                                </div>
                            </div> 
                             <button type="submit" class="btn btn-info"><span class="fa fa-check"></span> Simpan</button>
                   	 </div>
                    </div>
                 <?php 
					}else{
									
                            ?>
                       <div class="card">
                    	<div class="card-body">
                    	
                    	
                            <h1>Maaf, Menu ini diakses hanya untuk Hr..</h1>
                              <a href="{!! route('admin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a></div>
                    </div>
                            <?php }?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <script  >
    	$('.jqueryui-marker-datepicker').datetimepicker({
        showSecond: true,
        dateFormat: 'dd/mm/yy',
	    timeFormat: 'hh:mm:ss',
	    stepHour: 2,
	    stepMinute: 10,
	    stepSecond: 10

     });
     $('#tgl_absen').daterangepicker({
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
            format: 'YYYY-MM-DD'
        }
    })
     function load_data(key)
		{
			nama =  $('#nama').val();
			tgl_absen =  $('#tgl_absen').val();
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "{{ route('be.ajax_input_absen') }}", 
				data : {'token' : nama,'tgl' : tgl_absen},
				type : 'POST', 
				dataType : 'json',
				success : function(result){

					$('#no_absen').val((result.no_absen));
					$('#mesin').val((result.mesin));
					var datetime = result.date_time;
					var time = datetime.slice(-8);
					$('#jam_masuk').val((time));
					
				}
				
			});
	
	 	}
	 	
	 	
    </script>
    <!-- /.content-wrapper -->
@endsection
