@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    
        <!-- Content Header (Page header) -->
        <div class="row">
    <div class="col-md-3">
    	
   <?= view('frontend.club.sidebar',compact('id')); ?>
    </div>
    <!-- Content Wrapper. Contains page content -->
   <div class="col-md-9">
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Tambah Kegiatan Club</h4>

</div>
</div>
        <!-- /.content-header -->
@include('flash-message')
        <!-- Main content -->
        <div class="card">
           
            <div class="card-body">
           
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_kegiatan_club',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                   
                        
                        <div class="form-group">
                        	<label>Nama Kegiatan Club</label>
                            <input class="form-control " name="data[nama_kegiatan_club]"   placeholder="Nama Kegiatan Club">
                        </div>
                        <div class="form-group">
                        	<label>Deskripsi</label>
                            <textarea class="form-control " name="data[deskripsi]"   placeholder="Deskripsi"></textarea>
                        </div>
                       
                        <div class="form-group">
                        	<label>Tanggal</label>
                            <input class="form-control "type="date"  name="data[tgl]"   placeholder="Deskripsi">
                        </div>
                       
                        <div class="form-group">
                        	<label>Jam awal</label>
                            <input class="form-control "type="time"  name="data[jam_awal]"   placeholder="Deskripsi">
                        </div>
                        <div class="form-group">
                        	<label>Jam Akhir</label>
                            <input class="form-control "type="time"  name="data[jam_akhir]"   placeholder="Deskripsi">
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
