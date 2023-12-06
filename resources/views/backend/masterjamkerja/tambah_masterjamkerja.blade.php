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
                        <h1 class="m-0 text-dark">Jam Kerja Reguler</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Jam Kerja Reguler</li>
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
                <form class="form-horizontal" method="POST" action="{!! route('be.simpan_masterjamkerja') !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" placeholder="Nama  ..." id="judul" name="data[nama_master]" required>
                            </div>
                            <div class="form-group ">
                                <label>Seksi</label>
                                <select class="form-control select2" multiple placeholder="Nama Batas ..." id="batas_tipe" name="list_seksi[]"   required>
                                <?php 
                            $data = DB::connection()->select('select m_departemen.m_departemen_id,m_departemen.nama as nama_dept,m_lokasi.nama as nmlokasi from m_departemen 
                            left join m_divisi on m_departemen.m_divisi_id = m_divisi.m_divisi_id
                            left join m_divisi_new on m_divisi_new.m_divisi_new_id = m_divisi.m_divisi_new_id
                            left join m_directorat on m_divisi_new.m_directorat_id = m_directorat.m_directorat_id
                            left join m_lokasi on m_lokasi.m_lokasi_id = m_directorat.m_lokasi_id
                            where m_departemen.active=1');
					        echo'<option value="">- Seksi -</option>';
					        foreach($data as $row){
					            echo '<option value="'.$row->m_departemen_id.'">'.$row->nama_dept.'('.$row->nmlokasi.')</option>';
					        }
                            ?>
                                    
                                </select>
                            </div>
                            
                            <div class="row">
	                            <div class="form-group col-md-6">
	                                <label>Jam Masuk</label>
	                                <input type="time" class="form-control" placeholder="Nama  ..." id="judul" name="data[jam_masuk]" required>
	                            </div>
	                            <div class="form-group col-md-6">
	                                <label>Jam Keluar</label>
	                                <input type="time" class="form-control" placeholder="Nama  ..." id="judul" name="data[jam_keluar]" required>
	                            </div>
	                            <div class="form-group col-md-6">
	                                <label>Batas Jam Masuk</label>
	                                <input type="time" class="form-control" placeholder="Nama  ..." id="judul" name="data[jam_batas_masuk]" required>
	                            </div>
	                            <div class="form-group col-md-6 ">
	                                <label>Batas Jam Keluar</label>
	                                <input type="time" class="form-control" placeholder="Nama  ..." id="judul" name="data[jam_batas_keluar]" required>
	                            </div>
	                            <div class="form-group col-md-6 ">
	                                <label>Tanggal Awal</label>
	                                <input type="date" class="form-control" placeholder="Nama  ..." id="judul" name="data[tgl_awal]" required>
	                            </div>
	                            <div class="form-group col-md-6 ">
	                                <label>Tanggal Akhir</label>
	                                <input type="date" class="form-control" placeholder="Nama  ..." id="judul" name="data[tgl_akhir]" required>
	                            </div>
                       
                            <div class="form-group  col-md-3">
                                <label>Masuk Senin</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_senin]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk selasa</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_selasa]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Rabu</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_rabu]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Kamis</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_kamis]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Jumat</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_jumat]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Sabtu</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_sabtu]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                            <div class="form-group  col-md-3">
                                <label>Masuk Ahad</label>
                                <select class="form-control" placeholder="Nama Batas ..." id="batas_tipe" name="data[masuk_ahad]"   required>
                                    <option value="1">Masuk</option>
                                    <option value="0">Libur</option>
                                </select>
                            </div>
                       
                            </div>
                        
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer">
                        <a href="{!! route('be.masterjamkerja') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <script>
        function change_batas_tipe(){
            var val = $('#batas_tipe').val();
            //alert(val);
            if(val=='+-'){
                $('#double_plus_min').show();
                $('#single_plus_min').hide();
            }else{
                $('#double_plus_min').hide();
                $('#single_plus_min').show();
            }
        }
    </script>
    <!-- /.content-wrapper -->
@endsection
