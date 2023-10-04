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
                        <h1 class="m-0 text-dark">Generate,Preview, Edit, dan Approve Gaji</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Generate,Preview, Edit, dan Approve Gaji</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
                <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.previewgaji',$data['page']) !!}">
          <?php 
            echo  view('backend.gaji.preview.filter',compact('data','entitas','user','id_prl','help','periode','periode_absen','request','list_karyawan'));
            ?>
                </form>
                </div>
        <!-- Main content -->
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
				<div class="request-btn">
					<!--<div class="dash-stats-list">
						<p>Status Approval</p>
						<?php 
						//print_r($generate);
						if($generate->appr_status==0){
							echo '<h4>Belum Terdapat Approval</h4>';
							 foreach($periode AS $periode){
                                        if($periode->prl_generate_id==$id_prl){
                                            echo $periodegen = 'Pada Periode Generate : '.$periode->tahun_gener.' Bulan: '.$periode->bulan_gener.' | Absen:'.$periode->tgl_awal.' - '.$periode->tgl_akhir.' | Lembur:'.$periode->tgl_awal_lembur.' - '.$periode->tgl_akhir_lembur.'';
                                        }	
                                        }
                                        $visible = true;	
							}else if($generate->appr_status==1){
							echo '<h4>Data Gaji Disetujui</h4>';	
							echo '<div>Oleh : '.($generate->appr_nama).'</div>';	
							echo '<div>Pada Tanggal: '.$help->tgl_indo($generate->appr_date).'</div>';	
							echo '<div>Approval Terkunci Pada Tanggal: '.$help->tgl_indo($generate->appr_date_lock).'</div>';	
							echo '<div>Keterangan; '.$generate->appr_keterangan.'</div>';	
                                        if(date('Y-m-d')<=$generate->appr_date_lock )$visible = true;
                                        else $visible = false;
							}else if($generate->appr_status==2){
                                        $visible = true;	
							echo '<h4>Data Gaji Ditolak</h4>';	
							echo '<div>Oleh : '.($generate->appr_nama).'</div>';	
							echo '<div>Pada Tanggal: '.$help->tgl_indo($generate->appr_date).'</div>';	
							echo '<div>Keterangan; '.$generate->appr_keterangan.'</div>';	
							}
							?>
						</div>-->
					</div>
				</div>
        </div>
            
           
             <!--<div class="card">
            <div class="card-header">
                <h3 class="card-title">Konfirmasi Selesai Transfer Gaji</h3>
            </div>
           
            <div class="card-body">
            	
                
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_konfirm_gaji_hr') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                    
                        <div class="col-sm-12">
                            
                            
                            <div class="form-group">
                                <label>Entitas </label>
                                <select class="form-control select2" id="entitas" name="entitas" required>
                                	<option value="">Pilih Status</option>
                                	<?php
                                    foreach($entitas AS $entitas){
                                   	// if($sudah_appr[$entitas->m_lokasi_id]['ON']){
									 	
                                    ?>
                                    
                                	<option value="<?=$entitas->m_lokasi_id;?>-on"><?=$entitas->nama;?> ON</option>
                                	<?php //} if($sudah_appr[$entitas->m_lokasi_id]['OFF']){?>
                                	<option value="<?=$entitas->m_lokasi_id;?>-off"><?=$entitas->nama;?> OFF</option>
                                	<?php //}?>
                                	<?php }?>
                                </select>
                                
                                <input  type="hidden" name="id_prl" value="<?=$id_prl;?>"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                           
                            <div class="form-group">
                                <label>Status Approval </label>
                                <select class="form-control" id="status" name="status" required>
                                	<option value="1">Selesai</option>
                                	<option value="2">Belum</option>
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea id="deskripsi_berita" class="form-control " name="keterangan" ></textarea>
                            </div>
                        </div>
                       
                    </div>
                   
                    <div class="box-footer">
                        <a href="{!! route('be.previewgaji',$data['page']) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    
                </form>
            </div>
           
        </div>
-->
		<!--<div class="card">
            <div class="card-header">
                <h3 class="card-title">Approval Absensi dan Data Gaji</h3>
            </div>
            
            <div class="card-body">
            	
               
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_appr_gaji') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                           
                            <div class="form-group">
                                <label>Status Approval </label>
                                <select class="form-control" id="status" name="status" required>
                                	<option value="">Pilih Status</option>
                                	<option value="1">Setuju</option>
                                	<option value="2">Ditolak</option>
                                </select>
                                <input  type="hidden" name="id_prl" value="<?=$id_prl;?>"/>
                            </div>
                        </div>
                       
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea id="deskripsi_berita" class="form-control " name="keterangan" required></textarea>
                            </div>
                        </div>
                    </div>
                   
                    <div class="box-footer">
                        <a href="{!! route('be.berita') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                 
                </form>
            </div>
          
        </div>-->
        	
        	<h3>Histori Ajuan HR</h3>
        	
        	@foreach($appr as $appr)
        		<div class="card">
            <!-- /.card-header -->
            	<div class="card-header">
            	Approval <?=$help->tgl_indo($appr->appr_date);?>
	            </div>
            	<div class="card-body">
            	<?php 
							echo '<div>Entitas : '.($appr->nmlokasi).' '.strtoupper($appr->pajak).'</div>';	
            	 if($appr->appr_status==1){
							echo '<h4>Data Gaji Diajukan</h4>';	
							echo '<div>Oleh : '.($appr->appr_nama).'</div>';	
							echo '<div>Pada Tanggal: '.$help->tgl_indo($appr->appr_date).'</div>';	
							echo '<div>Keterangan; '.$appr->appr_keterangan.'</div>';	
							}else if($appr->appr_status==2){
                                        $visible = true;	
							echo '<h4>Data Gaji Belum Selesai</h4>';	
							echo '<div>Oleh : '.($appr->appr_nama).'</div>';	
							echo '<div>Pada Tanggal: '.$help->tgl_indo($appr->appr_date).'</div>';	
							echo '<div>Keterangan; '.$appr->appr_keterangan.'</div>';	
							}?>
	            </div>
	        </div>
            
        	@endforeach
        	
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
