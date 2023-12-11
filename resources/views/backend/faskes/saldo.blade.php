@extends('layouts.appsA')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-7">
                        <h2 class="m-0 text-dark"> Informasi Saldo Fasilitas Kesehatan</h2>
                    </div><!-- /.col -->
                    <div class="col-sm-5">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Saldo Faskes</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <style>
            .dash-widget-icon {
                background-color: #fff !important;
            }
        </style>
        <?php 
                            $bpjs= 0;
                            $faskes=0;
                            $belum=0;
         $fasilitas_temp = $fasilitas;
                            ?>
         @if(!empty($fasilitas))
         
                        @foreach($fasilitas as $fasilitas)
                          
                            
                                        
                               
                               
                                <?php
                                $status = $fasilitas->bpjs_kantor?'BPJS':(date('Y-m-d')>$help->tambah_bulan($fasilitas->tgl_bergabung,3)?'Faskes':'-');
                                if($status=='BPJS')
                                    $bpjs+=1;
                                else if($status=='Faskes')
                                    $faskes+=1;
                                else if($status=='-')
                                    $belum+=1;
                                ?>
                        @endforeach
                    @endif
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
							<div class="dash-widget clearfix card-box">
								<span class="">                                                            
								<img src="{{url('dist/img/fasilitas/BPJS.png')}}" class="dash-widget-icon" style="height: 75px;">
                                </span>
								<div class="dash-widget-info">
								<h5>BPJS KESEHATAN</h5>
									<h3><?=$bpjs;?></h3>
									<span>Karyawan</span>
								</div>
							</div>
						</div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
							<div class="dash-widget clearfix card-box">
								<span class="">                                                            
								<img src="{{url('dist/img/fasilitas/Faskes.png')}}" class="dash-widget-icon" style="height: 75px;">
                                </span>
								<div class="dash-widget-info">
								<h5>FASILITAS KESEHATAN</h5><h3><?=$faskes;?></h3>
									<span>Karyawan</span>
								</div>
							</div>
						</div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
							<div class="dash-widget clearfix card-box">
								<span class="">                                                            
								<img src="{{url('dist/img/fasilitas/Tidak ada.png')}}" class="dash-widget-icon" style="height: 75px;">
                                </span>
								<div class="dash-widget-info">
								<h5>BELUM ADA TUNJANGAN</h5>
								<h3><?=$belum;?></h3>
									<span>Karyawan</span>
								</div>
							</div>
						</div>
						</div>
                
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <a href="{!! route('be.tambah_saldo_faskes') !!}" class="btn btn-primary" title='Tambah' data-toggle='tooltip'><span class='fa fa-plus'></span> Saldo Faskes </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <form id="cari_absen" class="form-horizontal" method="get" action="{!!route('be.saldo_faskes')!!}">
               
            <div class="form-group row">
							<label class="col-md-1" style="align-content: center;display: flex;align-items: center;font-size: 16px;">Entitas</label>
							<div class="col-md-5">
							<select class="form-control select2 " name="entitas" style="width: 100%;" >
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
						<div class="col-md-2">
						    <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
						    
                        <a href="{!!route('be.saldo_faskes')!!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
						</div>
						</div>
						<div class="form-group">
                    <div class="col-md-12">
                        


                    </div>
                </div>
                </form>
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama </th>
                        <th>Entitas </th>
                        <th>Tanggal Bergabung </th>
                        <!--<th>Tanggal Saldo </th>-->
                        <th>Status </th>
                        <th>Saldo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0;
                        $fasilitas=$fasilitas_temp; ?>
                    @if(!empty($fasilitas))
                        @foreach($fasilitas as $fasilitas)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $fasilitas->nama !!}</td>
                                <td>{!! $fasilitas->nama_entitas !!}</td>
                                <td>        {!! $fasilitas->tgl_bergabung !!}</td>
                                <!--<td>        {!! $help->tambah_bulan($fasilitas->tgl_bergabung,3) !!}</td>-->
                                        
                                        
                                <td> {!! $fasilitas->bpjs_kantor?'BPJS':(date('Y-m-d')>$help->tambah_bulan($fasilitas->tgl_bergabung,3)?'Faskes':'-') !!}</td>
                                <td>{!! $help->rupiah2($help->lap_faskes($fasilitas->p_karyawan_id)['nominal']) !!}</td>
                                
                                
                            </tr>
                        @endforeach
                    @endif
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
