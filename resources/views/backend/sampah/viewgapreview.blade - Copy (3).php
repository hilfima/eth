@extends('layouts.appsA')

@section('content')

<style>
    html {
        box-sizing: border-box;
    }

    *,
    *:before,
    *:after {
        box-sizing: inherit;
    }

    .intro {
        max-width: 1280px;
        margin: 1em auto;
    }

    .table-scroll {
        position: relative;
        width: 100%;
        z-index: 1;
        margin: auto;
        overflow: auto;
        height: 655px;
    }

    .table-scroll table {
        width: 100%;
        min-width: 1280px;
        margin: auto;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-wrap {
        position: relative;
    }

    .table-scroll td {
        min-width: 120px
    }

    .table-scroll th,

    .table-scroll td {
        padding: 5px 10px;
        border: 1px solid #000;
        background: #fff;
        vertical-align: top;
    }

    .table-scroll thead th {
        background: #333;
        color: #fff;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
    }

    .table-scroll thead tr.secondline th {
        top: 35px;
    }

    /* safari and ios need the tfoot itself to be position:sticky also */
    .table-scroll tfoot,
    .table-scroll tfoot th,
    .table-scroll tfoot td {
        position: -webkit-sticky;
        position: sticky;
        bottom: 0;
        background: #666;
        color: #fff;
        z-index: 4;
    }

    th.statictable:first-child {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        z-index: 2;
        background: #ccc;
    }

    thead th.statictable:first-child {
        background: #333;
        position: sticky;
        left: 0;
        z-index: 3;
    }

    thead th:first-child,
    tfoot th:first-child {
        z-index: 5;
    }

    .text-black {
        color: #012851
    }
</style>

<div class="content-wrapper">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Generate, Preview, Aproval, dan Ajuan Gaji</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                        <li class="breadcrumb-item active">Generate, Preview, Aproval, dan Ajuan Gaji</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.previewgaji',$data['page']) !!}">
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <a href="{!! route('be.tambah_generate') !!}" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah Generate Gaji </a>
                <a href="{!! route('be.generategaji') !!}" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-plus'></span> Edit Data </a>
            </div>
            <?php
            echo  view('backend.gaji.preview.filter', compact('data', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan'));
            ?>

            @if(!empty($list_karyawan))
            <div class="card-body">

                <div id="table-scroll" class="table-scroll">
                    <table id="main-table" class="main-table">
                        <thead>
                            <tr>

                                <th rowspan="2" class="statictable">Nama</th>
                                <th rowspan="2" style="width: 30px;max-width: 30px;min-width: 30px">No </th>

                                <th rowspan="2">Jabatan</th>
                                <th rowspan="2">Tanggal Masuk</th>
                                <th rowspan="2">Masa Kerja</th>
                                <th rowspan="2">Perusahaan</th>
                                <th rowspan="2">Pajak</th>
                                <th rowspan="2">Job Weight</th>
                                <th rowspan="2">Grade</th>



                                <th colspan="10" style="position: -webkit-sticky; position: sticky;">DATA ABSENSI</th>
                                <th colspan="3">PENDAPATAN</th>
                                <th colspan="6">TUNJANGAN LAINNYA </th>
                                <th colspan="14">POTONGAN</th>



                                <th rowspan="2" style="font-size: 12px">TOTAL POTONGAN</th>
                                <th rowspan="2" style="font-size: 12px">TOTAL PENDAPATAN</th>
                                <th rowspan="2">THP</th>
                            </tr>
                            <tr class="secondline">





                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;z-index:1">H. ABSEN</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">IHK</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">SAKIT</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">CUTI</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">IPG</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">TK</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">J.LEMBUR</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Terlambat</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">TP</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">PM</th>
                                <th>GAJI POKOK</th>
                                <th>T.GRADE</th>
                                <th>TOTAL</th>
                                <th>LEMBUR</th>
                                <th>BPJS KES</th>
                                <th>BPJS KT</th>
                                <th>T. Sewa Kost</th>
                                <th>Koreksi (+)</th>
                                <th>Total</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. Telat </th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. tidak finger</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. PM</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. Izin</th>
                                <th>Pot. Absen</th>
                                <th>BPJS KES</th>
                                <th>BPJS TK</th>
                                <th>Zakat</th>
                                <th>Infaq</th>
                                <th>Sewa Kost</th>
                                <th>Koperasi KKB</th>
                                <th>Koperasi ASA</th>
                                <th>PAJAK</th>
                                <th>Koreksi (-)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;
                            $gaji_entitas = array();
                            $total['H. ABSEN'] = 0;
                            $total['IHK'] = 0;
                            $total['SAKIT'] = 0;
                            $total['CUTI'] = 0;
                            $total['IPG'] = 0;
                            $total['TK'] = 0;
                            $total['TP'] = 0;
                            $total['PM'] = 0;
                            $total['Terlambat'] = 0;
                            $total['J.LEMBUR'] = 0;
                            $total['Gaji Pokok'] = 0;
                            $total['Tunjangan Grade'] = 0;
                            $total['JUMLAH PENDAPATAN'] = 0;
                            $total['Lembur'] = 0;
                            $total['Tunjangan BPJS Kesehatan'] = 0;
                            $total['Tunjangan BPJS Ketenaga Kerjaan'] = 0;
                            $total['Koreksi(+)'] = 0;
                            $total['Tunjangan Kost'] = 0;
                            $total['TOTAL TUNJANGAN'] = 0;
                            $total['Potongan Telat'] = 0;
                            $total['Potongan Izin'] = 0;
                            $total['Potongan Fingerprint'] = 0;
                            $total['Potongan Pulang Mendahului'] = 0;
                            $total['Potongan Absen'] = 0;
                            $total['Sewa Kost'] = 0;
                            $total['Iuran BPJS Kesehatan'] = 0;
                            $total['Iuran BPJS Ketenaga Kerjaan'] = 0;
                            $total['Zakat'] = 0;
                            $total['Infaq'] = 0;
                            $total['Koreksi(-)'] = 0;
                            $total['Simpanan Wajib KKB'] = 0;
                            $total['Potongan Koperasi Asa'] = 0;
                            $total['Pajak'] = 0;
                            $total['TOTAL POTONGAN'] = 0;
                            $total['TOTAL PENDAPATAN'] = 0;
                            $total['THP'] = 0; ?>
                            @foreach($list_karyawan as $list_karyawan)
                            <?php $no++;
                            $color = '';

                            if ($list_karyawan->pajak_onoff) {

                                if ($sudah_appr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                                    $color = 'background: orange;color: white;';
                                if ($sudah_appr_keuangan[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                                    $color = 'background: green;color: white;';
                                if ($sudah_appr_hr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff])
                                    $color = 'background: blue;color: white;';
                            }
                            ?>
                            <tr>
                                <th style="font-size: 11px;<?= $color ?>" class="statictable">{!! $list_karyawan->nama_lengkap !!}</th>
                                <td style="width: 50px;max-width: 50px;min-width: 50px">{!! $no !!}</td>
                                <td style="font-size: 10px">{!! $list_karyawan->nmjabatan !!}</td>
                                <td>{!! $list_karyawan->tgl_awal !!}</td>
                                <td style="font-size: 10px">{!! $list_karyawan->umur !!}</td>
                                <td>{!! $list_karyawan->nmlokasi !!} </td>
                                <td>{!! $list_karyawan->pajak_onoff !!}</td>
                                <td>{!! $list_karyawan->jobweight !!}</td>
                                <td>{!! $list_karyawan->grade !!}</td>






                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Hari Absen']) ? $data[$list_karyawan->p_karyawan_id]['Hari Absen'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Izin Hak Karyawan']) ? $data[$list_karyawan->p_karyawan_id]['Izin Hak Karyawan'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Sakit']) ? $data[$list_karyawan->p_karyawan_id]['Sakit'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Cuti']) ? $data[$list_karyawan->p_karyawan_id]['Cuti'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Izin Potongan Gaji']) ? $data[$list_karyawan->p_karyawan_id]['Izin Potongan Gaji'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan']) ? $data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Jam Lembur']) ? $data[$list_karyawan->p_karyawan_id]['Jam Lembur'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Terlambat']) ? $data[$list_karyawan->p_karyawan_id]['Terlambat'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Fingerprint']) ? $data[$list_karyawan->p_karyawan_id]['Fingerprint'] : '-'; ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;"><?= isset($data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin']) ? $data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin'] : '-'; ?></td>

                                <?php





                                $data_row = array();
                                $data_row[] = array('Gaji Pokok', 'gapok');
                                $data_row[] = array('Tunjangan Grade', 'tunjangan_grade');
                                for ($x = 0; $x < count($data_row); $x++) {
                                    $row1 = $data_row[$x][0];
                                    $row2 = $data_row[$x][1];
                                    if(isset($data_row[$x][2]))
                                    	$row3 = $data_row[$x][2];
                                	else
                                		$row3= true;
                                    $$row2 = isset($data[$list_karyawan->p_karyawan_id][$row1]) ? $data[$list_karyawan->p_karyawan_id][$row1] :   0;
                                    $total[$row1] +=  $$row2;
                                    if (!isset($data[$list_karyawan->p_karyawan_id]['id'][$row1]))
                                        $data[$list_karyawan->p_karyawan_id]['id'][$row1] = -1;
                                  // if($row1=='Potongan Pulang Mendahului') die;
                                ?>
                                    <td style="font-size:13px">
                                    <?php  if (!$sudah_appr_hr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff]){?>
                                        <a href="javascript:void(0)" class="text-black" id="<?=$row2;?>-<?=$list_karyawan->p_karyawan_id?>-<?=$data[$list_karyawan->p_karyawan_id]['id'][$row1]?>" onclick="change_nominal(<?= $data[$list_karyawan->p_karyawan_id]['id'][$row1] ?>,<?= $$row2 ?>,'<?= $row2 ?>','<?=$row1;?>','<?=$list_karyawan->p_karyawan_id;?>','<?=$list_karyawan->nmlokasi;?>')">
                                            <?= $help->rupiah2($$row2); ?>
                                        </a>

                                       <input class="total_pendapatan  total_pendapatan-<?=$list_karyawan->nmlokasi;?> total_pendapatan-<?=$list_karyawan->p_karyawan_id;?> total_pendapatan-<?=$row2;?> total_pendapatan-<?=$row1;?> " type="hidden" value="<?=$$row2;?>" id="input-<?=$row2;?>-<?=$list_karyawan->p_karyawan_id?>-<?=$data[$list_karyawan->p_karyawan_id]['id'][$row1];?>"> <?php
                                       if($row3){
                                      $nama_row = $row2;
                                        if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                            $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                        if ($$row2 != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                            echo '<div style="color:red"><a href="' .
                                                route(
                                                    'be.update_nominal',
                                                    [
                                                        $id_prl, $data[$list_karyawan->p_karyawan_id]['id'][$row1],
                                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master']
                                                    ]
                                                ) . '"><i class="fa fa-lightbulb-o "></i></a> ' .
                                                $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) .
                                                '</div>';
										}
										}else{
											echo $help->rupiah2($$row2);
										}
                                        ?>
                                    </td>
                                <?php } ?>
                                <td style="font-size:13px" ><div id="total_pendapatan_karyawan-<?=$list_karyawan->p_karyawan_id?>"><?= $help->rupiah2($gapok + $tunjangan_grade); ?></div>
                                	
                                       <input class="jumlah_pendapatan" type="hidden" value="<?=$gapok + $tunjangan_grade;?>" id="input-jumlah_pendapatan-<?=$list_karyawan->p_karyawan_id?>">
                                	
                                </td>
                                <?php
                                $data_row = array();
                                $data_row[] = array('Lembur', 'lembur',false);
                                $data_row[] = array('Tunjangan BPJS Kesehatan', 'tunjangan_bpjskes');
                                $data_row[] = array('Tunjangan BPJS Ketenaga Kerjaan', 'tunjangan_bpjsket');
                                $data_row[] = array('Tunjangan Kost', 'tunjangan_kost');
                                $data_row[] = array('Koreksi(+)', 'korekplus',false);
                                for ($x = 0; $x < count($data_row); $x++) {
                                    $row1 = $data_row[$x][0];
                                    $row2 = $data_row[$x][1];
                                    if(isset($data_row[$x][2]))
                                    	$row3 = $data_row[$x][2];
                                	else
                                		$row3= true;
                                    $$row2 = isset($data[$list_karyawan->p_karyawan_id][$row1]) ? $data[$list_karyawan->p_karyawan_id][$row1] :   0;
                                    $total[$row1] +=  $$row2;
                                    if (!isset($data[$list_karyawan->p_karyawan_id]['id'][$row1]))
                                        $data[$list_karyawan->p_karyawan_id]['id'][$row1] = -1;
                                  // if($row1=='Potongan Pulang Mendahului') die;
                                ?>
                                    <td style="font-size:13px">
                                      <?php  if (!$sudah_appr_hr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff]){?>
                                        <a href="javascript:void(0)" class="text-black" id="<?=$row2;?>-<?=$list_karyawan->p_karyawan_id?>-<?=$data[$list_karyawan->p_karyawan_id]['id'][$row1]?>" onclick="change_nominal(<?= $data[$list_karyawan->p_karyawan_id]['id'][$row1] ?>,<?= $$row2 ?>,'<?= $row2 ?>','<?=$row1;?>','<?=$list_karyawan->p_karyawan_id;?>','<?=$list_karyawan->nmlokasi;?>')">
                                            <?= $help->rupiah2($$row2); ?>
                                        </a>

                                       <input class="total_tunjangan total_tunjangan-<?=$list_karyawan->nmlokasi;?> total_tunjangan-<?=$list_karyawan->p_karyawan_id;?> total_tunjangan-<?=$row2;?> total_tunjangan-<?=$row1;?> " type="hidden" value="<?=$$row2;?>" id="input-<?=$row2;?>-<?=$list_karyawan->p_karyawan_id?>-<?=$data[$list_karyawan->p_karyawan_id]['id'][$row1];?>"> <?php
                                       if($row3){
                                      $nama_row = $row2;
                                        if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                            $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                        if ($$row2 != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                            echo '<div style="color:red"><a href="' .
                                                route(
                                                    'be.update_nominal',
                                                    [
                                                        $id_prl, $data[$list_karyawan->p_karyawan_id]['id'][$row1],
                                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master']
                                                    ]
                                                ) . '"><i class="fa fa-lightbulb-o "></i></a> ' .
                                                $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) .
                                                '</div>';
										}}else{
											echo $help->rupiah2($$row2);
										}
                                        ?>
                                    </td>
                                <?php } ?>

                                <td style="font-size:13px"  ><div id="total_tunjangan_karyawan-<?=$list_karyawan->p_karyawan_id?>"><?= $help->rupiah2($tunjangan = $tunjangan_kost + $korekplus + $tunjangan_bpjskes + $tunjangan_bpjsket + $lembur); ?></div>
                                	<input class="jumlah_tunjangan" type="hidden" value="<?=$tunjangan;?>" id="input-jumlah_tunjangan-<?=$list_karyawan->p_karyawan_id?>">
                                	
                                </td>

                                	
                                <?php
                                $data_row = array();
                                $data_row[] = array('Potongan Telat', 'telat',false);
                                $data_row[] = array('Potongan Fingerprint', 'potfinger',false);
                                $data_row[] = array('Potongan Pulang Mendahului', 'potpm',false);
                                $data_row[] = array('Potongan Izin', 'potizin',false);
                                $data_row[] = array('Potongan Absen', 'absen',false);
                                $data_row[] = array('Iuran BPJS Kesehatan', 'iuran_bpjskes');
                                $data_row[] = array('Iuran BPJS Ketenaga Kerjaan', 'iuran_bpjsket');
                                $data_row[] = array('Zakat', 'zakat');
                                $data_row[] = array('Infaq', 'infaq');
                                $data_row[] = array('Sewa Kost', 'sewa_kost');
                                $data_row[] = array('Simpanan Wajib KKB', 'KKB');
                                $data_row[] = array('Potongan Koperasi Asa', 'ASA');
                                $data_row[] = array('Pajak', 'pajak');
                                $data_row[] = array('Koreksi(-)', 'korekmin',false);
                                for ($x = 0; $x < count($data_row); $x++) {
                                    $row1 = $data_row[$x][0];
                                    $row2 = $data_row[$x][1];
                                    if(isset($data_row[$x][2]))
                                    	$row3 = $data_row[$x][2];
                                	else
                                		$row3= true;
                                    $$row2 = isset($data[$list_karyawan->p_karyawan_id][$row1]) ? $data[$list_karyawan->p_karyawan_id][$row1] :   0;
                                    $total[$row1] +=  $$row2;
                                    if (!isset($data[$list_karyawan->p_karyawan_id]['id'][$row1]))
                                        $data[$list_karyawan->p_karyawan_id]['id'][$row1] = -1;
                                  // if($row1=='Potongan Pulang Mendahului') die;
                                ?>
                                    <td style="font-size:13px">
                                      <?php  if (!$sudah_appr_hr[$list_karyawan->m_lokasi_id][$list_karyawan->pajak_onoff]){?>
                                        <a href="javascript:void(0)" class="text-black" id="<?=$row2;?>-<?=$list_karyawan->p_karyawan_id?>-<?=$data[$list_karyawan->p_karyawan_id]['id'][$row1]?>" onclick="change_nominal(<?= $data[$list_karyawan->p_karyawan_id]['id'][$row1] ?>,<?= $$row2 ?>,'<?= $row2 ?>','<?=$row1;?>','<?=$list_karyawan->p_karyawan_id;?>','<?=$list_karyawan->nmlokasi;?>')">
                                            <?= $help->rupiah2($$row2); ?>
                                        </a>

                                       <input class="total_potongan total_potongan-<?=$list_karyawan->nmlokasi;?> total_potongan-<?=$list_karyawan->p_karyawan_id;?> total_potongan-<?=$row2;?> total_potongan-<?=$row1;?> " type="hidden" value="<?=$$row2;?>" id="input-<?=$row2;?>-<?=$list_karyawan->p_karyawan_id?>-<?=$data[$list_karyawan->p_karyawan_id]['id'][$row1];?>"> <?php
                                       if($row3){
                                      $nama_row = $row2;
                                        if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                            $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                        if ($$row2 != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                            echo '<div style="color:red"><a href="' .
                                                route(
                                                    'be.update_nominal',
                                                    [
                                                        $id_prl, $data[$list_karyawan->p_karyawan_id]['id'][$row1],
                                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master']
                                                    ]
                                                ) . '"><i class="fa fa-lightbulb-o "></i></a> ' .
                                                $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) .
                                                '</div>';
										}}else{
											echo $help->rupiah2($$row2);
										}
                                        ?>
                                    </td>
                                <?php } ?>
                                <td style="font-size:13px" ><div id="total_potongan_karyawan-<?=$list_karyawan->p_karyawan_id?>"><?= $help->rupiah2($potongan = $telat + $absen + $sewa_kost + $iuran_bpjskes + $iuran_bpjsket + $zakat + $infaq + $korekmin + $KKB + $ASA + $pajak + $potizin + $potfinger + $potpm); ?></div>
                                	<input class="jumlah_potongan" type="hidden" value="<?=$potongan;?>" id="input-jumlah_potongan-<?=$list_karyawan->p_karyawan_id?>">
                                	
                                	
                                </td>
                                <td style="font-size:13px" id="total_pendapatan_tunjangan_karyawan-<?=$list_karyawan->p_karyawan_id?>"><?= $help->rupiah2(($tunjangan_grade + $gapok + $tunjangan)); ?></td>
                                <td style="font-size:13px" id="thp_karyawan-<?=$list_karyawan->p_karyawan_id?>"><?= $help->rupiah2(($tunjangan_grade + $gapok + $tunjangan) - $potongan); ?></td>

                            </tr>
                            <?php
                            $total['H. ABSEN'] += isset($data[$list_karyawan->p_karyawan_id]['Hari Absen']) ? $data[$list_karyawan->p_karyawan_id]['Hari Absen'] : 0;;

                            $total['IHK'] += isset($data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja'] : 0;;

                            $total['SAKIT'] += isset($data[$list_karyawan->p_karyawan_id]['Sakit']) ? $data[$list_karyawan->p_karyawan_id]['Sakit'] : 0;;
                            $total['CUTI'] += isset($data[$list_karyawan->p_karyawan_id]['Cuti']) ? $data[$list_karyawan->p_karyawan_id]['Cuti'] : 0;;
                            $total['IPG'] += isset($data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas']) ? $data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas'] : 0;;
                            $total['TK'] += isset($data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan']) ? $data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan'] : 0;;
                            $total['J.LEMBUR'] += isset($data[$list_karyawan->p_karyawan_id]['Jam Lembur']) ? $data[$list_karyawan->p_karyawan_id]['Jam Lembur'] : 0;;
                            $total['Terlambat'] += isset($data[$list_karyawan->p_karyawan_id]['Terlambat']) ? $data[$list_karyawan->p_karyawan_id]['Terlambat'] : 0;;
                            $total['TP'] += isset($data[$list_karyawan->p_karyawan_id]['Fingerprint']) ? $data[$list_karyawan->p_karyawan_id]['Fingerprint'] : 0;;
                            $total['PM'] += isset($data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin']) ? $data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin'] : 0;;

                            $total['JUMLAH PENDAPATAN'] += $tunjangan_grade + $gapok;
                            $total['Koreksi(+)'] += $korekplus;

                            $total['Gaji Pokok'] += $gapok;
                            $total['Tunjangan Grade'] += $tunjangan_grade;
                            $total['Koreksi(-)'] += $korekmin;
                            $total['Potongan Izin'] += $potizin;
                            $total['Potongan Pulang Mendahului'] += $potpm;
                            $total['TOTAL TUNJANGAN'] += $tunjangan;
                            $total['Potongan Telat'] += $telat;
                            $total['Potongan Absen'] += $absen;
                            /*
                            $total['Lembur'] += $lembur;
                            $total['Tunjangan BPJS Kesehatan'] += $tunkes;
                            $total['Tunjangan BPJS Ketenaga Kerjaan'] += $tunket;
                            $total['Tunjangan Kost'] += $tunkost;
                            $total['Sewa Kost'] += $sewakost;
                            $total['Iuran BPJS Kesehatan'] += $ibpjs;
                            $total['Iuran BPJS Ketenaga Kerjaan'] += $ibpjt;
                            $total['Zakat'] += $zakat;
                            $total['Infaq'] += $infaq;
                            $total['Simpanan Wajib KKB'] += $kkp;
                            $total['Potongan Koperasi Asa'] += $asa;
                            $total['Potongan Fingerprint'] += $potfinger;*/

                            $total['Pajak'] += isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $data[$list_karyawan->p_karyawan_id]['Pajak'] : 0;
                            $total['TOTAL POTONGAN'] += $potongan;
                            $total['TOTAL PENDAPATAN'] += ($tunjangan_grade + $gapok + $tunjangan);
                            $total['THP'] += ($tunjangan_grade + $gapok + $tunjangan) - $potongan;


                            if (isset($gaji_entitas[$list_karyawan->nmlokasi])) {
                                $gaji_entitas[$list_karyawan->nmlokasi] += ($tunjangan_grade + $gapok + $tunjangan) - $potongan + $korekmin - $korekplus;
                            } else {
                                $gaji_entitas[$list_karyawan->nmlokasi] = ($tunjangan_grade + $gapok + $tunjangan) - $potongan + $korekmin - $korekplus;
                            }
                            if (isset($data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(+)'])) {
                                $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(+)'];
                                if (isset($gaji_entitas[$nmlok])) {
                                    $gaji_entitas[$nmlok] += $korekplus;
                                } else {
                                    $gaji_entitas[$nmlok] = $korekplus;
                                }
                            }
                            if (isset($data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(-)'])) {
                                $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas']['Koreksi(-)'];
                                if (isset($gaji_entitas[$nmlok])) {
                                    $gaji_entitas[$nmlok] -= $korekmin;
                                } else {
                                    $gaji_entitas[$nmlok] = -$korekmin;
                                }
                            }
                            ?>
                            @endforeach
                            <tr>
                                <th style="font-size: 11px;color:red" class="statictable">TOTAL</th>
                                <td style="width: 50px;max-width: 50px;min-width: 50px"></td>
                                <td style="font-size: 10px"></td>
                                <td></td>
                                <td style="font-size: 10px"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>


                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['H. ABSEN'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['IHK'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['SAKIT'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['CUTI'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['IPG'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['TK'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['J.LEMBUR'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['Terlambat'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['TP'] ?></td>
                                <td style="width: 40px;min-width: 40px;max-width: 40px;font-size:8px"><?= $total['PM'] ?></td>
                                <td style="font-size:13px" id="total_field-gapok"><?= $help->rupiah2($total['Gaji Pokok']); ?></td>
                                <td style="font-size:13px" id="total_field-tunjangan_grade"><?= $help->rupiah2($total['Tunjangan Grade']); ?></td>
                                <td style="font-size:13px" id="total_field-total_pendapatan_all"><?= $help->rupiah2($total['JUMLAH PENDAPATAN']); ?></td>
                                <td style="font-size:13px" id="total_field-lembur"><?= $help->rupiah2($total['Lembur']); ?></td>
                                <td style="font-size:13px" id="total_field-tunjangan_bpjskes"><?= $help->rupiah2($total['Tunjangan BPJS Kesehatan']); ?></td>
                                <td style="font-size:13px" id="total_field-tunjangan_bpjsket"><?= $help->rupiah2($total['Tunjangan BPJS Ketenaga Kerjaan']); ?></td>
                                <td style="font-size:13px" id="total_field-tunjangan_kost"><?= $help->rupiah2($total['Tunjangan Kost']); ?></td>
                                <td style="font-size:13px" id="total_field-korekplus"><?= $help->rupiah2($total['Koreksi(+)']); ?></td>
                                <td style="font-size:13px" id="total_field-total_tunjangan_all"><?= $help->rupiah2($total['TOTAL TUNJANGAN']); ?></td>
 
                                <td style="font-size:13px" id="total_field-telat"><?= $help->rupiah2($total['Potongan Telat']); ?></td>
                                <td style="font-size:13px" id="total_field-potfinger"><?= $help->rupiah2($total['Potongan Fingerprint']); ?></td>
                                <td style="font-size:13px" id="total_field-potpm"><?= $help->rupiah2($total['Potongan Pulang Mendahului']); ?></td>
                                <td style="font-size:13px" id="total_field-potizin"><?= $help->rupiah2($total['Potongan Izin']); ?></td>
                                <td style="font-size:13px" id="total_field-absen"><?= $help->rupiah2($total['Potongan Absen']); ?></td>
                                <td style="font-size:13px" id="total_field-iuran_bpjskes"><?= $help->rupiah2($total['Iuran BPJS Kesehatan']); ?></td>
                                <td style="font-size:13px" id="total_field-iuran_bpjsket"><?= $help->rupiah2($total['Iuran BPJS Ketenaga Kerjaan']); ?></td>
                                <td style="font-size:13px" id="total_field-zakat"><?= $help->rupiah2($total['Zakat']); ?></td>
                                <td style="font-size:13px" id="total_field-infaq"><?= $help->rupiah2($total['Infaq']); ?></td>
                                <td style="font-size:13px" id="total_field-sewa_kost"><?= $help->rupiah2($total['Sewa Kost']); ?></td>
                                <td style="font-size:13px" id="total_field-KKB"><?= $help->rupiah2($total['Simpanan Wajib KKB']); ?></td>
                                <td style="font-size:13px" id="total_field-ASA"><?= $help->rupiah2($total['Potongan Koperasi Asa']); ?></td>
                                <td style="font-size:13px" id="total_field-pajak"><?= $help->rupiah2($total['Pajak']); ?></td>
                                <td style="font-size:13px" id="total_field-korekmin"><?= $help->rupiah2($total['Koreksi(-)']); ?></td>
                                <td style="font-size:13px" id="total_field-total_potongan_all"><?= $help->rupiah2($total['TOTAL POTONGAN']); ?></td>
                                <td style="font-size:13px" id="total_field-total_pendapatan_tunjangan_all"><?= $help->rupiah2($total['TOTAL PENDAPATAN']); ?></td>
                                <td style="font-size:13px" id="total_field-thp_all"><?= $help->rupiah2($total['THP']); ?></td>

                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>

                                <th rowspan="2" class="statictable">Nama</th>
                                <th rowspan="2" style="width: 30px;max-width: 30px;min-width: 30px">No </th>

                                <th rowspan="2">Jabatan</th>
                                <th rowspan="2">Tanggal Masuk</th>
                                <th rowspan="2">Masa Kerja</th>
                                <th rowspan="2">Perusahaan</th>
                                <th rowspan="2">Pajak</th>
                                <th rowspan="2">Job Weight</th>
                                <th rowspan="2">Grade</th>



                                <th colspan="10" style="position: -webkit-sticky; position: sticky;">DATA ABSENSI</th>
                                <th colspan="3">PENDAPATAN</th>
                                <th colspan="6">TUNJANGAN LAINNYA </th>
                                <th colspan="14">POTONGAN</th>



                                <th rowspan="2" style="font-size: 12px">TOTAL POTONGAN</th>
                                <th rowspan="2" style="font-size: 12px">TOTAL PENDAPATAN</th>
                                <th rowspan="2">THP</th>
                            </tr>
                            <tr class="secondline">





                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;z-index:1">H. ABSEN</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">IHK</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">SAKIT</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">CUTI</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">IPG</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">TK</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">J.LEMBUR</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">Terlambat</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">TP</th>
                                <th style="width: 40px;min-width: 40px;max-width: 40px;font-size: 9px;vertical-align: top;">PM</th>
                                <th>GAJI POKOK</th>
                                <th>T.GRADE</th>
                                <th>TOTAL</th>
                                <th>LEMBUR</th>
                                <th>BPJS KES</th>
                                <th>BPJS KT</th>
                                <th>T. Sewa Kost</th>
                                <th>Koreksi (+)</th>
                                <th>Total</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. Telat </th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. tidak finger</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. PM</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. Izin</th>
                                <th>Pot. Absen</th>
                                <th>BPJS KES</th>
                                <th>BPJS TK</th>
                                <th>Zakat</th>
                                <th>Infaq</th>
                                <th>Sewa Kost</th>
                                <th>Koperasi KKB</th>
                                <th>Koperasi ASA</th>
                                <th>PAJAK</th>
                                <th>Koreksi (-)</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                TOTAL GAJI PER ENTITAS
                <table style="width: 100%" class="table table-striped">
                    <tbody>
                        <?php
                        $t = 0;
                        foreach ($gaji_entitas as $key => $value) {
                            $t += $value;

                            echo '
							<tr>
								<th style="width:10%">' . $key . '</th>
								<td id="total_entitas-' . $key . '">' . $help->rupiah($value) . '</td>
							</tr>';
                        }
                        ?>
                        <tr>
                            <td style="width:10%"> TOTAL</td>
                            <td> <?= $help->rupiah($t) ?></td>
                        </tr>



                    </tbody>
                </table>
                @endif



            </div>
            <!-- /.card-body -->
        </div>
    </form>
    <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>

<!-- Modal -->
<div class="modal fade" id="changeNominalModal" tabindex="-1" role="dialog" aria-labelledby="changeNominalModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Nominal: <span id="nama_field"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="number" id="nominal_now" value="" class="form-control">
                <input type="hidden" id="id_prl_gaji" value="">
                <input type="hidden" id="field" value="">
                <input type="hidden" id="id_karyawan" value="">
                <input type="hidden" id="entitas" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save_change_nominal()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    function save_change_nominal() {
		var id_nominal = $('#id_prl_gaji').val();
    	var nominal_now = $('#nominal_now').val();
    	var field = $('#field').val();
    	var id_karyawan = $('#id_karyawan').val();
    	var entitas = $('#entitas').val();
    	//alert(id_nominal) 
    	$.ajax({
			type: 'get',
			data: {
					'id_nominal': id_nominal,
					'nominal_now': nominal_now,
					'id_karyawan': id_karyawan,
					'id_prl': '<?=$id_prl;?>',
					'field': field
				},
			url: '<?=route('be.save_change_nominal');?>',
			dataType: 'html',
			success: function(data) {
				//
				//var tgl_cicilan = myDate.addMonths(cicilan);
				$('#input-'+field+'-'+id_karyawan+'-'+id_nominal).val(nominal_now);
				var rp = formatRupiah(nominal_now,'');
				//alert(rp);
				$('#'+field+'-'+id_karyawan+'-'+id_nominal).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				//alert(''+field+'-'+id_karyawan+'-'+id_nominal);
				total_pendapatan_karyawan = 0;
				$('.total_pendapatan-'+id_karyawan).each(function(){
				    total_pendapatan_karyawan += parseFloat(this.value);
				});
				
				total_pendapatan_field = 0;
				$('.total_pendapatan-'+field).each(function(){
				    total_pendapatan_field += parseFloat(this.value);
				});
				total_pendapatan_entitas = 0;
				$('.total_pendapatan-'+entitas).each(function(){
				    total_pendapatan_entitas += parseFloat(this.value);
				});
				total_pendapatan = 0;
				$('.total_pendapatan').each(function(){
				    total_pendapatan += parseFloat(this.value);
				});
				
				total_tunjangan_karyawan = 0;
				$('.total_tunjangan-'+id_karyawan).each(function(){
				    total_tunjangan_karyawan += parseFloat(this.value);
				});
				
				total_tunjangan_field = 0;
				$('.total_tunjangan-'+field).each(function(){
				    total_tunjangan_field += parseFloat(this.value);
				});
				total_tunjangan_entitas = 0;
				$('.total_tunjangan-'+entitas).each(function(){
				    total_tunjangan_entitas += parseFloat(this.value);
				});
				total_tunjangan = 0;
				$('.total_tunjangan').each(function(){
				    total_tunjangan += parseFloat(this.value);
				});
				
				total_potongan_karyawan = 0;
				$('.total_potongan-'+id_karyawan).each(function(){
				    total_potongan_karyawan += parseFloat(this.value);
				});
				
				total_potongan_field = 0;
				$('.total_potongan-'+field).each(function(){
				    total_potongan_field += parseFloat(this.value);
				});
				total_potongan_entitas = 0;
				$('.total_potongan-'+entitas).each(function(){
				    total_potongan_entitas += parseFloat(this.value);
				});
				total_potongan = 0;
				$('.total_potongan').each(function(){
				    total_potongan += parseFloat(this.value);
				});
				
				
				rp = (new Intl.NumberFormat().format(total_potongan_karyawan));
				$('#total_potongan_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				//alert(rp);
				rp = (new Intl.NumberFormat().format(total_pendapatan_karyawan));
				$('#total_pendapatan_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
					
				
				rp = new Intl.NumberFormat().format(total_tunjangan_karyawan);
				$('#total_tunjangan_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				
				rp = new Intl.NumberFormat().format((total_pendapatan_karyawan+total_tunjangan_karyawan));
				$('#total_pendapatan_tunjangan_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				
				rp = new Intl.NumberFormat().format((total_pendapatan_karyawan+total_tunjangan_karyawan-total_potongan_karyawan))
				$('#thp_karyawan-'+id_karyawan).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>  ');
				
				rp = new Intl.NumberFormat().format((total_pendapatan_entitas+total_tunjangan_entitas-total_potongan_entitas))
				$('#total_entitas-'+entitas).html('Rp. '+rp+'');
				
				rp = new Intl.NumberFormat().format((total_pendapatan_field+total_tunjangan_field-total_potongan_field))
				$('#total_field-'+field).html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				
				$('#input-jumlah_pendapatan-'+id_karyawan).val(total_pendapatan_karyawan);
				$('#input-jumlah_tunjangan-'+id_karyawan).val(total_tunjangan_karyawan);
				$('#input-jumlah_potongan-'+id_karyawan).val(total_potongan_karyawan);
				
				jumlah_pendapatan = 0;
				$('.jumlah_pendapatan').each(function(){
				    jumlah_pendapatan += parseFloat(this.value);
				});
				jumlah_tunjangan = 0;
				$('.jumlah_tunjangan').each(function(){
				    jumlah_tunjangan += parseFloat(this.value);
				});
				jumlah_potongan = 0;
				$('.jumlah_potongan').each(function(){
				    jumlah_potongan += parseFloat(this.value);
				});
				
				
				
				rp = new Intl.NumberFormat().format((jumlah_potongan))
				$('#total_field-total_potongan_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				rp = new Intl.NumberFormat().format((jumlah_pendapatan))
				$('#total_field-total_pendapatan_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				rp = new Intl.NumberFormat().format((jumlah_tunjangan))
				$('#total_field-total_tunjangan_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				rp = new Intl.NumberFormat().format((jumlah_pendapatan+jumlah_tunjangan))
				$('#total_field-total_pendapatan_tunjangan_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				rp = new Intl.NumberFormat().format((jumlah_pendapatan+jumlah_tunjangan-jumlah_potongan))
				$('#total_field-thp_all').html('<div style="width:100%;display: inline-flex;"><span class="text-left" style="width: 30%;">Rp </span><span class="text-right" style="width: 70%;">'+rp+'</span></div>');
				
		$('#changeNominalModal').modal('toggle');
				
				
				
				//console.log(data);
			},
			error: function (error) {
				console.log('error; ' + eval(error));
				//alert(2);
			}
		});
	}
    function change_nominal(id,nominal_now,field,fieldtext,id_karyawan,entitas) {
    	$('#id_prl_gaji').val(id);
    	$('#nominal_now').val($('#input-'+field+'-'+id_karyawan+'-'+id).val());
    	$('#field').val(field);
    	$('#nama_field').html(fieldtext);
    	$('#id_karyawan').val(id_karyawan);
    	$('#entitas').val(entitas);
		$('#changeNominalModal').modal('toggle');
		
    }
    		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
		}
</script>
<!-- /.content-wrapper -->
@endsection