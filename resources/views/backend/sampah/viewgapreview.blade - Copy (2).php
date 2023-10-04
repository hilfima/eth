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
                                <th>Koreksi (+)</th>
                                <th>T. Sewa Kost</th>
                                <th>Total</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. Telat </th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. tidak finger</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. PM</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. Izin</th>
                                <th>Pot. Absen</th>
                                <th>Sewa Kost</th>
                                <th>BPJS KES</th>
                                <th>BPJS TK</th>
                                <th>Zakat</th>
                                <th>Infaq</th>
                                <th>Koreksi (-)</th>
                                <th>Koperasi KKB</th>
                                <th>Koperasi ASA</th>
                                <th>PAJAK</th>
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
                            $total['LEMBUR'] = 0;
                            $total['Tunjangan BPJS Kesehatan'] = 0;
                            $total['Tunjangan BPJS Ketenaga Kerjaan'] = 0;
                            $total['Koreksi(+)'] = 0;
                            $total['Tunjangan Kost'] = 0;
                            $total['TOTAL TUNJANGAN'] = 0;
                            $total['Potongan Telat'] = 0;
                            $total['Potongan Izin'] = 0;
                            $total['Potongan Fingerprint'] = 0;
                            $total['Potongan PM'] = 0;
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
                                <td style="font-size:13px"><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok']) ? $gapok = $data[$list_karyawan->p_karyawan_id]['Gaji Pokok'] : $gapok = 0);

                                                            $nama_row = 'gapok';
                                                            if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                                                $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                                            if ($gapok != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                                                echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Gaji Pokok'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';

                                                            ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']) ? $grade = $data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'] : $grade = 0);

                                    $nama_row = 'tunjangan_grade';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($grade != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Tunjangan Grade'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';

                                    ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($grade + $gapok); ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0); ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                                    $nama_row = 'tunjangan_bpjskes';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($tunkes != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Tunjangan BPJS Kesehatan'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                                    $nama_row = 'tunjangan_bpjsket';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($tunket != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Tunjangan BPJS Ketenaga Kerjaan'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0); ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                                    $nama_row = 'tunjangan_kost';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($tunkost != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Tunjangan Kost'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>'; ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($tunjangan = $tunkost + $korekplus + $tunket + $tunkes + $lembur);

                                                            ?></td>

                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat'] : $telat = 0); ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']) ? $potfinger = $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : $potfinger = 0); ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']) ? $potpm = $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'] : $potpm = 0); ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin']) ? $potizin = $data[$list_karyawan->p_karyawan_id]['Potongan Izin'] : $potizin = 0); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0); ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                                    $nama_row = 'sewa_kost';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($sewakost != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Sewa Kost'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                                    $nama_row = 'iuran_bpjskes';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($ibpjs != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Iuran BPJS Kesehatan'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    ?></td>
                                <td><?= $help->rupiah2(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                                    $nama_row = 'iuran_bpjsket';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($ibpjt != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Iuran BPJS Ketenaga Kerjaan'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    ?></td>
                                    
                                <td><?= $help->rupiah2($zakat = isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $data[$list_karyawan->p_karyawan_id]['Zakat'] : 0);
                                    $nama_row = 'zakat';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($zakat != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Zakat'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    ?></td>
                                    
                                    
                                <td><?= $help->rupiah2($infaq = isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $data[$list_karyawan->p_karyawan_id]['Infaq'] : 0);
                                    $nama_row = 'infaq';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($infaq != $data[$list_karyawan->p_karyawan_id][$nama_row]['master'])
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Infaq'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';


                                    ?></td>
                                <td><?= $help->rupiah2($korekmin = isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : 0); ?></td>
                                <td><?= $help->rupiah2($kkp = isset($data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB']) ? $data[$list_karyawan->p_karyawan_id]['Simpanan Wajib KKB'] : 0);
                                    $nama_row = 'KKB';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master'])){
                                    	
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                        
                                    }
                                    if(!isset($data[$list_karyawan->p_karyawan_id]['id']['Simpanan Wajib KKB']))
                                    	$data[$list_karyawan->p_karyawan_id]['id']['Simpanan Wajib KKB'] = -1;
                                    if ($kkp != $data[$list_karyawan->p_karyawan_id][$nama_row]['master']) {
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Simpanan Wajib KKB'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    }
                                    ?></td>
                                <td><?= $help->rupiah2($asa = isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : 0);
                                    $nama_row = 'ASA';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master'])){
                                    	
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    }
                                    if(!isset($data[$list_karyawan->p_karyawan_id]['id']['Potongan Koperasi Asa']))
                                    	$data[$list_karyawan->p_karyawan_id]['id']['Potongan Koperasi Asa'] = -1;
                                    if ($asa != $data[$list_karyawan->p_karyawan_id][$nama_row]['master']) {
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Potongan Koperasi Asa'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    }
                                    ?></td>
                                <td><?= $help->rupiah2($pajak = isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $data[$list_karyawan->p_karyawan_id]['Pajak'] : 0);
                                    $nama_row = 'pajak';
                                    if (!isset($data[$list_karyawan->p_karyawan_id][$nama_row]['master']))
                                        $data[$list_karyawan->p_karyawan_id][$nama_row]['master'] = 0;
                                    if ($pajak != $data[$list_karyawan->p_karyawan_id][$nama_row]['master']) {
                                        echo '<div style="color:red"><a href="' . route('be.update_nominal', [$id_prl, $data[$list_karyawan->p_karyawan_id]['id']['Pajak'], $data[$list_karyawan->p_karyawan_id][$nama_row]['master']]) . '"><i class="fa fa-lightbulb-o "></i></a> ' . $help->rupiah2($data[$list_karyawan->p_karyawan_id][$nama_row]['master']) . '</div>';
                                    }
                                    ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($potongan = $telat + $absen + $sewakost + $ibpjs + $ibpjt + $zakat + $infaq + $korekmin + $kkp + $asa + $pajak + $potizin + $potfinger + $potpm); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2(($grade + $gapok + $tunjangan)); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2(($grade + $gapok + $tunjangan) - $potongan); ?></td>

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

                            $total['Gaji Pokok'] += $gapok;
                            $total['Tunjangan Grade'] += $grade;
                            $total['JUMLAH PENDAPATAN'] += $grade + $gapok;
                            $total['LEMBUR'] += $lembur;
                            $total['Tunjangan BPJS Kesehatan'] += $tunkes;
                            $total['Tunjangan BPJS Ketenaga Kerjaan'] += $tunket;
                            $total['Koreksi(+)'] += $korekplus;
                            $total['Tunjangan Kost'] += $tunkost;
                            $total['TOTAL TUNJANGAN'] += $tunjangan;
                            $total['Potongan Telat'] += $telat;
                            $total['Potongan Absen'] += $absen;
                            $total['Sewa Kost'] += $sewakost;
                            $total['Iuran BPJS Kesehatan'] += $ibpjs;
                            $total['Iuran BPJS Ketenaga Kerjaan'] += $ibpjt;
                            $total['Zakat'] += $zakat;
                            $total['Infaq'] += $infaq;
                            $total['Koreksi(-)'] += $korekmin;
                            $total['Simpanan Wajib KKB'] += $kkp;
                            $total['Potongan Koperasi Asa'] += $asa;
                            $total['Potongan Fingerprint'] += $potfinger;
                            $total['Potongan Izin'] += $potizin;
                            $total['Potongan PM'] += $potpm;
                            $total['Pajak'] += isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $data[$list_karyawan->p_karyawan_id]['Pajak'] : 0;
                            $total['TOTAL POTONGAN'] += $potongan;
                            $total['TOTAL PENDAPATAN'] += ($grade + $gapok + $tunjangan);
                            $total['THP'] += ($grade + $gapok + $tunjangan) - $potongan;


                            if (isset($gaji_entitas[$list_karyawan->nmlokasi])) {
                                $gaji_entitas[$list_karyawan->nmlokasi] += ($grade + $gapok + $tunjangan) - $potongan + $korekmin - $korekplus;
                            } else {
                                $gaji_entitas[$list_karyawan->nmlokasi] = ($grade + $gapok + $tunjangan) - $potongan + $korekmin - $korekplus;
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
                                <td style="font-size:13px"><?= $help->rupiah2($total['Gaji Pokok']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Tunjangan Grade']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['JUMLAH PENDAPATAN']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['LEMBUR']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Tunjangan BPJS Kesehatan']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Tunjangan BPJS Ketenaga Kerjaan']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Koreksi(+)']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Tunjangan Kost']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['TOTAL TUNJANGAN']); ?></td>

                                <td style="font-size:13px"><?= $help->rupiah2($total['Potongan Telat']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Potongan Fingerprint']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Potongan PM']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Potongan Izin']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Potongan Absen']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Sewa Kost']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Iuran BPJS Kesehatan']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Iuran BPJS Ketenaga Kerjaan']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Zakat']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Infaq']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Koreksi(-)']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Simpanan Wajib KKB']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Potongan Koperasi Asa']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['Pajak']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['TOTAL POTONGAN']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['TOTAL PENDAPATAN']); ?></td>
                                <td style="font-size:13px"><?= $help->rupiah2($total['THP']); ?></td>

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
                                <th>Koreksi (+)</th>
                                <th>T. Sewa Kost</th>
                                <th>Total</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. Telat </th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. tidak finger</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. PM</th>
                                <th style="font-size: 9px;vertical-align: top;min-width: 70px;">Pot. Izin</th>
                                <th>Pot. Absen</th>
                                <th>Sewa Kost</th>
                                <th>BPJS KES</th>
                                <th>BPJS TK</th>
                                <th>Zakat</th>
                                <th>Infaq</th>
                                <th>Koreksi (-)</th>
                                <th>Koperasi KKB</th>
                                <th>Koperasi ASA</th>
                                <th>PAJAK</th>
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
								<td>' . $help->rupiah($value) . '</td>
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

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- /.content-wrapper -->
@endsection