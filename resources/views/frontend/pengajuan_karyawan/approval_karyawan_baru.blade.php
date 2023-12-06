 @extends('layouts.app_fe')



@section('content')
					<!-- Page Title -->
					 <div class="content-wrapper">
					 <div class="row">
			<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
						<?= view('layouts.app_side');?>
					</div>
<div class="col-xl-9 col-lg-8 col-md-12">
    @include('flash-message')
   
    
	<!-- Main content -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Pengajuan Karyawan Baru</h4>
<ul class="nav nav-tabs float-right border-0 tab-list-emp">

</ul>
</div>
</div>
<div class="card">
<div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Posisi Diajukan</th>
                        <th>Tanggal Diperlukan</th>
                        <th>Jumlah Kebutuhan</th>
                        <th>Status Approval</th>
                        <th>Status Ajuan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no =0;
                   
                    
                    ?>
                    		@if(!empty($tkaryawan))
                    		@foreach($tkaryawan as $tkaryawan)
                    <?php $no++;?>
                    		<tr>
                                <td><?=$no;?></td>
                                <td><?=$tkaryawan->namaposisi;?></td>
                                <td><?=$tkaryawan->tgl_diperlukan;?></td>
                                <td><?=$tkaryawan->jumlah_dibutuhkan;?></td>
                                 <td>
									
										@if($tkaryawan->appr_status==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($tkaryawan->appr_status==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
								</td>
                                <td><?php 
                               if($tkaryawan->status==1)
                               	echo 'Selesai ';
                               	else if($tkaryawan->status==0)
                               		echo 'Menunggu Approval Atasan'	;
                               	else if($tkaryawan->status==5)
                               		echo 'Menunggu Approval Keuangan'	;
                               	else if($tkaryawan->status==6)
                               		echo 'Menunggu Approval Direksi'	;
								else if($tkaryawan->status==2)
								   echo 'Diproses HC';	
                               	else if($tkaryawan->status==3)
                               		echo 'Proses Interview';	
                               	else if($tkaryawan->status==41)
                               	echo 'Ditolak Atasan';	
                               	else if($tkaryawan->status==42)
                               	echo 'Ditolak Keuangan';
                               	else if($tkaryawan->status==44)
                               	echo 'Ditolak Direksi';
                               	else if($tkaryawan->status==4)
                               	echo 'Ditolak HC';
								   else
								   echo 'Pending'	; 
                               	?></td>
								<td>
									
									<a href="{!! route('fe.view_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" title='Upload' class="btn btn-primary" data-toggle='tooltip'>
										<i class="fa fa-eye" aria-hidden="true"></i> Lihat Detail
									</a>
									@if(!in_array($tkaryawan->status,array(0,4)))
									<a href="{!! route('fe.list_database_kandidat',$tkaryawan->t_karyawan_id) !!}" title='Upload' class="btn btn-primary" data-toggle='tooltip'>
										<i class="fa fa-id-badge" aria-hidden="true"></i> Data Kandidat
									</a>
									@endif
									@if(($tkaryawan->status==0 ) )
									@if($tkaryawan->appr ==$id)
									 <a href="{!! route('fe.acc_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" class="btn btn-success btn-sm"  title='Approve - 1 ' data-toggle='tooltip'> 
                                    	Approve  (Atasan) 
                                    </a><a href="{!! route('fe.dec_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" class="btn btn-danger btn-sm" title='Tolak - 1' data-toggle='tooltip'> 
                                    	Tolak    (Atasan) 
                                    </a> 
                                    @endif
                                    @endif
                                    
									@if($tkaryawan->appr_direksi ==$id and $tkaryawan->status==6)
								
                                    <a href="{!! route('fe.acc_karyawan_baru2',$tkaryawan->t_karyawan_id) !!}" class="btn btn-success btn-sm"  title='Approve - 2 ' data-toggle='tooltip'> 
                                    	Approve (Direksi) 
                                    </a><a href="{!! route('fe.dec_karyawan_baru2',$tkaryawan->t_karyawan_id) !!}" class="btn btn-danger btn-sm" title='Tolak - 2' data-toggle='tooltip'> 
                                    	Tolak  (Direksi)   
                                    </a> 
                                    
                                    @elseif($tkaryawan->status==6)
                                    Menunggu Approval Keuangan & Atasan
                                   
                                    @endif
									</td>
									<td>
										
									</td>
                            </tr>
                            @endforeach
                            @endif
                   </table>
            </div>
            </div>
@endsection