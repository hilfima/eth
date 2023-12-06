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
            <form id="cari_absen" class="form-horizontal" method="get" action="{!!route('fe.approval_lintas_mesin_absen')!!}">
                    {{ csrf_field() }}
                    <div class="row">
                        
                        
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
                        <th>Tanggal</th>
                        <th>Detail</th>
                        
                        
                        
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no=0 ?>
                    @if(!empty($list))
                        @foreach($list as $data)
                            <?php $no++;
                            
                            ?>
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data->nama !!}</td>
                                
                                <td>{!! date("Y-m-d",strtotime($data->date_time)) !!}
                                
                                </td>
                                
                                <td>
									Seharusnya : <?=$data->m_s;?><br>
									Absen : <?=$data->m_a;?><br>
                                    
								</td>
                                    
								
                                  <td>
                                  	<a href="{!! route('fe.setujui_lintas',$data->absen_log_id) !!}" class="btn btn-success btn-sm"  title='Approve' data-toggle='tooltip'> 
                                    	Approve  
                                    </a><a href="{!! route('fe.tolak_lintas',$data->absen_log_id) !!}" class="btn btn-danger btn-sm" title='Tolak' data-toggle='tooltip'> 
                                    	Tolak    
                                    </a> 
                                  </td>
                               
                                
                               
                                
                                
                                
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <!-- /.card-body -->
        </div> 
       
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
