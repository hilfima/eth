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
<h4 class="card-title float-left mb-0 mt-2">List Database Kandidat</h4>
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
					<th>Nama</th>
					

					<th>No Wa</th>
					<th>Email</th>
					<th>File</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no =0;


			?>
			@if(!empty($cv))
			@foreach($cv as $cv)
			<?php $no++; ?>
			<tr>
				<td><?=$no; ?></td>

				<td><?=$cv->nama; ?></td>
				
				<td><?=$cv->wa; ?></td>
				<td><?=$cv->email; ?></td>
				<td style="">
					@if(!empty($cv->file))
					CV:
					<a href="{!! asset('dist/img/file/'.$cv->file) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
					@endif
					<?php if($cv->file_interview1 ){?>
					<br>
					Hasil Interview 1 :
					<a href="{!! asset('dist/img/file/'.$cv->file_interview1) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
					<br>
					<?php }?>
					<?php if($cv->file_interview2 ){?>
					Hasil Interview 2 :
					<a href="{!! asset('dist/img/file/'.$cv->file_interview2) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
					<br>
					<?php }?>
					<?php if($cv->file_psikogram ){?>
					Hasil Psikogram :
					<a href="{!! asset('dist/img/file/'.$cv->file_psikogram) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
					
					<?php }?>
					</td>
				

				<td>
					<?php
					if($cv->status==0){
					echo 'Konfirmasi Dari Atasan';
					}else if($cv->status==1){
					echo 'Pengisian Form Kandidat';
					} else if($cv->status==2){
					echo 'Kandidat Telah Mengisi Form Kandidat -> Menunggu Konfirmasi Panggilan Interview';
					}  else if($cv->status==3){
					echo 'Tahap Interview';
					} else if($cv->status==4){
					echo 'Tahap Review Pengaju';
					} else if($cv->status==5){
					echo 'Tahap Penerimaan Pihak HC';
					}else if($cv->status==6){
					echo 'Tahap Offering Letter';
					}else if($cv->status==7){
					echo 'Tahap Kontrak Kerja -> Migrasi Karyawan';
					}else if($cv->status==8){
					echo 'Telah Menjadi Karyawan';
					} else if ($cv->status==10) {
					echo 'Tidak Direkomendasikan Karyawan Pengaju dan Atasan';
					}else if ($cv->status==11) {
					echo 'Tidak Direkomendasikan Ke Tahap Interview';
					}else if ($cv->status==12) {
					echo 'Interview: Tidak Direkomendasikan Pihak HC';
					}else if ($cv->status==13) {
					echo 'Review Pengaju: Tidak Direkomendasikan';
					}else if ($cv->status==14) {
					echo 'Penerimaan: Tidak Dilanjutkan';
					}else if ($cv->status==15) {
					echo 'Offering Letter: Tidak Dilanjutkan';
					}else if ($cv->status==16) {
					echo 'Kontrak Kerja: Tidak Dilanjutkan';
					}?><br><br>
					<?php if($cv->status>=4 and $cv->status<10  ){
					echo 'Suggest HC:';
					if($cv->rekomendasi_hr==1){
					echo 'Disarankan';
					} else if($cv->rekomendasi_hr==2){
					echo 'Dapat Dipertimbangkan';
					}  else if($cv->rekomendasi_hr==3){
					echo 'Tidak Disarankan';
					}
					echo '<br>'.$cv->keterangan_hr;
					}?>
				</td>


				<td>
					<?php
					if ($cv->status==0) { ?>
					<a href="{!! route('fe.approve_kandidat',[$id,$cv->t_karyawan_kandidat_id]) !!}" title='Approve Kandidat' data-toggle='tooltip' class="btn btn-primary btn-sm"> 
						<i class="fa fa-check" aria-hidden="true"></i> Setujui
					</a>
					<a href="{!! route('fe.decline_kandidat',[$id,$cv->t_karyawan_kandidat_id]  ) !!}" title='Tolak Kandidat' data-toggle='tooltip' class="btn btn-primary btn-sm">
						<i class="fa fa-times" aria-hidden="true"></i> Tolak
					</a>
					<?php } if($cv->status==3  ){?>
						<a href="{!! route('fe.upload_interview',[$id,$cv->t_karyawan_kandidat_id]) !!}" title='Lolos Tahap Review' data-toggle='tooltip' class="btn btn-primary btn-sm">
							<i class="fa fa-upload" aria-hidden="true"></i> Upload File Interview
						</a>
					
					<?php } if($cv->status==4  ){?>
					<a href="{!! route('fe.approve_review',[$id,$cv->t_karyawan_kandidat_id]) !!}" title='Lolos Tahap Review' data-toggle='tooltip' >
						<i class="fa fa-check" aria-hidden="true"></i>
					</a>
					<a href="{!! route('fe.decline_review',[$id,$cv->t_karyawan_kandidat_id]  ) !!}" title='Gugur Tahap Review' data-toggle='tooltip'>
						<i class="fa fa-times" aria-hidden="true"></i>
					</a>
					<?php } ?>
				</td>
			</tr>
			@endforeach
			@endif
		</table>
	</div>
</div>
@endsection