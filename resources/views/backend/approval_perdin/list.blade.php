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
                        <h1 class="m-0 text-dark">Approval Perdin</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">List Permit </li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Approval Perdin</h3>
            </div> 
            <!-- /.card-header -->
            <div class="card-body">
            <form action="<?=route('be.hr_appr');?>" method="get" enctype="multipart/form-data">
            	 {{ csrf_field() }}
            	<div class="row mb-3">
	            	<div class="col-md-2">
	            		<div class="form-group">
                                <label>Tanggal Awal</label>
                                <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?=$tgl_awal;?>" placeholder="Tanggal Awal..." required>
                            </div>	
	            	</div>
	            	<div class="col-md-2">
	            		<div class="form-group">
                                <label>Tanggal Awal</label>
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?=$tgl_akhir;?>" placeholder="Tanggal Awal..." required>
                            </div>
	            		
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group">
                                <label>Karyawan*</label>
                               <select type="text" class="form-control select2" id="nama" name="p_karyawan_id" >
									<option value="">- Pilih Karyawan - </option>
									<?php foreach($karyawan as $karyawan){?>
									<option value="<?=$karyawan->p_karyawan_id ?>" <?=$request->get('p_karyawan_id')==$karyawan->p_karyawan_id?'selected':''; ?>><?=$karyawan->nama ?></option>
									<?php }?>
										
									</select>
                            </div>
	            	</div>
	            	<div class="col-md-3">
	            		<div class="form-group">
                                <label>Pengajuan*</label>
                                <select class="form-control select2" name="pengajuan" style="width: 100%;" >
                                    <option value="">Pilih Pengajuan</option>
                                    <?php foreach($list_izin as $izin){?>
                                    <option value="<?=$izin->m_jenis_ijin_id;?>" <?=$request->get('pengajuan')==$izin->m_jenis_ijin_id?'selected':''?>><?=$izin->nama;?></option>
                                    <?php }?>
                                    
                                </select>
                            </div>
	            	</div><div class="col-md-2">
	            		<div class="form-group">
                                <label>Status*</label>
                                <select class="form-control " name="status" style="width: 100%;" >
                                    <option value="">Pilih Status</option>
                                    <option value="3" <?=$request->get('status')==3?'selected':''?>>Pending</option>
                                    <option value="1" <?=$request->get('status')==1?'selected':''?>>Setuju</option>
                                    <option value="2" <?=$request->get('status')==2?'selected':''?>>Tolak</option>
                                    
                                </select>
                            </div>
	            	</div>
            		<div class="col-md-12">
            			<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
            		</div>
            	</div>
            </form>
            
                <table id="example1" class="table table-striped custom-table mb-0">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Tgl Pengajuan</th>
                        <!--<th>Detail Perdin</th>-->
                        <th>Detail Biaya</th>
                        
                        <th>Status Approve</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($data))
                        @foreach($data as $data)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data->nama!!}<br>
                                	<span style="font-size: small;">
                                	{!! $data->nama_ijin !!}
                                	<br><?=$data->tipe_perdin;?>
                                	</span>
                                	<br><?=$data->nama_alat_transportasi;?><span style="font-size: 7px;">(Transportasi)</span>
                                	<br><?=$data->km_awal;?><span style="font-size: 7px;">(KM Awal)</span>
                                	<br><?=$data->tempat_tujuan;?><span style="font-size: 7px;">(Tempat Tujuan)</span>
                                </td>
                                <td>
                                Pengajuan : {!! date('d-m-Y', strtotime($data->create_date)) !!}
                                <br>Tgl Awal  : {!! date('d-m-Y', strtotime($data->tgl_awal)) !!}
                                <br>Tgl Akhir :{!! date('d-m-Y', strtotime($data->tgl_akhir)) !!}</td>
                                
                                <td>
a. Bensin : <?=$data->biaya_bensin;?><br>
b. Tol : <?=$data->biaya_tol;?><br>
c. Type Penginapan : <br>
d. Penginapan :  : <?=$data->biaya_penginapan;?><br>
e. Uang Makan :  : <?=$data->biaya_uang_makan;?><br>
f. Uang Saku :  : <?=$data->biaya_uang_saku;?><br>
g. Tiket :  : <?=$data->biaya_tiket;?><br>
h. Transportasi Dalam Kota :  <?=$data->biaya_transportasi_dalam_kota;?><br>
i. Penyebrangan Kapal : <?=$data->biaya_penyebrangan_kapal;?><br>
Total : <?=$data->total_biaya;?>

                                	
                                </td>
                                
                                <td style="text-align: center">
                                	<div style="text-align: center">Approval:</div>
                                    @if($data->appr_1==null)
                                    	<span><br></span>
                                    @elseif($data->status_appr_1==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($data->status_appr_1==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                   <br>{!! $data->nama_appr !!}
                                   <hr style="margin: 5px 0;">
                                   <div style="text-align: center">Approval Admin:</div>
                                    	@if($data->status_appr_admin==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($data->status_appr_admin==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                  
                                   <hr style="margin: 5px 0;">
                                   <div style="text-align: center">Approval Keuangan:</div>
                                    	@if($data->status_appr_keuangan==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($data->status_appr_keuangan==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
	                                    @else
	                                        <span class="fa fa-edit"> Pending</span>
	                                    @endif
                                  
                                </td>
                                @if(!empty($data->foto))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/file/'.$data->foto) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                
                                <td style="text-align: center">
                                	
                                    <a href="{!! route('be.print_perdin',[$data->t_form_exit_id]) !!}" title='Print' data-toggle='tooltip' class="btn btn-info btn-sm"><span class='fa fa-print'></span>Print</a>
                                    <a href="{!! route('be.lihat_perdin_appr',[$data->t_form_exit_id]) !!}" title='Lihat' data-toggle='tooltip' class="btn btn-info btn-sm"><span class='fa fa-search'></span>Lihat</a>
                                </td>
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
