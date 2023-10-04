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
                        <h1 class="m-0 text-dark">Cuti Parameter</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Cuti Parameter</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_parameter_reset_cuti') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Reset Tahun Cuti</label>
                                <select type="text" class="form-control"  id="judul" name="thn" required>
                                <option value="">- Pilih Reset Tahun Cuti -</option>
                                <?php 
                                $sql ="select * from m_parameter_cuti where active= 1";
    							$cuti=DB::connection()->select($sql);
    							$tahun =array();
    							foreach($cuti as $c){
									$tahun[] = $c->ditahun;
								}
                                for($i=date('Y');$i>=(2010);$i--){
                                	if(!in_array($i,$tahun)){
										
                                	?>
                                <option value="<?=$i;?>"><?=$i;?></option>
								<?php }?>
								<?php }?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input class="form-control " name="tgl" type="date" style="width: 100%;" required>
                                    
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
    </div>
    <!-- /.content-wrapper -->
@endsection
