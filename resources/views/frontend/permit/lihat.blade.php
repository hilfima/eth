@extends('layouts.app_fe')

@section('content')

	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
         
   <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Lihat</h4>

</div>
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
                <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $data[0]->nik !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $data[0]->nama_lengkap !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" placeholder="Nama Jabatan ..." id="jabatan" name="jabatan" value="{!! $data[0]->jabatan !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Departemen</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->departemen !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y',strtotime($data[0]->create_date)) !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Jenis Cuti*</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->nama_ijin !!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tanggal Awal*</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_awal" name="tgl_awal" data-target="#tgl_awal" value="{!! date('d-m-Y',strtotime($data[0]->tgl_awal)) !!}" required readonly>
                                    <div class="input-group-append" data-target="#tgl_awal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Tanggal Akhir*</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" id="tgl_akhir" name="tgl_akhir" data-target="#tgl_akhir" value="{!! date('d-m-Y',strtotime($data[0]->tgl_akhir)) !!}" required readonly>
                                    <div class="input-group-append" data-target="#tgl_akhir" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
						 <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Awal*</label>
                                <input type="time" class="form-control without_ampm" id="jam_awal" name="jam_awal" value="{!! $data[0]->jam_awal!!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Akhir*</label>
                                <input type="time" class="form-control without_ampm" id="jam_akhir" name="jam_akhir" value="{!! $data[0]->jam_akhir!!}" readonly>
                            </div>
                        </div>
                        
						 <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam Masuk Finger*</label>
                                <input type="time" class="form-control without_ampm" id="jam_awal" value="{!! $data[0]->jam_masuk_finger!!}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Jam  Keluar Finger*</label>
                                <input type="time" class="form-control without_ampm" id="jam_akhir" value="{!! $data[0]->jam_keluar_finger!!}" readonly>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Lama *</label>
                                <input type="text" class="form-control" placeholder="Lama..." id="lama" name="lama" value="{!! $data[0]->lama !!}" readonly>
                            </div>
                        </div>
                         <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Status Pengajuan*</label>
                                <input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $data[0]->sts_pengajuan !!}" readonly>
                            </div>
                        </div>
                        <?php 
                        
                        if($data[0]->m_jenis_ijin_id==21 or $data[0]->m_jenis_ijin_id==29){?>
                        
                        <div class="col-sm-12">
                        <div id="jenisalasan" >
                            <div class="form-group">
                                <label>Alasan*</label>
                                <input type="text" class="form-control" placeholder="Status..." id="alasan" name="alasan" value="{!! $data[0]->alasan_idt_ipm !!}" readonly>
                                
                            </div>
                        </div>
                        </div>
                        <?php }?>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Keterangan *</label>
                                <textarea class="form-control" placeholder="Alasan Cuti..." id="alasan" name="alasan" readonly>{!! $data[0]->keterangan !!}</textarea>
                            </div>
                        </div>
                       
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('fe.listed') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    </div>
    </div>
    <!-- /.content-wrapper -->
@endsection
