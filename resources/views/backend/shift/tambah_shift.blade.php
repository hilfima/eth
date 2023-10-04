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
                        <h1 class="m-0 text-dark">Shift Kerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Shift Kerja</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.'.$type,$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                       
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Entitas</label>
                                
								 <select class="form-control select2" name="entitas" id="entitas" onchange="changeentitas(this)" style="width: 100%;" required>
                                    <option value="">Pilih Entitas</option>
                                    <?php
                                    foreach($lokasi AS $entitas){
                                    	$selected = $entitas->m_lokasi_id==$data['m_lokasi_id']?'selected':'';
                                        echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
                                    }
                                    ?>
                                </select>
                                
                        </div>
                            </div>
                           
                                    <input type="hidden" class="form-control " style="width: 20px" id="shifting" value="1" name="shifting" onclick="check(this)"  required=""/>
                                    <div class="col-sm-12" id="karyawanKonten">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Karyawan</label>
                                
								 <select class="form-control select2" name="karyawan[]" id="karyawan" multiple style="width: 100%;" required>
                                    <option value="">Pilih Karyawn</option>
                                   	<?php 
                                   	if($type=='update_shift'){
	                                   	foreach($list_karyawan AS $karyawan){
	                                    	$selected = in_array($karyawan->p_karyawan_id,$data['karyawan'])?'selected':'';
	                                        echo '<option value="'.$karyawan->p_karyawan_id.'" '.$selected.'>'.$karyawan->nama.'</option>';
	                                    }
									}
                                    ?>
                                </select>
                                
                        </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_absen" name="tgl_awal" data-target="#tgl_absen" value="{!! $data['tgl_awal']?$data['tgl_awal']:date('Y-m-d') !!}"/>
                                   
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="{!! $data['tgl_akhir']?$data['tgl_akhir']:date('Y-m-d') !!}"/>
                                   
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Jam Masuk</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="time" class="form-control timepicker-input" id="jam_masuk" name="jam_masuk" value="{!! $data['jam_awal']?$data['jam_awal']:'07:31' !!}" required=""/>
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Jam Keluar</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="time" class="form-control timepicker-input" id="jam_keluar" name="jam_keluar"  value="{!! $data['jam_akhir']?$data['jam_akhir']:'16:30' !!}" required=""/>
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <textarea class="form-control " id="tgl_absen" name="keterangan"  value="" placeholder="Keterangan">{!! $data['keterangan']!!}</textarea>
                                    
                                </div>
                            </div>
                        </div>
                            </div><div class="col-sm-12">
                        
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('admin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
  <script>
  	function check(e){
  		if ($(e).is(':checked')) {
			$('#karyawanKonten').show();
		}else
			$('#karyawanKonten').hide();
			
		
		$('#karyawan').val('');
		changeentitas();
	}
  	function changeentitas(){
		var entitas = $('#entitas').val();
		
		$('#karyawan').val('');
  		
  			$.ajax({
				type: 'get',
				
				url: 'daftarKaryawan/'+entitas+'/',
				dataType: 'json',
				success: function(data){
					//alert(data.respon)
					$('#karyawan').html(data.respon);
				}
			});
		
	}
  	 
  </script>
    <!-- /.content-wrapper -->
@endsection
