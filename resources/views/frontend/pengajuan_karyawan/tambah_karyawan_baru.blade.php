@extends('layouts.app_fe')

@section('content')
<link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'>
<link rel="stylesheet" href="./style.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
<div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
         <div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2">Pengajuan Karyawan Baru</h4>
			</div>
		</div>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
               <h3 class="card-title mb-0 pb-0">Tambah</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_karyawan_baru') !!}" enctype="multipart/form-data" onsubmit="return confirm('Apakah anda yakin?');">
                    {{ csrf_field() }}
                    <div class="row">
                       <div class="col-sm-12" id="karyawanKonten">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Posisi Yang Dibutuhkan</label>
                                
								 <select class="form-control select2" name="jabatan" id="jabatan"  style="width: 100%;" required onchange="changeposisi(this)">
                                    <option value="" >Pilih Jabatan</option>
                                   <option value="-1">Posisi Baru</option>
                                   
                                </select>
                                
                        </div>
                            <div id="jabatan_lain" style="display: none" >
                            <div class="form-group" >
                                <label>Nama Posisi</label>
                              <input class="form-control " id="tgl_absen" name="posisi"  value="" placeholder="Nama Posisi">
                              	
                              </div>
                            <div class="form-group">
                                <label>Dapartement</label>
                              <select class="form-control " name="dept"  style="width: 100%;" onchange="change_dept(this);" >
                                   <option value="" >Pilih Departement</option>
                                   <option value="-1">Departement Baru</option>
                                    <?php foreach($departemen as $dept){
                                    	if($dept->m_departemen_id==$pekerjaan[0]->m_departemen_id){?>
                                    <option value="<?=$dept->m_departemen_id;?>" ><?=$dept->nama;?></option>
									<?php }?>
									<?php }?>
                                </select>
                            </div>
                            <div id="dept_lain" style="display: none" >
                            <div class="form-group" >
                                <label>Nama Departemen Baru</label>
                              	<input class="form-control " id="dept_baru" name="dept_baru"  value="" placeholder="Nama Departemen Baru">
                              	
                              </div>
                            </div>
                           
                            <div class="form-group">
                                <label>Level</label>
                               <select class="form-control select2" name="level" id="karyawan"  style="width: 100%;" >
                                    <option value="" >Pilih Level</option>
                                    <?php foreach($pangkat as $p){?>
                                    <option value="<?=$p->m_pangkat_id;?>"><?=$p->nama;?></option>
									<?php }?>
                                </select>
                            </div><div class="form-group">
                                <label>Entitas</label>
                               <select class="form-control " name="lokasi" id="karyawan"  style="width: 100%;" >
                                   
                                    <?php foreach($lokasi as $lokasi){
                                    	if($lokasi->m_lokasi_id==$pekerjaan[0]->m_lokasi_id){?>
                                    <option value="<?=$lokasi->m_lokasi_id;?>" ><?=$lokasi->nama;?></option>
									<?php }?>
									<?php }?>
                                </select>
                            </div>
                            </div>
                            <div class="form-group">
                                <label>Lokasi Penempatan</label> 
                              <input class="form-control"  name="lokasi_penempatan"  value="" placeholder="Lokasi Penempatan...">    
                            </div> <div class="form-group">
                                <label>Jumlah Kebutuhan</label>
                              <input class="form-control"  name="kebutuhan"  value="" placeholder="Jumlah Kebutuhan..." required="">    
                            </div> <div class="form-group">
                                <label>Tanggal Diperlukan</label>
                              <input class="form-control" type="date" name="tangal_diperlukan"  value="" placeholder="Jumlah Kebutuhan...">    
                            </div>
                            <div class="form-group">
                                <label>Alasan Permintaan</label>
                              <select class="form-control " id="tgl_absen" name="alasan" required="" value="" placeholder="Keterangan" onclick="changealasan(this)">
                                    	
                                    	<option value="">- Pilih Alasan Permintaan -</option>
                                    	<option value="Pergantian Karyawan">Pergantian Karyawan</option>
                                    	<option value="Penambahan Karyawan">Penambahan Karyawan</option>
                                    	<option value="Penambahan & Pergantian Karyawan">Penambahan & Pergantian Karyawan</option>
                                    </select>
                            </div>
                            
                             <div id="tambahkaryawan" style="display: none">
	                             <div class="form-group">
	                                <label>Alasan Penambahan Karyawan</label>
	                              <textarea class="form-control " id="tgl_absen" name="alasantambah"  value="" placeholder="Deskripsi"></textarea>
	                            </div>
                             </div>
                             <div id="gantikaryawan"  style="display: none">
	                             <div class="form-group">
	                                <label>Nama Karyawan yang diganti</label>
	                              <select class="form-control select2" id="tgl_absen" name="alasanganti"  value="" placeholder="Deskripsi" style="width: 100%">
	                              	<option value="">- Pilih Karyawan -</option>
	                              	<?php foreach($karyawan as $karyawan){?>
	                              	<option value="<?=$karyawan->p_karyawan_id;?>"><?=$karyawan->nama;?></option>
	                              	<?php }?>
	                              </select>
	                            </div>
	                            <!--<div class="form-group">
                                	<label>Alasan Resign</label>
	                              	<textarea class="form-control " id="tgl_absen" name="resign"  value="" placeholder="Alasan Resign"></textarea>
	                            </div>-->
                             </div>
                             <div class="form-group">
                                <label>Gambarkan Posisi dalam Struktur Organisasi</label>
                              <input type="file" class="form-control" name="struktur"  value="" placeholder="Deskripsi">
                            </div><div class="form-group">
                                <label>Uraian Pekerjaan</label>
                              <textarea class="form-control summernote" id="tgl_absen" name="uraian"  value="" required="" placeholder="Deskripsi"></textarea>
                            </div>
                           <div class="form-group ">
                                <label class="">Kualifikasi Usia</label>
                                <div class="row">
                                	<div class="col-6">
                                		
                              <input type="number" class="form-control  " id="tgl_absen" name="usia_dari"  value="" placeholder="Usia dari.." required="" >
                                	</div>
                                	<div class="col-6">
                              <input type="number" class="form-control  " id="tgl_absen" name="usia_ke"  value=""  placeholder="Usia Sampai.." required="" >
                                	</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Kualifikasi Jenis Kelamin</label>
                                 <select class="form-control " id="tgl_absen" name="jk"  value="" required="" >
                                    	
                                    	<option value="">- Pilih Kualifikasi Jenis Kelamin -</option>
                                    	<option value="Laki Laki">Laki Laki</option>
                                    	<option value="Wanita">Wanita</option>
                                    	<option value="Pria dan Wanita">Pria dan Wanita</option>
                                    </select>
                             
                            </div><div class="form-group">
                                <label>Kualifikasi Keahlian</label>
                              <input class="form-control "  name="k_keahlian"  value="" required=""  placeholder="Kualifikasi Kompetensi Kerja">
                            </div>
                           <div class="form-group">
                                <label>Kualifikasi Minimal Pendidikan dan Jurusan</label>
                              <input class="form-control "  name="k_pendidikan"  value="" placeholder="Kualifikasi Minimal Pendidikan dan Jurusan">
                            </div>
                        <div class="form-group">
                                <label>Kualifikasi Pengalaman Kerja</label>
                              <input class="form-control "  name="k_pengalaman"  value="" placeholder="KualifikasiPengalaman Kerja">
                            </div><div class="form-group">
                                <label>Kompetensi lainnya</label>
                              <textarea class="form-control "  name="k_kompetensi"  value="" placeholder="Kompetensi lainnya"></textarea>
                            </div>
                            <hr>
                            @if($idkar[0]->m_pangkat_id!=6)
                            <div class="form-group">
	                                <label>Atasan 1(Atasan Langsung)</label>
	                              <select class="form-control select2" id="atasan1" name="atasan"  value="" style="width: 100%">
	                              	<option value="">- Pilih Atasan 1-</option>
	                              	
	                              </select>
	                            </div>
                            <div class="form-group">
	                                <label>Atasan 2(Direksi)</label>
	                              <select class="form-control select2" id="atasan2" name="atasan2"  value="" required="" style="width: 100%">
	                              	<option value="">- Pilih Atasan 2-</option>
	                              	
	                              </select>
	                            </div>
	                       @endif
                        </div>
                           
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.karyawan_baru') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>

  	function changeposisi(e){
  		
  		if($(e).val()=='-1'){
			$('#jabatan_lain').show();
		}else{
			$('#jabatan_lain').hide();
			
		}
	}  function change_dept(e){
           //alert($(e).val());
           if($(e).val()=='-1'){
           		$("#dept_baru").val("");
                $("#dept_lain").show();
            }else{
           		$("#dept_baru").val("");
            	$("#dept_lain").hide();
            }
    }
	function changealasan(e){
  		
  		if($(e).val()=='Penambahan Karyawan'){
			$('#tambahkaryawan').show();
			$('#gantikaryawan').hide();
		}else if($(e).val()=='Pergantian Karyawan'){
			$('#tambahkaryawan').hide();
			$('#gantikaryawan').show();
		}else{
			$('#tambahkaryawan').show();
			$('#gantikaryawan').show();
			
		}
	}
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
	optimasi_jabtruk();
  	function optimasi_jabtruk(){
		
  		
  			$.ajax({
				type: 'get',
				
				url: '{{route('optimasi_jabstruk')}}',
				dataType: 'json',
				success: function(data){
					//alert(data.respon)
					$('#jabatan').html(data.option_jabatan);
					$('#atasan1').html(data.option_atasan1);
					$('#atasan2').html(data.option_atasandireksi);
				}
			});
		
	}
  	 
  </script> <script>
                            	
                            </script>
    <!-- /.content-wrapper -->
@endsection
