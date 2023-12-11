@extends('layouts.app_fe')



@section('content')
<style>
    tr td,th th{
        font-size:11px
    }
    tr td:last-child {
   display:flex;
}
</style>
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

<li class="nav-item pl-3">
<a href="<?=route('fe.tambah_karyawan_baru');?>" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah</a>
</li>
</ul>
</div>
</div>
<div class="card">
<div class="card-body">
                <table id="exam" class="table table-bordered table-striped text-nowrap">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>No Pengajuan</th>
                        <th>Tgl Pengajuan</th>
                        <th>Posisi Diajukan</th>
                        <th>Tgl Diperlukan</th>
                        <th>Jumlah Kebutuhan</th>
                        <th>Status </th>
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
                                <td><?=$tkaryawan->nomor_pengajuan;?></td>
                                <td><?=date('Y-m-d',strtotime($tkaryawan->create_date));?></td>
                                <td><?=$tkaryawan->namaposisi;?></td>
                                <td><?=$tkaryawan->tgl_diperlukan;?></td>
                                <td ><b><?=$tkaryawan->jumlah_dibutuhkan;?> Orang</b><br>
                                
                               
                                 </td>
                                <td><?php 
									$edit = false;								   	
                                 if($tkaryawan->status==1)
                               	echo 'Selesai ';
                               	else if($tkaryawan->status==0){
									$edit = true;								   	
								   
                               		echo 'Menunggu Approval Atasan'	;
								   }
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
								   else{
									$edit = true;								   	
								   echo 'Pending'	; 
								   }
                               	?>
                               	<br>
                               	<br>
                               	 <b style="">Informasi Approve</b>
                                   	@if($tkaryawan->karyawan_approve_atasan)
                                     <br> Atasan : <?=$tkaryawan->karyawan_approve_atasan;?> Orang
                                    @endif 
                                    @if($tkaryawan->karyawan_approve_keuangan)
                                    <br> Keuangan : <?=$tkaryawan->karyawan_approve_keuangan;?> Orang
                                    @endif 
                                    @if($tkaryawan->karyawan_approve_direksi)
                                    <br> Direksi : <?=$tkaryawan->karyawan_approve_direksi;?> Orang
                                    @endif 
                                    
                                    <br>
                                    @if($tkaryawan->final_approval and $tkaryawan->karyawan_approve_direksi)
                                    <br>FINAL APPROVE   : <b style="color:red"><?=$tkaryawan->final_approval;?> Orang</b>
                                    @endif  
                               	
                               	</td>
                                 
								<td style="
    display: flex !important;
">
									
									<a href="{!! route('fe.view_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" title='Lihat Detail' class="btn btn-primary" data-toggle='tooltip' style="margin-right: 12px;">
										<i class="fa fa-eye" aria-hidden="true"></i> 
									</a>
									<?php if($edit){?>
										
									<a href="{!! route('fe.edit_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" title='Edit' class="btn btn-primary" data-toggle='tooltip' style="margin-right: 12px;">
										<i class="fa fa-edit" aria-hidden="true"></i> 
									</a>
									<?php }?>
									@if(!in_array($tkaryawan->status,array(0,4)))
									<a href="{!! route('fe.list_database_kandidat',$tkaryawan->t_karyawan_id) !!}" title='Data Kandidat' class="btn btn-primary" data-toggle='tooltip' style="margin-right: 12px;">
										<i class="fa fa-id-badge" aria-hidden="true"></i> 
									</a>
									@endif
									
									</td>
                            </tr>
                            @endforeach
                            @endif
                   </table>
            </div>
            </div>
@endsection