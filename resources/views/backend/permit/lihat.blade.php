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
                        <h1 class="m-0 text-dark">Pengajuan {!!ucwords($type)!!}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{!! route('be.list_ajuan',$type) !!}">List Permit  {!!ucwords($type)!!}</a></li>
                            <li class="breadcrumb-item active">Detail Ajuan  {!!ucwords($type)!!}</li>
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
                <form class="form-horizontal" @if(Auth::user()->role==-1) action="{{route('be.simpan_perubahan_permit',$kode)}}" @endif  method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="hidden" class="form-control" placeholder="Nik ..." id="nik" name="p_karyawan_id" value="{!! $data[0]->p_karyawan_id !!}" readonly>
                                <input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $data[0]->nik !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
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
                                <label>Jenis Cuti*</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->nama_ijin !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Awal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_awal" readonly name="tgl_awal" data-target="#tgl_awal" value="{!! date('d-m-Y',strtotime($data[0]->tgl_awal)) !!}" required>
                                    <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Akhir*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_akhir" readonly name="tgl_akhir" data-target="#tgl_akhir" value="{!! date('d-m-Y',strtotime($data[0]->tgl_akhir)) !!}" required>
                                    <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Awal *</label>
                                <input type="text" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="jam_awal" value="{!! $data[0]->jam_awal !!}" @if(Auth::user()->role!=-1) readonly @endif>
                            </div>
                        </div><div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Akhir *</label>
                                <input type="text" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="jam_akhir" value="{!! $data[0]->jam_akhir !!}" @if(Auth::user()->role!=-1) readonly @endif>
                            </div>
                        </div><div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Lama *</label>
                                <input type="text" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="lama" value="{!! $data[0]->lama !!}" @if(Auth::user()->role!=-1) readonly @endif>
                            </div>
                        </div>
                        @if(Auth::user()->role==-1)
                        <div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Awal *</label>
                                <input type="date" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="tgl_awal" value="{!! $data[0]->tgl_awal !!}"  >
                            </div>
                        </div><div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Akhir *</label>
                                <input type="date" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="tgl_akhir" value="{!! $data[0]->tgl_akhir !!}"  >
                            </div>
                        </div><div class="col-sm-2">
                        </div><div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Approve 1 *</label>
                                <input type="date" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="tgl_appr_1" value="{!! $data[0]->tgl_appr_1 !!}"  >
                            </div>
                        </div><div class="col-sm-2">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Approve 2 *</label>
                                <input type="date" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="tgl_appr_2" value="{!! $data[0]->tgl_appr_2 !!}"  >
                            </div>
                        </div>
                        @endif
                        <div class="col-sm-4">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Status Pengajuan*</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->sts_pengajuan !!}" readonly>
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
                                <label>Keterangan *</label>
                                <textarea class="form-control" placeholder="Alasan Cuti..." id="alasan" name="alasan" readonly>{!! $data[0]->keterangan !!}</textarea>
                            </div>
                        </div>
                    
                    <?php if($type=='perdin'){
                    	if($data[0]->lama>1)
                    	$uangsaku = $uangsaku2;
                    	else 
                    	$uangsaku = $uangsaku1;
                    	?>
						<div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Uang Makan</label>
                                <input type="text" class="form-control" placeholder="Uang Saku..." id="uang_saku" name="uang_saku" value="{!! $help->rupiah($uangmakan) !!}" readonly>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Uang Saku</label>
                                <input type="text" class="form-control" placeholder="Uang Saku..." id="uang_saku" name="uang_saku" value="{!! $help->rupiah($uangsaku) !!}" readonly>
                            </div>
                        </div>
					
					
					<?php }?></div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                    	@if(Auth::user()->role==-1) 
                    		<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Simpan</button>
                    	@endif
                        <a href="{!! route('be.list_ajuan',$type) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
