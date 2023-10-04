@extends('layouts.app_fe')



@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

<style>
    .trr {
        background-color: #0099FF;
        color: #ffffff;
        align: center;
        padding: 10px;
        height: 20px;
    }

    tr.odd>td {
        background-color: #E3F2FD;
    }

    tr.even>td {
        background-color: #BBDEFB;
    }

    .fixedTable .table {
        background-color: white;
        width: auto;
        display: table;
    }

    .fixedTable .table tr td,
    .fixedTable .table tr th {
        min-width: 100px;
        width: 100px;
        min-height: 20px;
        height: 20px;
        padding: 5px;
        max-width: 100px;
    }

    .fixedTable-header {
        width: 100%;
        height: 60px;
        /*margin-left: 150px;*/
        overflow: hidden;
        border-bottom: 1px solid #CCC;
    }

    .fixedTable-sidebar {
        width: 0;
        height: 510px;
        float: left;
        overflow: hidden;
        border-right: 1px solid #CCC;
    }

    @media screen and (max-height: 700px) {
        .fixedTable-body {
            overflow: auto;
            width: 100%;
            height: 410px;
            float: left;
        }
    }

    @media screen and (min-height: 700px) {
        .fixedTable-body {
            overflow: auto;
            width: 100%;
            height: 510px;
            float: left;
        }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <form action="<?= route('fe.lihat_slip'); ?>" method="get">
                <div class="form-group">
                    <label>Periode Absen</label>
                    <select class="form-control select2" name="periode_gajian" style="width: 100%;" required>
                        <option value="">Pilih Periode</option>
                        <?php
                        $udah = array();
                        foreach ($periode as $periode) {
                            if (!in_array($periode->prl_generate_id, $udah)) {
                                $selected = '';
                                if ($periode->prl_generate_id == $id_prl) {
                                    $selected = 'selected';
                                }
                                if ($periode->tipe == 'Bulanan') {
                                    echo '<option value="' . $periode->prl_generate_id . '" ' . $selected . '>' . $help->bulan($periode->bulan) . ' - ' . $periode->tahun . '</option>';
                                } else if ($periode->tipe == 'Pekanan') {
                                    echo '<option value="' . $periode->prl_generate_id . '" ' . $selected . '>' . $help->bulan($periode->bulan) . ' - ' . $periode->tahun . ' (Pekanan ' . $periode->pekanan_ke . ')</option>';
                                } else
                                    echo '<option value="' . $periode->prl_generate_id . '" ' . $selected . '>' . $periode->bulan . ' - ' . $periode->tahun . ' - ' . $periode->tipe . ' - ' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . '</option>';
                            }
                            $udah[] = $periode->prl_generate_id;
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>

                </div>
        </div>
    </div>
    @if(!empty($generate))
    <div class="card shadow-sm ctm-border-radius">
        <div class="card-body align-center">
            <h4 class="card-title float-left mb-0 mt-2">Slip Gaji</h4>
            <ul class="nav nav-tabs float-right border-0 tab-list-emp">

                <li class="nav-item pl-3">
                    <a href="{!! route('fe.tambah_chat',['','key=Klarifikasi Gaji Bulan '.$help->bulan($generate[0]->bulan).' '.($generate[0]->tahun)]) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">Klarifikasi Gaji</a>
                    <button type="submit" name="Cari" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding" value="PDF"><span class="fa fa-search"></span> PDF</button>
                    <!-- 
				<a href="{!! route('fe.lihat_slip',[$id_prl,'Cari=PDF']) !!}" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding">PDF</a>-->
                </li>
            </ul>

        </div>
    </div>
    </form>
    <!-- /.content-header -->

    <!-- Main content -->

    <!-- /.card-body -->

    <div class="content container-fluid">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <?php
                    $view = 'slip';
                    echo $view;;
                    ?>
                    <?= view('frontend.slip.pdf_slip',compact('karyawan','help','generate','id_prl','id_kary','view'));?>

                </div>

                <!-- /Page Content -->
            </div>
        </div>
        <!-- /.card -->
        <!-- /.card -->
        <!-- /.content -->
    </div>
    @endif
    <!-- /.content-wrapper -->
    @endsection