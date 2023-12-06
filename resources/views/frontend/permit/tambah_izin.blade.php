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
           
            <!-- /.card-header -->
           
             
              <div class="card-body">   <!-- form start -->
                <form class="form-horizontal" id="formIzin" method="POST" action="{!! route('fe.simpan_izin') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    

<form class="form-horizontal form-horizontal" id="formIzin" method="POST" action="{!! route('fe.simpan_izin') !!}" enctype="multipart/form-data">
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
                               <select class="form-control select2" name="cuti" id="jenis_ijin" style="width: 100%;" onchange="get_min_max_izin();cek_min_all()" required>
                                    <option value="">Pilih Izin</option>
                                    <?php
                                    /*change_jenis(this);*/
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
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tanggal Awal*</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control " id="tgl_awaldate" name="tgl_awal" value="<?=date('Y-m-d');?>" required  onchange="countdate(),cek_min(this),change_tglawal()" <?php if($tgl_cut_off) echo 'min="'.$tgl_cut_off.'"';?> onkeyup="change_tglawal()" >
                                    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tanggal Akhir*</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control " id="tgl_akhirdate" name="tgl_akhir" data-target="#tgl_akhir" value="<?=date('Y-m-d');?>" required onchange="countdate();cek_min();" data-date-format="DD-MMMM-YYYY" <?php if($tgl_cut_off) echo 'min="'.$tgl_cut_off.'"';?>>
                                       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Lama (Hari)*</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control masking0" id="lama" name="lama" value="1" placeholder="Lama Cuti/Izin..."  readonly="">       
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
                        
                    
                   <div id="JenisAlasanContent"></div>
                   
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Keterangan*</label>
                        <div class="col-sm-10">
                                
                                <textarea class="form-control" placeholder="Keterangan Izin..." id="alasan" name="alasan" required=""></textarea>
                        </div>
                    </div>
                   
                            <div>
                            	<input type="hidden" id="jam_masuk_kerja" name="jam_masuk_kerja">
                            	<input type="hidden" id="jam_keluar_kerja" name="jam_keluar_kerja">
                            	<input type="hidden" id="jam_masuk_finger" name="jam_masuk_finger">
                            	<input type="hidden" id="jam_keluar_finger" name="jam_keluar_finger">
                            	<input type="hidden" id="simpan_type" name="jam_masuk_kerja">
                            	<input type="hidden" id="simpan_keterangan" name="simpan_keterangan">
                            </div>
                              </div>
      
                    <!-- /.box-body -->
                    <div id="pesanSubmit"></div>
                    <div class="card-footer">
                        <a href="{!! route('fe.list_izin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="button" onclick="submit_form()" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                   
                    <!-- /.box-footer -->
                </form> </div>
           
            
           
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <input type="hidden" value="<?=$tgl_cut_off;?>" id="cut_off">
    <input type="hidden" value="" id="kontentParameter">
    <!-- /.content-wrapper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
            $(document).ready(function () {
                 $('#tgl_awaldate').attr('min',$('#cut_off').val());
                $('#tgl_akhirdate').attr('min',$('#cut_off').val());
                cek_min_all();
                get_min_max_izin()
                $('#demo').click(function () {
                    alert("Button is Clicked");
                });
            });
            function get_min_max_izin(){
            val = $('#jenis_ijin').val();
                $('#tgl_awaldate').attr('min',$('#cut_off').val());
                $('#tgl_akhirdate').attr('min',$('#cut_off').val());
            $.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "{!! route('fe.get_izin_detail') !!}", 
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
                        if(result.alasan){
                            
			          
                        
                        $('#JenisAlasanContent').html(result.kontent_alasan);
                          $('#alasan_id').attr('required', true);  
                        }else{
                        $('#JenisAlasanContent').html("");
			            $('#alasan_id').attr('required', false);  
                        }
                        
                    
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
					
				},
                error: function (error) {
                        $('#JenisAlasanContent').html("");
			            $('#filess').attr('required', false);  
			            $('#alasan_id').attr('required', false);  
                            $('#kontentParameter').html("");
                            $('#tgl_awaldate').attr('disabled',false);
                            $('#tgl_akhirdate').attr('disabled',false);
                    
                }
				
			});
        }
        function cek_min(e){
            min  = $(e).attr('min');
            
            val = $(e).val();
            if(val<min){
                 $(e).val(min)
            }
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
        $(document).ready(function(){
         // $('#jenis_ijin').val('');
          change_jenis();
          cek_min_all();
        });
        var tgl_cut_off_awal = '<?=$tgl_cut_off?>';
        var today_cut = '<?=date('Y-m-d')?>';
    	function change_jenis(){
    	   
			var val = $('#jenis_ijin').val();
		//	alert(val)
			if(val==20){
			$('#filess').attr('required', true);   
		
			    
			}else if(val==21){
			$('#filess').attr('required', true);   
		
			}else if(val==26){
			$('#filess').attr('required', true);   
		
			      
			}else			
			    $('#filess').attr('required', false);   
			
			if(val==25){
			    $('#tgl_awaldate').attr('min', today_cut);   
			    $('#tgl_akhirdate').attr('min', today_cut);
			}else {
			     $('#tgl_awaldate').attr('min', tgl_cut_off_awal);   
			    $('#tgl_akhirdate').attr('min', tgl_cut_off_awal);
			}
			
			if(val==21){
				
				$('#tgl_akhirdate').attr('disabled', true);   
				$('#jenisalasan').show();   
				$('#jenisalasan_ipc').hide();  
				$('#tgl_akhirdate').val($("#tgl_awaldate").val());   
				izin_khusus()
			}else if(val==26){
				
				$('#tgl_akhirdate').attr('disabled', true);   
				$('#jenisalasan').show();   
				$('#jenisalasan_ipc').hide(); 
				$('#tgl_akhirdate').val($("#tgl_awaldate").val());   
				izin_khusus()
			}else if(val==25){
				
				//$('#tgl_akhirdate').attr('disabled', true);   
				$('#jenisalasan_ipc').show();    
				$('#jenisalasan').hide(); 
				$('#tgl_akhirdate').val($("#tgl_awaldate").val());   
				izin_khusus()
			}else{
			    $('#tgl_akhirdate').attr('disabled', false);   
				$('#jenisalasan').hide();   
				$('#jenisalasan_ipc').hide();
				
			}
		
		}
		function change_tglawal()
		{
			
			if($('#jenis_ijin').val()==21){
				$('#tgl_akhirdate').val($("#tgl_awaldate").val());   
				izin_khusus()
				countdate()
			}else
		     if($('#jenis_ijin').val()==26){
				$('#tgl_akhirdate').val($("#tgl_awaldate").val());   
				izin_khusus()
				countdate()
			}
		}
		function submit_form()
		{
		    //alert($('#jenis_ijin').val());
		     if($('#jenis_ijin').val()==21){
				$('#tgl_akhirdate').val($("#tgl_awaldate").val());   
				izin_khusus()
				countdate()
			}else
		     if($('#jenis_ijin').val()==26){
				$('#tgl_akhirdate').val($("#tgl_awaldate").val());   
				izin_khusus()
				countdate()
			}
		    if($('#tgl_awaldate').val()=='1970-01-01'){
		        alert('Tanggal Awal tidak valid');
		    }else
		    if($('#tgl_akhirdate').val()=='1970-01-01'){
		        $('#tgl_akhirdate').val($('#tgl_awaldate').val());
		        alert('Tanggal Akhir dalam penyesuaian tanggal awal, silahkan meng-klik tombol simpan lagi!');
		    }else
		    if($('#tgl_awaldate').val()==''){
		        alert('Tanggal Awal belum terinput');
		    }else
		    if($('#tgl_akhirdate').val()==''){
		        alert('Tanggal Akhir belum terinput');
		    }else
		    if($('#atasan').val()==''){
		        alert('Atasan belum terinput');
		    }else
		    if($('#alasan').val()==''){
		        alert('Keterangan belum terinput');
		    }else
		    if($('#jenis_ijin').val()==20 && typeof  document.getElementById("filess").files[0] === 'undefined'){
		       
		       
		      
		        alert('File belum terinput');
		    }else
		    if($('#jenis_ijin').val()==21 && typeof  document.getElementById("filess").files[0] === 'undefined'){
		       
		       
		      
		        alert('File belum terinput');
		    }else
		    if($('#jenis_ijin').val()==26 && typeof  document.getElementById("filess").files[0] === 'undefined'){
		       
		       
		      
		        alert('File belum terinput');
		             
		    }else
		    if($('#simpan_type').val()=="0")
			    alert($('#simpan_keterangan').val())
			else{
			     countdate()
			      var required = $('form#formIzin input,form#formIzin textarea,form#formIzin select').filter('[required]:visible');
                var allRequired = true;
                required.each(function(){
                    if($(this).val() == ''){
                        allRequired = false;
                    }
                });
                
                if(allRequired){
                     $('#pesanSubmit').html('');
                   
			     document.getElementById('formIzin').submit();
				$('form#formIzin').submit();
                }else{
                     
                    $('#pesanSubmit').html('<div class="alert alert-danger" role="alert">silahkan isi form dengan benar, cek kembali form</div>');
                    
                    
                }
		       
		    }
		}function izin_khusus()
		{
			
			$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url : "{!! route('fe.get_jam_permit') !!}", 
					data : {'tgl_awal' : $("#tgl_awaldate").val(),'tgl_akhir' : $("#tgl_akhirdate").val(),'pengajuan' :'lembur',"_token": "{{ csrf_token() }}"},
					type : 'get',
					dataType : 'json',
					success : function(result){
							$('#jam_masuk_finger').val(result.jam_masuk_finger);
							$('#jam_keluar_finger').val(result.jam_keluar_finger);
							console.log("===== " + result + " =====");
						
					}
					
				});	
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
