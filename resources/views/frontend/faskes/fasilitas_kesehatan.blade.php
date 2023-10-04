@extends('layouts.app_fe')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content ">
	@include('flash-message')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

	<!-- Content Header (Page header) -->
	<div class="card shadow-sm ctm-border-radius">
		<div class="card-body align-center">
			<h4 class="card-title float-left mb-0 mt-2">Fasilitas Kesehatan</h4>
			<ul class="nav nav-tabs float-right border-0 tab-list-emp">
<?php if( ($faskes['nominal']) >0){?>
<li class="nav-item pl-3">

<a href="{!!route('fe.pengajuan_faskes')!!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">+ Pengajuan</a>
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
                    <?= $help->rupiah($faskes['nominal']) ?></span>
				</p>
			</div>
		</div>
	</div>
	</div>
	<div class="col-md-6">
	<div class="card dash-widget ctm-border-radius shadow-sm">
		<div class="card-body">
			<div class="card-icon bg-primary">
				<i class="fa fa-users" aria-hidden="true"></i>
			</div>
			<div class="card-right">
				<h4 class="card-title">Plafon Selanjutnya</h4>
				<p class="card-text"> <span class="info-box-number"><?=isset($m_faskes[0])?$help->tgl_indo($m_faskes[0]->generate_add_date):''?></span>
				</p>
			</div>
		</div>
	</div>
	</div>
	</div>
	<div class="card">
		<div class="card-header">
		</div>
		<div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Foto</th>
                        <th>Status Pengajuan</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Nominal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?= $faskes['kontent'] ?>
                    </tbody>
                    
                </table>
            </div>
	</div>
</div>

	@endsection