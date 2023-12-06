 @extends('layouts.appsA')



@section('content')
					<!-- Page Title -->
					 <div class="content-wrapper">
					
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
               <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Karyawan Pengaju</th>
                        <th>Posisi Diajukan</th>
                        <th>Tanggal Diperlukan</th>
                        <th>Status Ajuan</th>
                        
                        <th>Jumlah Kebutuhan</th>
                        <th>Lokasi</th>
                        <th>Departemen</th>
                        <th>Level</th>
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
                                <td><?=$tkaryawan->create_date;?></td>
                                <td><?=$tkaryawan->nama;?></td>
                                <td><?=$tkaryawan->m_jabatan_id==-1?($tkaryawan->posisi):$tkaryawan->namaposisi;?></td>
                                <td><?=$tkaryawan->tgl_diperlukan;?></td>
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
                               	else if($tkaryawan->status==15)
                               		echo 'Hold Ajuan HC';
								   else
								   echo 'Pending'	; 
                               	?></td>
                               	</td>
                                
                                <td>Total Diajukan : <b><?=$tkaryawan->jumlah_dibutuhkan;?> Orang</b><br>
                                <br>Approve Atasan : <?=$tkaryawan->karyawan_approve_atasan;?> Orang
                                <br>Approve Direksi : <?=$tkaryawan->karyawan_approve_direksi;?> Orang
                                <br>Approve Keuangan : <?=$tkaryawan->karyawan_approve_keuangan;?> Orang
                                <br>
                                <br>FINAL APPROVE   : <b style="color:red"><?=$tkaryawan->final_approval;?> Orang</b>
                                    
                                </td>
                                <td><?=$tkaryawan->nmlokasi;?></td>
                                <td><?=$tkaryawan->nmdept;?></td>
                                <td><?=$tkaryawan->nmlevel;?></td>
                                <td>
                                <?php if($tkaryawan->status==2 or $tkaryawan->status==15){?>
                                	 <a href="{!! route('be.edit_pengajuan_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-eye'></span></a>
                                <?php }?>
                                	 <a href="{!! route('be.hapus_karyawan_baru',$tkaryawan->t_karyawan_id) !!}" title='Print' data-toggle='tooltip'><span class='fa fa-trash'></span></a>
                                 </td>
                            </tr>
                            @endforeach
                            @endif
                   </table>
            </div>
            </div>
@endsection