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
                        <h1 class="m-0 text-dark">Gaji Pokok</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Gaji Pokok</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <form class="form-horizontal" method="POST" action="{!! route('be.simpan_gapok',$type) !!}" enctype="multipart/form-data">
            
            <div class="card-header">
                <h3 class="card-title">Tambah</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            
                        <div class="form-group">
                                <label>Nama</label>
                                 <?php 
                            
                            	
									$sqlkaryawans="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap
FROM p_karyawan a
                    					WHERE 1=1 and a.active=1 and a.p_karyawan_id not in(select p_karyawan_id from p_karyawan_gapok where active=1)order by a.nama";
        								$karyawans=DB::connection()->select($sqlkaryawans);?>
								 <select class="form-control select2" name="karyawan" style="width: 100%;" placeholder="Pilih Karyawan" required>
                                    <option value="" >Pilih Karyawan</option>
                                    <?php
                                    foreach($karyawans AS $karyawans){
                                        echo '<option value="'.$karyawans->p_karyawan_id.'">'.$karyawans->nama_lengkap.'</option>';
                                    }
                                    ?>
                                </select>
                               
                           
                               
                            
                        </div>
                        </div>
                             
                             
                        <div class="col-sm-6">
                          <h3>Tunjangan</h3> 
                        	
                        	<?php  for($y=0;$y<count($array);$y++){
                        		if($array[$y][2]=='tunjangan'){
                        		?>
	       	 	
                        	
                        	<div class="form-group">
                                <label><?=$array[$y][0]?></label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="<?=$array[$y][1]?>" name="<?=$array[$y][1]?>"  value="Rp "    onkeypress="handleNumber(event, 'Rp {15,3}')"   placeholder="<?=$array[$y][0]?>"/>
                                    
                                </div>
                            </div> 
	     <?php   }?>
	     <?php   }?>
                        
                        	
                        </div><div class="col-sm-6">
                        <h3>Potongan</h3> 
                        	<?php  for($y=0;$y<count($array);$y++){
                        		if($array[$y][2]=='potongan'){
                        		?>
	       	 	
                        	
                        	<div class="form-group">
                                <label><?=$array[$y][0]?></label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <input type="text" class="form-control" id="<?=$array[$y][1]?>" name="<?=$array[$y][1]?>"  value="Rp "    onkeypress="handleNumber(event, 'Rp {15,3}')"   placeholder="<?=$array[$y][0]?>"/>
                                    
                                </div>
                            </div> 
				     <?php   }?>
				     <?php   }?>
                        </div> 
                        
                        
                      
                        
                        
                        
                           
                    </div>
                  
                        <a href="{!! route('be.gapok',$type) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Ubah</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
