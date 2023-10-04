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
                        <h1 class="m-0 text-dark">Cek Absen</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Cek Absen</li>
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
                <!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
                <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.cek_cari_absen') !!}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Nama</label>
                                <select class="form-control select2" name="nama" style="width: 100%;" >
                                    <option value="">Pilih Nama</option>
                                    <?php
                                    foreach($users AS $users){
                                        if($users->p_karyawan_id==$nama){
                                            echo '<option selected="selected" value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                        else{
                                            echo '<option value="'.$users->p_karyawan_id.'">'.$users->nama.' ('.$users->nmdept.') '. '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Tanggal Awal </label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_awal" name="tgl_awal" value="{!! $tgl_awal!='1970-01-01'?$tgl_awal:date('Y-m-d') !!}"  />
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control " id="tgl_akhir" name="tgl_akhir" value="{!! $tgl_akhir !!}" data-target="#tgl_akhir" />
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Entitas</label>
                                <div >
                                    <select type="text" class="form-control " id="entitas" name="entitas" />
                                        <option value="">- Entitas -</option>
                                        <?php foreach($entitas as $entitas){?>
                                            <option value="<?=$entitas->m_lokasi_id;?>" <?=$request->entitas==$entitas->m_lokasi_id?'selected':'';?>><?=$entitas->nama;?></option>
                                        <?php }?>
                                    </select>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Lokasi Absen</label>
                                <div >
                                    <select type="text" class="form-control " id="lokasi_absen" name="lokasi_absen"  />
                                        <option value="">- Lokasi Absen -</option>
                                        <?php foreach($list_mesin as $mesin){?>
                                            <option value="<?=$mesin->mesin_id;?>" <?=$request->lokasi_absen==$mesin->mesin_id?'selected':'';?>><?=(strtoupper($mesin->nama));?></option>
                                        <?php }?>
                                    </select>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!! route('be.cek_absen') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Excel"><span class="fa fa-file-excel"></span> Excel</button>

                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                       
                        <?php if(!$nama)
                        echo '<th>Nama Karyawan</th>';
                        ?>
                        <!--<th>No. Absen </th>-->
                        <th>Tgl. Absen</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th>Lokasi Absen Seharusnya</th>
                        <th>Lokasi Absen </th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0;
                   // print_r($rekap);
                    ?>
                    @if(!empty($rekap))
                       <?php  
                       foreach($list_karyawan as $list){
                           if($nama)
                       $return = $help->total_rekap_absen($rekap,$list->p_karyawan_id);
							    
                           else
                       $return = $help->total_rekap_absen($rekap,$list->p_karyawan_id,"AllKaryawan");
									
									
									
									echo $return['all_content_cek_absen'];
									$masuk = $return['total']['masuk'] ;
									$cuti = $return['total']['cuti'] ;
									$fingerprint = $return['total']['fingerprint'] ;
									$ipg = $return['total']['ipg'] ;
									$izin = $return['total']['izin'] ;
									$ipd = $return['total']['ipd'] ;
									$ipc = $return['total']['ipc'] ;
									$sakit = $return['total']['sakit'] ;
									$alpha = $return['total']['alpha'] ;
									$terlambat = $return['total']['terlambat'] ;
									$tabsen = $return['total']['Total Absen'] ;
									$tmasuk = $return['total']['Total Masuk'] ;
									$tkerja = $return['total']['Total Hari Kerja'] ;
									
                     
                       }
                     ?>
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
