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
                        <h1 class="m-0 text-dark">Karyawan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Karyawan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
			<form class="form-horizontal" method="get" action="{!! route('be.file_karyawan') !!}" enctype="multipart/form-data">
             
           
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                            
                       <div class="col-sm-12">
                        	<div class="form-group">
                                <label>Data File Karyawan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <Select type="file" class="form-control" id="gapok" name="tipe"     >
                                    <option value=""> Pilih Data File Karaywan </option>
                                    <option value="bpjs_karyawan"> BPJS  </option>
                                    <option value="ktp"> KTP </option>
                                    <option value="kk"> KK</option>
                                    </select>
                                    
                                </div>
                            </div> 
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Entitas</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <Select class="form-control select2" id="gapok" name="lokasi"     >
                                    <option value=""> Semua Entitas </option>
                                    <?php foreach($lokasi as $lokasi){ ?>
                                     	<option value="<?=$lokasi->m_lokasi_id?>" <?=$request->lokasi==$lokasi->m_lokasi_id?'selected':'';;?>> <?=$lokasi->nama?></option>
                                    <?php }?>
                                    </select>
                                    
                                </div>
                            </div> 
                        </div>
                       
                    </div>
                        <button type="submit" class="btn btn-info pull-right"> Cari</button>
                  </div>
                 
                     
                        <br>
                        <br>
                        <br>
                    </div>
                    <!-- /.box-footer -->
                </form>
                <?php if($request->tipe){?>
           	<div class="card">
           	<div class="card-body">
           		<table id="example1" class="table table-striped custom-table mb-0">
					<tbody>
						<th>No</th>
						<th>Nama</th>
						<th>File</th>
						<th>Action</th>
					</tbody>           			
           	<?php 
           	$no=0;
           	$row ='file_'.$request->tipe;
           	foreach($karyawan as $karyawan){
           		if($karyawan->$row){
           		$no++;
           		?>
           	<tr>
           		<td><?=$no;?></td> 
           		<td><?= $karyawan->nama?></td>
           		<td>
           		<a href="{!! asset('dist/img/file/'.$karyawan->$row) !!}" target="_blank">
								<?php
									$info = pathinfo($karyawan->$row);
									if(isset($info["extension"])){
									if ($info["extension"] == "jpg" or $info["extension"] == "png" or $info["extension"] == "jpeg" ) {
								?>
								<img src="{!! asset('dist/img/file/'.$karyawan->$row) !!}"  class="" height="40px">
<?php }else{?>
								<i class="fa fa-download"></i>
								<?php }?><?php }?></a></td>
           		<td><a href="{!!route('be.hapus_file_karyawan',[$request->tipe,$karyawan->p_karyawan_kartu_id]) !!}"><i class="fa fa-trash"></i></a></td>
           	</tr>
           	<?php }?>
           	<?php }?>
           		</table>
                <?php }?>
           	</div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
