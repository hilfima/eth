@extends('layouts.appsA')



@section('content')
<div class="content-wrapper">
@include('flash-message')
<!-- Content Header (Page header) -->

<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">Kotak Laporan Gratifikasi</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">

				
		</ul>

	</div>
</div>
<div class="card">
<div class="card-header">
			<!--<h3 class="card-title">DataTable with default features</h3>-->
			<a href="{!! route('be.export_gratifikasi') !!}" title='Tambah' data-toggle='tooltip'><span class='fa fa-download'></span>  Export </a>
		</div>
	<div class="card-body">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Kode Gratifikasi</th>
					<th>Tanggal Lapor</th>
					<th>Nama Karyawan</th>
					<th>Entitas</th>
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
				<td><?=$no;?></td>
				<td><?='GRA.'.date('my').'.'.sprintf('%04d',$gratifikasi->t_gratifikasi_id);?></td>
				<td><?=$gratifikasi->tgl_pelaporan;?></td>
				<td><?=$gratifikasi->nama_karyawan.($gratifikasi->status==1?$help->rupiah((int)$gratifikasi->plafon_awal - $gratifikasi->terpakai):'');?></td>
				<td><?=$gratifikasi->nama_entitas;?></td>
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
					<a href="{!! route('be.baca_laporan_gratifikasi',$gratifikasi->t_gratifikasi_id	) !!}" target="_blank" title="Download"><span class="fa fa-search"></span></a>
					<a href="{!! route('be.edit_laporan_gratifikasi',$gratifikasi->t_gratifikasi_id	) !!}" target="_blank" title="Edit"><span class="fa fa-edit"></span></a>
					


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