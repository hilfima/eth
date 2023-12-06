@extends('layouts.app_fe')
<?php
date_default_timezone_set('Asia/Jakarta');
?>
<style type="text/css">
    .without_ampm::-webkit-datetime-edit-ampm-field {
        display: none;
    }
    input[type=time]::-webkit-clear-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        -o-appearance: none;
        -ms-appearance:none;
        appearance: none;
        margin: -10px;
    }
</style>

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
<h4 class="card-title float-left mb-0 mt-2">Tambah Perintah Lembur</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <?php 
            if(!in_array($kar[0]->m_pangkat_id,[5,6]) or $kar[0]->p_karyawan_id==269 ){
				
			
            ?>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_lembur') !!}" enctype="multipart/form-data" onsubmit="change_jam()" id="formlembur">
                    
                        
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
                           <input type="hidden" class="form-control" placeholder="Nama ..." id="p_karyawan_id"  value="{!! $kar[0]->p_karyawan_id !!}" readonly>
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
                        <label class="col-sm-2 control-label">Tanggal*</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control " id="tgl" name="tgl" value="<?=date('Y-m-d');?>" onchange="cek_min(this)" <?php if($tgl_cut_off) echo 'min="'.(date('Y-m-d')).'"';?> required >
                                       
                        </div>
                    </div>
                        
                     <div class="form-group row">
                        <label class="col-sm-2 control-label">Lama (Jam)*</label>
                        <div class="col-sm-10"><input type="text" class="form-control masking0" id="lama" name="lama" value="" placeholder="Lama Lembur..." required readonly="">
                                       
                        </div>
                    </div>
                   
                     <div class="form-group row">
                        <label class="col-sm-2 control-label">Jam Lembur*</label>
                        <div class="col-sm-5">
                            
                                <input type="text" class="form-control without_ampm masked" id="jam_awal" name="jam_awal" value="16:30"  placeholder="Jam Awal" required>
                                       
                        </div>
                        <div class="col-sm-5">
                            
                                <input type="text" class="form-control without_ampm masked" id="jam_akhir" name="jam_akhir" value="23:00"  placeholder="Jam Akhir" required>
                                       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Jam Istirahat*</label>
                        <div class="col-sm-5">
                            
                                <input type="time" class="form-control istirahat " id="jam_istirahat_awal" name="jam_istirahat_awal"   placeholder="Jam Istirahat" >
                                       
                        </div>
                        <div class="col-sm-5">
                            
                                <input type="time" class="form-control istirahat " id="jam_istirahat_akhir" name="jam_istirahat_akhir"   placeholder="Jam Istirahat" >
                                       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Tipe Lembur*</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="tipe_lembur" style="width: 100%;" required>
                                    <option value="">Pilih Lembur</option>
                                    <option value="Lembur Hari Kerja">Lembur Hari Kerja</option>
                                    <option value="Lembur Hari Libur">Lembur Hari Libur</option>
                                    <option value="Lembur Proposional">Lembur Proposional</option>
                                </select>
                                       
                        </div>
                    </div>
                        
                        
                        
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Atasan Layer 1</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="atasan" style="width: 100%;" >
                                    <option value="">Pilih Atasan</option>
                                    <?php
                                    foreach($appr1 AS $appr){
                                        echo '<option value="'.$appr->p_karyawan_id.'">'.$appr->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                                       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Atasan Layer 2*</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="atasan2" style="width: 100%;" required>
                                    <option value="">Pilih Atasan</option>
                                    <?php
                                    foreach($appr2 AS $appr){
                                        echo '<option value="'.$appr->p_karyawan_id.'">'.$appr->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Keterangan Lembur*</label>
                        <div class="col-sm-10">
                                <textarea class="form-control" placeholder="Keterangan Penugasan Lembur..." id="alasan" name="alasan"  required=""></textarea>
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
                    <div class="card-footer">
                        <div id="pesanSubmit"></div>
                        <a href="{!! route('fe.list_lembur') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="button" onclick="lembur_jam_yang_sama()" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
			<?php }else{?>
				<div class="card-body">
					<h3>Maaf, Untuk Manager dan Direktur tidak bisa mengajukan lembur</h3>
				</div>
			<?php }?>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
	<style>
	input{
  height:32px;
  width:100%;
}
body{
  margin:20px auto;
/*   display:flex;
  justify-content:center; */
}
.show-input{
  width:300px;
  padding:10px;
}
h3{
  margin-top:20px;
  white-space:no-wrap;
  margin-left:10px
}
	</style>
	<!DOCTYPE html>
<html lang="en" >
<head>
  <link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'><link rel="stylesheet" href="./style.css">

</head>

  <script src='https://code.jquery.com/jquery-1.11.0.min.js'></script>
<script src='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js'></script><script  src="./script.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	
<script src='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js'></script><script  src="./script.js"></script> 


<script>
    	var masking = {

  // User defined Values
  //maskedInputs : document.getElementsByClassName('masked'), // add with IE 8's death
  maskedInputs : document.querySelectorAll('.masked'), // kill with IE 8's death
  maskedNumber : 'XdDmMyY9',
  maskedLetter : '_',

  init: function () {
    masking.setUpMasks(masking.maskedInputs);
    masking.maskedInputs = document.querySelectorAll('.masked'); // Repopulating. Needed b/c static node list was created above.
    masking.activateMasking(masking.maskedInputs);
  },

  setUpMasks: function (inputs) {
    var i, l = inputs.length;

    for(i = 0; i < l; i++) {
      masking.createShell(inputs[i]);
    }
  },
  
  // replaces each masked input with a shall containing the input and it's mask.
  createShell : function (input) {
    var text = '', 
        placeholder = input.getAttribute('placeholder');

    input.setAttribute('maxlength', placeholder.length);
    input.setAttribute('data-placeholder', placeholder);
    input.removeAttribute('placeholder');

    text = '<span class="shell">' +
      '<span aria-hidden="true" style="display:none" id="' + input.id + 
      'Mask"><i></i>' + placeholder + '</span>' + 
      input.outerHTML +
      '</span>';

    input.outerHTML = text;
  },

  setValueOfMask : function (e) {
    var value = e.target.value,
        placeholder = e.target.getAttribute('data-placeholder');

    return "<i>" + value + "</i>" + placeholder.substr(value.length);
  },
  
  // add event listeners
  activateMasking : function (inputs) {
    var i, l;

    for (i = 0, l = inputs.length; i < l; i++) {
      if (masking.maskedInputs[i].addEventListener) { // remove "if" after death of IE 8
        masking.maskedInputs[i].addEventListener('keyup', function(e) {
          masking.handleValueChange(e);
        }, false); 
      } else if (masking.maskedInputs[i].attachEvent) { // For IE 8
          masking.maskedInputs[i].attachEvent("onkeyup", function(e) {
          e.target = e.srcElement; 
          masking.handleValueChange(e);
        });
      }
    }
  },
  
  handleValueChange : function (e) {
    var id = e.target.getAttribute('id');
        
    switch (e.keyCode) { // allows navigating thru input
      case 20: // caplocks
      case 17: // control
      case 18: // option
      case 16: // shift
      case 37: // arrow keys
      case 38:
      case 39:
      case 40:
      case  9: // tab (let blur handle tab)
        return;
      }

    document.getElementById(id).value = masking.handleCurrentValue(e);
    document.getElementById(id + 'Mask').innerHTML = masking.setValueOfMask(e);

  },

  handleCurrentValue : function (e) {
    var isCharsetPresent = e.target.getAttribute('data-charset'), 
        placeholder = isCharsetPresent || e.target.getAttribute('data-placeholder'),
        value = e.target.value, l = placeholder.length, newValue = '', 
        i, j, isInt, isLetter, strippedValue;

    // strip special characters
    strippedValue = isCharsetPresent ? value.replace(/\W/g, "") : value.replace(/\D/g, "");

    for (i = 0, j = 0; i < l; i++) {
        var x = 
        isInt = !isNaN(parseInt(strippedValue[j]));
        isLetter = strippedValue[j] ? strippedValue[j].match(/[A-Z]/i) : false;
        matchesNumber = masking.maskedNumber.indexOf(placeholder[i]) >= 0;
        matchesLetter = masking.maskedLetter.indexOf(placeholder[i]) >= 0;

        if ((matchesNumber && isInt) || (isCharsetPresent && matchesLetter && isLetter)) {

                newValue += strippedValue[j++];

          } else if ((!isCharsetPresent && !isInt && matchesNumber) || (isCharsetPresent && ((matchesLetter && !isLetter) || (matchesNumber && !isInt)))) {
                // masking.errorOnKeyEntry(); // write your own error handling function
                return newValue; 

        } else {
            newValue += placeholder[i];
        } 
        // break if no characters left and the pattern is non-special character
        if (strippedValue[j] == undefined) { 
          break;
        }
    }
    if (e.target.getAttribute('data-valid-example')) {
      return masking.validateProgress(e, newValue);
    }
    return newValue;
  },

  validateProgress : function (e, value) {
    var validExample = e.target.getAttribute('data-valid-example'),
        pattern = new RegExp(e.target.getAttribute('pattern')),
        placeholder = e.target.getAttribute('data-placeholder'),
        l = value.length, testValue = '';

    //convert to months
    if (l == 1 && placeholder.toUpperCase().substr(0,2) == 'MM') {
      if(value > 1 && value < 10) {
        value = '0' + value;
      }
      return value;
    }
    // test the value, removing the last character, until what you have is a submatch
    for ( i = l; i >= 0; i--) {
      testValue = value + validExample.substr(value.length);
      if (pattern.test(testValue)) {
        return value;
      } else {
        value = value.substr(0, value.length-1);
      }
    }
  
    return value;
  },

  errorOnKeyEntry : function () {
    // Write your own error handling
  }
}

masking.init();
    </script>
<script type="text/javascript">
function lembur_jam_yang_sama()
		{
		    
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "{!! route('fe.lembur_duplicate_check_single') !!}", 
				data : {'tgl_awal' : $("#tgl_pengajuan").val(),'tgl_akhir' : $("#tgl_pengajuan").val(),'jam_awal' : $("#jam_awal").val(),'jam_akhir' : $("#jam_akhir").val(),'p_karyawan_id' : $("#p_karyawan_id").val()},
				type : 'get',
				dataType : 'json',
				success : function(result){
						if(result.count==0){
						      var required = $('form#formlembur input,form#formlembur textarea,form#formlembur select').filter('[required]:visible');
                              var allRequired = true;
                                required.each(function(){
                                    if($(this).val() == ''){
                                        allRequired = false;
                                    }
                                });
                                
                                if(allRequired){
                                     $('#pesanSubmit').html('');
                                   
                			     document.getElementById('formlembur').submit();
                				$('form#formlembur').submit();
                                }else{
                                     
                                    $('#pesanSubmit').html('<div class="alert alert-danger" role="alert">silahkan isi form dengan benar, cek kembali form</div>');
                                    
                                    
                                }
						}else{
						    alert("Pengajuan tidak dapat dilanjutkan, karena terdapat pengajuan jam lembur yang sama");
						}
					
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
$("input.without_ampm").clockpicker({       
  placement: 'bottom',
  align: 'left',
  autoclose: true,
  default: 'now',
  donetext: "Select",
  init: function() { 
                            console.log("colorpicker initiated");
                        },
                        beforeShow: function() {
                            console.log("before show");
                        },
                        afterShow: function() {
                            console.log("after show");
                        },
                        beforeHide: function() {
                            console.log("before hide");
                        },
                        afterHide: function() {
                            console.log("after hide");
                        },
                        beforeHourSelect: function() {
                            console.log("before hour selected");
                        },
                        afterHourSelect: function() {
                            console.log("after hour selected");
                        },
                        beforeDone: function() {
                            console.log("before done");
                        },
                        afterDone: function() {
                            console.log("after done");
                        }
});
$(document).ready(function(){
	
function roundDown(number, decimals) {
    decimals = decimals || 0;
    return ( Math.floor( number * Math.pow(10, decimals) ) / Math.pow(10, decimals) );
}
  $("input.without_ampm").keyup(function(){

     change_jam();
  });$("input.istirahat").keyup(function(){
     
     change_jam();
  });
  $("input.without_ampm").change(function(){
     change_jam();
  });
  $("input.without_ampm").keypress(function(){
     change_jam();
  });
  $("input.without_ampm").blur(function(){
     change_jam();
  });
  $("input.istirahat").change(function(){
     change_jam();
  });
  $("input.istirahat").keypress(function(){
     change_jam();
  });
  $("input.istirahat").blur(function(){
     change_jam();
  });
  
  function countdate()
		{
		    
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
  function change_jam(){
      
     	$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "{!! route('fe.hitung_jam_lembur') !!}", 
				data : {'tgl_awal' : $("#tgl_awaldate").val(),'tgl_akhir' : $("#tgl_akhirdate").val(),'jam_awal':$('#jam_awal').val(),'jam_akhir':$('#jam_akhir').val(),'jam_istirahat_awal' : $("#jam_istirahat_awal").val(),'jam_istirahat_akhir' : $("#jam_istirahat_akhir").val()},
				type : 'get',
				dataType : 'html',
				success : function(result){
				$('#lama').val(result);

					
				}
				
			});
			
  }
});


</script>
 <!--
Mulai : <input type="text" id="waktuMulai" ><br>
Selesai : <input type="text" id="waktuSelesai"><br>
Selisih : <input type="text" id="selisih">-->
    <!-- /.content-wrapper -->
@endsection
