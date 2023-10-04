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
                        <h1 class="m-0 text-dark">Cek Absen</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Cek Absen</li>
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
                <!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
                <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.cek_cari_absen_hr') !!}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;" required>
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($users AS $users){
                                        if($users->p_karyawan_id==$nama){
                                            echo '<option selected="selected" value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                        else{
                                            echo '<option value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_awal" name="tgl_awal" value="{!! $tgl_awal !!}" data-target="#tgl_awal" />
                                    <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_akhir" name="tgl_akhir" value="{!! $tgl_akhir !!}" data-target="#tgl_akhir" />
                                    <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! route('be.cek_absen') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Excel"><span class="fa fa-file-excel"></span> Excel</button>

                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Absen </th>
                        <th>Kantor </th>
                        <th>Tgl. Absen</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($rekap))
                       <?php $date = $tgl_awal;
                       for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
								
                       ?>
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $list_karyawan->pin !!}</td>
                                <td><?=   isset( $rekap[$list_karyawan->p_karyawan_id][$date]['a']['mesin_id'])? $mesin[$rekap[$list_karyawan->p_karyawan_id][$date]['a']['mesin_id']]:''; ?></td>
                                <td>{!!$date !!}</td>
                                <td><?= isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'])?$rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk']:'';?></td>
                                <td> <?php if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'])){  echo ''.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'].'';	
										}?> </td>
                                <td><?php 
                                
                                if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['terlambat'])){
                                if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['terlambat'] ) echo 'TERLAMBAT';else echo 'OK';
							}if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
									echo $rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'];
								}
                                ?> 
                                
                                </td>
                                <td>
                                	<?php
                                	$status = '';
                                	if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_masuk'])){
											if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_masuk']==3){
                                				$info= 'dirubah Manual';
                                				
											}else if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_masuk']==4){												
                                				$info= 'diinput Manual';
											}else{
                                				$info= 'sesuai Mesin';
												
											}
											$status .= 'Data Masuk '.$info;
											if($info!= 'sesuai Mesin'){
												
												$status .= '<br><b>Oleh</b> '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['updated_by_masuk'];
												$status .= '<br><b>tgl</b> '.(($rekap[$list_karyawan->p_karyawan_id][$date]['a']['updated_at_masuk']));
												$status .= '<br><b>data awal</b> :  '.(($rekap[$list_karyawan->p_karyawan_id][$date]['a']['time_before_update_masuk'])).'<br>';
											}
									}
									if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_keluar'])){
										if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_keluar']==3){
                                				$info= 'dirubah Manual';
                                				
											}else if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_keluar']==4){												
                                				$info= 'diinput Manual';
											}else{
                                				$info= 'sesuai Mesin';
												
											}
											$status .= '<br>Data Keluar '.$info;
											if($info!= 'sesuai Mesin'){
												
												$status .= 'Oleh '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['updated_by_keluar'];
												$status .= 'tgl '.(($rekap[$list_karyawan->p_karyawan_id][$date]['a']['updated_at_keluar']));
												$status .= 'data awal :  '.(($rekap[$list_karyawan->p_karyawan_id][$date]['a']['time_before_update_keluar']));
									}
									
									}
									echo $status;
                                	?>
                                	
                                	
                                	
                                </td>
                                <td> 
                                <?php if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['absen_log_id_masuk'])){?>
									
								<strong>MASUK</strong><br>								
                                <a href="{!! route('be.edit_cari_absen_hr',$rekap[$list_karyawan->p_karyawan_id][$date]['a']['absen_log_id_masuk']) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_cari_absen_hr',$rekap[$list_karyawan->p_karyawan_id][$date]['a']['absen_log_id_masuk']) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                    <?php }?>
                                    
                                    <?php if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['absen_log_id_keluar'])){?>
									
								<br><strong>KELUAR</strong>	<br>							
                                <a href="{!! route('be.edit_cari_absen_hr',$rekap[$list_karyawan->p_karyawan_id][$date]['a']['absen_log_id_keluar']) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_cari_absen_hr',$rekap[$list_karyawan->p_karyawan_id][$date]['a']['absen_log_id_keluar']) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                    <?php }?>
                                    </td>
                            </tr>
                       <?php 
                       $date = $help->tambah_tanggal($date,1);
                       }?>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
