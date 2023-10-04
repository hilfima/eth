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
                        <h1 class="m-0 text-dark"><?=ucwords('File Pajak');?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active"><?=ucwords('File Pajak');?></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <form class="form-horizontal" method="POST" action="{!! route('be.'.$type,$id) !!}" enctype="multipart/form-data">
            
            <div class="card-header">
                <h3 class="card-title mb-0	"><?=ucwords('File Pajak');?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                     
                            	
                            <div class="col-md-12">
                            <div class="form-group">
                                <label>Tahun Pajak</label>
                               <input type="number" class="form-control " name="periode" style="width: 100%;" required>
								
                            </div>
                        </div>
                        
 						<div class="col-md-8">
                            <div class="form-group">
                                <label>Entitas</label>
                               <select class="form-control select2" name="entitas" style="width: 100%;" required onchange="entitas_karyawan(this)">
								<option value="">Pilih Entitas</option>
								<?php
								foreach($entitas AS $entitas){
									
										echo '<option value="'.$entitas->m_lokasi_id.'">'.$entitas->nama.' </option>';
									
								}
								?>
							</select>
                            </div>
                            </div>  
                            <?php for($i=0;$i<21;$i++){?>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label>Karyawan</label>
                             <div class="karyawan-select">
                             	
                               <select class="form-control select2 karyawan" name="karyawan[]" style="width: 100%;" >
								<option value="">Pilih Karyawan</option>
								<?php
								
								foreach($karyawan AS $karyawan2){
									if($karyawan2->p_karyawan_id==$data['p_karyawan_id']){
										echo '<option selected="selected" value="'.$karyawan2->p_karyawan_id.'">'.$karyawan2->nama.'</option>';
									}
									else{
										echo '<option value="'.$karyawan2->p_karyawan_id.'">'.$karyawan2->nama.' </option>';
									}
								}
								?>
							</select>
                             </div>
                            </div>
                            </div>   
                            <div class="col-md-6">                        
                             <div class="form-group">
                                <label>File</label>
                               <input class="form-control" type="file" name="bukti<?=$i;?>" style="width: 100%;" >
								
							
                            </div>
                        </div>
                        <?php }?>
                       
                        
                        
                        
                           
                    </div>
                 
                        <a href="{!! route('be.filepajak') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
                  <br>
                  <br>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <script>
    	function entitas_karyawan(e){
    		var entitas = $(e).val();
    		
    		
		//alert();
		
	
		$.ajax({
				type: 'get',
				data: {'entitas': entitas},
				url: '<?=route('be.karyawan_entitas');?>',
				dataType: 'html',
				success: function(data){
					$('.karyawan-select').html(data);
					$('.select3').select2()
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
		
	
	
    	}
    </script>
    <!-- /.content-wrapper -->
@endsection
