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
                        <h1 class="m-0 text-dark">Jam Kerja</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jam Kerja</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_jam_kerja') !!}" enctype="multipart/form-data">
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
                                        echo '<option value="'.$entitas->m_lokasi_id.'">'.$entitas->nama.'</option>';
                                    }
                                    ?>
                                </select>
                                
                        </div>
                            </div>
                                    <input type="hidden" class="form-control " style="width: 20px" id="shifting" value="0" name="shifting" onclick="check(this)"  required=""/>
                            <div class="col-sm-12 d-none">
                            <div class="col-sm-12">
                        	 <div class="form-group">
                                
                                <div class="" id="tgl_posting" data-target-input="nearest" style="display: -webkit-box;;">
                                    <span style="justify-items: center;display: flex;align-content: center;align-items: center;margin-left: 10px;">
                                    	
                                    Shift Kerja
                                    </span> 
                                    
                                </div>
                            </div>
                        </div> <div class="col-sm-12" style="display: none" id="karyawanKonten">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Karyawan</label>
                                
								 <select class="form-control select2" name="karyawan[]" id="karyawan" multiple style="width: 100%;" required>
                                    <option value="">Pilih Karyawn</option>
                                   
                                </select>
                                
                        </div>
                        </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_absen" name="tgl_awal" data-target="#tgl_absen" value="{!! date('Y-m-d') !!}"/>
                                   
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="{!! date('Y-m-d') !!}"/>
                                   
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Jam Masuk</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="time" class="form-control timepicker-input" id="jam_masuk" name="jam_masuk" value="00:00:00" required=""/>
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-6">
                            <div class="form-group">
                                <label>Jam Keluar</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="time" class="form-control timepicker-input" id="jam_keluar" name="jam_keluar"  value="00:00:00" required=""/>
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <textarea class="form-control " id="tgl_absen" name="keterangan"  value="" placeholder="Keterangan"></textarea>
                                    
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
		//alert(entitas);
		$('#karyawan').val('');
  		if ($('#shifting').is(':checked')) {
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
	}
  	 
  </script>
    <!-- /.content-wrapper -->
@endsection
