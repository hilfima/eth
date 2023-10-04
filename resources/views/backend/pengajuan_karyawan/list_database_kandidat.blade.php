 @extends('layouts.appsA')



@section('content')
<!-- Page Title -->
<div class="content-wrapper">

@include('flash-message')


<!-- Main content -->
<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">List Database Kandidiat </h4>
		
			<ul class="nav nav-tabs float-right border-0 tab-list-emp">

				<li class="nav-item pl-3">
					<a href="<?=route('be.tambah_kandidat',$id);?>" class="btn btn-primary button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding" style="border-radius: 10%">Tambah</a>
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
					<th>Nama</th>
					<th>Kode</th>

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
				<td style="color: red"><?= $cv->kode?'https://hcms.mafazaperforma.com/form_biodata_kandidat?kode='.$cv->kode:''; ?></td>
				<td><?=$cv->wa; ?></td>
				<td><?=$cv->email; ?></td>
				<td style="">
				@if(!empty($cv->file))
					CV:				<a href="{!! asset('dist/img/file/'.$cv->file) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
				@endif
				<?php if($cv->status>=2 and $cv->status<10  ){?>
					<br>
					Form :
					<a href="{!! route('view_form_biodata_kandidat',$cv->t_karyawan_kandidat_id) !!}" target="_blank" title="Download"><span class="fa fa-search"></span></a>
					<?php }?>
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
					<?php }
					
					if($cv->file_offfering_letter){?>
					<br>Surat Offering Letter :
					<a href="{!! asset('dist/img/file/'.$cv->file_offfering_letter) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
					<?php }
					
					if($cv->file_kontrak_kerja){?>
					<br>Kontrak Kerja :
					<a href="{!! asset('dist/img/file/'.$cv->file_kontrak_kerja) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
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
						  echo 'Tahap Offering Leter';
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
						  }
					?><br><br>
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
					
				
				
				<td>
					<?php
					if($cv->status==2 ){?>
					<a href="{!! route('be.kandidat_to_interview_acc',[$cv->t_karyawan_id,$cv->t_karyawan_kandidat_id]) !!}" title='Lolos Ke tahap Interview' data-toggle='tooltip'>
						<i class="fa fa-check" aria-hidden="true"></i>
					</a>
					<a href="{!! route('be.kandidat_to_interview_dec',[$cv->t_karyawan_id,$cv->t_karyawan_kandidat_id]) !!}" title='Tidak Dirokemdasikan' data-toggle='tooltip'>
						<i class="fa fa-times" aria-hidden="true"></i>
					</a>
					<?php }else if($cv->status==3 ){?>
					<a href="{!! route('be.kandidat_interview',[$cv->t_karyawan_id,$cv->t_karyawan_kandidat_id]) !!}" title='Upload file Interview' data-toggle='tooltip'>
						<i class="fa fa-upload" aria-hidden="true"></i>
					</a>
					
					<?php }else if($cv->status==6 ){?>
					<a href="{!! route('be.kandidat_offering_letter',[$cv->t_karyawan_id,$cv->t_karyawan_kandidat_id]) !!}" title='Offering Letter' data-toggle='tooltip'>
						<i class="fa fa-upload" aria-hidden="true"></i>
					</a>
					
					<?php }else if($cv->status==7 ){?>
					<a href="{!! route('be.kandidat_kontrak_kerja',[$cv->t_karyawan_id,$cv->t_karyawan_kandidat_id]) !!}" title='Offering Letter' data-toggle='tooltip'>
						<i class="fa fa-upload" aria-hidden="true"></i>
					</a>
					
					<?php }else if($cv->status==5 ){?>
					<a href="{!! route('be.kandidat_penerimaan_acc',[$cv->t_karyawan_id,$cv->t_karyawan_kandidat_id]) !!}" title='Diterima' data-toggle='tooltip'>
						<i class="fa fa-check" aria-hidden="true"></i>
					</a>
					<a href="{!! route('be.kandidat_penerimaan_dec',[$cv->t_karyawan_id,$cv->t_karyawan_kandidat_id]) !!}" title='Ditolak' data-toggle='tooltip'>
						<i class="fa fa-times" aria-hidden="true"></i>
					</a>
					
					<?php }?>
					<a href="{!! route('be.delete_kandidat',[$cv->t_karyawan_id,$cv->t_karyawan_kandidat_id]) !!}" title='Hapus' data-toggle='tooltip'>
						<i class="fa fa-trash" aria-hidden="true"></i>
					</a>
				</td>
			</tr>
			@endforeach
			@endif
		</table>
	</div>
</div>
@endsection