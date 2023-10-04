@extends('layouts.appsA')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Point Point Penilaian KPI</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

				
			</ul>

        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card">
       
        <!-- /.card-header -->
        <div class="card-body">
           

           <form class="form-horizontal" method="POST" action="{!! route('be.simpan_parameter_penilaian_kpi') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                            <!-- text input -->
                            <div class="form-group">
                                <label>Penilaian</label>
                                <select type="text" class="form-control" placeholder="Nama Penilaian ..." id="judul" name="data[m_point_utama_kpi_id]" required>
                                <option value="">Pilih Penilaian</option>
                                <?php foreach($penilaian as $penilaian){?>
                                	<option value="<?=$penilaian->m_point_utama_kpi_id;?>"><?=$penilaian->nama_point;?>(<?=$penilaian->nama_penilaian;?>)</option>
                                <?php }?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Jawaban</label>
                                <input type="text" class="form-control" placeholder="Jawaban" id="judul" name="data[key]" required>
                            </div>
                            <div class="form-group">
                                <label>Penjelasan Jawaban</label>
                                <textarea type="text" class="form-control" placeholder="Keterangan ..." id="judul" name="data[deskripsi]" ></textarea>
                            </div>
                            <div class="form-group">
                                <label>Point(skala 1-5)</label>
                                <select type="number" class="form-control" placeholder="Nama Point ..." id="judul" name="data[point]" required>
                                <option value="">Pilih</option>
                                <option >1</option>
                                <option >2</option>
                                <option >3</option>
                                <option >4</option>
                                <option >5</option>
                                </select>
                            </div>
                   	     <div class="box-footer">
                        <a href="{!! route('be.jenis_penilaian_kpi') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span> Simpan</button>
                    </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection