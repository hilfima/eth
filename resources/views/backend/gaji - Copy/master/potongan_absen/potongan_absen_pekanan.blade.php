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
                        <h1 class="m-0 text-dark">Master Gaji</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Master  Potongan Absen Pekanan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <form class="form-horizontal" method="POST" action="{!! route('be.simpan_master_potongan_absen_pekanan') !!}" enctype="multipart/form-data">
             {{ csrf_field() }}
                    
            <!-- /.card-header -->
            <div class="card-body">
            	<?php 
            	$sql = "SELECT *,
            	(select nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='absen') as nominal_absen,
            	(select nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='izin') as nominal_izin,
            	(select nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='alpha') as nominal_alpha,
            	(select nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='fingerprint') as nominal_fingerprint,
            	(select nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='pm') as nominal_pm,
            	(select type_nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='absen') as type_nominal_absen,
            	(select type_nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='izin') as type_nominal_izin,
            	(select type_nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='alpha') as type_nominal_alpha,
            	(select type_nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='fingerprint') as type_nominal_fingerprint,
            	(select type_nominal from m_potongan_absen_pekanan a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='pm') as type_nominal_pm
            	
            	FROM m_pangkat where active=1";
    			$pangkat=DB::connection()->select($sql);
            	foreach($pangkat as $pangkat){
					
            	?>
            	<h3><?=$pangkat->nama?></h3>
            	<div class="row">
            		
                <div class="col-4">
	                <div class="form-group">
	                     <label>Type Potongan Absen</label>
	                     <select type="text" class="form-control"  name="type_absen[<?=$pangkat->m_pangkat_id?>]" value="">
	                     	<option value="1" <?=$pangkat->type_nominal_absen==1?'selected':'';?>>Nominal</option>
	                     	<option value="2" <?=$pangkat->type_nominal_absen==2?'selected':'';?>>Persen</option>
	                     	<option value="3" <?=$pangkat->type_nominal_absen==3?'selected':'';?>>Prorata</option>
	                     </select>
	                </div>
                </div>
                <div class="col-8">
                <div class="form-group">
                     <label>Potongan Absen</label>
                     <input type="text" class="form-control"  name="absen[<?=$pangkat->m_pangkat_id?>]" value="<?=$pangkat->nominal_absen;?>">
                </div>
                </div>
                
                <div class="col-4">
	                <div class="form-group">
	                     <label>Type Potongan Fingerprint</label>
	                     <select type="text" class="form-control"  name="type_fingerprint[<?=$pangkat->m_pangkat_id?>]" value="">
	                     	<option value="1" <?=$pangkat->type_nominal_fingerprint==1?'selected':'';?>>Nominal</option>
	                     	<option value="2" <?=$pangkat->type_nominal_fingerprint==2?'selected':'';?>>Persen</option>
	                     	<option value="3" <?=$pangkat->type_nominal_fingerprint==3?'selected':'';?>>Prorata</option>
	                     </select>
	                </div>
                </div>
                <div class="col-8">
                <div class="form-group">
                     <label>Potongan Fingerprint</label>
                     <input type="text" class="form-control" name="fingerprint[<?=$pangkat->m_pangkat_id?>]" value="<?=$pangkat->nominal_fingerprint;?>">
                </div> 
                </div> <div class="col-4">
	                <div class="form-group">
	                     <label>Type Potongan PM</label>
	                     <select type="text" class="form-control"  name="type_pm[<?=$pangkat->m_pangkat_id?>]" value="">
	                     	<option value="1" <?=$pangkat->type_nominal_pm==1?'selected':'';?>>Nominal</option>
	                     	<option value="2" <?=$pangkat->type_nominal_pm==2?'selected':'';?>>Persen</option>
	                     	<option value="3" <?=$pangkat->type_nominal_pm==3?'selected':'';?>>Prorata</option>
	                     </select>
	                </div>
                </div><div class="col-8">
                <div class="form-group">
                     <label>Potongan PM</label>
                     <input type="text" class="form-control" name="pm[<?=$pangkat->m_pangkat_id?>]" value="<?=$pangkat->nominal_pm;?>">
                </div> 
                </div> 
                
            	</div>
                <?php }?>
                <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
