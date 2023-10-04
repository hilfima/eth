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
                    <h1 class="m-0 text-dark">Revisi Data Generate Gaji</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                        <li class="breadcrumb-item active">Generate Gaji</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <form id="cari_absen" class="form-horizontal" method="post" action="{!! route('be.send_revisi') !!}">
        {{ csrf_field() }}
        <div class="card">
            <div class="card-body">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
                <!--<a href="http://localhost/get-data/udp.php" target="_blank" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-sync'></span> Sync Absen </a>-->

                <div class="row">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Generate Data </label>
                            <select class="form-control select2" name="generate" style="width: 100%;" required>
                                <?php

                                foreach ($generate as $periode) {
                                    if ($periode->prl_generate_id == $id_prl) {
                                        echo '<option selected="selected" value="' . $periode->prl_generate_id . '">' . $periode->tipe . ' - Periode: ' . $periode->tahun_gener . ' Bulan: ' . $periode->bulan_gener . ' | Absen:' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . ' | Lembur:' . $periode->tgl_awal_lembur . ' - ' . $periode->tgl_akhir_lembur . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Karyawan </label>
                            <select class="form-control select2" name="karyawan" style="width: 100%;" required>

                                <?php foreach ($karyawan as $karyawan) {
                                    if ($karyawan->p_karyawan_id == $id) { ?>
                                        <option value="<?= $karyawan->p_karyawan_id; ?>"><?= $karyawan->nama; ?></option>
                                <?php }
                                } ?>

                            </select>
                        </div>
                    </div>
                </div>
                <div>

                </div>

            </div>

            <!-- /.card-body -->
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <?php
                        $id_kary = $id;
                        $sql = "select *
												from prl_gaji a 
											 	join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
											 	join m_gaji_absen on b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id 
											 	where prl_generate_id = $id_prl and 
											 		p_karyawan_id = $id_kary  and 
											 		b.type=1  and 
											 		m_gaji_absen.active =1
											 		order by m_gaji_absen.m_gaji_absen_id
											 		";
                        $row = DB::connection()->select($sql);
                        $sudah = array();
                        $data = array();
                        $max = 0;
                        $masuk = 0;
                        foreach ($row as $row) {

                            if (!in_array($row->m_gaji_absen_id, $sudah)) {
                                if ($row->nama_gaji == 'Hari Kerja')
                                    $max = round($row->nominal);
                                if ($row->nama_gaji == 'Hari Absen')
                                    $masuk = round($row->nominal);


                                $function = str_replace(' ', '', $row->nama_gaji);
                                echo '<tr>
													<td>' . $row->nama_gaji . '</td>
													<td><input value="' . round($row->nominal) . '" name="summary[' . $row->prl_gaji_detail_id . ']" class="form-control" onkeyup="' . $function . '(this)"></td>
												</tr>';
                                $sudah[] = $row->m_gaji_absen_id;
                            }
                        } ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <h4 class="m-b-10"><strong>Pendapatan</strong></h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <?php
                                    $id_kary = $id;
                                    $sql = "select *,
													case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
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
											 		
											 		";
                                    $row = DB::connection()->select($sql);
                                    $data = array();
                                    $sudah = array();
                                    $total_tunjangan = 0;
                                    foreach ($row as $row) {

                                        if (!in_array($row->nama, $sudah)) {
                                            if ($row->nama == 'Upah Harian')
                                                $total_tunjangan += $row->nominal * $masuk;
                                            else
                                                $total_tunjangan += $row->nominal;



                                            echo '<tr>
													<td>' . $row->nama . '</td>
													<td >' . $help->rupiah2($row->nominal) . '</td>
												</tr>';
                                            $sudah[] = $row->nama;
                                        }
                                    }



                                    echo '<tr>
													<td><h4><b>TOTAL PENDAPATAN</b></h4></td>
													<td>' . $help->rupiah2($total_tunjangan) . '</td>
												</tr>';

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
                                    $id_kary = $id;
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
											 		b.type in (4,5)   
											 		";
                                    $row = DB::connection()->select($sql);
                                    $data = array();
                                    $sudah = array();
                                    $total_potongan = 0;
                                    foreach ($row as $row) {
                                        if (!in_array($row->nama, $sudah)) {
                                            $function = str_replace(' ', '', $row->nama);
                                            $total_potongan += $row->nominal;
                                            echo '<tr>
															<td>' . $row->nama . '</td>
															<td ><div id="' . $function . '">' . $help->rupiah($row->nominal, 0) . '</div>
															 
															<input id="input-' . $function . '" value="' . $row->nominal . '"  name="potongan[' . $row->prl_gaji_detail_id . ']">
															</td>
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
                        <input id="potizin" value="<?= $datapot['izin']; ?>" type="hidden">
                        <input id="potalpha" value="<?= $datapot['alpha']; ?>" type="hidden">
                        <input id="potabsen" value="<?= $datapot['absen']; ?>" type="hidden">
                        <input id="potfingerprint" value="<?= $datapot['fingerprint']; ?>" type="hidden">
                    </div>
                </div>
            </div>

            <button type="submit" name="Cari" class="btn btn-primary" value="Simpan"> Simpan</button>
        </div>

    </form> <!-- /.card -->
    <!-- /.card -->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    function IzinPotonganTanpaKeterangan(e) {
        var izin = $(e).val();
        var nilaiper = $("#potizin").val();

        var nominal = parseInt(izin) * parseInt(nilaiper);
        $('#PotonganIzin').html(format(nominal));
        $('#input-PotonganIzin').val(nominal);



    }

    function TanpaKeterangan(e) {
        var val = $(e).val();
        var nilaiper = $("#potalpha").val();

        var nominal = parseInt(val) * parseInt(nilaiper);
        $('#PotonganAbsen').html(format(nominal));
        $('#input-PotonganAbsen').val(nominal);
    }

    function Terlambat(e) {
        var val = $(e).val();
        var nilaiper = $("#potabsen").val();

        var nominal = parseInt(val) * parseInt(nilaiper);
        $('#PotonganTelat').html(format(nominal));
        $('#input-PotonganTelat').val(nominal);
    }

    function Fingerprint(e) {
        var val = $(e).val();
        var nilaiper = $("#potfingerprint").val();

        var nominal = parseInt(val) * parseInt(nilaiper);
        $('#PotonganFingerprint').html(format(nominal));
        $('#input-PotonganFingerprint').val(nominal);
    }

    function handleNumber(event, mask) {
        /* numeric mask with pre, post, minus sign, dots and comma as decimal separator
            {}: positive integer
            {10}: positive integer max 10 digit
            {,3}: positive float max 3 decimal
            {10,3}: positive float max 7 digit and 3 decimal
            {null,null}: positive integer
            {10,null}: positive integer max 10 digit
            {null,3}: positive float max 3 decimal
            {-}: positive or negative integer
            {-10}: positive or negative integer max 10 digit
            {-,3}: positive or negative float max 3 decimal
            {-10,3}: positive or negative float max 7 digit and 3 decimal
        */
        with(event) {
            stopPropagation()
            preventDefault()
            if (!charCode) return
            var c = String.fromCharCode(charCode)
            if (c.match(/[^-\d,]/)) return
            with(target) {
                var txt = value.substring(0, selectionStart) + c + value.substr(selectionEnd)
                var pos = selectionStart + 1
            }
        }
        var dot = count(txt, /\./, pos)
        txt = txt.replace(/[^-\d,]/g, '')

        var mask = mask.match(/^(\D*)\{(-)?(\d*|null)?(?:,(\d+|null))?\}(\D*)$/);
        if (!mask) return // meglio exception?
        var sign = !!mask[2],
            decimals = +mask[4],
            integers = Math.max(0, +mask[3] - (decimals || 0))
        if (!txt.match('^' + (!sign ? '' : '-?') + '\\d*' + (!decimals ? '' : '(,\\d*)?') + '$')) return

        txt = txt.split(',')
        if (integers && txt[0] && count(txt[0], /\d/) > integers) return
        if (decimals && txt[1] && txt[1].length > decimals) return
        txt[0] = txt[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.')

        with(event.target) {
            value = mask[1] + txt.join(',') + mask[5]
            selectionStart = selectionEnd = pos + (pos == 1 ? mask[1].length : count(value, /\./, pos) - dot)
        }

        function count(str, c, e) {
            e = e || str.length
            for (var n = 0, i = 0; i < e; i += 1)
                if (str.charAt(i).match(c)) n += 1
            return n
        }
    }

    function format(number, prefix = 'Rp ', decimals = 0, decimalSeparator = ',', thousandsSeparator = '.') {
        const roundedNumber = number.toFixed(decimals);
        let integerPart = '',
            fractionalPart = '';
        if (decimals == 0) {
            integerPart = roundedNumber;
            decimalSeparator = '';
        } else {
            let numberParts = roundedNumber.split('.');
            integerPart = numberParts[0];
            fractionalPart = numberParts[1];
        }
        integerPart = prefix + integerPart.replace(/(\d)(?=(\d{3})+(?!\d))/g, `$1${thousandsSeparator}`);
        return `${integerPart}${decimalSeparator}${fractionalPart}`;
    }

    function rupiahtonumber(text) {
        var chars = {
            '.': '',
            ',': '.',
            'R': '',
            'p': '',
            ' ': ''
        };

        text = text.replace(/[.,Rp ]/g, m => chars[m]);


        return text
    }

    function formatRupiah(angka, prefix) {
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');

        return prefix == undefined ? ribuan : (ribuan ? 'Rp ' + ribuan : '');
    }
</script>
@endsection