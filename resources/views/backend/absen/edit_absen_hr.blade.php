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
                        <h1 class="m-0 text-dark">Input Absen Hr</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Input Absen Hr</li>
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
                <?php if($user[0]->p_karyawan_id==174 or $user[0]->p_karyawan_id==269 or $user[0]->role==-1 or $user[0]->p_karyawan_id==22){?>
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_cek_absen_hr',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                    <input type="text" class="form-control " id="" name="" data-target="#tgl_absen" value="<?=$info->nama;?>" disabled=""/>
                                 
                               
                           
                               
                            
                        </div>
                            </div>
                         <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control"  name="tgl_absen" data-target="#tgl_absen" value="{!! date('Y-m-d',strtotime($info->date_time)) !!}"   readonly=""  />
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Mesin</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <select class="form-control " name="mesin" style="width: 100%;" id="nama"  placeholder="Pilih Karyawan" required>
                                    <option value="" disabled="">Pilih Mesin</option>
                                    <?php
                                    foreach($dmesin AS $mesins){
                                    	$selected = $mesins->mesin_id==$info->mesin_id ?'selected':'';
                                        echo '<option value="'.$mesins->mesin_id.'" '.$selected.'>'.$mesins->nama.'</option>';
                                    }
                                    ?>
                                </select>
                                    </div>
                                </div>
                            </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Jam Masuk</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="time" class="form-control " id="jam_masuk" name="jam_masuk" data-target="#tgl_absen" value="{!!date('H:i:s',strtotime($info->date_time))!!}" required=""/>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('admin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
                 <?php 
								}else{
									
                            ?>
                            <h1>Maaf, Menu ini diakses hanya untuk Hr..</h1>
                              <a href="{!! route('admin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                            <?php }?>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <script  >	
	 	
    </script> 
    <!-- /.content-wrapper -->
@endsection
