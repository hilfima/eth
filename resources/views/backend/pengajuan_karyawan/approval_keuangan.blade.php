 @extends('layouts.appsA')



@section('content')
					<!-- Page Title -->
					 <div class="content-wrapper">
					
    @include('flash-message')
   
    
	<!-- Main content -->
	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Pengajuan Karyawan Baru : Approval Keuangan</h4>
<ul class="nav nav-tabs float-right border-0 tab-list-emp">

<li class="nav-item pl-3">
<a href="<?=route('fe.tambah_karyawan_baru');?>" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah</a>
</li>
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
                                <td>Total Diajukan : <b><?=$tkaryawan->jumlah_dibutuhkan;?> Orang</b><br>
                                <br>Approve Atasan : <?=$tkaryawan->karyawan_approve_atasan;?> Orang
                                <br>Approve Direksi : <?=$tkaryawan->karyawan_approve_direksi;?> Orang
                                <br>Approve Keuangan : <?=$tkaryawan->karyawan_approve_keuangan;?> Orang
                                <br>
                                <br>FINAL APPROVE   : <b style="color:red"><?=$tkaryawan->final_approval;?> Orang</b>
                                    
                                </td>
                                 <td>
									@if(in_array($tkaryawan->status,array(5,6,2,3,42,44,4)))
										@if($tkaryawan->appr_keuangan_status==1)
                                        <span class="fa fa-check-circle"> Disetujui</span>
                                        @elseif($tkaryawan->appr_keuangan_status==2)
                                            <span class="fa fa-window-close"> Ditolak</span>
                                    @else
                                        <span class="fa fa-edit"> Pending</span>
                                    @endif
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
									
									
									<!--@if(!in_array($tkaryawan->status,array(0,4,5)))-->
									<!--<a href="{!! route('be.list_database_kandidat',$tkaryawan->t_karyawan_id) !!}" title='Upload' class="btn btn-primary" data-toggle='tooltip'>-->
									<!--	<i class="fa fa-id-badge" aria-hidden="true"></i> Data Kandidat-->
									<!--</a>-->
									<!--@endif-->
									@if($tkaryawan->status==5)
									<a href="{!! route('be.approval_karyawan_baru_keuangan',$tkaryawan->t_karyawan_id) !!}" title='Upload' class="btn btn-primary" data-toggle='tooltip'>
										<i class="fa fa-eye" aria-hidden="true"></i> Lihat Detail & Edit Approval
									</a>
									
									 <a href="{!! route('be.acc_keuangan_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" class="btn btn-success btn-sm"  title='Approve - Keuangan ' data-toggle='tooltip'> 
                                    	Approve  
                                    </a><a href="{!! route('be.dec_keuangan_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" class="btn btn-danger btn-sm" title='Tolak - keuangan' data-toggle='tooltip'> 
                                    	Tolak    
                                    </a> 
                                    @else
                                    <a href="{!! route('be.view_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" title='Upload' class="btn btn-primary" data-toggle='tooltip'>
										<i class="fa fa-eye" aria-hidden="true"></i> Lihat Detail 
									</a>
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