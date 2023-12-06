@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<!-- Content Wrapper. Contains page content -->
<div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Form Penilaian KPI</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

			
			</ul>

        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="card">
       <Style>
           tr td:first-child, tr td:last-child {
              text-align: left;
            }
       </Style>
        <!-- /.card-header -->
         <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_kpi_penilaian',[$kpi->t_kpi_penilaian_karyawan_id]) !!}" enctype="multipart/form-data">
                 {{ csrf_field() }}
        <div class="card-header">
            <table style="text-align:left">
                <tr>
                    <td>Nama Karyawan</td>
                    <td>:</td>
                    <td><?=$kpi->nama;?></td>
                </tr>
                <tr>
                    <td>Periode KPI</td>
                    <td>:</td>
                    <td><?php 
                        if($kpi->periode_kpi==1){
                            echo 'Costum';
                        }else if($kpi->periode_kpi==2){
                            echo 'Bulanan';
                        }else if($kpi->periode_kpi==3){
                            echo 'Triwulan';
                        }else if($kpi->periode_kpi==4){
                            echo 'Tahunan';
                        }
                        
                        echo $kpi->tanggal_awal.' s/d '.$kpi->tanggal_akhir;
                        ?></td>
                </tr>
                <tr>
                    <td>Jenis Penilaian </td>
                    <td>:</td>
                    <td>{!! $kpi->nama_penilaian !!}</td>
                </tr>
            </table>
        </div>
        <div class="card-body">
           

            <table id="example1" class="table table-bordered table-striped">
                 @if(!empty($kpi))
                 <?php 
                 foreach($content as $key=>$value){
                 $content_key = explode('||',$key);
                 $i=0;
                 foreach($content[$key] as $key2=>$value2){
                 ?>
                   <tr>
                       <?php if($i==0){?>
                       <td rowspan="<?=count($content[$key]);?>"><strong><b><?=$content_key[0]?></b></strong><br><?=$content_key[1]?></td>
                       <?php }?>
                       <td ><input type="radio" value="<?=$key2?>" name="penilaian[<?=$content_key[2]?>]" <?=isset($penilaian[$content_key[2]])?($key2==($penilaian[$content_key[2]])?'checked disabled':'disabled'):'disabled'?>></td>
                       <td style="text-align:left"><b><?=$value2[0]?></b><br><?=$value2[1]?></td>
                   </tr>
                   
                 <?php
                 $i++;
                 }
                 }
                 ?>
                @endif
                </table>
        </div> 
        <div class="p-3">
            <?php if($kpi->penilaian==1){?>
            <button type="submit" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person "><span class="fa fa-check"></span> Simpan</button>
            <?php }?>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection