@extends('layouts.app_fe')

@section('content')
<style>
.form-horizontal .radio,
.form-horizontal .checkbox,
.form-horizontal .radio-inline,
.form-horizontal .checkbox-inline {
  margin-top: 0;
  margin-bottom: 0;
  padding-top: 7px;
}
.form-horizontal .radio,
.form-horizontal .checkbox {
  min-height: 27px;
}
.form-horizontal .form-group {
  margin-left: -15px;
  margin-right: -15px;
}
@media (min-width: 768px) {
  .form-horizontal .control-label {
    text-align: right;
    margin-bottom: 0;
    padding-top: 7px;
  }
}
.form-horizontal .has-feedback .form-control-feedback {
  right: 15px;
}
@media (min-width: 768px) {
  .form-horizontal .form-group-lg .control-label {
    padding-top: 11px;
    font-size: 18px;
  }
}
@media (min-width: 768px) {
  .form-horizontal .form-group-sm .control-label {
    padding-top: 6px;
    font-size: 12px;
  }
}
.control-label{
    font-weight:bold;
}
</style>
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
<h4 class="card-title float-left mb-0 mt-2">Lihat Penugasan Perjalanan Dinas</h4>

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
                   
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">NIK</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Nik ..." id="nik" name="nik" value="{!! $cuti[0]->nik !!}" readonly></div>
                            </div>
                        
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Nama</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Nama ..." id="nama" name="nama" value="{!! $cuti[0]->nama_lengkap !!}" readonly></div>
                            </div>
                        
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Jabatan</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Nama Jabatan ..." id="jabatan" name="jabatan" value="{!! $cuti[0]->jabatan !!}" readonly></div>
                            </div>
                        
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Departemen</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Nama Departemen..." id="departemen" name="departemen" value="{!! $cuti[0]->departemen !!}" readonly></div>
                            </div>
                        
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Tanggal Pengajuan</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y',strtotime($cuti[0]->create_date)) !!}" readonly></div>
                            </div>
                        
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Jenis Cuti*</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! $cuti[0]->nama_ijin !!}" readonly></div>
                            </div>
                        
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Tanggal Awal*</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y',strtotime($cuti[0]->tgl_awal)) !!}" readonly></div>
                            </div>
                        
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Tanggal Akhir*</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="{!! date('d-m-Y',strtotime($cuti[0]->tgl_akhir)) !!}" readonly></div>
                            </div>
                        
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Lama *</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Lama Cuti/Izin..." id="lama" name="lama" value="{!! $cuti[0]->lama !!}" readonly></div>
                            </div>
                        
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Penanggung Jawab Sementara*</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Nama Atasan..." id="atasan" name="atasan" value="{!! $cuti[0]->pjs !!}" readonly></div>
                            </div>
                        
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Atasan*</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Nama Atasan..." id="atasan" name="atasan" value="{!! $cuti[0]->nama_appr !!}" readonly></div>
                            </div>
                        
                            <!-- text input -->
                            
                            <!-- text input -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Alasan Cuti*</label>
                                <div class="col-sm-10"><textarea class="form-control" placeholder="Alasan Cuti..." id="alasan" name="alasan" readonly>{!! $cuti[0]->keterangan !!}</textarea></div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Status Pengajuan *</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Status..." id="status" name="status" value="{!! $cuti[0]->sts_pengajuan !!}" readonly></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Approval HR *</label>
                                <div class="col-sm-10"><input type="text" class="form-control" placeholder="Status..." id="status" name="status" value="{!! $cuti[0]->approve_hr !!}" readonly></div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Keterangan HR *</label>
                                <div class="col-sm-10"><textarea type="text" class="form-control" placeholder="Status..." id="status" name="status" value="{!! $cuti[0]->keterangan_hr !!}" readonly></textarea></div>
                            </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('fe.list_perdin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
