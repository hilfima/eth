@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side',compact('help'));?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Status Pengajuan</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
               
            </div>
            <!-- /.card-header -->
            <div class="card-body">
             <form id="cari_absen" class="form-horizontal" method="get" action="{!!route('fe.status_persetujuan')!!}">
                    <input type="hidden" name="_token" value="BU37mt9onXbQGdPbalUa4Cr97nDgjboBBu7BSYp7">
                    <div class="row">
                        
                        <div class="col-lg-6">
                        	 <div class="form-group">
                                <label>Jenis Ajuan</label>
                                
                                    <select  class="form-control" id="tgl_awal" name="ajuan" >
                                    	<option value=""> - Pilih Ajuan -</option>
                                    	<option value="1" <?=$request->get('ajuan')==1?'selected':'';?>>Izin</option>
                                    	<option value="3" <?=$request->get('ajuan')==3?'selected':'';?>>Cuti</option>
                                    	<option value="2" <?=$request->get('ajuan')==2?'selected':'';?>>Lembur</option>
                                    	<option value="4" <?=$request->get('ajuan')==4?'selected':'';?>>Perdin</option>
                                    </select>
                                   
                               
                            </div>
                        </div><div class="col-lg-6">
                        	 <div class="form-group">
                                <label>Status Approve</label>
                                
                                    <select  class="form-control" id="tgl_awal" name="status" >
                                    	<option value="0"> - Pilih Status -</option>
                                    	<option value="3" <?=$request->get('status')==3?'selected':'';?>>Pending</option>
                                    	<option value="1" <?=$request->get('status')==1?'selected':'';?>>Disetujui</option>
                                    	<option value="2" <?=$request->get('status')==2?'selected':'';?>>Ditolak</option>
                                    </select>
                                   
                               
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?= $request->get('tgl_awal')?>">
                                   
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= $request->get('tgl_akhir')?>">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!!route('fe.status_persetujuan')!!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                           

                        </div>
                    </div>
                </form>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Ajuan</th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>Approve</th>
                        <th>Status Approve</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($permit))
                        @foreach($permit as $permit)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $permit->nik !!}</td>
                                <td>{!! $permit->nama !!}</td>
                                <td>{!! $permit->nama_ijin !!}</td>
                                <td>{!! date('d-m-Y', strtotime($permit->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($permit->tgl_akhir)) !!}</td>
                                <td>{!! $permit->nama_appr !!}</td>
                                <td style="text-align: center">
                                    @if($permit->status_appr_1==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($permit->status_appr_1==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <a href="{!! route('fe.lihat_permit',$permit->t_form_exit_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                  
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

        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
 </div>   
 </div>   
 <!-- /.content-wrapper -->
@endsection
