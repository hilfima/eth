
@extends('layouts.app_fe')



@section('content')


	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<style>
    .trr {
        background-color: #0099FF;
        color: #ffffff;
        align: center;
        padding: 10px;
        height: 20px;
    }

    tr.odd>td {
        background-color: #E3F2FD;
    }

    tr.even>td {
        background-color: #BBDEFB;
    }

    .fixedTable .table {
        background-color: white;
        width: auto;
        display: table;
    }

    .fixedTable .table tr td,
    .fixedTable .table tr th {
        min-width: 100px;
        width: 100px;
        min-height: 20px;
        height: 20px;
        padding: 5px;
        max-width: 100px;
    }

    .fixedTable-header {
        width: 100%;
        height: 60px;
        /*margin-left: 150px;*/
        overflow: hidden;
        border-bottom: 1px solid #CCC;
    }

    .fixedTable-sidebar {
        width: 0;
        height: 510px;
        float: left;
        overflow: hidden;
        border-right: 1px solid #CCC;
    }

    @media screen and (max-height: 700px) {
        .fixedTable-body {
            overflow: auto;
            width: 100%;
            height: 410px;
            float: left;
        }
    }

    @media screen and (min-height: 700px) {
        .fixedTable-body {
            overflow: auto;
            width: 100%;
            height: 510px;
            float: left;
        }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('flash-message')
    
    <!-- Content Header (Page header) -->
            <form action="<?= route('fe.lihat_slip'); ?>" method="get" id="slipGAJI" enctype="multipart/form-data">
    @csrf
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Periode Tahun</label>
                    <select class="form-control " name="tahun" style="width: 100%;" required>
                    	<option value=""> - Periode Tahun -</option>
                    	<?php for($i=2022;$i<=date('Y');$i++){
                    				$selected = '';
                    			if($request->get('tahun')==$i){
                    				$selected = 'selected';
                    			}
                    			echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                    	}?>	
                    </select>
                </div>
                </div>
                <div class="col-sm-3">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select class="form-control select2" name="bulan" style="width: 100%;" required id="bulan"	>
                                    <option value="">Pilih</option>
                                    <option value="1" <?=$request->bulan==1?'selected':'';?>>Januari</option>
                                    <option value="2" <?=$request->bulan==2?'selected':'';?>>Februari</option>
                                    <option value="3" <?=$request->bulan==3?'selected':'';?>>Maret</option>
                                    <option value="4" <?=$request->bulan==4?'selected':'';?>>April</option>
                                    <option value="5" <?=$request->bulan==5?'selected':'';?>>Mei</option>
                                    <option value="6" <?=$request->bulan==6?'selected':'';?>>Juni</option>
                                    <option value="7" <?=$request->bulan==7?'selected':'';?>>Juli</option>
                                    <option value="8" <?=$request->bulan==8?'selected':'';?>>Agustus</option>
                                    <option value="9" <?=$request->bulan==9?'selected':'';?>>September</option>
                                    <option value="10" <?=$request->bulan==10?'selected':'';?>>Oktober</option>
                                    <option value="11" <?=$request->bulan==11?'selected':'';?>>November</option>
                                    <option value="12" <?=$request->bulan==12?'selected':'';?>>Desember</option>
                                </select>
                            </div>
                        </div> 
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Periode Gajian</label>
                                <select class="form-control " name="periode_gajian" id="periode_gajian" style="width: 100%;" required onclick="pekanan()"	>
                                    <option value="">- Pekanan -</option>
                                    <option value="0" <?=$idkar[0]->periode_gajian==0?'selected':'';?>>Pekanan</option>
                                    <option value="1" <?=$idkar[0]->periode_gajian==1?'selected':'';?>>Bulanan</option>
                                </select>
                               
                            </div>
                        </div>
                       
                        <div class="col-sm-3 " id="pekanan" style="display: none">
                            <div class="form-group">
                                <label>Pekanan Ke</label>
                                <select class="form-control " name="pekanan_ke" id="pekanan_ke" style="width: 100%;" required 	>
                                    <option value="">- Pekanan -</option>
                                    <option value="1" <?=$request->pekanan_ke==1?'selected':'';?>>Pekanan Ke 1</option>
                                    <option value="2" <?=$request->pekanan_ke==2?'selected':'';?>>Pekanan Ke 2</option>
                                </select>
                            </div>
                        </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
                    <script>
                    	pekanan();
                    	function pekanan(){
                    		
                    		var periode_gajian = $("#periode_gajian").val();
                    		if(periode_gajian == 0){
                    			$('#pekanan').show();
                    		}else{
                    			$('#pekanan').hide();
                    			
                    		}
                    	}
                    	
                    	
                    	
                    </script>
                </div>
                
                <div class="form-group">
                    <button type="button" onclick="pop_up()" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>

                </div>
        </div>
    </div>
    @if($id_prl==-99)
	<div class="card">
	<div class="card-body text-center">
		<h2>Data gaji belum ada.</h2>
	</div>
	</div>
    @elseif(!empty($generate))
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Slip Gaji</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

                <li class="nav-item pl-3">
                    <a href="{!! route('fe.tambah_chat',['','key=Klarifikasi Gaji Bulan '.$help->bulan($generate[0]->bulan).' '.($generate[0]->tahun)]) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Klarifikasi Gaji</a>
                    <button type="button" onclick="pdf()" name="Cari"  name="Cari" class="btn  btn-theme button-1 "value="PDF"><span class="fa fa-search"></span> PDF</button>
                    <!-- 
				<a href="{!! route('fe.lihat_slip',[$id_prl,'Cari=PDF']) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">PDF</a>-->
                </li>
            </ul>

        </div>
    </div>
    
    

    <div class="content container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <?php
                    $view = 'slip';
                    ?>
                    <?= view('frontend.slip.pdf_slip',compact('karyawan','help','generate','id_prl','id_kary','view'));?>
            </div>-->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    
    @endif
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js">
    	<script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
    
    <textarea id="texttt" style="display: none">
    	Informasi yang ada di Slip Gaji ini bersifat RAHASIA..
    	<br>
    	<br>
    	Karyawan tidak diperkenanankan untuk menyebarluaskan informasi gaji karyawan kepada pihak lain tanpa persetujuan perusahaan.  
    	<br>
    	<br>
    	<br>
    	
    	
    	Hal ini sesuai dengan Ketentuan Perusahaan, karyawan yang melanggar dapat dikenakan sanksi sesuai Ketentuan Perusahaan.
    </textarea>
    <script>
        function pdf(){
        	
		$('#slipGAJI').append('<div><input type="hidden" name="Cari" value="PDF"/></div>');
		document.getElementById('slipGAJI').submit();
		}
        function pop_up(){
        	var txt =  "";
        	swal({
                        title: 'PERINGATAN!!!',
                        html:$("#texttt").val(),
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Mengerti !',
                        cancelButtonText: ' Batalkan!',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        allowEnterKey: true,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                           
                            document.getElementById('slipGAJI').submit();
                        } else if (
                            // Read more about handling dismissals
                            result.dismiss === swal.DismissReason.cancel
                        ) {
                            swal(
                                'Cancelled',
                                'Anda Batal Melihat Slip Gaji.. :)',
                                'error'
                            )
                        }
                    })
        }
    </script>
    <!-- /.content-wrapper -->
    @endsection