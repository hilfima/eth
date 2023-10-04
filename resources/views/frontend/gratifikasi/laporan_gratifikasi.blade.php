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
		<h4 class="card-title float-left mb-0 mt-2">Kotak Laporan Gratifikasi</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<?php if((int)$gratifikasi_sisa_plafon[0]->plafon_awal - $gratifikasi_sisa_plafon[0]->terpakai>0){ ?>
			<li class="nav-item pl-3">
			<a href="{!! route('fe.tambah_laporan_gratifikasi') !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Tambah Kotak Laporan Gratifikasi</a>
			</li>
			<?php }?>
		</ul>

	</div>
</div>
<div class="row">
	<div class="col-md-6">
		
	<div class="card dash-widget ctm-border-radius shadow-sm">
		<div class="card-body">
			<div class="card-icon bg-primary">
				<i class="fa fa-users" aria-hidden="true"></i>
			</div>
			<div class="card-right">
				<h4 class="card-title">Sisa Plafon</h4>
				<p class="card-text"> <span class="info-box-number">
                    <?= $help->rupiah((int)$gratifikasi_sisa_plafon[0]->plafon_awal - $gratifikasi_sisa_plafon[0]->terpakai) ?></span>
				</p>
			</div>
		</div>
	</div>
	</div><div class="col-md-6">
		
	<div class="card dash-widget ctm-border-radius shadow-sm">
		<div class="card-body">
			<div class="card-icon bg-primary">
				<i class="fa fa-users" aria-hidden="true"></i>
			</div>
			<div class="card-right">
				<h4 class="card-title">Terpakai</h4>
				<p class="card-text"> <span class="info-box-number">
                    <?= $help->rupiah($gratifikasi_sisa_plafon[0]->terpakai) ?></span>
				</p>
			</div>
		</div>
	</div>
	</div>
	</div>
<div class="card">

	<!-- /.card-header -->
	<div class="card-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Kode Gratifikasi</th>
					<th>Tanggal Terima</th>
					<th>Nama lembaga/perusahaan/nama pemberi</th>
					<th>Nama Barang</th>
					<th>Perkiraan Harga</th>
					<th>Bukti</th>
					<th>Status</th>

					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=0 ?>
			@if(!empty($gratifikasi))
			<?php
			foreach ($gratifikasi as $gratifikasi) { ?>
			<?php $no++ ?>
			<tr>
				<td><?=$no?></td>
				<td><?='GRA.'.date('my').'.'.sprintf('%04d',$gratifikasi->t_gratifikasi_id);?></td>
				<td><?=$gratifikasi->tgl_diterima;?></td>
				<td><?=$gratifikasi->dari;?></td>
				<td><?=$gratifikasi->nama_tipe_pemberian;?></td>
				<td><?=$help->rupiah($gratifikasi->perkiraan_harga);?></td>
				<td> @if(!empty($gratifikasi->bukti))
                                   <a href="{!! asset('dist/img/file/'.$gratifikasi->bukti) !!}" target="_blank" title="Download"><span class="fa fa-download"></span></a>
                                   @endif</td>
                                   <td><?php
					if($gratifikasi->status==1) 
						echo 'Pending';
					else if($gratifikasi->status==2) 
						echo 'Milik Karyawan';
					else if($gratifikasi->status==3) 
						echo 'Dikembalikan kepada perusahaan';
					else if($gratifikasi->status==4) 
						echo 'Karyawan sudah mengembalikan';
					else if($gratifikasi->status==5) 
						echo 'Karyawan mengkonfirmasi milik pribadi';
				?></td>

				<td>
					<a href="{!! route('fe.baca_laporan_gratifikasi',$gratifikasi->t_gratifikasi_id	) !!}"  title="Download"><span class="fa fa-search"></span></a>
					@if($gratifikasi->status==1)
					<a href="{!! route('fe.hapus_laporan_gratifikasi',$gratifikasi->t_gratifikasi_id	) !!}" title="Hapus"><span class="fa fa-trash"></span></a>
					@elseif($gratifikasi->status==3)
					<a href="{!! route('fe.kembalikan_laporan_gratifikasi',$gratifikasi->t_gratifikasi_id	) !!}"  title="Sudah dikembalikan"><span class="fa fa-check"></span></a>
					@elseif($gratifikasi->status==2)
					<a href="{!! route('fe.konfirmasi_laporan_gratifikasi',$gratifikasi->t_gratifikasi_id	) !!}"  title="Konfirmasi" aria-hidden="true"><span class="fa check-circle fa-check-circle "></span></a>
					@endif

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