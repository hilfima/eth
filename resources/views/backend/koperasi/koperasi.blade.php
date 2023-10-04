@extends('layouts.appsA')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
    <?php 
    if($page =='sewa kost'){
    	$title = 'Potongan Sewa Kost';
    }else 
    if($page =='asa'){
    	$title = 'Potongan ASA';
    }else 
    	$title = 'Potongan KKB';
    	
    ?>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"> <?=$page=='tunjangan kost'?'':($page=='bonus'?'Tunjangan': 'Potongan');?> <?=	(!in_array(strtoupper(''.$page),array('KKB','ASA')))  ?ucwords(''.$page):strtoupper(''.$page);?></h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="{!! route('be.koperasi',$page) !!}"><?=$page=='tunjangan kost'?'':($page=='bonus'?'Tunjangan': 'Potongan');?></a></li>
                            <li class="breadcrumb-item active"> <?=(!in_array(strtoupper(''.$page),array('KKB','ASA')))  ?ucwords(''.$page):strtoupper(''.$page);?></li>
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
				<a href="{!! route('be.tambah_koperasi',$page) !!}" title='Tambah' class="btn btn-sm btn-primary" data-toggle='tooltip'><span class='fa fa-plus'></span>  <?=$page=='tunjangan kost'?'':($page=='bonus'?'Tunjangan': 'Potongan');?> <?=(!in_array(strtoupper(''.$page),array('KKB','ASA')))  ?ucwords(''.$page):strtoupper(''.$page);?> </a>
				<a href="{!! route('be.tambah_excel_koperasi',$page) !!}" title='Tambah'  class="btn btn-sm btn-danger" data-toggle='tooltip'><span class='fa fa-upload'></span>  <?=ucwords('Import Data Excel');?> </a>
				<a href="{!! route('be.excel_exist_data_koperasi',$page) !!}" title='Tambah' class="btn btn-sm btn-success" data-toggle='tooltip'><span class='fa fa-download'></span> Download Template Existing Data</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.koperasi',$page) !!}">
            	<div class="row">
            	<div class="col-lg-6">
                            <div class="form-group">
                                <label>Periode Gaji</label>
                                <select class="form-control select2" name="periode_gaji" style="width: 100%;" >
                                    <option value="">Pilih Periode Gaji</option>
                                    <option value="1" <?= $request->get('periode_gaji')==1?'selected':''?>>Bulanan</option>
                                    <option value="-1" <?= $request->get('periode_gaji')==-1?'selected':''?>>Pekanan</option>
                                    
                                </select>
                            </div>
                            </div>
                            <div class="col-lg-3">
                            <div class="form-group">
                                <label>Pajak</label>
                                <select class="form-control select2" name="pajak" style="width: 100%;" >
                                    <option value="">Pilih Pajak</option>
                                    <option value="ON">ON</option>
                                    <option value="OFF">OFF</option>
                                   
                                </select>
                            </div>
                        </div>	
                        <div class="col-lg-3">
                           <div class="form-group">
							<label>Entitas</label>
							<select class="form-control select2" name="entitas" style="width: 100%;" >
								<option value="">Pilih Entitas</option>
								<?php
								foreach($entitas AS $entitas){
										$selected = '';
									if($entitas->m_lokasi_id== $request->get('entitas')){
										$selected = 'selected';
										
									}
									
										echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
								}
								?>
							</select>
						</div>
                        </div>	
                        </div>	
                     <button type="submit" name="Cari" class="btn btn-primary mb-3" value="Cari"><span class="fa fa-search"></span> Cari</button>
                 
                     </form>
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                      
                        <th>Nama Karyawan</th>
                        <th>Entitas</th>
                        <th>Pajak</th>
                        <th>Periode Gajian</th>
                        <th>Nominal</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <?php if(strtoupper($page)!='PAJAK'){?>
                        	<th><?= strtoupper($page)=='SEWA KOST'?'Alamat Rumah':'No Anggota';?></th>
                        <?php }?>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0;$total=0; 
                    $total_entitas = array();
                    ?>
                    @if(!empty($koperasi))
                        @foreach($koperasi as $koperasi)
                            <?php $no++;
                            if(isset($total_entitas[$koperasi->nmentitas]))
                            $total_entitas[$koperasi->nmentitas] +=$koperasi->nominal;
                            else
                            $total_entitas[$koperasi->nmentitas] =$koperasi->nominal;
                             ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $koperasi->nama !!}</td>
                                <td>{!! $koperasi->nmentitas !!}</td>
                                <td>{!! $koperasi->pajak_onoff !!}</td>
                                <td>{!! $koperasi->periode_gajian !!}</td>
                                <td>{!! $help->rupiah($koperasi->nominal)!!}</td>
                                <td>{!! $koperasi->tgl_awal !!}</td>
                                <td>{!! $koperasi->tgl_akhir !!}</td>
                                <?php if(strtoupper($page)!='PAJAK'){?>
                                <td>{!! $koperasi->no_anggota !!}</td>
                                <?php }
                                $total += $koperasi->nominal;
                                ?>
                                <td style="text-align: center">
                                   
								   <a href="{!! route('be.edit_koperasi',[$koperasi->p_karyawan_koperasi_id,$page]) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
								   <a href="{!! route('be.hapus_koperasi',[$koperasi->p_karyawan_koperasi_id,$page]) !!}" title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                </td>
                               
                            </tr>
                    @endforeach
                    @endif
                </table>
            <h3>TOTAL : 
            <?=$help->rupiah($total)?></h3>
            <?php foreach($total_entitas as $key=>$value){
            		echo '<br><b>'.$key.'</b> :  '.$help->rupiah($value);
				}?>
            
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
