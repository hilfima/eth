@extends('layouts.appsA')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Tambah Cuti </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Tambah Cuti</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_cuti') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Karyawan</label>
                                <select type="text" class="form-control select2"  id="judul" name="karyawan"required>
                                <option value="">- Pilih  Karyawan -</option>
                                <?php
                                $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 order by p_karyawan.nama";
            $karyawan=DB::connection()->select($sqlusers);
                                 foreach($karyawan as $karyawan){?>
                                <option value="<?=$karyawan->p_karyawan_id;?>"><?=$karyawan->nama;?> (<?=$karyawan->nmdept;?>)</option>
								<?php }?>
                               
								
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tipe</label>
                                <select type="text" class="form-control"  id="judul" name="tipe" onclick="changetipe(this)" required>
                                <option value="">- Pilih  Tipe -</option>
                                <option value="1">Penambahan Cuti Tahunan </option>
                                <option value="8">Rekap Sinkronisasi </option>
                                <option value="3">Penambahan Cuti Besar </option>
                                <option value="4">Reset Cuti Besar </option>
                               
								
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tahun Cuti</label>
                                <select type="text" class="form-control"  id="tahun" name="tahun" required>
                                <option value="">- Pilih terlebih dahulu tipe -</option>
                                <?php 
                                
                                for($i=1;$i<=(10);$i++){
                                	
										
                                	?>
                                <option value="<?=$i;?>">Cuti Besar ke <?=$i;?></option>
								<?php } for($i=date('Y');$i>=(2010);$i--){
                                	
										
                                	?>
                                <option value="<?=$i;?>">Cuti Tahunan <?=$i;?></option>
								<?php }?>
								
                                </select>
                            </div>
                       
                            <div class="form-group" id="Total">
                                <label>Total </label>
                                <input class="form-control " name="total" type="text" style="width: 100%;" required>
                                    
                            </div><div class="form-group" id="Total">
                                <label>Tanggal </label>
                                <input class="form-control " name="tanggal" type="date" style="width: 100%;" required>
                                    
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.parameter_cuti') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div><script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!--tgl_akhir-->
<script>
		
		function changetipe(key)
		{
			var tipe = $(key).val();
			$('#tahun').html();
			var text ='<option value="">-Pilih Tahun Cuti-</option>';;
			const d = new Date();
			let year = d.getFullYear();
			if(tipe == '1' || tipe == '2' || tipe == '5'  || tipe == '8' ){
				for (let i = year; i > 2008; i--) {
				  text += "<option value='"+i+"'>Cuti Tahunan "+ i+ "<br>";
				} 
			}else{
				for (let i = 1; i <= 10; i++) {
				  text += "<option value='"+i+"'>Cuti Besar ke-"+ i+ "<br>";
				} 
			}
			$('#tahun').html(text);
			
	
	 	}
	 	
    			
    </script>
    <!-- /.content-wrapper -->
@endsection
