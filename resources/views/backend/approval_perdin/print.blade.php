<body>
<style>
    body{
        font-size:12px;
    }
</style>
<center>Nomor : /ST-ETHICAMEGAHMADANI/GA/OPS/X/2023</center>

<table>
    <tr>
        <td>1. Atasan yang memberi tugas</td>
        <td>:</td>
        <td>{!! $data[0]->nama_appr !!}</td>
    </tr>
    <tr>
        <td>2. Nama Pegawai yang bertugas</td>
        <td>:</td>
        <td>{!! $data[0]->nama_lengkap !!}</td>
    </tr>
    <tr>
        <td>3. Jabatan</td>
        <td>:</td>
        <td>{!! $data[0]->jabatan !!}</td>
    </tr>
    <tr>
        <td>4. Maksud Perjalanan Dinas</td>
        <td>:</td>
        <td>{!! $data[0]->keterangan !!}</td>
    </tr>
    <tr>
        <td>5. Alat Transportasi yang digunakan</td>
        <td>:</td>
        <td>{!! $data[0]->nama_alat_transportasi !!}</td>
    </tr>
   
    <tr>
        <td>6. Tempat Tujuan</td>
        <td>:</td>
        <td><?=$data[0]->tempat_tujuan;?></td>
    </tr>
    <tr>
        <td>7. Lamanya Perjalanan Dinas</td>
        <td>:</td>
        <td><?=$data[0]->lama;?></td>
    </tr>
    <tr>
        <td>8. Tanggal Berangkat</td>
        <td>:</td>
        <td>{!! date('d-m-Y', strtotime($data[0]->tgl_awal)) !!}</td>
    </tr>
    <tr>
        <td>9. Tanggal Kembali </td>
        <td>:</td>
        <td>{!! date('d-m-Y', strtotime($data[0]->tgl_akhir)) !!}</td>
    </tr>
    <tr>
        <td>10. Rincian Biaya Perjalanan </td>
        <td>:</td>
        <td>-</td>
    </tr>
    <tr>
        <td>a. Biaya Bensin</td>
        <td>:</td>
        <td><?=$data[0]->biaya_bensin;?></td>
    </tr>
    <tr>
        <td>b. Biaya Tol</td>
        <td>:</td>
        <td><?=$data[0]->biaya_tol;?></td>
    </tr>
    <tr>
        <td>c. Biaya Penginapan</td>
        <td>:</td>
        <td><?=$data[0]->biaya_penginapan;?></td>
    </tr>
    <tr>
        <td>d. Uang Saku</td>
        <td>:</td>
        <td></td>
    </tr>
    <tr>
        <td>e. Uang Makan</td>
        <td>:</td>
        <td><?=$data[0]->biaya_uang_makan;?></td>
    </tr>
    <tr>
        <td>f. Biaya Tiket</td>
        <td>:</td>
        <td><?=$data[0]->biaya_tiket;?></td>
    </tr>
    <tr>
        <td>g. Biaya Transportasi Dalam Kota</td>
        <td>:</td>
        <td><?=$data[0]->biaya_transportasi_dalam_kota;?></td>
    </tr>
    <tr>
        <td>h. Biaya Penyebrangan Kapal</td>
        <td>:</td>
        <td><?=$data[0]->biaya_penyebrangan_kapal;?></td>
    </tr>
    <tr>
        <td>Total</td>
        <td>:</td>
        <td><?=$data[0]->total_biaya;?></td>
    </tr>
</table>

<table style="width:100%;text-align:center">
    <tr>
        <td></td>
        <td>Bandung, <?= $help->tgl_indo(date('Y-m-d'));?></td>
    </tr>
    <tr>
        <td>Menyetujui</td>
        <td>Mengajukan</td>
    </tr>
    <tr>
        <td><br><br><br><br><?=$data[0]->nama_appr;?></td>
        <td><br><br><br><br><?=$data[0]->nama_lengkap;?></td>
    </tr>
</table>

Keterangan : -
</body>