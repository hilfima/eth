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
                    <div class="form-group">
                                <label>Nama Jam Kerja</label>
                                <input type="text" class="form-control" placeholder="Nama  ..." id="judul" name="data[nama_master]" required>
                            </div>
                    <div class="row">
                       
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Entitas</label>
                                
								 <select class="form-control select2" name="entitas[]" multiple="" id="entitas" onchange="changeentitas(this)" style="width: 100%;" required >
                                    <option value="" disabled>Pilih Entitas</option>
                                    <?php
                                    foreach($lokasi AS $entitas){
                                        echo '<option value="'.$entitas->m_lokasi_id.'">'.$entitas->nama.'</option>';
                                    }
                                    ?>
                                </select>
                                
                        </div>
                             
                        <div class="form-group ">
                               
                               <input class="text-left" type="checkbox" placeholder="Nama Batas ..." id="semua_seksi" name="semua_seksi" value="1"   required onclick="semua()"> Semua Departemen(seusai entitas)
                              
                               </div>
                               <div class="form-group ">
                               <label>Seksi</label>
                               <select class="form-control select2" multiple placeholder="Nama Batas ..." id="seksi" name="list_seksi[]"   required>
                               <?php 
                           $data = DB::connection()->select('select m_departemen.m_departemen_id,m_departemen.nama as nama_dept,m_lokasi.nama as nmlokasi from m_departemen 
                           left join m_divisi on m_departemen.m_divisi_id = m_divisi.m_divisi_id
                           left join m_divisi_new on m_divisi_new.m_divisi_new_id = m_divisi.m_divisi_new_id
                           left join m_directorat on m_divisi_new.m_directorat_id = m_directorat.m_directorat_id
                           left join m_lokasi on m_lokasi.m_lokasi_id = m_directorat.m_lokasi_id
                           where m_departemen.active=1');
                           echo'<option value="">- Seksi -</option>';
                           foreach($data as $row){
                               echo '<option value="'.$row->m_departemen_id.'">'.$row->nama_dept.'('.$row->nmlokasi.')</option>';
                           }
                           ?>
                                   
                               </select>
                           </div></div> 	
                           
                           
                           
                                 
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
                        </div>
                        <div class="form-group col-md-6">
	                                <label>Batas Jam Masuk</label>
	                                <input type="time" class="form-control" placeholder="Nama  ..." id="judul" name="data[jam_batas_masuk]" required>
	                            </div>
	                            <div class="form-group col-md-6 ">
	                                <label>Batas Jam Keluar</label>
	                                <input type="time" class="form-control" placeholder="Nama  ..." id="judul" name="data[jam_batas_keluar]" required>
	                            </div>
	                            <div class="form-group  col-md-3">
                                <label>Masuk Senin</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_senin]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk selasa</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_selasa]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Rabu</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_rabu]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Kamis</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_kamis]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Jumat</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_jumat]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Sabtu</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_sabtu]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Ahad</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_ahad]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                        <div class="col-sm-12">
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
  	function semua(){
  		if($('#semua_seksi').is(':checked')){
  			$('#seksi').attr('disabled',true);
  		}else{
  			$('#seksi').attr('disabled',false);
  			
  		}
  	}
  	function changeentitas(){
		var entitas = $('#entitas').val();
		//alert(entitas);
        $.ajax({
				type: 'post',
				data:{_token: "{{ csrf_token() }}", entitas:entitas},
                cache : false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				url: '<?=route('be.entitas_departement')?>',
				dataType: 'html',
				success: function(data){
					//alert(data.respon)
					$('#seksi').html(data);
					
				}
			});
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
