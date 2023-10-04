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
<h4 class="card-title float-left mb-0 mt-2">Edit Pengajuan </h4>

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
                <form class="form-horizontal" method="POST" action="{!! route('fe.approve_ajuan_2',$data[0]->t_form_exit_id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $data[0]->nik !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $data[0]->nama_lengkap !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" placeholder="Nama Jabatan ..." id="jabatan" name="jabatan" value="{!! $data[0]->jabatan !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Departemen</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->departemen !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y',strtotime($data[0]->create_date)) !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Jenis Cuti*</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->nama_ijin !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tanggal Awal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" value="{!! date('d-m-Y',strtotime($data[0]->tgl_awal)) !!}" readonly>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tanggal Akhir*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="{!! date('d-m-Y',strtotime($data[0]->tgl_akhir)) !!}" readonly>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Awal*</label>
                                <input type="time" class="form-control without_ampm" id="jam_awal" name="jam_awal" value="{!! $data[0]->jam_awal!!}" <?=$data[0]->tipe==2?'':'readonly';?>>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Akhir*</label>
                                <input type="time" class="form-control without_ampm" id="jam_akhir" name="jam_akhir" value="{!! $data[0]->jam_akhir!!}" <?=$data[0]->tipe==2?'':'readonly';?>>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label>Lama*</label>
                                <input type="text" class="form-control" placeholder="Lama..." id="lama" name="lama" value="{!! $data[0]->lama !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <label>File*</label>
                                @if($data[0]->foto!='' || $data[0]->foto!=null)
                                    <a href="{!! asset('dist/img/file/'.$data[0]->foto) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Alasan *</label>
                                <textarea class="form-control" placeholder="Alasan Cuti..." id="alasan" name="alasan" readonly>{!! $data[0]->keterangan !!}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Keterangan Atasan *</label>
                                <textarea class="form-control" placeholder="Keterangan..." id="keterangan_atasan" name="keterangan_atasan" readonly>{!! $data[0]->keterangan_atasan !!}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Status Pengajuan*</label>
                                 <input value="<?php if($data[0]->status_appr_1==1) echo 'Disetujui';else if($data[0]->status_appr_1==2) echo 'Ditolak';else if($data[0]->status_appr_1==3) echo 'Pending';?>" class="form-control " name="sts_pengajuan" style="width: 100%;" required title="Pilih" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Keterangan Atasan Layer 2 *</label>
                                <textarea class="form-control" placeholder="Keterangan..." id="keterangan_atasan" name="keterangan_atasan2" ></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Status Pengajuan  Layer 2*</label>
                                <select class="form-control " name="sts_pengajuan2" style="width: 100%;" required title="Pilih" >
                                    <option value="">Pilih </option>
                                    <option value="1">Disetujui</option>
                                    <option value="2">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.listed') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper --><style>
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
  <link rel='stylesheet' href='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css'><link rel="stylesheet" href="./style.css">

  <script src='https://code.jquery.com/jquery-1.11.0.min.js'></script>
<script src='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js'></script><script  src="./script.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	
<script src='https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js'></script><script  src="./script.js"></script> 
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
       	 
         minutes = waktuSelesai.split(':')[1] - waktuMulai.split(':')[1];
         
         if (waktuSelesai <= waktuMulai){ 
         		var hours = parseInt(24 - waktuMulai.split(':')[0]) +  parseInt(waktuSelesai.split(':')[0]);
			}else{
 				var hours = waktuSelesai.split(':')[0] - waktuMulai.split(':')[0];
				
			}
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
@endsection
