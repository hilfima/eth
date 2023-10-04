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
<h4 class="card-title float-left mb-0 mt-2">Tambah Anggota Club</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
            <div class="card-body">
           
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_anggota_club',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                   
                        
                        <div class="form-group">
                                                        <label>Admin</label>
                                                        <select class="form-control select2" name="karyawan_admin[]" multiple  style="width: 100%;" >
                                                           	<option value="">- Pilih Karyawan Admin- </option>
                                                            <?php
                                                            foreach($karyawan AS $karyawan2){
                                                               
                                                                    echo '<option value="'.$karyawan2->p_karyawan_id.'">'.$karyawan2->nama.'</option>';
                                                               
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                        <div class="form-group">
                                                        <label>Anggota</label>
                                                        <select class="form-control select2" name="karyawan_anggota[]" multiple style="width: 100%;" >
                                                           	<option value="">- Pilih Karyawan Anggota- </option>
                                                            <?php
                                                            foreach($karyawan AS $karyawan){
                                                               
                                                                    echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
                                                               
                                                            }
                                                            ?>
                                                        </select>
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
