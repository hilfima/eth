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
                        <h1 class="m-0 text-dark">Approval {!!ucwords($type)!!}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('be.list_ajuan',$type) !!}">List Approval HR  {!!ucwords($type)!!}</a></li>
                            <li class="breadcrumb-item active">Detail Approval  {!!ucwords($type)!!}</li>
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
                <form class="form-horizontal" method="POST" action="<?=route('be.simpan_hr_appr',$type);?>" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input  type="hidden" name="idform" value="<?=$kode;?>"/>
                    <div class="row">
                        <div class="col-sm-1">
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $data[0]->nik !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $data[0]->nama_lengkap !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" placeholder="Nama Jabatan ..." id="jabatan" name="jabatan" value="{!! $data[0]->jabatan !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Departemen</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->departemen !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y',strtotime($data[0]->create_date)) !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Jenis Pengajuan*</label>
                                 <select class="form-control select2" name="pengajuan" style="width: 100%;" required>
                                    <?php 
                                    //if($data[0]->m_jenis_ijin_id==20){
                                    	?>
                                    <option value="">Pilih Pengajuan</option>
                                    <?php
                                    	
		                                    foreach($pengajuan AS $pengajuan){
		                                    		$select = '';
		                                    		$disable = '';
		                                    		$cuti = '';
													
		                                    	if($data[0]->m_jenis_ijin_id == $pengajuan->m_jenis_ijin_id)
		                                    		$select = 'selected';
		                                    	if($tolcut==0 and  $pengajuan->m_jenis_ijin_id==5){
													
		                                    		$disable = 'disable';
												}else if($pengajuan->m_jenis_ijin_id==5){
													$cuti= '(Total Sisa Cuti : '.$tolcut.')';
												}
		                                        echo '<option value="'.$pengajuan->m_jenis_ijin_id.'" '.$select.' '.$disable.'>'.$pengajuan->nama.' ('.$pengajuan->satuan.' Hari) '.$cuti.'</option>';
											}
		                                /*}else{
		                                	if($data[0]->m_jenis_ijin_id==21)
		                                		$ijin= 'IZIN DATANG TERLAMBAT';
		                                	else if($data[0]->m_jenis_ijin_id==26)
		                                		$ijin= 'IZIN PULANG MENDAHULUI';
											echo '<option value="'.$data[0]->m_jenis_ijin_id.'">'.$ijin.'</option>';
										}*/
                                   	
                                    ?>
                                </select>
                               
                                </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tanggal Awal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
									<input type="date" class="form-control " id="tgl_awal"  name="tgl_awal" data-target="#tgl_awal" value="{!! date('Y-m-d',strtotime($data[0]->tgl_awal)) !!}" required>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Tanggal Akhir*</label>
								<input type="date" class="form-control" id="tgl_akhir"  name="tgl_akhir" data-target="#tgl_akhir" value="{!! date('Y-m-d',strtotime($data[0]->tgl_akhir)) !!}" required>
                                    
                                </div>
                            
                        </div> <div class="col-sm-2">
                            <div class="form-group">
                                <label>Jam Awal*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
									<input type="time" class="form-control datetimepicker-input" id="tgl_akhir"  name="jam_awal" data-target="#tgl_akhir" value="{!! date('H:i:s',strtotime($data[0]->jam_awal)) !!}" required>
                                    
                                </div>
                            </div>
                        </div><div class="col-sm-2">
                            <div class="form-group">
                                <label>Jam Akhir*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
									<input type="time" class="form-control datetimepicker-input" id="tgl_akhir"  name="jam_akhir" data-target="#tgl_akhir" value="{!! date('H:i:s',strtotime($data[0]->jam_akhir)) !!}" required>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Lama *</label>
                                <input type="text" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="lama" value="{!! $data[0]->lama !!}" >
                            </div>
                        </div><div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Status Approval Atasan *</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->sts_pengajuan !!}" readonly>
                            
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Status Approval Tim HR*</label>
                                <select name="status_apprhr"  class="form-control">
                                	<option value=""> - Pilih Approval - </option>
                                	<option value="1" <?=$data[0]->status_appr_2==1?'selected':'';?>>Setuju</option>
                                	<option value="2" <?=$data[0]->status_appr_2==2?'selected':'';?>>Tolak</option>
                                	
                                </select>
                               </div>
                        </div>
                        <div class="col-sm-5">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Keterangan *</label>
                                <textarea class="form-control" placeholder="Alasan Cuti..." id="alasan" name="alasan" readonly>{!! $data[0]->keterangan !!}</textarea>
                            </div>
                        </div>
                        <?php 
                        
                        if($data[0]->m_jenis_ijin_id==21 or $data[0]->m_jenis_ijin_id==29){?>
                        
                        <div class="col-sm-6">
                        <div id="jenisalasan" >
                            <div class="form-group">
                                <label>Alasan*</label>
                                <input type="text" class="form-control" placeholder="Status..." id="alasan" name="alasan" value="{!! $data[0]->alasan_idt_ipm !!}" readonly>
                                
                            </div>
                        </div>
                        </div>
                        <?php }?>
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Keterangan Atasan *</label>
                                <textarea class="form-control" placeholder="Keterangan Atasan..." id="alasan" name="alasan" readonly>{!! $data[0]->keterangan_atasan !!}</textarea>
                            </div>
                        </div><div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Keterangan HR *</label>
                                <textarea class="form-control" placeholder="Keterangan HR..." id="keterangan" name="keteranganhr" >{!! $data[0]->keterangan_hr !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.list_ajuan',$type) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
