@extends('layouts.appsA')



@section('content')
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
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Rekap Slip</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                        <li class="breadcrumb-item active">Rekap Slip</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.slip_gaji') !!}">
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Periode Generate</label>
                            <select class="form-control select2" name="prl_generate" style="width: 100%;" required>
                                <option value="">Pilih Periode Generate</option>
                                <?php
                                foreach ($periode as $periode) {
                                    if ($periode->prl_generate_id == $id_prl) {
                                        echo '<option  value="' . $periode->prl_generate_id . '" selected>' . $periode->tipe . ' - Periode: ' . $periode->tahun_gener . ' Bulan: ' . $periode->bulan_gener . ' | Absen:' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . ' | Lembur:' . $periode->tgl_awal_lembur . ' - ' . $periode->tgl_akhir_lembur . '</option>';
                                    } else {
                                        echo '<option  value="' . $periode->prl_generate_id . '">' . $periode->tipe . ' - Periode: ' . $periode->tahun_gener . ' Bulan: ' . $periode->bulan_gener . ' | Absen:' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . ' | Lembur:' . $periode->tgl_awal_lembur . ' - ' . $periode->tgl_akhir_lembur . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Karyawan</label>
                            <select class="form-control select2" name="p_karyawan" style="width: 100%;" required>
                                <option value="">Pilih Karyawan</option>
                                <?php
                                foreach ($list_karyawan as $users) {
                                    if ($users->p_karyawan_id == $request->get('p_karyawan')) {
                                        echo '<option selected="selected" value="' . $users->p_karyawan_id . '">' . $users->nama . '  ' . '</option>';
                                    } else {
                                        echo '<option value="' . $users->p_karyawan_id . '">' . $users->nama . '  ' . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-9">
                        <a href="{!! route('be.slip_gaji') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                        <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>

                    </div>

                </div>
            </div>

        </div>
        <div class="card-body d-none">
            <div class="row">
                <div class="col-sm-12">


                </div>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="content container-fluid">
            <?php if (!empty($request->get('prl_generate') and $request->get('p_karyawan'))) { ?>

                <!-- Page Title -->
                <div class="row">


                    <div class="col-sm-5 col-4">
                        <h4 class="page-title">Slip Gaji</h4>
                    </div>
                    <div class="col-sm-7 col-8 text-right m-b-30">
                        <div class="btn-group btn-group-sm">
                            <button type="submit" name="Cari" class="btn btn-white" value="PDF"><span class="fa fa-search"></span> PDF</button>

                        </div>

                    </div>
                </div>

                <!-- /Page Title -->

                <div class="row">

                    <div class="col-md-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php
                                    if ($karyawan[0]->m_lokasi_id == 3) {
                                        $logo = 'Logo Rea Arta Mulia.png';
                                    } else if ($karyawan[0]->m_lokasi_id == 4) {
                                        $logo = 'Logo EMM_Page12.png';
                                    } else if ($karyawan[0]->m_lokasi_id == 5) {
                                        $logo = 'cc.png';
                                    } else if ($karyawan[0]->m_lokasi_id == 2) {
                                        $logo = 'Logo SJP Guideline.png';
                                    } else  if ($karyawan[0]->m_lokasi_id == 9) {
                                        $logo = 'Logo ASA.png';
                                    } else if ($karyawan[0]->m_lokasi_id == 13) {
                                        $logo = 'Logo Mafaza Hires.png';
                                    } else if ($karyawan[0]->m_lokasi_id == 6) {
                                        $logo = 'JKA LOGO.png';
                                    } else
                                        $logo = 'logo.png';


                                    ?>
                                    <img src="<?= url('dist\img\logo/' . $logo); ?>" style="height:70px;margin-left:5%;vertical-align:middle" alt="">
                                </div>
                                <div class="col-sm-10 text-center">
                                    <div style="margin-left: -20%">

                                        <h5>Ethics Group</h5>
                                        <h3><?= $karyawan[0]->nmlokasi; ?></h3>
                                        <div><?= $karyawan[0]->alamatlokasi; ?></div>
                                    </div>
                                </div>
                            </div>
                            <hr style="border-bottom:2px solid black">
                            <h4 class="payslip-title mb-0">Slip Gaji </h4>
                            <Div class="text-center">Gaji: <span><?= $help->bulan($generate[0]->bulan); ?>, <?= ($generate[0]->tahun); ?></span></Div>
                            <div class="row">
                                <div class="col-sm-6 m-b-20">
                                    <!---->

                                </div>
                                <div class="col-sm-6 m-b-20">
                                    <div class="invoice-details">

                                    </div>
                                </div>
                                <div class="col-lg-12 m-b-20">
                                    <ul class="list-unstyled">
                                        <li>
                                            <h4 class="mb-0"><strong><?= $karyawan[0]->nama_lengkap; ?></strong></h4>
                                        </li>
                                        <li><span><?= $karyawan[0]->nmjabatan; ?> </span></li>
                                        <li>Departement <?= $karyawan[0]->nmdept; ?></li>
                                        <li><?= $karyawan[0]->nmpangkat; ?></li>
                                        <li><?= $karyawan[0]->nama_grade; ?></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div>
                                        <h4 class="m-b-10"><strong>Data Kerja</strong></h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <?php
                                                $id_kary = $request->get('p_karyawan');
                                                $sql = "select *
										from prl_gaji a 
										join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
										join m_gaji_absen on b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id 
										where prl_generate_id = $id_prl and 
										p_karyawan_id = $id_kary  and 
										b.type=1 and b.active=1 and 
										m_gaji_absen.active =1
										 and b.active=1
										order by prl_gaji_detail_id  desc,nominal desc
		 
										";
                                                $row = DB::connection()->select($sql);
                                                $data = array();
                                                $masuk = 0;
                                                $sudah = array();
                                                foreach ($row as $row) {
                                                    if (!in_array($row->m_gaji_absen_id, $sudah)) {

                                                        if ($row->nama_gaji == 'Hari Absen')
                                                            $masuk = round($row->nominal);
                                                        echo '<tr>
															<td>' . $row->nama_gaji . '</td>
															<td>' . round($row->nominal) . '</td>
														</tr>';
                                                        $sudah[] = $row->m_gaji_absen_id;
                                                    }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div>
                                        <h4 class="m-b-10"><strong>Pendapatan</strong></h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <?php
                                                $id_kary = $request->get('p_karyawan');
                                                $sql = "select *,case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
										when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
										when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
										when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
										when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
										end as nama
										from prl_gaji a 
										join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
											 	
										where prl_generate_id = $id_prl and 
										p_karyawan_id = $id_kary  and 
										b.type in (2,3)   
										and b.active=1 and b.active=1
										order by prl_gaji_detail_id  desc,nominal desc
										";
                                                //echo $sql;
                                                $row = DB::connection()->select($sql);
                                                $data = array();
                                                $total_tunjangan = 0;
                                                $sudah = array();
                                                foreach ($row as $row) {
                                                    if (!isset($data[$row->nama]))
                                                        $data[$row->nama] = 0;


                                                    if (!in_array($row->nama, $sudah)) {
                                                        if ($row->nama == 'Upah Harian') {
                                                            $row->nominal = $row->nominal * $masuk;
                                                        }
                                                        if($row->nama=='Tunjangan Entitas' and $row->nominal==0){
                                                        	
                                                        }else{
                                                        	
                                                        $total_tunjangan += $row->nominal;
                                                        echo '<tr>
															<td>' . $row->nama . '</td>
															<td>' . $help->rupiah2($row->nominal) . '</td>
														</tr>';
                                                        $sudah[] = $row->nama;
                                                        $data[$row->nama] = $row->nominal;
                                                        }
                                                    }
                                                    //print_r($row);



                                                }
                                                echo '<tr>
											<td><h4><b>TOTAL PENDAPATAN</b></h4></td>
											<td>' . $help->rupiah2($total_tunjangan) . '</td>
										</tr>'
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div>
                                        <h4 class="m-b-10"><strong>Potongan</strong></h4>
                                        <table class="table table-bordered">
                                            <tbody>
                                                <?php
                                                $id_kary = $request->get('p_karyawan');
                                                $sql = "
										select *, (select count(*) from p_karyawan_koperasi where nama_koperasi='ASA' and active=1 and b.p_karyawan_id = p_karyawan_koperasi.p_karyawan_id) as is_koperasi_asa, (select count(*) from p_karyawan_koperasi where nama_koperasi='KKB' and active=1 and b.p_karyawan_id = p_karyawan_koperasi.p_karyawan_id) as is_koperasi_kkb,
			case 
				when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
				when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
				when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
				when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
				when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
				end as nama,m_lokasi.kode as nm_lokasi
			from prl_gaji a 
			join m_lokasi on a.m_lokasi_id = m_lokasi.m_lokasi_id
			join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
			where prl_generate_id = $id_prl and p_karyawan_id = $id_kary and 
										b.type in (4,5)
		
			 and b.active=1
			order by prl_gaji_detail_id  desc,nominal desc
										
										";
                                                $row = DB::connection()->select($sql);

                                                $data = array();
                                                $total_potongan = 0;
                                                $sudah = array();
                                                foreach ($row as $row) {
                                                    if (!in_array($row->nama, $sudah)) {

                                                        $total_potongan += $row->nominal;
                                                        echo '<tr>
															<td>' . $row->nama . '</td>
															<td>' . $help->rupiah2($row->nominal, 0) . '</td>
														</tr>';
                                                        $sudah[] = $row->nama;
                                                    }
                                                }
                                                echo '<tr>
											<td><h4><b>TOTAL POTONGAN</b></h4></td>
											<td>' . $help->rupiah2($total_potongan) . '</td>
										</tr>' ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <b>THP: <?= $help->rupiah($total_tunjangan - $total_potongan) . '</b><br>' . $help->terbilang($total_tunjangan - $total_potongan) . ''; ?>
                        </div>
                    <?php } ?>
                    </div>

                    <!-- /Page Content -->
                </div>
                <!-- /.card -->
                <!-- /.card -->
                <!-- /.content -->
        </div>
    </form>
    <!-- /.content-wrapper -->
    @endsection