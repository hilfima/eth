@extends('layouts.app_fe')

@section('content')


<div class="row">
    <div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
        <?= view('layouts.app_side'); ?>
    </div>
    <div class="col-xl-9 col-lg-8 col-md-12">
        <div class="card shadow-sm ctm-border-radius">
            <div class="card-body align-center">
                <h4 class="card-title float-left mb-0 mt-2">Tambah Pesan </h4>


            </div>
        </div>
        <form action="{!!route('fe.simpan_chat')!!}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card">
                @include('flash-message')
                <div class="card-body">
                    <div class="form-group">
                        <label>Topik</label>
                        <input class="form-control " id="tgl_absen" name="topik" value="<?php if (isset($_GET['key'])) echo $_GET['key']; ?>" placeholder="Masukan Topik Masalah" required="" <?php if (isset($_GET['key'])) echo 'readonly'; ?>>


                    </div>
                    <div class="form-group">
                        <label>Tanggal Klarifikasi</label>
                        <input class="form-control " id="tgl_absen" type="date" name="tanggal" value="<?php if (isset($_GET['key'])) echo str_replace('Klarifikasi Absen Tanggal ', '', $_GET['key']); ?>" placeholder="Masukan Topik Masalah" required="" <?php if (isset($_GET['key'])) echo 'readonly'; ?>>


                    </div>
                    <div class="form-group">
                        <label>Masalah</label>
                        <select class="form-control " id="tgl_absen" name="tujuan" value="" required="">
                            <option value="">Pilih Masalah</option>
                            <option value="1">Absensi - Finger tidak terbaca</option>
                            <option value="2">Absensi - Izin</option>
                            <option value="3">Absensi - Sakit</option>
                            <option value="4">Absensi - Mesin absen Error</option>
                            <option value="5">Absensi - Lainnya</option>
                            <option value="6">Gaji - Kelebihan bayar</option>
                            <option value="7">Gaji - Kekurangan bayar</option>
                            <option value="8">Gaji - Lainnya</option>
                            <option value="9">Lainnya</option>
                        </select>

                    </div>

                    <div class="form-group">
                        <label>File</label>
                        <input class="form-control " id="tgl_absen" name="file" type="file">
                    </div>
                    <div class="form-group">
                        <label>Jam Masuk(Khusus Absen)</label>

                        <input type="time" class="form-control " id="tgl_absen" name="jam_masuk" value="" placeholder="Deskripsi"></textarea>


                    </div>
                    <div class="form-group">
                        <label>Jam Keluar(Khusus Absen)</label>

                        <input type="time" class="form-control " id="tgl_absen" name="jam_keluar" value="" placeholder="Deskripsi"></textarea>


                    </div>
                    <div class="form-group">
                        <label>Atasan*</label>
                        <select class="form-control select2" name="atasan" style="width: 100%;" required>
                            <option value="">Pilih Atasan</option>
                            <?php
                            foreach ($appr as $appr) {
                                echo '
								<option value="' . $appr->p_karyawan_id . '">' . $appr->nama_lengkap . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>

                        <textarea class="form-control " id="tgl_absen" name="keterangan" value="" placeholder="Deskripsi" required=""></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection