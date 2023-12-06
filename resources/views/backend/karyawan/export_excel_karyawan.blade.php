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
                        <h1 class="m-0 text-dark">Karyawan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Karyawan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
			<form class="form-horizontal" method="POST" action="{!! route('be.download_excel_karyawan') !!}" enctype="multipart/form-data">
             
           
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                            
                       <div class="col-sm-12">
                        	<div class="form-group">
                                <label>Data Karyawan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <Select type="file" class="form-control" id="gapok" name="excel"     >
                                    <option value=""> Pilih Data Karaywan </option>
                                    <option value="riwayat_pekerjaan"> Riwayat Pekerjaan </option>
                                    <option value="pendidikan"> Pendidikan </option>
                                    <option value="kursus"> Kursus dan Pelatihan </option>
                                    <option value="keluarga"> Keluarga</option>
                                    <option value="pakaian"> Pakaian </option>
                                    <option value="award"> Award Perusahaan </option>
                                    <option value="sanksi"> Sanksi Perusahaan </option>
                                    </select>
                                    
                                </div>
                            </div> 
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Karyawan</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <Select class="form-control select2" id="gapok" name="karyawan"     >
                                    <option value=""> Semua Karyawan </option>
                                    <?php foreach($karyawan as $karyawan){ ?>
                                    <option value="<?=$karyawan->p_karyawan_id?>" <?=$request->karyawan==$karyawan->p_karyawan_id?'selected':'';;?>> <?=$karyawan->nama?></option>
                                    <?php }?>
                                    </select>
                                    
                                </div>
                            </div> 
                        </div><div class="col-sm-6">
                        	<div class="form-group">
                                <label>Entitas</label>
                                <div class="input-group date" id="tgl_posting" data-target-input="nearest">
                                    <Select class="form-control select2" id="gapok" name="lokasi"     >
                                    <option value=""> Semua Entitas </option>
                                    <?php foreach($lokasi as $lokasi){ ?>
                                     	<option value="<?=$lokasi->m_lokasi_id?>" <?=$request->lokasi==$lokasi->m_lokasi_id?'selected':'';;?>> <?=$lokasi->nama?></option>
                                    <?php }?>
                                    </select>
                                    
                                </div>
                            </div> 
                        </div>
                       
                    </div>
                        <button type="submit" class="btn btn-info pull-right"> Export</button>
                  </div>
                 
                     
                        <br>
                        <br>
                        <br>
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
