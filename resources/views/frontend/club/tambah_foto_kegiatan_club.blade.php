@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="row">
    <div class="col-md-3">
    	
   <?= view('frontend.club.sidebar',compact('id')); ?>
    </div>
    <!-- Content Wrapper. Contains page content -->
   <div class="col-md-9">
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Tambah Foto Kegiatan Club</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
            <div class="card-body">
           
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_foto_kegiatan_club',[$id,$id_kegiatan]) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                   
                        
                        <div class="form-group">
                        	<label>Nama Kegiatan Foto Kegiatan</label>
                            <input class="form-control "  type="file" name="file[]"  multiple="" placeholder="Nama Kegiatan Foto Kegiatan">
                        </div>
                        
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.club') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
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
