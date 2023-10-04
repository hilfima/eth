@extends('layouts.app_fe')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Persetujuan KPI</h4>

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
            
            <!-- /.card-header -->
            <div class="card-body">
          
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Periode KPI </th>
                        <th>Status Approval</th>
                        <th>Action </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($kpi))
                          @foreach($kpi as $kpi)
                    <?php $no++ ?>
                    <tr>
                        <td>{!! $no !!}</td>
                        <td>{!! $kpi->nama !!}</td>
                        <td>{!! $kpi->jabatan !!}</td>
                         <td><?php 
                        if($kpi->periode_kpi==1){
                            echo 'Costum';
                        }else if($kpi->periode_kpi==2){
                            echo 'Bulanan';
                        }else if($kpi->periode_kpi==3){
                            echo 'Triwulan';
                        }else if($kpi->periode_kpi==4){
                            echo 'Tahunan';
                        }
                        
                        echo ' '.date('Y',strtotime(date($kpi->tanggal_awal)));
                        if(date('Y',strtotime(date($kpi->tanggal_awal)))!=date('Y',strtotime(date($kpi->tanggal_akhir))))
                        echo ' s/d '.date('Y',strtotime(date($kpi->tanggal_akhir)));
                        ?></td>
                                @if($kpi->atasan_1==$id)
	                              
	                                <td>
									@if($kpi->status_appr_1==1)
	                                        <span class="fa fa-check-circle"> Disetujui</span>
	                                        @elseif($kpi->status_appr_1==2)
	                                            <span class="fa fa-window-close"> Ditolak</span>
	                                    @else
	                                        <span class="fa fa-edit"> Pending</span>
	                                    @endif
									</td>
                                @elseif($kpi->atasan_2==$id)
                                
                                <td>
									
										@if($kpi->status_appr_2==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($kpi->status_appr_2==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
								</td>
                                    
								
                                  
                                    @endif
                               
                                
                               
                                
                                <td style="text-align: center">
         <!--                          <div class="d-flex">-->
                                    <a href="{!! route('fe.approval_kpi_detail',$kpi->t_kpi_id) !!}" title='Edit' data-toggle='tooltip' class="btn btn-primary mr-2"><span class='fa fa-edit'></span> </a><Br>
									
                      				          
                                   	
									<!--@if($kpi->status_appr_1==3 and $kpi->atasan_1==$id)-->
                                    	
         <!--                               <a href="{!! route('fe.acc_kpi_1',$kpi->t_kpi_id) !!}" class="btn btn-success btn-sm mr-2"  title='Approve - 1 ' data-toggle='tooltip'> -->
         <!--                               <i class="fa fa-check"></i>-->
         <!--                           </a><a href="{!! route('fe.dec_kpi_1',$kpi->t_kpi_id) !!}" class="btn btn-danger btn-sm" title='Tolak - 1' data-toggle='tooltip'> -->
         <!--                           	<i class="fa fa-close"></i>    -->
         <!--                           </a> -->
         <!--                           @endif-->
         <!--                           @if($kpi->status_appr_2==3 and $kpi->atasan_2==$id  and (($kpi->status_appr_1==1 and $kpi->atasan_1!=null) or $kpi->atasan_1==null))-->
                                   
         <!--                              <a href="{!! route('fe.acc_kpi_2',$kpi->t_kpi_id) !!}" class="btn btn-success btn-sm"  title='Approve - 2' data-toggle='tooltip'> -->
         <!--                           	<i class="fa fa-check"></i>     -->
         <!--                           </a><a href="{!! route('fe.dec_kpi_2',$kpi->t_kpi_id) !!}" class="btn btn-danger btn-sm" title='Tolak - 2' data-toggle='tooltip'> -->
         <!--                           	<i class="fa fa-close"></i>    -->
         <!--                           </a>-->
         <!--                          @endif-->
                      				          
         <!--                          </div>-->
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
                                        <a href="{!! route('fe.lihat_ajuan_2',$data2->t_kpi_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                        <a href="{!! route('fe.edit_ajuan_2',$data2->t_kpi_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    @else
                                        <a href="{!! route('fe.lihat_ajuan_2',$data2->t_kpi_id) !!}" title='Lihat' data-toggle='tooltip'><span class='fa fa-search'></span></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div>-->
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    </div>
    </div>
    </div>
    <!-- /.content-wrapper -->
@endsection
