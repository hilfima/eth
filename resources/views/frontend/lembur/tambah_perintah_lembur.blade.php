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
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
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
<h4 class="card-title float-left mb-0 mt-2">Penjadwalan Shift Kerja</h4>

</div>
</div>
    <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Tambah Perintah Lembur</h4>

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
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_perintah_lembur') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                       
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                
										
                                <select type="text" class="form-control select2" placeholder="Nama ..." id="nama" name="nama[]" multiple="">
                                	<option value="">- Pilih Karyawan -</option>
                                	<?php foreach($karyawan as $karyawan){?>
                                	<option value="<?=$karyawan->p_karyawan_id;?>"><?=$karyawan->nama;?></option>
									<?php }?>
                                </select>
                                
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y') !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Tanggal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl" name="tgl" data-target="#tgl_awal" value="" required>
                                   
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Lama (Jam)*</label>
                                <input type="text" class="form-control masking0" id="lama" name="lama" value="" placeholder="Lama Lembur..." required readonly="">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Awal*</label>
                                <input type="text" class="form-control without_ampm masked" id="jam_awal" name="jam_awal" value="16:30"  placeholder="HH:II" >
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Akhir*</label>
                                <input type="text" class="form-control without_ampm masked" id="jam_akhir" name="jam_akhir" value="23:00"  placeholder="JJ:MM" >
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tipe Lembur*</label>
                                <select class="form-control select2" name="tipe_lembur" style="width: 100%;" required>
                                    <option value="">Pilih Lembur</option>
                                    <option value="Lembur Hari Kerja">Lembur Hari Kerja</option>
                                    <option value="Lembur Hari Libur">Lembur Hari Libur</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Atasan Layer 2*</label>
                                <select class="form-control select2" name="atasan2" style="width: 100%;" required>
                                    
                                    <?php
                                    
                                        echo '<option value="'.$kar[0]->p_karyawan_id.'">'.$kar[0]->nama.'</option>';
                                    
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.list_lembur') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
			
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
  <meta charset="UTF-8">
  <title>CodePen - Time Picker by jquery</title>
  <link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'><link rel="stylesheet" href="./style.css">

</head>

  <script src='https://code.jquery.com/jquery-1.11.0.min.js'></script>
<script src='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js'></script><script  src="./script.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	
<script src='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js'></script><script  src="./script.js"></script> <script>
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
	

  $("input.without_ampm").keyup(function(){
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
  function change_jam(){
	 var waktuMulai = $('#jam_awal').val(),
          waktuSelesai = $('#jam_akhir').val(),
       hours = waktuSelesai.split(':')[0] - waktuMulai.split(':')[0],
          minutes = waktuSelesai.split(':')[1] - waktuMulai.split(':')[1];
 
      if (waktuMulai <= "12:00:00" && waktuSelesai >= "13:00:00"){
        a = 1;
      }else {
        a = 0;
      }
      minutes = minutes.toString().length<2?'0'+minutes:minutes;
      if(minutes<0){ 
          hours--;
          minutes = 60 + minutes;        
      }
      hours = hours.toString().length<2?'0'+hours:hours;
 		lama = hours-a;
 		if(minutes>35){
			lama +=1;
		}
      $('#lama').val(hours-a);
  }
});
</script>
 <!--
Mulai : <input type="text" id="waktuMulai" ><br>
Selesai : <input type="text" id="waktuSelesai"><br>
Selisih : <input type="text" id="selisih">-->
    <!-- /.content-wrapper -->
@endsection
