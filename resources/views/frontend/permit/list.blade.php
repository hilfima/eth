@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

    <!-- Content Wrapper. Contains page content -->
    
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Pengajuan Cuti/Izin/Perdin/Lembur</h4>

</div>
</div>
<style>
strong{
	font-weight: 900;
}
</style>

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajuan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <form id="cari_absen" class="form-horizontal" method="get" action="{!!route('fe.listed')!!}">
                    {{ csrf_field() }}
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
                                    	<option value=""> - Pilih Status -</option>
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
                                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?= $request->get('tgl_awal')?$request->get('tgl_awal'):date('Y-m-d')?>">
                                   
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= $request->get('tgl_akhir')?$request->get('tgl_akhir'):date('Y-m-d')?>">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!!route('fe.listed')!!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                           

                        </div>
                    </div>
                </form>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Ajuan</th>
                        <th>Tgl </th>
                        <th>Nama Approval</th>
                        
                        
                        <th>Approval</th>
                        
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($data))
                        @foreach($data as $data)
                            <?php $no++;
                            if(!$data->tgl_akhir and $data->tgl_akhir=='1970-01-01'){
                                $data->tgl_akhir = $data->tgl_awal;
                            }
                            ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data->nama_lengkap !!} <!--({!! $data->nik !!})--></td>
                                
                                <td>{!! $data->nama_ijin !!}
                                 @if(!empty($data->foto))
                                   <a href="{!! asset('dist/img/file/'.$data->foto) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
                               
                                @endif
                                </td>
                                <td>{!! date('d-m-Y', strtotime($data->tgl_awal))==date('d-m-Y', strtotime($data->tgl_akhir))?date('d-m-Y', strtotime($data->tgl_awal)):date('d-m-Y', strtotime($data->tgl_awal)).' s/d '.date('d-m-Y', strtotime($data->tgl_akhir)) !!} </td>
                                
                                @if($data->appr_1==$id)
	                               <td>
									{!! $data->nama_appr !!}
									</td>
	                                <td>
									@if($data->status_appr_1==1)
	                                        <span class="fa fa-check-circle"> Disetujui</span>
	                                        @elseif($data->status_appr_1==2)
	                                            <span class="fa fa-window-close"> Ditolak</span>
	                                    @else
	                                        <span class="fa fa-edit"> Pending</span>
	                                    @endif
									</td>
                                @elseif($data->appr_2==$id)
                                <td>
									@if($data->nama_appr2)
									{!! $data->nama_appr2 !!}
                                    @endif
								</td>
                                <td>
									@if($data->nama_appr2)
										@if($data->status_appr_2==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($data->status_appr_2==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
								</td>
                                    
								
                                    @endif
                                    @endif
                               
                                
                               
                                
                                <td style="text-align: center">
                                    <a href="{!! route('fe.lihat_ajuan_2',$data->t_form_exit_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
									@if($data->status_appr_1==3 and $data->appr_1==$id)
                                    	
                                        <a href="{!! route('fe.edit_ajuan',$data->t_form_exit_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                          <a href="{!! route('fe.setujui_ajuan',$data->t_form_exit_id) !!}" class="btn btn-success btn-sm"  title='Approve' data-toggle='tooltip'> 
                                    	Approve  
                                    </a><a href="{!! route('fe.tolak_ajuan',$data->t_form_exit_id) !!}" class="btn btn-danger btn-sm" title='Tolak' data-toggle='tooltip'> 
                                    	Tolak    
                                    </a> 
                                    @endif
                                    @if($data->status_appr_2==3 and $data->appr_2==$id and (($data->status_appr_1==1 and $data->appr_1!=null) or $data->appr_1==null) and $data->m_jenis_ijin_id==22)
                                   
                                       <a href="{!! route('fe.edit_ajuan_2',$data->t_form_exit_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                       <a href="{!! route('fe.setujui_ajuan_2',$data->t_form_exit_id) !!}" class="btn btn-success btn-sm"  title='Ubah' data-toggle='tooltip'> 
                                    	Approve     
                                    </a><a href="{!! route('fe.tolak_ajuan_2',$data->t_form_exit_id) !!}" class="btn btn-danger btn-sm" title='Ubah' data-toggle='tooltip'> 
                                    	Tolak    
                                    </a>
                                    @else
                                    @if($data->m_jenis_ijin_id==22)
                                    	Menunggu Approval 1  
                                    @endif
                                    @endif
                      				          
                                </td>
                                
                                
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div> 
       <!-- <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajuan Layer 2</h3>
            </div>
            <!-- /.card-header 
            <div class="card-body">
           
                    <div class="row">
                        
                        <div class="col-lg-6">
                        	 <div class="form-group">
                                <label>Jenis Ajuan</label>
                                
                                    <select  class="form-control" id="tgl_awal" name="ajuan2" >
                                    	<option value=""> - Pilih Ajuan -</option>
                                    	<option value="1" <?=$request->get('ajuan2')==1?'selected':'';?>>Izin</option>
                                    	<option value="3" <?=$request->get('ajuan2')==3?'selected':'';?>>Cuti</option>
                                    	<option value="2" <?=$request->get('ajuan2')==2?'selected':'';?>>Lembur</option>
                                    	<option value="4" <?=$request->get('ajuan2')==4?'selected':'';?>>Perdin</option>
                                    </select>
                                   
                               
                            </div>
                        </div><div class="col-lg-6">
                        	 <div class="form-group">
                                <label>Status Approve</label>
                                
                                    <select  class="form-control" id="tgl_awal2" name="status2" >
                                    	<option value=""> - Pilih Status -</option>
                                    	<option value="-1" <?=$request->get('status2')==-1?'selected':'';?>>Pending</option>
                                    	<option value="1" <?=$request->get('status2')==1?'selected':'';?>>Disetujui</option>
                                    	<option value="2" <?=$request->get('status2')==2?'selected':'';?>>Ditolak</option>
                                    </select>
                                   
                               
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group date" id="tgl_awal" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal2" value="<?= $request->get('tgl_awal2')?>">
                                   
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group date" id="tgl_akhir" data-target-input="nearest">
                                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir2" value="<?= $request->get('tgl_akhir2')?>">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{!!route('fe.listed')!!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                            <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                           

                        </div>
                    </div>
                </form>
                <table id="example2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Ajuan</th>
                        <th>Tgl Awal</th>
                        <th>Tgl Akhir</th>
                        <th>Approval</th>
                        <th>File</th>
                        <th>Status Approve</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($data2))
                        @foreach($data2 as $data2)
                            <?php $no++ ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data2->nik !!}</td>
                                <td>{!! $data2->nama_lengkap !!}</td>
                                <td>{!! $data2->nama_ijin !!}</td>
                                <td>{!! date('d-m-Y', strtotime($data2->tgl_awal)) !!}</td>
                                <td>{!! date('d-m-Y', strtotime($data2->tgl_akhir)) !!}</td>
                                <td>{!! $data2->nama_ijin !!}</td>
                                @if(!empty($data2->foto))
                                    <td style="text-align: center"><a href="{!! asset('dist/img/file/'.$data2->foto) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a></td>
                                @else
                                    <td></td>
                                @endif
                                <td>{!! $data2->nama_appr !!}</td>
                                <td style="text-align: center">
                                    @if($data2->status_appr_2==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($data2->status_appr_2==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if($data2->status_appr_2==3)
                                        <a href="{!! route('fe.lihat_ajuan_2',$data2->t_form_exit_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                        <a href="{!! route('fe.edit_ajuan_2',$data2->t_form_exit_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    @else
                                        <a href="{!! route('fe.lihat_ajuan_2',$data2->t_form_exit_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    @endif
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
    </div>
    </div>
    </div>
    <!-- /.content-wrapper -->
@endsection
