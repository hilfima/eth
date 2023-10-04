@extends('layouts.app_fe')

@section('content')
<!-- Content Wrapper. Contains page content -->
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
                    <h4 class="card-title float-left mb-0 mt-2">Klarifikasi Absen</h4>

                </div>
            </div>
            <style>
                strong {
                    font-weight: 900;
                }
            </style>

            <!-- /.content-header -->

            <!-- Main content -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Klarifikasi Absen</h3>
                </div>
                <div class="card-body">

                    <form class="form-horizontal" method="POST" action="{!! route('fe.update_klarifikasi_karyawan',$id_chat) !!}" enctype="multipart/form-data">


                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- form start -->
                            {{ csrf_field() }}

                            <!-- ./row -->

                            <div class="form-group">
                                <label>Nama Karyawan</label>
                                <input class="form-control " id="tgl_absen" name="topik" value="<?= $chat_list[0]->nama; ?>" placeholder="Masukan Topik Masalah" readonly>
                            </div>
                            <div class="form-group">
                                <label>Topik</label>
                                <input class="form-control " id="tgl_absen" name="topik" value="<?= $chat_list[0]->topik; ?>" placeholder="Masukan Topik Masalah"  readonly>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Klarifikasi</label>
                                <input class="form-control " id="tgl_absen" type="date" name="tanggal" value="<?= str_replace('Klarifikasi Absen Tanggal ', '', $chat_list[0]->topik) ? str_replace('Klarifikasi Absen Tanggal ', '', $chat_list[0]->topik) : $chat_list[0]->tanggal; ?>" placeholder="Masukan Topik Masalah"  readonly>


                            </div>
                            <div class="form-group">
                                <label>Masalah</label>
                                <select class="form-control " id="tgl_absen" name="tujuan" readonly>
                                    <option value="">Pilih Masalah</option>
                                    <option value="1" <?= $chat_list[0]->tujuan == 1 ? 'selected' : ''; ?>>Absensi - Finger tidak terbaca</option>
                                    <option value="2" <?= $chat_list[0]->tujuan == 2 ? 'selected' : ''; ?>>Absensi - Izin</option>
                                    <option value="3" <?= $chat_list[0]->tujuan == 3 ? 'selected' : ''; ?>>Absensi - Sakit</option>
                                    <option value="4" <?= $chat_list[0]->tujuan == 4 ? 'selected' : ''; ?>>Absensi - Mesin absen Error</option>
                                    <option value="5" <?= $chat_list[0]->tujuan == 5 ? 'selected' : ''; ?>>Absensi - Lainnya</option>
                                    <option value="6" <?= $chat_list[0]->tujuan == 6 ? 'selected' : ''; ?>>Gaji - Kelebihan bayar</option>
                                    <option value="7" <?= $chat_list[0]->tujuan == 7 ? 'selected' : ''; ?>>Gaji - Kekurangan bayar</option>
                                    <option value="8" <?= $chat_list[0]->tujuan == 8 ? 'selected' : ''; ?>>Gaji - Lainnya</option>
                                    <option value="9" <?= $chat_list[0]->tujuan == 9 ? 'selected' : ''; ?>>Lainnya</option>
                                </select>

                            </div>

                            <div class="form-group">
                                <label>File</label>
                                <?php if (!empty($chat_list[0]->file))
                                    echo '
						<a href="' . asset('dist/img/file/' . $chat_list[0]->file) . '" target="_blank" title="Download"><span class="fa fa-download"></span></a>'; ?>

                            </div>
                            <div class="form-group">
                                <label>Atasan*</label>
                                <select class="form-control " name="atasan" style="width: 100%;" readonly>
                                    <option value="">Pilih Atasan</option>
                                    <?php
                                    foreach ($appr as $appr) {
                                        $selected = $chat_list[0]->appr == $appr->p_karyawan_id ? 'selected' : '';
                                        echo '
						<option value="' . $appr->p_karyawan_id . '" ' . $selected . '>' . $appr->nama_lengkap . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="form-control " id="tgl_absen" name="keterangan" placeholder="Deskripsi" readonly><?= $chat_list[0]->deskripsi; ?></textarea>


                            </div>
                            <div class="form-group">
                               <div class="form-group">
	<label>Approval HR</label>
	<select class="form-control " id="tgl_absen" name="appr_hr_status"   readonly>
		<option value="">Pilih Approval</option>
		<option value="1" <?=$chat_list[0]->appr_hr_status==1?'selected':''; ?>>Setujui dan Telah Dirubah</option>
		<option value="2" <?=$chat_list[0]->appr_hr_status==2?'selected':''; ?>>Tolak(Perlu Approve Atasan)</option>
		<option value="4" <?=$chat_list[0]->appr_hr_status==4?'selected':''; ?>>Perlu Konfirmasi Karyawan </option>
	</select>


                            </div>
                            <div class="form-group">
                                <label>Keterangan HR</label>
                                <textarea class="form-control " id="tgl_absen" name="keterangan_hr" placeholder="Deskripsi" readonly><?= $chat_list[0]->keterangan_hr; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>File Bukti dari HR</label>
                                <?php if (!empty($chat_list[0]->file_hr))
                                    echo '
						<a href="' . asset('dist/img/file/' . $chat_list[0]->file_hr) . '" target="_blank" title="Download"><span class="fa fa-download"></span></a>'; ?>

                            </div>
                            <div class="form-group">
                                <label>Approval </label>
                                <select class="form-control " id="tgl_absen" name="appr_status"  readonly>
                                    <option value="">Pilih Approval</option>
                                    <option value="1" <?= $chat_list[0]->appr_status == 1 ? 'selected' : ''; ?>>Setujui</option>
                                    <option value="2" <?= $chat_list[0]->appr_status == 2 ? 'selected' : ''; ?>>Tolak</option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label>Keterangan Atasan</label>
                                <textarea class="form-control " id="tgl_absen" name="keterangan_atasan" placeholder="Keterangan Atasan" readonly><?= $chat_list[0]->keterangan_atasan; ?></textarea>
                            </div>
                             <div class="form-group">
                                <label>File Klarifikasi  <?php if (!empty($chat_list[0]->file_karyawan))
                                    echo '
						<a href="' . asset('dist/img/file/' . $chat_list[0]->file_karyawan) . '" target="_blank" title="Download"><span class="fa fa-download"></span></a>'; ?></label>
                                <input class="form-control " id="tgl_absen" name="file_karyawan" type="file" >
                            </div>
 							<div class="form-group">
                                <label>Keterangan Dari Karyawan</label>
                                <textarea class="form-control " id="tgl_absen" name="keterangan_karyawan" placeholder="Keterangan Atasan" required=""><?= $chat_list[0]->deskripsi_karyawan; ?></textarea>
                            </div>



                        </div>

                        <a href="{!! route('be.agenda_perusahaan') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-edit"></span> Simpan</button>
                        <br>
                        <br>
                </div>
                <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @endsection