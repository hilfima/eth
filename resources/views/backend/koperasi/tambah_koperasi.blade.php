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
                        <h1 class="m-0 text-dark"><?=$page=='tunjangan kost'?'':($page=='bonus'?'Tunjangan': 'Potongan');?> <?=	(!in_array(strtoupper(''.$page),array('KKB','ASA')))  ?ucwords(''.$page):strtoupper(''.$page);?><</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active"><?=$page=='tunjangan kost'?'':($page=='bonus'?'Tunjangan': 'Potongan');?> <?=	(!in_array(strtoupper(''.$page),array('KKB','ASA')))  ?ucwords(''.$page):strtoupper(''.$page);?><</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            
            <form class="form-horizontal" method="POST" action="{!! route('be.'.$type,[$id,'page='.$page]) !!}" enctype="multipart/form-data">
            
            
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                    {{ csrf_field() }}
                    
                    <!-- ./row -->
                    <div class="row">
                     
                            <div class="col-md-12">
                            <div class="form-group">
                                <label>Karyawan</label>
                               <select class="form-control select2" name="p_karyawan_id" style="width: 100%;" required>
								<option value="">Pilih Karyawan</option>
								<?php
								foreach($karyawan AS $karyawan){
									if($karyawan->p_karyawan_id==$data['p_karyawan_id']){
										echo '<option selected="selected" value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
									}
									else{
										echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
									}
								}
								?>
							</select>
                            </div>
                        </div><div class="col-md-4">
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="text" class="form-control" id="nama" name="nominal" placeholder="Nominal" onkeypress="handleNumber(event, 'Rp {15,3}')" value="<?=$data['nominal'];?>" required>
                            </div>
                        </div><div class="col-md-4 d-none">
                            <div class="form-group">
                                <label>Jenis Koperasi</label>
                                <select type="text" class="form-control"  id="nama" name="nama_koperasai" placeholder="Tanggal Awal" value="" required="">
                                <option value="" >-Pilih Koperasi-</option>
                                <option value="ASA" <?=$data['nama']=='ASA'?'selected':'';?> selected>Koperasi ASA</option>
                                <option value="KKB" <?=$data['nama']=='KKB'?'selected':'';?>>Koperasi KKB</option>
                                </select>
                            </div>
                        </div><div class="col-md-4">
                            <?php if(in_array(strtoupper(''.$page),array('SEWA KOST','KKB','ASA'))){?>
                            <div class="form-group">
                                <label><?= strtoupper($page)=='SEWA KOST'?'Alamat Rumah':'No Anggota';?></label>
                                <input type="text" class="form-control" id="nama" name="no_anggota" placeholder="<?= strtoupper($page)=='SEWA KOST'?'Alamat Rumah':'No Anggota';?>" value="<?=$data['no_anggota'];?>">
                            </div>
                            <?php }?>
                        </div><div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <input type="date" class="form-control" id="nama" name="tgl_awal" placeholder="Tanggal Awal" value="<?=$data['tgl_awal'];?>" required>
                            </div>
                        </div><div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <input type="date" class="form-control" id="nama" name="tgl_akhir" placeholder="Tanggal Akhir" value="<?=$data['tgl_akhir'];?>" required>
                            </div>
                        </div>
                       
                        
                        
                        
                           
                    </div>
                 
                        <a href="{!! route('be.koperasi',$page) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
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
