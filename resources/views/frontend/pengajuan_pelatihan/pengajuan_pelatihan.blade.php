@extends('layouts.app_fe')



@section('content')
<div class="content-wrapper">
@include('flash-message')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<!-- Content Header (Page header) -->

<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">Pengajuan pelatihan</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

			
<?php
$iduser=Auth::user()->id;
					$sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
					where user_id=$iduser";
					$idkar=DB::connection()->select($sqlidkar);
					$id=$idkar[0]->p_karyawan_id;
					//echo $id;die;
					//echo $id;die;
					$bawahan = '';
					$bawahan = (hirarki($idkar[0]->m_jabatan_id,''));
					// echo $bawahan;
					function hirarki($id,$e)
					{
					//echo 'e adalah'.$e.'id adalah '.$id.'<br>';
					$filter_Entitas = '';
		
			
					$sqljabatan="SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
					FROM m_jabatan_atasan a 
					join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
					where m_atasan_id = $id ";
					//echo $sqljabatan;
					$jabatan=DB::connection()->select($sqljabatan);
					$return = array(); 
					//echo 'jallo';
					$e=count($jabatan);
					//$e = $jabatan[0]->count_jabatan;
					// echo '<br>';
					//echo '<br>';
					//print_r($e);
					return $e; 
					// print_r($return);
		
					}   
					if($bawahan){
						
?>
			<li class="nav-item pl-3">
			<a href="{!! route('fe.tambah_pengajuan_pelatihan') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Pengajuan pelatihan</a>
			</li>
			<?php }?>
		</ul>

	</div>
</div>
<div class="card">

	<!-- /.card-header -->
	<div class="card-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Tanggal</th>
				<th>Waktu</th>
				<th>CP</th>
				<th>Lokasi</th>
				<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=0 ?>
			@if(!empty($pengajuan_pelatihan))
			<?php
			foreach ($pengajuan_pelatihan as $kotak) { ?>
			<?php $no++ ?>
			<tr>
				<td><?=$no?></td>
				<td>{!! $kotak->nama_agenda !!}
					@if(!empty($kotak->brosur))
					
					<a href="{!! asset('dist/img/file/'.$kotak->brosur) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
					@endif
				</td>
				<td>{!! $kotak->tgl_awal !!} s/d {!! $kotak->tgl_akhir !!}</td>
				<td>{!! $kotak->waktu_mulai !!} - {!! $kotak->waktu_selesai != '00:00:00' ?$kotak->waktu_selesai:'Selesai'; !!}</td>
				<td>{!! $kotak->cp !!}</td>
				<td>{!! $kotak->lokasi !!}</td>
				<td>{!! $kotak->status==2?'Pending':'Disetujui' !!}</td>

				<td>
					<a href="{!! route('fe.baca_pengajuan_pelatihan',$kotak->t_agenda_perusahaan_id	) !!}" target="_blank" title="Download"><span class="fa fa-search"></span></a>
					<?php if($kotak->status!=1){?>
					<a href="{!! route('fe.edit_pengajuan_pelatihan',$kotak->t_agenda_perusahaan_id	) !!}" target="_blank" title="Download"><span class="fa fa-edit"></span></a>
					<a href="{!! route('fe.hapus_pengajuan_pelatihan',$kotak->t_agenda_perusahaan_id	) !!}" target="_blank" title="Download"><span class="fa fa-trash"></span></a>
					<?php }?>


				</td>



				</td>
			</tr>
			<?php
		} ?>
			@endif
		</table>
	</div>


</div>
@endsection