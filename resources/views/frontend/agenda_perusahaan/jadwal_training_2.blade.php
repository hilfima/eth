@extends('layouts.app_fe')



@section('content')
<div class="content-wrapper">
@include('flash-message')
<!-- Content Header (Page header) -->

<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<div class="card shadow-sm ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">Jadwal Training</h4>


	</div>
</div>
<?php
if (!count($agenda_perusahaan))
	echo '<div class="card p-2">Belum ada Data</div>';
?>
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 mb-3">
		<div class="card shadow-sm ctm-border-radius" style="background: #dff8ff">
			<div class="card-body align-center">
				<h4 class="card-title float-left text-center mb-0 mt-2" >Agenda Yang Akan Datang</h4>


			</div>
		</div>
	</div>
	<?php
	$x=0;
	foreach ($agenda_perusahaan as $data) {
		if ($data->tgl_awal<date('Y-m-d') and $x==0) {
			echo '
			<div class="col-xl-12 col-lg-12 col-md-12 mb-3">
				<div class="card shadow-sm ctm-border-radius" style="background: #dff8ff">
					<div class="card-body align-center">
						<h4 class="card-title text-center float-left mb-0 mt-2" >Agenda Terlaksana</h4>


					</div>
				</div>
			</div>';
			$x++;
		}
	?>

	<div class="col-xl-4 col-lg-4 col-md-4 d-flex">
		<div class="card ctm-border-radius shadow-sm flex-fill" style="background: #b5d9db;">
			<div class="card-header" >
				<p class="card-text text-center  "style="color:#474747;font-weight: 700;">
				<?php
				if(($data->tgl_awal)==($data->tgl_akhir)){
				echo $help->tgl_indo($data->tgl_akhir);
				}else
				if(date('Y-m',strtotime($data->tgl_awal)==date('Y-m',strtotime($data->tgl_akhir)))){
				echo date('d',strtotime($data->tgl_awal)).' - '.date('d',strtotime($data->tgl_akhir)).' '.$help->bulan(date('m',strtotime($data->tgl_akhir))).' '.date('Y',strtotime($data->tgl_akhir));
				}else {
				echo $help->tgl_indo($data->tgl_awal); ?> <?= $data->tgl_awal!= $data->tgl_akhir?' s/d '.$help->tgl_indo($data->tgl_akhir):'';
				}
				?>
				
				</p>
			</div>
			<div class="card-body ">
				<h4 class="card-title mb-0  text-center "style="color:#474747;font-weight: 700;"><?= ucwords($data->nama_agenda); ?></h4>
				
				<p class="card-text">Lokasi : <?= $data->lokasi; ?></p>
				<p class="card-text">Narahubung : <?= $data->cp; ?></p>
				<p class="card-text">Deskripsi :<?= $data->deskripsi; ?> </p>

				<div class="mt-2">

				<button onlick="konfirmasi_kejadiran(<?=$data->t_agenda_perusahaan_id?>,<?=$data->t_agenda_perusahaan_karyawan_id?>)" class="btn btn-success ctm-border-radius text-white float-right text-white">Konfirmasi Kehadiran</button>
				<button href="{!!route('fe.konfirmasi_kehadiran',$data->t_agenda_perusahaan_id)!!}" class="btn btn-success ctm-border-radius text-white float-right text-white">Get Barcode </button>
				</div>
			</div>
		</div>
	</div>
	<?php
} ?>

</div>
	<script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
        <script type="text/javascript">
            function submit_konfirmasi(id_agenda,id_karyawan) {
               alert();

            }

           
</script>
@endsection