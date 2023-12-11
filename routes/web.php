<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/approve_cuti/{id}', 'Frontend\ApprPermitController@email_approve_cuti')->name('approve_cuti');
Route::get('/reject_cuti/{id}', 'Frontend\ApprPermitController@email_reject_cuti')->name('reject_cuti');
Route::get('/approve_izin/{id}', 'Frontend\ApprPermitController@email_approve_izin')->name('approve_izin');
Route::get('/reject_izin/{id}', 'Frontend\ApprPermitController@email_reject_izin')->name('reject_izin');
Route::get('/approve_perdin/{id}', 'Frontend\ApprPermitController@email_approve_perdin')->name('approve_perdin');
Route::get('/reject_perdin/{id}', 'Frontend\ApprPermitController@email_reject_perdin')->name('reject_perdin');
Route::get('/approve_lembur/{id}', 'Frontend\ApprPermitController@email_approve_lembur')->name('approve_lembur');
Route::get('/reject_lembur/{id}', 'Frontend\ApprPermitController@email_reject_lembur')->name('reject_lembur');
Route::get('/undermaintenance', 'k@undermaintenance')->name('undermaintenance');
 Route::get('/absensi/', 'HomeController@absensi')->name('be.absensi');
Route::get('/', function () {
  
    $sqlslider="select * from m_slider where active=1";
    $slider=DB::connection()->select($sqlslider);
    return view('auth.login', compact('slider'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/historis', 'HomeController@historis')->name('historis');
Route::get('/ultah', 'HomeController@ultah')->name('ultah');
Route::get('/news', 'HomeController@news')->name('news');
Route::get('/admin', 'HomeController@admin')->name('admin');
Route::get('/password', 'HomeController@password')->name('password');
Route::get('/absen_migration', 'HomeController@absen_migration')->name('absen_migration');
Route::get('/optimasi_jabstruk', 'HomeController@optimasi_jabstruk')->name('optimasi_jabstruk');
Route::get('/optimasi_berita', 'HomeController@optimasi_berita')->name('optimasi_berita');
Route::get('/optimasi_total_cuti', 'HomeController@optimasi_total_cuti')->name('optimasi_total_cuti');
Route::get('/optimasi_rekap_absen', 'HomeController@optimasi_rekap_absen')->name('optimasi_rekap_absen');
Route::get('/optimasi_rekap_absen_atasan', 'HomeController@optimasi_rekap_absen_atasan')->name('optimasi_rekap_absen_atasan');
Route::get('/optimasi_fasilitas', 'HomeController@optimasi_fasilitas')->name('optimasi_fasilitas');
Route::get('/optimasi_today_info', 'HomeController@optimasi_today_info')->name('optimasi_today_info');
Route::post('/simpan_password', 'HomeController@simpan_password')->name('simpan_password');
Route::get('/recovery_password/{id}', 'HomeController@recovery_password')->name('be.recovery_password');
Route::get('/import_jabatan/', 'HomeController@import_jabatan')->name('be.import_jabatan');
Route::get('/login_admin', 'Auth\AdminController@index')->name('login_admin');
Route::get('/generate_hari_ini', 'CronJobController@generate_hari_ini')->name('generate_hari_ini');
Route::get('/generate_sp_st', 'CronJobController@generate_sp_st')->name('generate_sp_st');
Route::get('/sp_st_auto/{id}/{id2}/{id3}', 'Backend\QueueController@sp_st_auto')->name('sp_st_auto');
Route::get('/generate_absen_tanggal/{tanggal}', 'CronJobController@generate_absen_tanggal')->name('generate_absen_tanggal');
Route::get('/form_biodata_kandidat', 'RekruitmentController@form_biodata_kandidat')->name('form_biodata_kandidat');
Route::get('/edit_form_biodata_kandidat/{id}', 'RekruitmentController@edit_form_biodata_kandidat')->name('edit_form_biodata_kandidat');
Route::get('/view_form_biodata_kandidat/{id}', 'RekruitmentController@view_form_biodata_kandidat')->name('view_form_biodata_kandidat');
Route::post('/save_form_biodata_kandidat', 'RekruitmentController@save_form_biodata_kandidat')->name('save_form_biodata_kandidat');

//Route::get('/login', 'Auth\AdminController@login')->name('login');
Route::POST('/get_login_admin', 'Auth\AdminController@get_login')->name('get_login_admin');
/*Route::get('/logout', function (){
    return view('auth.login');
});*/
Route::group(['prefix' => 'frontend'], function(){
    
	Route::get('/anggota_club/{id}', 'Frontend\ClubController@anggota_club')->name('fe.anggota_club');
    Route::get('/hapus_anggota_club/{id}/{id_anggota}', 'Frontend\ClubController@hapus_anggota_club')->name('fe.hapus_anggota_club');
    Route::get('/foto_kegiatan_club/{id}/{id_anggota}', 'Frontend\ClubController@foto_kegiatan_club')->name('fe.foto_kegiatan_club');
    Route::get('/tambah_foto_kegiatan_club/{id}/{id_anggota}', 'Frontend\ClubController@tambah_foto_kegiatan_club')->name('fe.tambah_foto_kegiatan_club');
    Route::post('/simpan_foto_kegiatan_club/{id}/{id_anggota}', 'Frontend\ClubController@simpan_foto_kegiatan_club')->name('fe.simpan_foto_kegiatan_club');
    Route::get('/hapus_foto_club/{id}/{id_anggota}/{id_foto}', 'Frontend\ClubController@hapus_foto_club')->name('fe.hapus_foto_club');
    Route::get('/tambah_anggota_club/{id}', 'Frontend\ClubController@tambah_anggota_club')->name('fe.tambah_anggota_club');
    Route::post('/simpan_anggota_club/{id}', 'Frontend\ClubController@simpan_anggota_club')->name('fe.simpan_anggota_club');
    Route::get('/kegiatan_club/{id}', 'Frontend\ClubController@kegiatan_club')->name('fe.kegiatan_club');
    Route::get('/galeri_club/{id}', 'Frontend\ClubController@galeri_club')->name('fe.galeri_club');
    Route::get('/tambah_kegiatan_club/{id}', 'Frontend\ClubController@tambah_kegiatan_club')->name('fe.tambah_kegiatan_club');
    Route::post('/simpan_kegiatan_club/{id}', 'Frontend\ClubController@simpan_kegiatan_club')->name('fe.simpan_kegiatan_club');
    Route::get('/club', 'Frontend\ClubController@club')->name('fe.club');
    Route::get('/tambah_club', 'Frontend\ClubController@tambah_club')->name('fe.tambah_club');
    Route::post('/simpan_club', 'Frontend\ClubController@simpan_club')->name('fe.simpan_club');
    Route::get('/edit_club/{id}', 'Frontend\ClubController@edit_club')->name('fe.edit_club');
    Route::get('/hapus_club/{id}', 'Frontend\ClubController@hapus_club')->name('fe.hapus_club');
    
    Route::get('/kpi_detail/{id}', 'Frontend\KPIController@kpi_detail')->name('fe.kpi_detail');
    Route::get('/kpi_review/{id}', 'Frontend\KPIController@kpi_review')->name('fe.kpi_review');
    Route::get('/kpi_review_detail/{id}', 'Frontend\KPIController@kpi_review_detail')->name('fe.kpi_review_detail');
    Route::get('/hapus_kpi/{id}', 'Frontend\KPIController@hapus_kpi')->name('fe.hapus_kpi');
    Route::get('/evaluasi_tahunan/{id}', 'Frontend\KPIController@evaluasi_tahunan')->name('fe.evaluasi_tahunan');
    Route::get('/pdf_evaluasi_tahunan/{id}', 'Frontend\KPIController@pdf_evaluasi_tahunan')->name('fe.pdf_evaluasi_tahunan');
    Route::get('/mentoring_kpi/{id}', 'Frontend\KPIController@mentoring_kpi')->name('fe.mentoring_kpi');
    Route::get('/tambah_kpi_detail/{id}', 'Frontend\KPIController@tambah_kpi_detail')->name('fe.tambah_kpi_detail');
    Route::get('/edit_kpi/{id}', 'Frontend\KPIController@edit_kpi')->name('fe.edit_kpi');
    Route::post('/update_kpi/{id}', 'Frontend\KPIController@update_kpi')->name('fe.update_kpi');
    Route::get('/edit_kpi_detail/{id}/{id2}', 'Frontend\KPIController@edit_kpi_detail')->name('fe.edit_kpi_detail');
    Route::post('/simpan_kpi_penilaian/{id}', 'Frontend\KPIController@simpan_kpi_penilaian')->name('fe.simpan_kpi_penilaian');
    Route::post('/simpan_kpi_detail/{id}', 'Frontend\KPIController@simpan_kpi_detail')->name('fe.simpan_kpi_detail');
    Route::post('/update_kpi_detail/{id}/{id2}', 'Frontend\KPIController@update_kpi_detail')->name('fe.update_kpi_detail');
    Route::get('/hapus_kpi_detail/{id}/{id2}', 'Frontend\KPIController@hapus_kpi_detail')->name('fe.hapus_kpi_detail');
    Route::post('/simpan_kpi_pencapaian/{id}/{id2}/{type}', 'Frontend\KPIController@simpan_kpi_pencapaian')->name('fe.simpan_kpi_pencapaian');
    Route::post('/simpan_kpi_pencapaian_all/{id}/{id2}/{type}', 'Frontend\KPIController@simpan_kpi_pencapaian_all')->name('fe.simpan_kpi_pencapaian_all');
    Route::get('/edit_pencapaian/{id}/{id2}/{type}', 'Frontend\KPIController@edit_pencapaian')->name('fe.edit_tw');
    Route::get('/edit_pencapaian_all/{id}/{id2}/{type}', 'Frontend\KPIController@edit_pencapaian_all')->name('fe.edit_all_tw');
    Route::get('/kpi', 'Frontend\KPIController@kpi')->name('fe.kpi');
    
    Route::get('/penilaian_kpi/{id}', 'Frontend\KPIController@penilaian_kpi')->name('fe.penilaian_kpi');
    Route::get('/form_penilaian_kpi/{id}', 'Frontend\KPIController@penilaian_kpi')->name('fe.penilaian_kpi');
    
    Route::get('/approval_kpi', 'Frontend\KPIController@approval_kpi')->name('fe.approval_kpi');
    Route::get('/approval_kpi_detail/{id}', 'Frontend\KPIController@approval_kpi_detail')->name('fe.approval_kpi_detail');
    Route::get('/approval_parameter_kpi', 'Frontend\KPIController@approval_parameter_kpi')->name('fe.approval_parameter_kpi');
    Route::get('/acc_kpi_parameter/{id}/{id2}/{id3}', 'Frontend\KPIController@acc_kpi_parameter')->name('fe.acc_kpi_parameter');
    Route::get('/dec_kpi_parameter/{id}/{id2}/{id3}', 'Frontend\KPIController@dec_kpi_parameter')->name('fe.dec_kpi_parameter');
    Route::get('/acc_kpi_pengajuan/{id}/{id2}', 'Frontend\KPIController@acc_kpi_pengajuan')->name('fe.acc_kpi_pangajuan');
    Route::get('/dec_kpi_pengajuan/{id}/{id2}', 'Frontend\KPIController@dec_kpi_pengajuan')->name('fe.dec_kpi_pangajuan');
    Route::get('/acc_kpi_1/{id}', 'Frontend\KPIController@acc_kpi_1')->name('fe.acc_kpi_1');
    Route::get('/acc_kpi_2/{id}', 'Frontend\KPIController@acc_kpi_2')->name('fe.acc_kpi_2');
    Route::get('/dec_kpi_1/{id}', 'Frontend\KPIController@dec_kpi_1')->name('fe.dec_kpi_1');
    Route::get('/dec_kpi_2/{id}', 'Frontend\KPIController@dec_kpi_2')->name('fe.dec_kpi_2');
    Route::get('/tambah_kpi', 'Frontend\KPIController@tambah_kpi')->name('fe.tambah_kpi');
    Route::post('/simpan_kpi', 'Frontend\KPIController@simpan_kpi')->name('fe.simpan_kpi');
    Route::get('/penilaian_kpi', 'Frontend\KPIController@penilaian_kpi')->name('fe.penilaian_kpi');
    Route::get('/form_penilaian_kpi/{id}', 'Frontend\KPIController@form_penilaian_kpi')->name('fe.form_penilaian_kpi');
    
    
    Route::get('/sop', 'Frontend\SopController@sop')->name('fe.sop');
    Route::get('/baca_sop/{id}', 'Frontend\SopController@baca_sop')->name('fe.baca_sop');
    Route::post('/selesai_baca_sop/{id}', 'Frontend\SopController@selesai_baca_sop')->name('fe.selesai_baca_sop');
    Route::get('/perintah_lembur', 'Frontend\LemburController@perintah_lembur')->name('fe.perintah_lembur');
    Route::get('/tambah_perintah_lembur', 'Frontend\LemburController@tambah_perintah_lembur')->name('fe.tambah_perintah_lembur');
    Route::post('/simpan_perintah_lembur', 'Frontend\LemburController@simpan_perintah_lembur')->name('fe.simpan_perintah_lembur');
    Route::post('/lembur_duplicate_check_multi', 'Frontend\LemburController@lembur_duplicate_check_multi')->name('fe.lembur_duplicate_check_multi');
    Route::get('/acc_intruksi_lembur/{id}', 'Frontend\LemburController@acc_intruksi_lembur')->name('fe.acc_intruksi_lembur');
    Route::get('/dec_intruksi_lembur/{id}', 'Frontend\LemburController@dec_intruksi_lembur')->name('fe.dec_intruksi_lembur');
    Route::post('/lembur_duplicate_check', 'Frontend\PermitController@lembur_duplicate_check')->name('fe.lembur_duplicate_check');
    Route::get('/generate_jam_finger', 'Frontend\PermitController@generate_jam_finger')->name('fe.generate_jam_finger');
    Route::get('/generate_jam_finger_klarifikasi', 'Frontend\PermitController@generate_jam_finger_klarifikasi')->name('fe.generate_jam_finger_klarifikasi');
    Route::get('/approval_lintas_mesin_absen', 'Frontend\PermitController@approval_lintas_mesin_absen')->name('fe.approval_lintas_mesin_absen');
    Route::get('/lembur_duplicate_check_single', 'Frontend\LemburController@lembur_duplicate_check')->name('fe.lembur_duplicate_check_single');
    Route::get('/generate_jam_finger', 'CronJobController@generate_jam_finger')->name('fe.generate_jam_finger');
    Route::get('/generate_jam_finger_klarifikasi', 'CronJobController@generate_jam_finger_klarifikasi')->name('fe.generate_jam_finger_klarifikasi');
    Route::get('/hitung_jam_lembur', 'Frontend\PermitController@hitung_jam_lembur')->name('fe.hitung_jam_lembur');
    Route::get('/hitung_hari', 'Frontend\PermitController@hitung_hari')->name('fe.hitung_hari');
    Route::get('/hitung_hari_tanpa_libur', 'Frontend\PermitController@hitung_hari_tanpa_libur')->name('fe.hitung_hari_tanpa_libur');
    Route::get('/list_filepajak', 'Frontend\FilePajakController@filepajak')->name('fe.filepajak');
    Route::get('/tambah_filepajak', 'Frontend\FilePajakController@tambah_filepajak')->name('fe.tambah_filepajak');
    Route::post('/simpan_filepajak', 'Frontend\FilePajakController@simpan_filepajak')->name('fe.simpan_filepajak');
    Route::post('/simpan_multifilepajak', 'Frontend\FilePajakController@simpan_multifilepajak')->name('fe.simpan_multifilepajak');
    Route::get('/edit_filepajak/{id}', 'Frontend\FilePajakController@edit_filepajak')->name('fe.edit_filepajak');
    Route::post('/update_filepajak/{id}', 'Frontend\FilePajakController@update_filepajak')->name('fe.update_filepajak');
    Route::get('/hapus_filepajak/{id}', 'Frontend\FilePajakController@hapus_filepajak')->name('be.hapus_filepajak');

    Route::get('/get_jam_permit', 'Frontend\PermitController@get_jam_permit')->name('fe.get_jam_permit');
    
    
    Route::get('/approval_pergantian_hari_libur', 'Frontend\HariLiburController@approval_pergantian_hari_libur')->name('fe.approval_pergantian_hari_libur');
    Route::get('/view_approval_pergantian_hari_libur/{id}', 'Frontend\HariLiburController@view_approval_pergantian_hari_libur')->name('fe.view_approval_pergantian_hari_libur');
    Route::get('/update_approval_pergantian_hari_libur/{id}', 'Frontend\HariLiburController@update_approval_pergantian_hari_libur')->name('fe.update_approval_pergantian_hari_libur');
    Route::get('/acc_pergantian_hari_libur/{id}', 'Frontend\HariLiburController@acc_pergantian_hari_libur')->name('fe.acc_pergantian_hari_libur');
    Route::get('/dec_pergantian_hari_libur/{id}', 'Frontend\HariLiburController@dec_pergantian_hari_libur')->name('fe.dec_pergantian_hari_libur');
    Route::get('/pergantian_hari_libur', 'Frontend\HariLiburController@pergantian_hari_libur')->name('fe.pergantian_hari_libur');
    Route::get('/view_pergantian_hari_libur/{id}', 'Frontend\HariLiburController@view_pergantian_hari_libur')->name('fe.view_pergantian_hari_libur');
    Route::get('/hapus_pergantian_hari_libur/{id}', 'Frontend\HariLiburController@hapus_pergantian_hari_libur')->name('fe.hapus_pergantian_hari_libur');
    Route::get('/tambah_pergantian_hari_libur', 'Frontend\HariLiburController@tambah_pergantian_hari_libur')->name('fe.tambah_pergantian_hari_libur');
    Route::post('/simpan_pergantian_hari_libur', 'Frontend\HariLiburController@simpan_pergantian_hari_libur')->name('fe.simpan_pergantian_hari_libur');
   
    Route::get('/karyawan_bawahan', 'Frontend\KaryawanController@karyawan_bawahan')->name('fe.karyawan_bawahan');
    Route::get('/lihat_karyawan/{id}', 'Frontend\KaryawanController@lihat_karyawan')->name('fe.lihat_karyawan');
    Route::get('/karyawan_baru', 'Frontend\PengajuanKaryawanController@karyawan_baru')->name('fe.karyawan_baru');
   
    
    Route::get('/approval_karyawan_baru', 'Frontend\PengajuanKaryawanController@approval_karyawan_baru')->name('fe.approval_karyawan_baru');
    Route::get('/upload_interview/{id}/{id2}', 'Frontend\PengajuanKaryawanController@upload_interview')->name('fe.upload_interview');
    Route::post('/simpan_upload_interview/{id}/{id2}', 'Frontend\PengajuanKaryawanController@simpan_upload_interview')->name('fe.simpan_upload_interview');
    
    
    Route::get('/karyawan_baru', 'Frontend\PengajuanKaryawanController@karyawan_baru')->name('fe.karyawan_baru');
    Route::get('/appr_mudepro', 'Frontend\PerpindahanKaryawanController@appr_mudepro')->name('fe.appr_mudepro');
    Route::get('/edit_appr_mudepro/{id}', 'Frontend\PerpindahanKaryawanController@edit_appr_mudepro')->name('fe.edit_appr_mudepro');
    Route::post('/update_appr_mudepro/{id}', 'Frontend\PerpindahanKaryawanController@update_appr_mudepro')->name('fe.update_appr_mudepro');
    
    
    
    Route::get('/mudepro', 'Frontend\PerpindahanKaryawanController@mudepro')->name('fe.mudepro');
    Route::get('/tambah_mudepro', 'Frontend\PerpindahanKaryawanController@tambah_mudepro')->name('fe.tambah_mudepro');
    Route::post('/simpan_mudepro', 'Frontend\PerpindahanKaryawanController@simpan_mudepro')->name('fe.simpan_mudepro');
    Route::get('/view_karyawan_baru/{id}', 'Frontend\PengajuanKaryawanController@view_karyawan_baru')->name('fe.view_karyawan_baru');
    Route::get('/tambah_karyawan_baru', 'Frontend\PengajuanKaryawanController@tambah_karyawan_baru')->name('fe.tambah_karyawan_baru');
    Route::post('/simpan_karyawan_baru', 'Frontend\PengajuanKaryawanController@simpan_karyawan_baru')->name('fe.simpan_karyawan_baru');
    Route::get('/edit_karyawan_baru/{id}', 'Frontend\PengajuanKaryawanController@edit_karyawan_baru')->name('fe.edit_karyawan_baru');
    Route::post('/update_karyawan_baru/{id}', 'Frontend\PengajuanKaryawanController@update_karyawan_baru')->name('fe.update_karyawan_baru');
    Route::get('/hapus_karyawan_baru/{id}', 'Frontend\PengajuanKaryawanController@hapus_karyawan_baru')->name('fe.hapus_karyawan_baru');

	Route::get('/list_database_kandidat/{id}', 'Frontend\PengajuanKaryawanController@list_database_kandidat')->name('fe.list_database_kandidat');
	Route::get('/database_kandidat/', 'Frontend\PengajuanKaryawanController@database_kandidat')->name('fe.database_kandidat');
	Route::get('/approve_kandidat/{id}/{id2}', 'Frontend\PengajuanKaryawanController@approve_kandidat')->name('fe.approve_kandidat');
	Route::get('/decline_kandidat/{id}/{id2}', 'Frontend\PengajuanKaryawanController@decline_kandidat')->name('fe.decline_kandidat');
	Route::get('/approve_review/{id}/{id2}', 'Frontend\PengajuanKaryawanController@approve_review')->name('fe.approve_review');
	Route::get('/decline_review/{id}/{id2}', 'Frontend\PengajuanKaryawanController@decline_review')->name('fe.decline_review');
	Route::get('/acc_karyawan_baru/{id}', 'Frontend\PengajuanKaryawanController@acc_karyawan_baru')->name('fe.acc_karyawan_baru');
	Route::get('/dec_karyawan_baru/{id}', 'Frontend\PengajuanKaryawanController@dec_karyawan_baru')->name('fe.dec_karyawan_baru');
	Route::get('/acc_karyawan_baru2/{id}', 'Frontend\PengajuanKaryawanController@acc_karyawan_baru2')->name('fe.acc_karyawan_baru2');
	Route::get('/dec_karyawan_baru2/{id}', 'Frontend\PengajuanKaryawanController@dec_karyawan_baru2')->name('fe.dec_karyawan_baru2');
	
    
    Route::get('/laporan_cuti', 'Frontend\CutiController@laporan_cuti')->name('fe.laporan_cuti');
    Route::get('/generate_cuti', 'Frontend\CutiController@generate_cuti')->name('fe.generate_cuti');
    
    
    Route::get('/bpjs', 'Frontend\BpjsController@bpjs')->name('fe.bpjs');
    Route::get('/tambah_cover_bpjs', 'Frontend\BpjsController@tambah_cover_bpjs')->name('fe.tambah_cover_bpjs');
    Route::post('/simpan_cover_bpjs', 'Frontend\BpjsController@simpan_cover_bpjs')->name('fe.simpan_cover_bpjs');
    
    Route::get('/keluhkesah', 'Frontend\KeluhkesahController@keluhkesah')->name('fe.keluh_kesah');
	Route::get('/baca_keluh_kesah/{id}', 'Frontend\KeluhkesahController@baca_keluh_kesah')->name('fe.baca_keluh_kesah');
    Route::get('/tambah_keluhkesah', 'Frontend\KeluhkesahController@tambah_keluhkesah')->name('fe.tambah_keluhkesah');
    Route::post('/simpan_keluhkesah', 'Frontend\KeluhkesahController@simpan_keluhkesah')->name('fe.simpan_keluhkesah');
    
    Route::get('/faskes', 'Frontend\FaskesController@faskes')->name('fe.faskes');
    Route::get('/generate_kurang_faskes_penyesuaian', 'Frontend\FaskesController@generate_kurang_faskes_penyesuaian')->name('fe.generate_kurang_faskes_penyesuaian');
    Route::get('/generate_faskes', 'Frontend\FaskesController@generate_faskes')->name('fe.generate_faskes');
    Route::get('/update_generate_saldo', 'Frontend\FaskesController@update_generate_saldo')->name('fe.update_generate_saldo');
    Route::get('/pengajuan_faskes', 'Frontend\FaskesController@pengajuan_faskes')->name('fe.pengajuan_faskes');
    Route::get('/hapus_pengajuan_faskes/{id}', 'Frontend\FaskesController@hapus_pengajuan_faskes')->name('fe.hapus_pengajuan_faskes');
    Route::post('/simpan_faskes', 'Frontend\FaskesController@simpan_faskes')->name('fe.simpan_faskes');
     
    
    Route::get('/jabatan_entitas', 'Frontend\PerpindahanKaryawanController@jabatan_entitas')->name('fe.jabatan_entitas');
    
    Route::get('/karyawan_klarifikasi/{id}', 'Frontend\ChatController@karyawan_klarifikasi')->name('fe.karyawan_klarifikasi');
    Route::post('/update_klarifikasi_karyawan/{id}', 'Frontend\ChatController@update_klarifikasi_karyawan')->name('fe.update_klarifikasi_karyawan');
    Route::get('/chat_list', 'Frontend\ChatController@chat_list')->name('fe.chat_list');
    Route::get('/chat_room/{id}', 'Frontend\ChatController@chat_room')->name('fe.chat_room');
    Route::get('/content_chat/{id}', 'Frontend\ChatController@content_chat')->name('fe.content_chat');
    Route::get('/send_chat/{id}', 'Frontend\ChatController@send_chat')->name('fe.send_chat');
    Route::post('/simpan_chat2', 'Frontend\ChatController@simpan_chat')->name('fe.simpan_chat');
    Route::get('/tambah_chat', 'Frontend\ChatController@tambah_chat')->name('fe.tambah_chat');
    
    
    Route::get('/laporan_atasan', 'Frontend\LaporanController@laporan_atasan')->name('fe.laporan_atasan');
    Route::get('/jobdesk', 'Frontend\PekerjaanController@jobdesk')->name('fe.jobdesk');
    Route::get('/struktur_organisasi', 'Frontend\PekerjaanController@struktur_organisasi')->name('fe.struktur_organisasi');
    Route::get('/hari_libur_shift', 'Frontend\ShiftController@hari_libur_shift')->name('fe.hari_libur_shift');
    Route::get('/jadwal_shift', 'Frontend\ShiftController@jadwal_shift')->name('fe.jadwal_shift');
    Route::get('/tambah_shift', 'Frontend\ShiftController@tambah_shift')->name('fe.tambah_shift');
    Route::get('/get_template_shift', 'Frontend\ShiftController@get_template_shift')->name('fe.get_template_shift');
    Route::post('/simpan_shift', 'Frontend\ShiftController@simpan_shift')->name('fe.simpan_shift');
	Route::post('/update_shift_karyawan/{id}', 'Frontend\ShiftController@update_shift_karyawan')->name('fe.update_shift_karyawan');
    Route::post('/simpan_shift_multiple', 'Frontend\ShiftController@simpan_shift_multiple')->name('fe.simpan_shift_multiple');
    Route::get('/tambah_shift_excel', 'Frontend\ShiftController@tambah_shift_excel')->name('fe.tambah_shift_excel');
    Route::post('/simpan_excel_shift', 'Frontend\ShiftController@simpan_excel_shift')->name('fe.simpan_excel_shift');
    Route::get('/excel_shift', 'Frontend\ShiftController@excel_shift')->name('fe.excel_shift');
    Route::get('/edit_shift/{id}', 'Frontend\ShiftController@edit_shift')->name('fe.edit_shift');
    Route::post('/update_shift/{id}', 'Frontend\ShiftController@update_shift')->name('fe.update_shift');
    Route::get('/hapus_shift/{id}', 'Frontend\ShiftController@hapus_shift')->name('fe.hapus_shift');
    Route::get('/tambah_libur_shift', 'Frontend\ShiftController@tambah_libur_shift')->name('fe.tambah_libur_shift');
    Route::post('/simpan_libur_shift', 'Frontend\ShiftController@simpan_libur_shift')->name('fe.simpan_libur_shift');
    Route::get('/edit_libur_shift/{id}', 'Frontend\ShiftController@edit_libur_shift')->name('fe.edit_libur_shift');
    Route::post('/update_libur_shift/{id}', 'Frontend\ShiftController@update_libur_shift')->name('fe.update_libur_shift');
    Route::get('/hapus_libur_shift/{id}', 'Frontend\ShiftController@hapus_libur_shift')->name('fe.hapus_libur_shift');
    
    Route::get('/penjadwalan_shift', 'Frontend\ShiftController@penjadwalan_shift')->name('fe.penjadwalan_shift');
    Route::get('/status_persetujuan', 'Frontend\PermitController@status_persetujuan')->name('fe.status_persetujuan');
    Route::get('/lihat_permit/{id}', 'Frontend\PermitController@lihat')->name('fe.lihat_permit');
    Route::get('/slip', 'Frontend\Fe_SlipController@slip')->name('fe.slip');
    Route::get('/slip_thr', 'Frontend\Fe_SlipController@slip_thr')->name('fe.slip_thr');
    Route::get('/kehadiran', 'Frontend\KehadiranController@rekap_absen')->name('fe.kehadiran');
    Route::get('/kehadiran_tahunan', 'Frontend\KehadiranController@rekap_absen_tahunan')->name('fe.kehadiran_tahunan');
  
    Route::get('/lihat_slip/', 'Frontend\Fe_SlipController@lihat_slip')->name('fe.lihat_slip');
    Route::get('/download_slip/{id}', 'Frontend\Fe_SlipController@download_slip')->name('fe.download_slip');
    Route::get('/ptest', 'Frontend\PTestController@ptest')->name('fe.ptest');
    Route::get('/rmib', 'Frontend\PTestController@rmib')->name('fe.rmib');
    Route::get('/tambah_rmib', 'Frontend\PTestController@tambah_rmib')->name('fe.tambah_rmib');
    Route::post('/simpan_rmib', 'Frontend\PTestController@simpan_rmib')->name('fe.simpan_rmib');
    Route::post('/simpan_rmib_last', 'Frontend\PTestController@simpan_rmib_last')->name('fe.simpan_rmib_last');
    Route::get('/view_rmib/{id}', 'Frontend\PAController@view_rmib')->name('fe.view_rmib');
    Route::get('/edit_rmib/{id}', 'Frontend\PAController@edit_rmib')->name('fe.edit_rmib');
    Route::post('/update_rmib/{id}', 'Frontend\PAController@update_rmib')->name('fe.update_rmib');
    Route::post('/hapus_rmib/{id}', 'Frontend\PAController@hapus_rmib')->name('fe.hapus_rmib');
    Route::get('/pa', 'Frontend\PAController@pa')->name('fe.pa');
    Route::get('/tambah_pa', 'Frontend\PAController@tambah_pa')->name('fe.tambah_pa');
    Route::post('/simpan_pa', 'Frontend\PAController@simpan_pa')->name('fe.simpan_pa');
    Route::get('/view_pa/{id}', 'Frontend\PAController@view_pa')->name('fe.view_pa');
    Route::get('/edit_pa/{id}', 'Frontend\PAController@edit_pa')->name('fe.edit_pa');
    Route::post('/update_pa/{id}', 'Frontend\PAController@update_pa')->name('fe.update_pa');
    Route::get('/approve_pa/{id}', 'Frontend\PAController@approve_pa')->name('fe.approve_pa');
    Route::post('/save_approve_pa/{id}', 'Frontend\PAController@save_approve_pa')->name('fe.save_approve_pa');
    Route::get('/hapus_pa/{id}', 'Frontend\PAController@hapus_pa')->name('fe.hapus_pa');

    /*Profile*/
    Route::get('/hapus_award/{id}', 'Frontend\ProfileController@hapus_award')->name('fe.hapus_award');
    Route::get('/hapus_keluarga/{id}', 'Frontend\ProfileController@hapus_keluarga')->name('fe.hapus_keluarga');
    Route::get('/hapus_pakaian/{id}', 'Frontend\ProfileController@hapus_pakaian')->name('fe.hapus_pakaian');
    Route::get('/hapus_kursus/{id}', 'Frontend\ProfileController@hapus_kursus')->name('fe.hapus_kursus');
    Route::get('/hapus_pendidikan/{id}', 'Frontend\ProfileController@hapus_pendidikan')->name('fe.hapus_pendidikan');
    Route::get('/hapus_pekerjaan/{id}', 'Frontend\ProfileController@hapus_pekerjaan')->name('fe.hapus_pekerjaan');
    Route::get('/edit_profile', 'Frontend\ProfileController@edit_profile')->name('fe.edit_profile');
    Route::post('/update_profile/{id}', 'Frontend\ProfileController@update_profile')->name('fe.update_profile');
    Route::post('/update_profile_utama/{id}', 'Frontend\ProfileController@update_profile_utama')->name('fe.update_profile_utama');
    /*Ubah Password*/
    Route::get('/edit_password', 'Frontend\ProfileController@edit_password')->name('fe.edit_password');
    Route::post('/update_password/{id}', 'Frontend\ProfileController@update_password')->name('fe.update_password');
    /*absen*/
    Route::get('/absen_pro', 'Frontend\AbsenProController@cari_absenpro')->name('fe.absenpro');
    Route::get('/cari_absen_pro', 'Frontend\AbsenProController@cari_absenpro')->name('fe.cari_absenpro');

    /*gallery*/
    Route::get('/show_gallery', 'Frontend\GalleryController@show_gallery')->name('fe.show_gallery');
    Route::get('/show_gallery_detil/{id}', 'Frontend\GalleryController@show_gallery_detail')->name('fe.show_gallery_detail');

    /*fasilitas*/
    Route::get('/show_fasilitas', 'Frontend\FasilitasController@show_fasilitas')->name('fe.show_fasilitas');
    Route::get('/download_fasilitas/{id}', 'Frontend\FasilitasController@download_fasilitas')->name('fe.download_fasilitas');

    /*Permit*/
   
    
    Route::get('/permit', 'Frontend\PermitController@permit')->name('fe.permit');
    Route::get('/get_izin_detail', 'Frontend\PermitController@get_izin_detail')->name('fe.get_izin_detail');
    Route::get('/list_perdin', 'Frontend\PermitController@list_perdin')->name('fe.list_perdin');
    Route::get('/list_cuti', 'Frontend\PermitController@list_cuti')->name('fe.list_cuti');
    Route::get('/list_izin', 'Frontend\PermitController@list_izin')->name('fe.list_izin');
    Route::get('/list_lembur', 'Frontend\PermitController@list_lembur')->name('fe.list_lembur');
    Route::get('/tambah_perdin', 'Frontend\PermitController@tambah_perdin')->name('fe.tambah_perdin');
    Route::get('/tambah_cuti', 'Frontend\PermitController@tambah_cuti')->name('fe.tambah_cuti');
    Route::get('/tambah_izin', 'Frontend\PermitController@tambah_izin')->name('fe.tambah_izin');
    Route::get('/tambah_lembur', 'Frontend\PermitController@tambah_lembur')->name('fe.tambah_lembur');
    Route::post('/simpan_perdin', 'Frontend\PermitController@simpan_perdin')->name('fe.simpan_perdin');
    Route::post('/simpan_cuti', 'Frontend\PermitController@simpan_cuti')->name('fe.simpan_cuti');
    Route::post('/simpan_izin', 'Frontend\PermitController@simpan_izin')->name('fe.simpan_izin');
    Route::post('/simpan_lembur', 'Frontend\PermitController@simpan_lembur')->name('fe.simpan_lembur');
    Route::get('/lihat_perdin/{id}', 'Frontend\PermitController@lihat_perdin')->name('fe.lihat_perdin');
    Route::get('/lihat_cuti/{id}', 'Frontend\PermitController@lihat_cuti')->name('fe.lihat_cuti');
    Route::get('/lihat_izin/{id}', 'Frontend\PermitController@lihat_izin')->name('fe.lihat_izin');
    Route::get('/hapus_cuti/{id}', 'Frontend\PermitController@hapus_cuti')->name('fe.hapus_cuti');
    Route::get('/hapus_perdin/{id}', 'Frontend\PermitController@hapus_perdin')->name('fe.hapus_perdin');
    Route::get('/hapus_izin/{id}', 'Frontend\PermitController@hapus_izin')->name('fe.hapus_izin');
    Route::get('/hapus_lembur/{id}', 'Frontend\PermitController@hapus_lembur')->name('fe.hapus_lembur');
    Route::get('/lihat_lembur/{id}', 'Frontend\PermitController@lihat_lembur')->name('fe.lihat_lembur');
    Route::get('/email_appr', 'Frontend\PermitController@email_appr')->name('fe.email_appr');

    Route::get('/team', 'Frontend\TeamController@team')->name('fe.team');
    
	//Route::get('/listed', 'homeController@undermaintenance')->name('undermaintenance');
    Route::get('/listed', 'Frontend\PermitController@listed')->name('fe.listed');
    Route::get('/lihat_ajuan/{id}', 'Frontend\PermitController@lihat_ajuan')->name('fe.lihat_ajuan');
    Route::get('/edit_ajuan/{id}', 'Frontend\PermitController@edit_ajuan')->name('fe.edit_ajuan');
    Route::post('/approve_ajuan/{id}', 'Frontend\PermitController@approve_ajuan')->name('fe.approve_ajuan');    
    Route::get('/setujui_ajuan/{id}', 'Frontend\PermitController@setujui_ajuan')->name('fe.setujui_ajuan');    
    Route::get('/tolak_ajuan/{id}', 'Frontend\PermitController@tolak_ajuan')->name('fe.tolak_ajuan');    
    Route::get('/setujui_lintas/{id}', 'Frontend\PermitController@setujui_lintas')->name('fe.setujui_lintas');    
    Route::get('/tolak_lintas/{id}', 'Frontend\PermitController@tolak_lintas')->name('fe.tolak_lintas');    
    Route::get('/setujui_ajuan_2/{id}', 'Frontend\PermitController@setujui_ajuan_2')->name('fe.setujui_ajuan_2');    
    Route::get('/tolak_ajuan_2/{id}', 'Frontend\PermitController@tolak_ajuan_2')->name('fe.tolak_ajuan_2');    
    
    Route::get('/lihat_ajuan_2/{id}', 'Frontend\PermitController@lihat_ajuan_2')->name('fe.lihat_ajuan_2');
    Route::get('/edit_ajuan_2/{id}', 'Frontend\PermitController@edit_ajuan_2')->name('fe.edit_ajuan_2');
	Route::post('/approve_ajuan_2/{id}', 'Frontend\PermitController@approve_ajuan_2')->name('fe.approve_ajuan_2');
	
	Route::get('/survei', 'Frontend\SurveiController@survei')->name('fe.survei');
	
	Route::get('/tambah_permintaan_surat', 'Frontend\Permintaan_suratController@tambah_permintaan_surat')->name('fe.tambah_permintaan_surat');
	Route::get('/permintaan_surat', 'Frontend\Permintaan_suratController@permintaan_surat')->name('fe.permintaan_surat');
	Route::get('/edit_permintaan_surat/{id}', 'Frontend\Permintaan_suratController@edit_permintaan_surat')->name('fe.edit_permintaan_surat');
	Route::post('/update_permintaan_surat/{id}', 'Frontend\Permintaan_suratController@update_permintaan_surat')->name('fe.update_permintaan_surat');
	Route::get('/hapus_permintaan_surat/{id}', 'Frontend\Permintaan_suratController@hapus_permintaan_surat')->name('fe.hapus_permintaan_surat');
	Route::post('/simpan_permintaan_surat', 'Frontend\Permintaan_suratController@simpan_permintaan_surat')->name('fe.simpan_permintaan_surat');
	
	Route::get('/tambah_pengajuan_sp_st', 'Frontend\Pengajuan_sp_stController@tambah_pengajuan_sp_st')->name('fe.tambah_pengajuan_sp_st');
	Route::get('/pengajuan_sp_st', 'Frontend\Pengajuan_sp_stController@pengajuan_sp_st')->name('fe.pengajuan_sp_st');
	Route::get('/edit_pengajuan_sp_st/{id}', 'Frontend\Pengajuan_sp_stController@edit_pengajuan_sp_st')->name('fe.edit_pengajuan_sp_st');
	Route::post('/update_pengajuan_sp_st/{id}', 'Frontend\Pengajuan_sp_stController@update_pengajuan_sp_st')->name('fe.update_pengajuan_sp_st');
	Route::get('/hapus_pengajuan_sp_st/{id}', 'Frontend\Pengajuan_sp_stController@hapus_pengajuan_sp_st')->name('fe.hapus_pengajuan_sp_st');
	Route::post('/simpan_pengajuan_sp_st', 'Frontend\Pengajuan_sp_stController@simpan_pengajuan_sp_st')->name('fe.simpan_pengajuan_sp_st');
	
	Route::get('/tambah_pengajuan_resign', 'Frontend\Pengajuan_resignController@tambah_pengajuan_resign')->name('fe.tambah_pengajuan_resign');
Route::get('/pengajuan_resign', 'Frontend\Pengajuan_resignController@pengajuan_resign')->name('fe.pengajuan_resign');
Route::get('/approval_pengajuan_resign', 'Frontend\Pengajuan_resignController@approval_pengajuan_resign')->name('fe.approval_pengajuan_resign');
Route::get('/edit_pengajuan_resign/{id}/{type}', 'Frontend\Pengajuan_resignController@edit_pengajuan_resign')->name('fe.edit_pengajuan_resign');
Route::post('/update_pengajuan_resign/{id}', 'Frontend\Pengajuan_resignController@update_pengajuan_resign')->name('fe.update_pengajuan_resign');
Route::get('/hapus_pengajuan_resign/{id}', 'Frontend\Pengajuan_resignController@hapus_pengajuan_resign')->name('fe.hapus_pengajuan_resign');
Route::post('/simpan_pengajuan_resign', 'Frontend\Pengajuan_resignController@simpan_pengajuan_resign')->name('fe.simpan_pengajuan_resign');
	
	
	Route::get('/jadwal_training', 'Frontend\Agenda_perusahaanController@jadwal_training')->name('fe.jadwal_training');
	Route::get('/konfirmasi_kehadiran', 'Frontend\Agenda_perusahaanController@konfirmasi_kehadiran')->name('fe.konfirmasi_kehadiran');
	Route::get('/lihat_jadwal_training', 'Frontend\Agenda_perusahaanController@lihat_jadwal_training')->name('fe.lihat_jadwal_training');
	Route::get('/agenda_perusahaan', 'Frontend\Agenda_perusahaanController@agenda_perusahaan')->name('fe.agenda_perusahaan');
	Route::get('/lihat_agenda_perusahaan/{id}', 'Frontend\Agenda_perusahaanController@lihat_agenda_perusahaan')->name('fe.lihat_agenda_perusahaan');
	Route::get('/qr_absen/{id}', 'Frontend\Agenda_perusahaanController@qr_absen')->name('fe.qr_absen');
	Route::get('/save_absen_form_acara/{id}/{id2}', 'Frontend\Agenda_perusahaanController@save_absen_form_acara')->name('fe.save_absen_form_acara');
	Route::get('/absen_kehadiran_agenda_konfirmasi_hadir/{id}', 'Frontend\Agenda_perusahaanController@absen_kehadiran_agenda_konfirmasi_hadir')->name('fe.absen_kehadiran_agenda_konfirmasi_hadir');
	Route::get('/absen_kehadiran_agenda_konfirmasi_tdkhadir/{id}', 'Frontend\Agenda_perusahaanController@absen_kehadiran_agenda_konfirmasi_tdkhadir')->name('fe.absen_kehadiran_agenda_konfirmasi_tdkhadir');
	
	
	Route::get('/baca_kotak_laporan/{id}', 'Frontend\KotakLaporanController@baca_kotak_laporan')->name('fe.baca_kotak_laporan');
	Route::get('/kotak_laporan', 'Frontend\KotakLaporanController@kotak_laporan')->name('fe.kotak_laporan');
	Route::get('/tambah_kotak_laporan', 'Frontend\KotakLaporanController@tambah_kotak_laporan')->name('fe.tambah_kotak_laporan');
	Route::post('/simpan_kotak_laporan', 'Frontend\KotakLaporanController@simpan_kotak_laporan')->name('fe.simpan_kotak_laporan');
	Route::get('/edit_kotak_laporan/{id}', 'Frontend\KotakLaporanController@edit_kotak_laporan')->name('fe.edit_kotak_laporan');
	Route::post('/update_kotak_laporan/{id}', 'Frontend\KotakLaporanController@update_kotak_laporan')->name('fe.update_kotak_laporan');
	Route::get('/hapus_kotak_laporan/{id}', 'Frontend\KotakLaporanController@hapus_kotak_laporan')->name('fe.hapus_kotak_laporan');
	Route::get('/baca_pengajuan_pelatihan/{id}', 'Frontend\Pengajuan_pelatihanController@baca_pengajuan_pelatihan')->name('fe.baca_pengajuan_pelatihan');
	Route::get('/pengajuan_pelatihan', 'Frontend\Pengajuan_pelatihanController@pengajuan_pelatihan')->name('fe.pengajuan_pelatihan');
	Route::get('/tambah_pengajuan_pelatihan', 'Frontend\Pengajuan_pelatihanController@tambah_pengajuan_pelatihan')->name('fe.tambah_pengajuan_pelatihan');
	Route::post('/simpan_pengajuan_pelatihan', 'Frontend\Pengajuan_pelatihanController@simpan_pengajuan_pelatihan')->name('fe.simpan_pengajuan_pelatihan');
	Route::get('/edit_pengajuan_pelatihan/{id}', 'Frontend\Pengajuan_pelatihanController@edit_pengajuan_pelatihan')->name('fe.edit_pengajuan_pelatihan');
	Route::post('/update_pengajuan_pelatihan/{id}', 'Frontend\Pengajuan_pelatihanController@update_pengajuan_pelatihan')->name('fe.update_pengajuan_pelatihan');
	Route::get('/hapus_pengajuan_pelatihan/{id}', 'Frontend\Pengajuan_pelatihanController@hapus_pengajuan_pelatihan')->name('fe.hapus_pengajuan_pelatihan');

	Route::get('/klarifikasi_absen', 'Frontend\ChatController@klarifikasi_absen')->name('fe.klarifikasi_absen');
	Route::get('/appr_atasan_klarifikasi/{id}', 'Frontend\ChatController@appr_atasan_klarifikasi')->name('fe.appr_atasan_klarifikasi');
	Route::post('/update_klarifikasi/{id}', 'Frontend\ChatController@update_klarifikasi')->name('fe.update_klarifikasi');
	
	Route::get('/absen_kehadiran_pelatihan_konfirmasi_hadir/{id}', 'Frontend\Agenda_perusahaanController@absen_kehadiran_pelatihan_konfirmasi_hadir')->name('fe.absen_kehadiran_pelatihan_hadir');
	Route::get('/absen_kehadiran_pelatihan_konfirmasi_tdkhadir/{id}', 'Frontend\Agenda_perusahaanController@absen_kehadiran_pelatihan_konfirmasi_tdkhadir')->name('fe.absen_kehadiran_pelatihan_tdkhadir');
	
	
	Route::get('/laporan_gratifikasi', 'Frontend\GratifikasiController@laporan_gratifikasi')->name('fe.laporan_gratifikasi');
	Route::get('/tambah_laporan_gratifikasi', 'Frontend\GratifikasiController@tambah_laporan_gratifikasi')->name('fe.tambah_laporan_gratifikasi');
	Route::post('/simpan_laporan_gratifikasi', 'Frontend\GratifikasiController@simpan_laporan_gratifikasi')->name('fe.simpan_laporan_gratifikasi');
	Route::get('/baca_laporan_gratifikasi/{id}', 'Frontend\GratifikasiController@baca_laporan_gratifikasi')->name('fe.baca_laporan_gratifikasi');
	Route::get('/hapus_laporan_gratifikasi/{id}', 'Frontend\GratifikasiController@hapus_laporan_gratifikasi')->name('fe.hapus_laporan_gratifikasi');
	Route::get('/kembalikan_laporan_gratifikasi/{id}', 'Frontend\GratifikasiController@kembalikan_laporan_gratifikasi')->name('fe.kembalikan_laporan_gratifikasi');
	Route::get('/konfirmasi_laporan_gratifikasi/{id}', 'Frontend\GratifikasiController@konfirmasi_laporan_gratifikasi')->name('fe.konfirmasi_laporan_gratifikasi');
	Route::post('/simpan_keluh_kesah_detail', 'Frontend\KeluhkesahController@simpan_keluh_kesah_detail')->name('fe.simpan_keluh_kesah_detail');
	
	
	});

/*Karyawan*/
Route::group(['prefix' => 'backend'], function(){
	Route::get('/pengaturan_page_bpjs', 'Backend\PengaturanBPJSController@pengaturan_page_bpjs')->name('be.pengaturan_page_bpjs');
	Route::post('/save_pengaturan_page_bpjs', 'Backend\PengaturanBPJSController@save_pengaturan_page_bpjs')->name('be.save_pengaturan_page_bpjs');
	Route::get('/export_gratifikasi', 'Backend\GratifikasiController@export_gratifikasi')->name('be.export_gratifikasi');
	Route::get('/laporan_gratifikasi', 'Backend\GratifikasiController@laporan_gratifikasi')->name('be.laporan_gratifikasi');
	Route::get('/tambah_laporan_gratifikasi', 'Backend\GratifikasiController@tambah_laporan_gratifikasi')->name('be.tambah_laporan_gratifikasi');
	Route::get('/edit_laporan_gratifikasi', 'Backend\GratifikasiController@edit_laporan_gratifikasi')->name('be.edit_laporan_gratifikasi');
	Route::post('/simpan_laporan_gratifikasi', 'Backend\GratifikasiController@simpan_laporan_gratifikasi')->name('be.simpan_laporan_gratifikasi');
	Route::get('/baca_laporan_gratifikasi/{id}', 'Backend\GratifikasiController@baca_laporan_gratifikasi')->name('be.baca_laporan_gratifikasi');
	Route::get('/edit_laporan_gratifikasi/{id}', 'Backend\GratifikasiController@edit_laporan_gratifikasi')->name('be.edit_laporan_gratifikasi');
	Route::post('/update_laporan_gratifikasi/{id}', 'Backend\GratifikasiController@update_laporan_gratifikasi')->name('be.update_laporan_gratifikasi');
	 
	Route::get('/rekap/{page}', 'Backend\RekapController@rekap')->name('be.rekap');
	
	Route::get('/tambah_pengajuan_resign', 'Backend\Pengajuan_resignController@tambah_pengajuan_resign')->name('be.tambah_pengajuan_resign');
	Route::get('/pengajuan_resign', 'Backend\Pengajuan_resignController@pengajuan_resign')->name('be.pengajuan_resign');
	Route::get('/approval_pengajuan_resign', 'Backend\Pengajuan_resignController@approval_pengajuan_resign')->name('be.approval_pengajuan_resign');
	Route::get('/edit_pengajuan_resign/{id}/{type}', 'Backend\Pengajuan_resignController@edit_pengajuan_resign')->name('be.edit_pengajuan_resign');
	Route::post('/update_pengajuan_resign/{id}', 'Backend\Pengajuan_resignController@update_pengajuan_resign')->name('be.update_pengajuan_resign');
	Route::get('/hapus_pengajuan_resign/{id}', 'Backend\Pengajuan_resignController@hapus_pengajuan_resign')->name('be.hapus_pengajuan_resign');
	Route::post('/simpan_pengajuan_resign', 'Backend\Pengajuan_resignController@simpan_pengajuan_resign')->name('be.simpan_pengajuan_resign');
		
		
	Route::get('/tambah_pengajuan_sp_st', 'Backend\Pengajuan_sp_stController@tambah_pengajuan_sp_st')->name('be.tambah_pengajuan_sp_st');
	Route::get('/pengajuan_sp_st', 'Backend\Pengajuan_sp_stController@pengajuan_sp_st')->name('be.pengajuan_sp_st');
	Route::get('/edit_pengajuan_sp_st/{id}', 'Backend\Pengajuan_sp_stController@edit_pengajuan_sp_st')->name('be.edit_pengajuan_sp_st');
	Route::post('/update_pengajuan_sp_st/{id}', 'Backend\Pengajuan_sp_stController@update_pengajuan_sp_st')->name('be.update_pengajuan_sp_st');
	Route::get('/hapus_pengajuan_sp_st/{id}', 'Backend\Pengajuan_sp_stController@hapus_pengajuan_sp_st')->name('be.hapus_pengajuan_sp_st');
	Route::post('/simpan_pengajuan_sp_st', 'Backend\Pengajuan_sp_stController@simpan_pengajuan_sp_st')->name('be.simpan_pengajuan_sp_st');
	
	
	Route::get('/mudepro', 'Backend\MudeproController@mudepro')->name('be.mudepro');
    Route::get('/edit_mudepro/{id}', 'Backend\MudeproController@edit_mudepro')->name('be.edit_mudepro');
    Route::post('/update_mudepro/{id}', 'Backend\MudeproController@update_mudepro')->name('be.update_mudepro');
    Route::post('/update_appr_mudepro/{id}', 'Backend\MudeproController@update_appr_mudepro')->name('be.update_appr_mudepro');
    Route::get('/tambah_mudepro', 'Backend\MudeproController@tambah_mudepro')->name('be.tambah_mudepro');
    Route::get('/finali_mudepro/{id}', 'Backend\MudeproController@finali_mudepro')->name('be.final_mudepro');
    Route::post('/simpan_mudepro', 'Backend\MudeproController@simpan_mudepro')->name('be.simpan_mudepro');
	
	Route::get('/queue/{id}', 'Backend\QueueController@queue')->name('be.queue');
	Route::get('/permintaan_surat', 'Backend\Permintaan_suratController@permintaan_surat')->name('be.permintaan_surat');
	Route::get('/edit_permintaan_surat/{id}', 'Backend\Permintaan_suratController@edit_permintaan_surat')->name('be.edit_permintaan_surat');
	Route::get('/pdf_permintaan_surat/{id}', 'Backend\Permintaan_suratController@pdf_permintaan_surat')->name('be.pdf_permintaan_surat');
	Route::post('/update_permintaan_surat/{id}', 'Backend\Permintaan_suratController@update_permintaan_surat')->name('be.update_permintaan_surat');
	Route::get('/survei', 'Backend\SurveiController@survei')->name('be.survei');
	Route::get('/keluh_kesah', 'Backend\Keluh_kesahController@keluh_kesah')->name('be.keluh_kesah');
	Route::get('/baca_keluh_kesah/{id}', 'Backend\Keluh_kesahController@baca_keluh_kesah')->name('be.baca_keluh_kesah');
	
	Route::get('/tambah_keluh_kesah', 'Backend\Keluh_kesahController@tambah_keluh_kesah')->name('be.tambah_keluh_kesah');
	Route::post('/simpan_keluh_kesah', 'Backend\Keluh_kesahController@simpan_keluh_kesah')->name('be.simpan_keluh_kesah');
	Route::post('/simpan_keluh_kesah_detail', 'Backend\Keluh_kesahController@simpan_keluh_kesah_detail')->name('be.simpan_keluh_kesah_detail');

	Route::get('/jadwal_training', 'Backend\Jadwal_trainingController@jadwal_training')->name('be.jadwal_training');
	Route::get('/tambah_jadwal_training', 'Backend\Jadwal_trainingController@tambah_jadwal_training')->name('be.tambah_jadwal_training');
	Route::post('/simpan_jadwal_training', 'Backend\Jadwal_trainingController@simpan_jadwal_training')->name('be.simpan_jadwal_training');
	
	Route::get('/qr_absen_acara/{id}', 'Backend\Agenda_perusahaanController@qr_absen_acara')->name('be.qr_absen_acara');
	Route::get('/hapus_list_karyawan_agenda/{id}', 'Backend\Agenda_perusahaanController@hapus_list_karyawan_agenda')->name('be.hapus_list_karyawan_agenda');
	Route::get('/generate_qr/{id}', 'Backend\Agenda_perusahaanController@livetimeQr')->name('be.generate_qr');
	
	
	Route::get('/absen_kehadiran_agenda_presensi_hadir/{id}/{id2}', 'Backend\Agenda_perusahaanController@absen_kehadiran_agenda_presensi_hadir')->name('be.absen_kehadiran_agenda_presensi_hadir');
	Route::get('/absen_kehadiran_agenda_presensi_tdkhadir/{id}/{id2}', 'Backend\Agenda_perusahaanController@absen_kehadiran_agenda_presensi_tdkhadir')->name('be.absen_kehadiran_agenda_presensi_tdkhadir');
	
	Route::get('/list_konfirmasi_jadwal_training/{id}', 'Backend\Jadwal_trainingController@list_konfirmasi_jadwal_training')->name('be.list_konfirmasi_jadwal_training');
	Route::get('/absen_kehadiran_pelatihan_hadir/{id}/{id2}', 'Backend\Jadwal_trainingController@absen_kehadiran_pelatihan_hadir')->name('be.absen_kehadiran_pelatihan_hadir');
	Route::get('/absen_kehadiran_pelatihan_tdkhadir/{id}/{id2}', 'Backend\Jadwal_trainingController@absen_kehadiran_pelatihan_tdkhadir')->name('be.absen_kehadiran_pelatihan_tdkhadir');
	Route::get('/edit_jadwal_training/{id}', 'Backend\Jadwal_trainingController@edit_jadwal_training')->name('be.edit_jadwal_training');
	Route::post('/update_jadwal_training/{id}', 'Backend\Jadwal_trainingController@update_jadwal_training')->name('be.update_jadwal_training');
	Route::get('/hapus_jadwal_training/{id}', 'Backend\Jadwal_trainingController@hapus_jadwal_training')->name('be.hapus_jadwal_training');
	
	Route::get('/pengajuan_pelatihan', 'Backend\Pengajuan_pelatihanController@pengajuan_pelatihan')->name('be.pengajuan_pelatihan');
	Route::get('/tambah_pengajuan_pelatihan', 'Backend\Pengajuan_pelatihanController@tambah_pengajuan_pelatihan')->name('be.tambah_pengajuan_pelatihan');
	Route::post('/simpan_pengajuan_pelatihan', 'Backend\Pengajuan_pelatihanController@simpan_pengajuan_pelatihan')->name('be.simpan_pengajuan_pelatihan');
	Route::get('/edit_pengajuan_pelatihan/{id}', 'Backend\Pengajuan_pelatihanController@edit_pengajuan_pelatihan')->name('be.edit_pengajuan_pelatihan');
	Route::post('/update_pengajuan_pelatihan/{id}', 'Backend\Pengajuan_pelatihanController@update_pengajuan_pelatihan')->name('be.update_pengajuan_pelatihan');
	Route::get('/hapus_pengajuan_pelatihan/{id}', 'Backend\Pengajuan_pelatihanController@hapus_pengajuan_pelatihan')->name('be.hapus_pengajuan_pelatihan');
	
    Route::get('/hitung_hari', 'Frontend\PermitController@hitung_hari')->name('be.hitung_hari');
    Route::get('/hitung_hari_tanpa_libur', 'Frontend\PermitController@hitung_hari_tanpa_libur')->name('be.hitung_hari_tanpa_libur');
	
	Route::get('/rekap_cuti_karyawan/', 'Backend\CutiController@rekap_cuti_karyawan')->name('be.rekap_cuti_karyawan');
	Route::get('/rekap_cuti_karyawan2/', 'Backend\CutiController@rekap_cuti_karyawan2')->name('be.rekap_cuti_karyawan2');
	Route::get('/laporan_gaji/{type}', 'Backend\GajiLaporanController@laporan')->name('be.laporan_gaji');
	Route::get('/cariPeriode/', 'Backend\GajiGenerateController@cariPeriode')->name('be.cariPeriode');
	Route::post('/to_generate/', 'Backend\GajiGenerateController@to_generate')->name('be.to_generate');
	Route::post('/simpan_cuti/', 'Backend\CutiController@simpan_cuti')->name('be.simpan_cuti');
	Route::get('/generatejabatanstruktural/', 'Backend\JabatanStrukturalController@generate')->name('be.generatejabatanstruktural');
	Route::get('/karyawan_shift/', 'Backend\KaryawanShiftController@karyawan_shift')->name('be.karyawan_shift');
	
    Route::get('/karyawan_pajak', 'Backend\KaryawanPajakController@pajak')->name('be.karyawan_pajak');
    Route::get('/edit_karyawan_pajak/{id}', 'Backend\KaryawanPajakController@edit_pajak')->name('be.edit_karyawan_pajak');
    Route::post('/simpan_karyawan_pajak', 'Backend\KaryawanPajakController@simpan_karyawan_pajak')->name('be.simpan_karyawan_pajak');
    
    Route::post('/update_karyawan_pajak/{id}', 'Backend\KaryawanPajakController@update_pajak')->name('be.update_karyawan_pajak');
    Route::get('/tambah_karyawan_shift', 'Backend\KaryawanShiftController@tambah_karyawan_shift')->name('be.tambah_karyawan_shift');
    Route::post('/simpan_karyawan_shift', 'Backend\KaryawanShiftController@simpan_karyawan_shift')->name('be.simpan_karyawan_shift');
    Route::get('/hapus_karyawan_shift/{id}', 'Backend\KaryawanShiftController@hapus_karyawan_shift')->name('be.hapus_karyawan_shift');
   
    Route::get('/view_jabatan_struktural/', 'Backend\JabatanStrukturalController@view_jabatan_struktural')->name('be.view_jabatan_struktural');
    Route::get('/jabatan_struktural/', 'Backend\JabatanStrukturalController@jabatan_struktural')->name('be.jabatan_struktural');
    
    
    Route::get('/tambah_jabatan_struktural', 'Backend\JabatanStrukturalController@tambah_jabatan_struktural')->name('be.tambah_jabatan_struktural');
    Route::post('/simpan_jabatan_struktural', 'Backend\JabatanStrukturalController@simpan_jabatan_struktural')->name('be.simpan_jabatan_struktural');
    Route::get('/edit_jabatan_struktural/{id}', 'Backend\JabatanStrukturalController@edit_jabatan_struktural')->name('be.edit_jabatan_struktural');
    Route::post('/update_jabatan_struktural/{id}', 'Backend\JabatanStrukturalController@update_jabatan_struktural')->name('be.update_jabatan_struktural');
    Route::get('/hapus_jabatan_struktural/{id}', 'Backend\JabatanStrukturalController@hapus_jabatan_struktural')->name('be.hapus_jabatan_struktural');
    
    Route::get('/kpi_detail/{id}', 'Backend\KPIController@kpi_detail')->name('be.kpi_detail');
    Route::get('/evaluasi_tahunan/{id}', 'Backend\KPIController@evaluasi_tahunan')->name('be.evaluasi_tahunan');
    Route::get('/pdf_evaluasi_tahunan/{id}', 'Backend\KPIController@pdf_evaluasi_tahunan')->name('be.pdf_evaluasi_tahunan');
    Route::get('/mentoring_kpi/{id}', 'Backend\KPIController@mentoring_kpi')->name('be.mentoring_kpi');
    Route::get('/tambah_kpi_detail/{id}', 'Backend\KPIController@tambah_kpi_detail')->name('be.tambah_kpi_detail');
    Route::get('/edit_kpi_detail/{id}/{id2}', 'Backend\KPIController@edit_kpi_detail')->name('be.edit_kpi_detail');
    Route::post('/simpan_kpi_detail/{id}', 'Backend\KPIController@simpan_kpi_detail')->name('be.simpan_kpi_detail');
    Route::post('/update_kpi_detail/{id}/{id2}', 'Backend\KPIController@update_kpi_detail')->name('be.update_kpi_detail');
    Route::get('/hapus_kpi_detail/{id}/{id2}', 'Backend\KPIController@hapus_kpi_detail')->name('be.hapus_kpi_detail');
    Route::post('/simpan_kpi_pencapaian/{id}/{id2}/{type}', 'Backend\KPIController@simpan_kpi_pencapaian')->name('be.simpan_kpi_pencapaian');
    Route::post('/simpan_kpi_pencapaian_all/{id}/{type}', 'Backend\KPIController@simpan_kpi_pencapaian_all')->name('be.simpan_kpi_pencapaian_all');
    Route::get('/edit_pencapaian/{id}/{id2}/{type}', 'Backend\KPIController@edit_pencapaian')->name('be.edit_tw');
    Route::get('/edit_pencapaian_all/{id}/{type}', 'Backend\KPIController@edit_pencapaian_all')->name('be.edit_all_tw');
    Route::get('/kpi', 'Backend\KPIController@kpi')->name('be.kpi');
    Route::get('/approval_kpi', 'Backend\KPIController@approval_kpi')->name('be.approval_kpi');
    Route::get('/approval_parameter_kpi', 'Backend\KPIController@approval_parameter_kpi')->name('be.approval_parameter_kpi');
    Route::get('/acc_kpi_parameter/{id}/{id2}/{id3}', 'Backend\KPIController@acc_kpi_parameter')->name('be.acc_kpi_parameter');
    Route::get('/dec_kpi_parameter/{id}/{id2}/{id3}', 'Backend\KPIController@dec_kpi_parameter')->name('be.dec_kpi_parameter');
    Route::get('/acc_kpi_1/{id}', 'Backend\KPIController@acc_kpi_1')->name('be.acc_kpi_1');
    Route::get('/acc_kpi_2/{id}', 'Backend\KPIController@acc_kpi_2')->name('be.acc_kpi_2');
    Route::get('/dec_kpi_1/{id}', 'Backend\KPIController@dec_kpi_1')->name('be.dec_kpi_1');
    Route::get('/dec_kpi_2/{id}', 'Backend\KPIController@dec_kpi_2')->name('be.dec_kpi_2');
    Route::get('/hapus_kpi/{id}', 'Backend\KPIController@hapus_kpi')->name('be.hapus_kpi');
    Route::get('/tambah_kpi', 'Backend\KPIController@tambah_kpi')->name('be.tambah_kpi');
    Route::post('/simpan_kpi', 'Backend\KPIController@simpan_kpi')->name('be.simpan_kpi');
    
    Route::get('/parameter_penilaian_kpi', 'Backend\KPIController@parameter_penilaian_kpi')->name('be.parameter_penilaian_kpi');
    Route::get('/tambah_parameter_penilaian_kpi', 'Backend\KPIController@tambah_parameter_penilaian_kpi')->name('be.tambah_parameter_penilaian_kpi');
    Route::post('/simpan_parameter_penilaian_kpi', 'Backend\KPIController@simpan_parameter_penilaian_kpi')->name('be.simpan_parameter_penilaian_kpi');
    Route::get('/masterpoinkpi', 'Backend\KPIController@masterpoinkpi')->name('be.masterpoinutamakpi');
    Route::get('/tambah_masterpoinkpi', 'Backend\KPIController@tambah_masterpoinkpi')->name('be.tambah_masterpoinutamakpi');
    Route::post('/simpan_masterpoinkpi', 'Backend\KPIController@simpan_masterpoinkpi')->name('be.simpan_masterpoinutamakpi');
    Route::get('/jenis_penilaian_kpi', 'Backend\KPIController@jenis_penilaian_kpi')->name('be.jenis_penilaian_kpi');
    Route::get('/tambah_jenis_penilaian_kpi', 'Backend\KPIController@tambah_jenis_penilaian_kpi')->name('be.tambah_jenis_penilaian_kpi');
    Route::post('/simpan_jenis_penilaian_kpi', 'Backend\KPIController@simpan_jenis_penilaian_kpi')->name('be.simpan_jenis_penilaian_kpi');
    
    Route::get('/edit_cuti/{id}', 'Backend\CutiController@edit_cuti')->name('be.edit_cuti');
	Route::get('/delete_cuti/{id}', 'Backend\CutiController@delete_cuti')->name('be.delete_cuti');
	Route::post('/update_cuti/{id}', 'Backend\CutiController@update_cuti')->name('be.update_cuti');
	Route::get('/laporan_cuti_karyawan/', 'Backend\CutiController@laporan_cuti')->name('be.laporan_cuti_karyawan');
	Route::get('/tambah_laporan_cuti_karyawan/', 'Backend\CutiController@tambah_laporan_cuti_karyawan')->name('be.tambah_laporan_cuti_karyawan');
	
	Route::get('/view_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@view_karyawan_baru')->name('be.view_karyawan_baru');
	Route::get('/approval_karyawan_baru_keuangan/{id}', 'Backend\PengajuanKaryawanController@approval_karyawan_baru_keuangan')->name('be.approval_karyawan_baru_keuangan');
    Route::post('/approve_keuangan_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@approve_keuangan_karyawan_baru')->name('be.approve_keuangan_karyawan_baru');
    Route::get('/keuangan_karyawan_baru', 'Backend\PengajuanKaryawanController@keuangan_karyawan_baru')->name('be.keuangan_karyawan_baru');
    Route::get('/keuangan_karyawan_baru', 'Backend\PengajuanKaryawanController@keuangan_karyawan_baru')->name('be.keuangan_karyawan_baru');
    Route::get('/dec_keuangan_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@dec_keuangan_karyawan_baru')->name('be.dec_keuangan_karyawan_baru');
    Route::get('/acc_keuangan_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@acc_keuangan_karyawan_baru')->name('be.acc_keuangan_karyawan_baru');
    Route::get('/edit_pengajuan_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@edit_karyawan_baru')->name('be.edit_pengajuan_karyawan_baru');
    Route::get('/print_pengajuan_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@print_karyawan_baru')->name('be.print_pengajuan_karyawan_baru');
	Route::get('/hapus_approval/{id}/{id_prl}', 'Backend\GajiPreviewController@hapus_approval')->name('be.hapus_approval');
	Route::get('/update_nominal/{id_prl}/{id}/{nominal}', 'Backend\GajiPreviewController@update_nominal')->name('be.update_nominal');
	Route::get('/save_keterangan_ajuan', 'Backend\GajiPreviewController@save_keterangan_ajuan')->name('be.save_keterangan_ajuan');
    Route::get('/ajuan_gaji/', 'Backend\GajiPreviewController@ajuan_gaji')->name('be.ajuan_gaji');
    Route::get('/save_change_nominal/', 'Backend\GajiPreviewController@save_change_nominal')->name('be.save_change_nominal');
    Route::get('/karyawan_baru/', 'Backend\PengajuanKaryawanController@karyawan_baru')->name('be.karyawan_baru');
    Route::get('/selesai_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@selesai_karyawan_baru')->name('be.selesai_karyawan_baru');
    Route::get('/tambah_karyawan_baru/', 'Backend\PengajuanKaryawanController@tambah_karyawan_baru')->name('be.tambah_karyawan_baru');
    Route::post('/simpan_karyawan_baru/', 'Backend\PengajuanKaryawanController@simpan_karyawan_baru')->name('be.simpan_karyawan_baru');
    Route::get('/edit_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@edit_karyawan_baru')->name('be.edit_karyawan_baru');
    Route::post('/update_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@update_karyawan_baru')->name('be.update_karyawan_baru');
    Route::get('/hapus_karyawan_baru/{id}', 'Backend\PengajuanKaryawanController@hapus_karyawan_baru')->name('be.hapus_karyawan_baru');
    
	Route::get('/database_kandidat/', 'Backend\PengajuanKaryawanController@database_kandidat')->name('be.database_kandidat');
	Route::get('/list_database_kandidat/{id}', 'Backend\PengajuanKaryawanController@list_database_kandidat')->name('be.list_database_kandidat');
	Route::post('/update_kandidat_interview/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@update_kandidat_interview')->name('be.update_kandidat_interview');
	Route::post('/update_kandidat_offering_letter/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@update_kandidat_offering_letter')->name('be.update_kandidat_offering_letter');
	Route::post('/update_kandidat_kontrak_kerja/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@update_kandidat_kontrak_kerja')->name('be.update_kandidat_kontrak_kerja');
	Route::get('/tambah_kandidat/{id}', 'Backend\PengajuanKaryawanController@tambah_kandidat')->name('be.tambah_kandidat');
	
	Route::get('/kandidat_to_interview_dec/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@kandidat_to_interview_dec')->name('be.kandidat_to_interview_dec');
	Route::get('/kandidat_to_interview_acc/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@kandidat_to_interview_acc')->name('be.kandidat_to_interview_acc');
	Route::get('/kandidat_penerimaan_acc/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@kandidat_penerimaan_acc')->name('be.kandidat_penerimaan_acc');
	Route::get('/kandidat_penerimaan_dec/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@kandidat_penerimaan_dec')->name('be.kandidat_penerimaan_dec');
	Route::get('/delete_kandidat/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@delete_kandidat')->name('be.delete_kandidat');
	Route::get('/kandidat_interview/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@kandidat_interview')->name('be.kandidat_interview');
	Route::get('/kandidat_offering_letter/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@kandidat_offering_letter')->name('be.kandidat_offering_letter');
	Route::get('/kandidat_kontrak_kerja/{id}/{id_kandidat}', 'Backend\PengajuanKaryawanController@kandidat_kontrak_kerja')->name('be.kandidat_kontrak_kerja');
	Route::post('/simpan_kandidat/{id}', 'Backend\PengajuanKaryawanController@simpan_kandidat')->name('be.simpan_kandidat');
	Route::post('/simpan_kandidat_2/{id}', 'Backend\PengajuanKaryawanController@simpan_kandidat_2')->name('be.simpan_kandidat');
    
	 Route::get('/saldo_faskes/', 'Backend\FaskesController@saldo_faskes')->name('be.saldo_faskes');
	 Route::get('/tambah_saldo_faskes/', 'Backend\FaskesController@tambah_saldo_faskes')->name('be.tambah_saldo_faskes');
	 Route::get('/edit_saldo_faskes/{id}', 'Backend\FaskesController@edit_saldo_faskes')->name('be.edit_saldo_faskes');
	 Route::get('/hapus_saldo_faskes/{id}', 'Backend\FaskesController@hapus_saldo_faskes')->name('be.hapus_saldo_faskes');
	 Route::get('/pengajuan_faskes/', 'Backend\FaskesController@pengajuan_faskes')->name('be.pengajuan_faskes');
	 Route::get('/generate_lokasi_pengajuan/', 'Backend\FaskesController@generate_lokasi_pengajuan')->name('be.generate_lokasi_pengajuan');
	 Route::get('/tambah_pengajuan_faskes/', 'Backend\FaskesController@tambah_pengajuan_faskes')->name('be.tambah_pengajuan_faskes');
	 Route::post('/simpan_pengajuan_faskes/', 'Backend\FaskesController@simpan_pengajuan_faskes')->name('be.simpan_pengajuan_faskes');
	 Route::get('/edit_pengajuan_faskes/{id}', 'Backend\FaskesController@edit_pengajuan_faskes')->name('be.edit_pengajuan_faskes');
	 Route::get('/update_pengajuan_faskes/{id}', 'Backend\FaskesController@update_pengajuan_faskes')->name('be.update_pengajuan_faskes');
	 Route::get('/hapus_pengajuan_faskes/{id}', 'Backend\FaskesController@hapus_pengajuan_faskes')->name('be.hapus_pengajuan_faskes');
	 Route::get('/laporan_faskes/', 'Backend\FaskesController@laporan_faskes')->name('be.laporan_faskes');
	 Route::get('/hapus_faskes/{id}', 'Backend\FaskesController@hapus_faskes')->name('be.hapus_faskes');
    Route::post('/simpan_faskes/', 'Backend\FaskesController@simpan_faskes')->name('be.simpan_faskes');
    Route::get('/appr_faskes/{id}', 'Backend\FaskesController@appr_faskes')->name('be.appr_faskes');
    Route::get('/appr_tolak_faskes/{id}', 'Backend\FaskesController@appr_tolak_faskes')->name('be.appr_tolak_faskes');
    
    Route::get('/', 'Backend\KaryawanController@karyawan')->name('be.karyawan');
    Route::get('/menu/', 'Backend\MenuController@menu')->name('be.menu');
    Route::get('/tambah_menu/', 'Backend\MenuController@tambah')->name('be.tambah_menu');
    Route::post('/simpan_menu/', 'Backend\MenuController@simpan')->name('be.simpan_menu');
    Route::get('/edit_menu/{id}', 'Backend\MenuController@edit')->name('be.edit_menu');
    Route::post('/update_menu/{id}', 'Backend\MenuController@update')->name('be.update_menu');
    Route::get('/hapus_menu/{id}', 'Backend\MenuController@hapus')->name('be.hapus_menu');
    /*Performa*/
    Route::get('/pa/', 'Backend\PAController@pa')->name('be.pa');
    Route::get('/tambah_pa/', 'Backend\PAController@tambah_pa')->name('be.tambah_pa');
    Route::post('/simpan_pa/', 'Backend\PAController@simpan_pa')->name('be.simpan_pa');
    Route::get('/view_pa/{id}', 'Backend\PAController@view_pa')->name('be.view_pa');
    Route::get('/edit_pa/{id}', 'Backend\PAController@edit_pa')->name('be.edit_pa');
    Route::post('/update_pa/{id}', 'Backend\PAController@update_pa')->name('be.update_pa');
    Route::get('/approve_pa/{id}', 'Backend\PAController@approve_pa')->name('be.approve_pa');
    Route::post('/save_approve_pa/{id}', 'Backend\PAController@save_approve_pa')->name('be.save_approve_pa');
    Route::get('/hapus_pa/{id}', 'Backend\PAController@hapus_pa')->name('be.hapus_pa');
    
	 Route::get('/pengajuan_direksi', 'Backend\PermitController@list_ajuan')->name('be.pengajuan_direksi'); 
	 Route::get('/edit_ajuan_direksi/{id}', 'Backend\PermitController@edit')->name('be.edit_ajuan_direksi');
	 Route::post('/update_pengajuan_permit/{id}', 'Backend\PermitController@update_pengajuan_permit')->name('be.update_pengajuan_permit');
    
	
	Route::get('/sop', 'Backend\SopController@sop')->name('be.sop');
	
	Route::get('/periode_absen_min', 'Backend\PeriodeController@periode_absen_min')->name('be.periode_absen_min');
    Route::get('/periode_absen_cek_duplicate', 'Backend\PeriodeController@periode_absen_cek_duplicate')->name('be.periode_absen_cek_duplicate');
    
	Route::get('/masterjamkerja', 'Backend\MasterJamKerjaController@masterjamkerja')->name('be.masterjamkerja');
    Route::get('/tambah_masterjamkerja', 'Backend\MasterJamKerjaController@tambah_masterjamkerja')->name('be.tambah_masterjamkerja');
    Route::post('/simpan_masterjamkerja', 'Backend\MasterJamKerjaController@simpan_masterjamkerja')->name('be.simpan_masterjamkerja');
    Route::get('/edit_masterjamkerja/{id}', 'Backend\MasterJamKerjaController@edit_masterjamkerja')->name('be.edit_masterjamkerja');
    Route::post('/update_masterjamkerja/{id}', 'Backend\MasterJamKerjaController@update_masterjamkerja')->name('be.update_masterjamkerja');
    Route::get('/hapus_masterjamkerja/{id}', 'Backend\MasterJamKerjaController@hapus_masterjamkerja')->name('be.hapus_masterjamkerja');
	
	 
	Route::get('/sop', 'Backend\SopController@sop')->name('be.sop');
    Route::get('/tambah_sop', 'Backend\SopController@tambah_sop')->name('be.tambah_sop');
    Route::post('/simpan_sop', 'Backend\SopController@simpan_sop')->name('be.simpan_sop');
    Route::get('/edit_sop/{id}', 'Backend\SopController@edit_sop')->name('be.edit_sop');
    Route::get('/reader_sop/{id}', 'Backend\SopController@reader_sop')->name('be.reader_sop');
    Route::post('/update_sop/{id}', 'Backend\SopController@update_sop')->name('be.update_sop');
    Route::get('/hapus_sop/{id}', 'Backend\SopController@hapus_sop')->name('be.hapus_sop');
    /*rmib*/
    Route::get('/rmib', 'Backend\RMIBController@rmib')->name('be.rmib');
    Route::get('/tambah_rmib', 'Backend\RMIBController@tambah_rmib')->name('be.tambah_rmib');
    Route::post('/simpan_rmib', 'Backend\RMIBController@simpan_rmib')->name('be.simpan_rmib');
    Route::get('/edit_rmib/{id}', 'Backend\RMIBController@edit_rmib')->name('be.edit_rmib');
    Route::post('/update_rmib/{id}', 'Backend\RMIBController@update_rmib')->name('be.update_rmib');
    Route::get('/hapus_rmib/{id}', 'Backend\RMIBController@hapus_rmib')->name('be.hapus_rmib');

    /*berita*/
    Route::get('/agenda_perusahaan', 'Backend\Agenda_perusahaanController@agenda_perusahaan')->name('be.agenda_perusahaan');
    Route::get('/tambah_agenda_perusahaan', 'Backend\Agenda_perusahaanController@tambah_agenda_perusahaan')->name('be.tambah_agenda_perusahaan');
    Route::post('/simpan_agenda_perusahaan', 'Backend\Agenda_perusahaanController@simpan_agenda_perusahaan')->name('be.simpan_agenda_perusahaan');
    Route::get('/edit_agenda_perusahaan/{id}', 'Backend\Agenda_perusahaanController@edit_agenda_perusahaan')->name('be.edit_agenda_perusahaan');
    Route::post('/update_agenda_perusahaan/{id}', 'Backend\Agenda_perusahaanController@update_agenda_perusahaan')->name('be.update_agenda_perusahaan');
    Route::get('/hapus_agenda_perusahaan/{id}', 'Backend\Agenda_perusahaanController@hapus_agenda_perusahaan')->name('be.hapus_agenda_perusahaan');
    Route::get('/qr_absen/{id}', 'Backend\Agenda_perusahaanController@qr_absen')->name('be.qr_absen');
    Route::get('/save_absen/{id_agenda}/{id_karyawan}', 'Backend\Agenda_perusahaanController@save_absen')->name('be.save_absen');
    Route::get('/tambah_karyawan_agenda/{id}', 'Backend\Agenda_perusahaanController@tambah_karyawan_agenda')->name('be.tambah_karyawan_agenda');
    Route::get('/save_absen_from_web', 'Backend\Agenda_perusahaanController@save_absen_from_web')->name('be.save_absen_from_web');
    Route::post('/save_tambah_karyawan_agenda/{id}', 'Backend\Agenda_perusahaanController@save_tambah_karyawan_agenda')->name('be.save_tambah_karyawan_agenda');
	Route::get('/rekap_kehadiran/{id}', 'Backend\Agenda_perusahaanController@rekap_kehadiran')->name('be.rekap_kehadiran');
	
    
	Route::get('/batasan_atasan_approve', 'Backend\BatasanAtasanApproveController@batasan_atasan_approve')->name('be.batasan_atasan_approve');
    Route::get('/tambah_batasan_atasan_approve', 'Backend\BatasanAtasanApproveController@tambah_batasan_atasan_approve')->name('be.tambah_batasan_atasan_approve');
    Route::post('/simpan_batasan_atasan_approve', 'Backend\BatasanAtasanApproveController@simpan_batasan_atasan_approve')->name('be.simpan_batasan_atasan_approve');
    Route::get('/edit_batasan_atasan_approve/{id}', 'Backend\BatasanAtasanApproveController@edit_batasan_atasan_approve')->name('be.edit_batasan_atasan_approve');
    Route::post('/update_batasan_atasan_approve/{id}', 'Backend\BatasanAtasanApproveController@update_batasan_atasan_approve')->name('be.update_batasan_atasan_approve');
    Route::get('/hapus_batasan_atasan_approve/{id}', 'Backend\BatasanAtasanApproveController@hapus_batasan_atasan_approve')->name('be.hapus_batasan_atasan_approve');
	
    

	Route::get('/alasan_jenis_ijin', 'Backend\AlasanJenisIzinController@alasan_jenis_ijin')->name('be.jenis_alasan');
    Route::get('/tambah_alasan_jenis_ijin', 'Backend\AlasanJenisIzinController@tambah_alasan_jenis_ijin')->name('be.tambah_alasan_jenis_ijin');
    Route::post('/simpan_alasan_jenis_ijin', 'Backend\AlasanJenisIzinController@simpan_alasan_jenis_ijin')->name('be.simpan_alasan_jenis_ijin');
    Route::get('/edit_alasan_jenis_ijin/{id}', 'Backend\AlasanJenisIzinController@edit_alasan_jenis_ijin')->name('be.edit_alasan_jenis_ijin');
    Route::post('/update_alasan_jenis_ijin/{id}', 'Backend\AlasanJenisIzinController@update_alasan_jenis_ijin')->name('be.update_alasan_jenis_ijin');
    Route::get('/hapus_alasan_jenis_ijin/{id}', 'Backend\AlasanJenisIzinController@hapus_alasan_jenis_ijin')->name('be.hapus_alasan_jenis_ijin');
	
  //  Route::get('/alasan_jenis_ijin', 'Backend\AlasanJenisIzinController@alasan_jenis_ijin')->name('be.alasan_jenis_ijin');
    Route::get('/tambah_alasan_jenis_ijin', 'Backend\AlasanJenisIzinController@tambah_alasan_jenis_ijin')->name('be.tambah_alasan_jenis_ijin');
    Route::post('/simpan_alasan_jenis_ijin', 'Backend\AlasanJenisIzinController@simpan_alasan_jenis_ijin')->name('be.simpan_alasan_jenis_ijin');
    Route::get('/edit_alasan_jenis_ijin/{id}', 'Backend\AlasanJenisIzinController@edit_alasan_jenis_ijin')->name('be.edit_alasan_jenis_ijin');
    Route::post('/update_alasan_jenis_ijin/{id}', 'Backend\AlasanJenisIzinController@update_alasan_jenis_ijin')->name('be.update_alasan_jenis_ijin');
    Route::get('/hapus_alasan_jenis_ijin/{id}', 'Backend\AlasanJenisIzinController@hapus_alasan_jenis_ijin')->name('be.hapus_alasan_jenis_ijin');
	
	Route::get('/berita', 'Backend\BeritaController@berita')->name('be.berita');
    Route::get('/tambah_berita', 'Backend\BeritaController@tambah_berita')->name('be.tambah_berita');
    Route::post('/simpan_berita', 'Backend\BeritaController@simpan_berita')->name('be.simpan_berita');
    Route::get('/edit_berita/{id}', 'Backend\BeritaController@edit_berita')->name('be.edit_berita');
    Route::post('/update_berita/{id}', 'Backend\BeritaController@update_berita')->name('be.update_berita');
    Route::get('/hapus_berita/{id}', 'Backend\BeritaController@hapus_berita')->name('be.hapus_berita');
	
    Route::get('/batas_pengajuan', 'Backend\BatasPengajuanController@batas_pengajuan')->name('be.batas_pengajuan');
    Route::get('/tambah_batas_pengajuan', 'Backend\BatasPengajuanController@tambah_batas_pengajuan')->name('be.tambah_batas_pengajuan');
    Route::post('/simpan_batas_pengajuan', 'Backend\BatasPengajuanController@simpan_batas_pengajuan')->name('be.simpan_batas_pengajuan');
    Route::get('/edit_batas_pengajuan/{id}', 'Backend\BatasPengajuanController@edit_batas_pengajuan')->name('be.edit_batas_pengajuan');
    Route::post('/update_batas_pengajuan/{id}', 'Backend\BatasPengajuanController@update_batas_pengajuan')->name('be.update_batas_pengajuan');
    Route::get('/hapus_batas_pengajuan/{id}', 'Backend\BatasPengajuanController@hapus_batas_pengajuan')->name('be.hapus_batas_pengajuan');
	
    Route::get('/jenis_izin', 'Backend\JenisIzinController@jenis_izin')->name('be.jenis_izin');
    Route::get('/tambah_jenis_izin', 'Backend\JenisIzinController@tambah_jenis_izin')->name('be.tambah_jenis_izin');
    Route::post('/simpan_jenis_izin', 'Backend\JenisIzinController@simpan_jenis_izin')->name('be.simpan_jenis_izin');
    Route::get('/edit_jenis_izin/{id}', 'Backend\JenisIzinController@edit_jenis_izin')->name('be.edit_jenis_izin');
    Route::post('/update_jenis_izin/{id}', 'Backend\JenisIzinController@update_jenis_izin')->name('be.update_jenis_izin');
    Route::get('/hapus_jenis_izin/{id}', 'Backend\JenisIzinController@hapus_jenis_izin')->name('be.hapus_jenis_izin');
	
	Route::get('/jamshift', 'Backend\JamShiftController@jamshift')->name('be.jamshift');
    Route::get('/tambah_jamshift', 'Backend\JamShiftController@tambah_jamshift')->name('be.tambah_jamshift');
    Route::post('/simpan_jamshift', 'Backend\JamShiftController@simpan_jamshift')->name('be.simpan_jamshift');
    Route::get('/edit_jamshift/{id}', 'Backend\JamShiftController@edit_jamshift')->name('be.edit_jamshift');
    Route::post('/update_jamshift/{id}', 'Backend\JamShiftController@update_jamshift')->name('be.update_jamshift');
    Route::get('/hapus_jamshift/{id}', 'Backend\JamShiftController@hapus_jamshift')->name('be.hapus_jamshift');
	
	Route::get('/kotak_laporan', 'Backend\Kotak_laporanController@kotak_laporan')->name('be.kotak_laporan');
    Route::get('/tambah_kotak_laporan', 'Backend\Kotak_laporanController@tambah_kotak_laporan')->name('be.tambah_kotak_laporan');
    Route::post('/simpan_kotak_laporan', 'Backend\Kotak_laporanController@simpan_kotak_laporan')->name('be.simpan_kotak_laporan');
	Route::get('/baca_kotak_laporan/{id}', 'Backend\Kotak_laporanController@baca_kotak_laporan')->name('be.baca_kotak_laporan');
    Route::get('/edit_kotak_laporan/{id}', 'Backend\Kotak_laporanController@edit_kotak_laporan')->name('be.edit_kotak_laporan');
    Route::post('/update_kotak_laporan/{id}', 'Backend\Kotak_laporanController@update_kotak_laporan')->name('be.update_kotak_laporan');
    Route::get('/hapus_kotak_laporan/{id}', 'Backend\Kotak_laporanController@hapus_kotak_laporan')->name('be.hapus_kotak_laporan');
	 
	Route::get('/survei', 'Backend\SurveiController@survei')->name('be.survei');
    Route::get('/tambah_survei', 'Backend\SurveiController@tambah_survei')->name('be.tambah_survei');
    Route::post('/simpan_survei', 'Backend\SurveiController@simpan_survei')->name('be.simpan_survei');
    Route::get('/edit_survei/{id}', 'Backend\SurveiController@edit_survei')->name('be.edit_survei');
    Route::post('/update_survei/{id}', 'Backend\SurveiController@update_survei')->name('be.update_survei');
    Route::get('/hapus_survei/{id}', 'Backend\SurveiController@hapus_survei')->name('be.hapus_survei');
	 
	Route::get('/sanksi', 'Backend\SanksiController@sanksi')->name('be.sanksi_karyawan');
    Route::get('/tambah_sanksi', 'Backend\SanksiController@tambah_sanksi')->name('be.tambah_sanksi');
    Route::post('/simpan_sanksi', 'Backend\SanksiController@simpan_sanksi')->name('be.simpan_sanksi');
    Route::get('/edit_sanksi/{id}', 'Backend\SanksiController@edit_sanksi')->name('be.edit_sanksi');
    Route::post('/update_sanksi/{id}', 'Backend\SanksiController@update_sanksi')->name('be.update_sanksi');
    Route::get('/hapus_sanksi/{id}', 'Backend\SanksiController@hapus_sanksi')->name('be.hapus_sanksi');
	
	Route::get('/reward', 'Backend\RewardController@reward')->name('be.reward_karyawan');
    Route::get('/tambah_reward', 'Backend\RewardController@tambah_reward')->name('be.tambah_reward');
    Route::post('/simpan_reward', 'Backend\RewardController@simpan_reward')->name('be.simpan_reward');
    Route::get('/edit_reward/{id}', 'Backend\RewardController@edit_reward')->name('be.edit_reward');
    Route::post('/update_reward/{id}', 'Backend\RewardController@update_reward')->name('be.update_reward');
    Route::get('/hapus_reward/{id}', 'Backend\RewardController@hapus_reward')->name('be.hapus_reward');
	
	
	
	 
	 Route::get('/qoute', 'Backend\QouteController@qoute')->name('be.qoute');
    Route::get('/tambah_qoute', 'Backend\QouteController@tambah_qoute')->name('be.tambah_qoute');
    Route::post('/simpan_qoute', 'Backend\QouteController@simpan_qoute')->name('be.simpan_qoute');
    Route::get('/edit_qoute/{id}', 'Backend\QouteController@edit_qoute')->name('be.edit_qoute');
    Route::post('/update_qoute/{id}', 'Backend\QouteController@update_qoute')->name('be.update_qoute');
    Route::get('/hapus_qoute/{id}', 'Backend\QouteController@hapus_qoute')->name('be.hapus_qoute');
    /*slider*/
    Route::get('/slider', 'Backend\SliderController@slider')->name('be.slider');
    Route::get('/tambah_slider', 'Backend\SliderController@tambah_slider')->name('be.tambah_slider');
    Route::post('/simpan_slider', 'Backend\SliderController@simpan_slider')->name('be.simpan_slider');
    Route::get('/edit_slider/{id}', 'Backend\SliderController@edit_slider')->name('be.edit_slider');
    Route::post('/update_slider/{id}', 'Backend\SliderController@update_slider')->name('be.update_slider');
    Route::get('/hapus_slider/{id}', 'Backend\SliderController@hapus_slider')->name('be.hapus_slider');

    /*fasilitas*/
    Route::get('/fasilitas', 'Backend\FasilitasController@fasilitas')->name('be.fasilitas');
    Route::get('/tambah_fasilitas', 'Backend\FasilitasController@tambah_fasilitas')->name('be.tambah_fasilitas');
    Route::post('/simpan_fasilitas', 'Backend\FasilitasController@simpan_fasilitas')->name('be.simpan_fasilitas');
    Route::get('/edit_fasilitas/{id}', 'Backend\FasilitasController@edit_fasilitas')->name('be.edit_fasilitas');
    Route::post('/update_fasilitas/{id}', 'Backend\FasilitasController@update_fasilitas')->name('be.update_fasilitas');
    Route::get('/hapus_fasilitas/{id}', 'Backend\FasilitasController@hapus_fasilitas')->name('be.hapus_fasilitas');

    /*loker*/
    Route::get('/loker', 'Backend\LokerController@loker')->name('be.loker');
    Route::get('/tambah_loker', 'Backend\LokerController@tambah_loker')->name('be.tambah_loker');
    Route::post('/simpan_loker', 'Backend\LokerController@simpan_loker')->name('be.simpan_loker');
    Route::get('/edit_loker/{id}', 'Backend\LokerController@edit_loker')->name('be.edit_loker');
    Route::post('/update_loker/{id}', 'Backend\LokerController@update_loker')->name('be.update_loker');
    Route::get('/hapus_loker/{id}', 'Backend\LokerController@hapus_loker')->name('be.hapus_loker');

    /*gallery*/
    Route::get('/gallery', 'Backend\GalleryController@gallery')->name('be.gallery');
    Route::get('/tambah_gallery', 'Backend\GalleryController@tambah_gallery')->name('be.tambah_gallery');
    Route::post('/simpan_gallery', 'Backend\GalleryController@simpan_gallery')->name('be.simpan_gallery');
    Route::get('/edit_gallery/{id}', 'Backend\GalleryController@edit_gallery')->name('be.edit_gallery');
    Route::post('/update_gallery/{id}', 'Backend\GalleryController@update_gallery')->name('be.update_gallery');
    Route::get('/hapus_gallery/{id}', 'Backend\GalleryController@hapus_gallery')->name('be.hapus_gallery');

    /*mesin_absen*/
    Route::get('/mesin', 'Backend\MesinController@mesin')->name('be.mesin');
    Route::get('/tambah_mesin', 'Backend\MesinController@tambah_mesin')->name('be.tambah_mesin');
    Route::post('/simpan_mesin', 'Backend\MesinController@simpan_mesin')->name('be.simpan_mesin');
    Route::get('/edit_mesin/{id}', 'Backend\MesinController@edit_mesin')->name('be.edit_mesin');
    Route::post('/update_mesin/{id}', 'Backend\MesinController@update_mesin')->name('be.update_mesin');
    Route::get('/hapus_mesin/{id}', 'Backend\MesinController@hapus_mesin')->name('be.hapus_mesin');

    /*periode_absen*/
    Route::get('/periode/{tipe}', 'Backend\PeriodeController@periode')->name('be.periode');
    Route::get('/periode_absen_min', 'Backend\PeriodeController@periode_absen_min')->name('be.periode_absen_min');
    Route::get('/periode_absen_cek_duplicate', 'Backend\PeriodeController@periode_absen_cek_duplicate')->name('be.periode_absen_cek_duplicate');
    Route::get('/periode_lembur', 'Backend\PeriodeController@periode_lembur')->name('be.periode_lembur');
    Route::get('/tambah_periode/{tipe}', 'Backend\PeriodeController@tambah_periode')->name('be.tambah_periode');
    Route::post('/simpan_periode', 'Backend\PeriodeController@simpan_periode')->name('be.simpan_periode');
    Route::get('/edit_periode/{id}/{tipe}', 'Backend\PeriodeController@edit_periode')->name('be.edit_periode');
    Route::post('/update_periode/{id}', 'Backend\PeriodeController@update_periode')->name('be.update_periode');
    Route::get('/hapus_periode/{id}/{tipe}', 'Backend\PeriodeController@hapus_periode')->name('be.hapus_periode');

    /*hari_libur*/
    Route::get('/hari_libur', 'Backend\HariLiburController@hari_libur')->name('be.hari_libur');
    Route::get('/tambah_hari_libur', 'Backend\HariLiburController@tambah_hari_libur')->name('be.tambah_hari_libur');
    Route::post('/simpan_hari_libur', 'Backend\HariLiburController@simpan_hari_libur')->name('be.simpan_hari_libur');
    Route::get('/edit_hari_libur/{id}', 'Backend\HariLiburController@edit_hari_libur')->name('be.edit_hari_libur');
    Route::post('/update_hari_libur/{id}', 'Backend\HariLiburController@update_hari_libur')->name('be.update_hari_libur');
    Route::get('/hapus_hari_libur/{id}', 'Backend\HariLiburController@hapus_hari_libur')->name('be.hapus_hari_libur');
	
    /*jam_kerja*/
    Route::get('/jam_kerja', 'Backend\JamKerjaController@jam_kerja')->name('be.jam_kerja');
    Route::get('/tambah_jam_kerja', 'Backend\JamKerjaController@tambah_jam_kerja')->name('be.tambah_jam_kerja');
    Route::post('/simpan_jam_kerja', 'Backend\JamKerjaController@simpan_jam_kerja')->name('be.simpan_jam_kerja');
    Route::get('/edit_jam_kerja/{id}', 'Backend\JamKerjaController@edit_jam_kerja')->name('be.edit_jam_kerja');
    Route::post('/update_jam_kerja/{id}', 'Backend\JamKerjaController@update_jam_kerja')->name('be.update_jam_kerja');
    Route::get('/hapus_jam_kerja/{id}', 'Backend\JamKerjaController@hapus_jam_kerja')->name('be.hapus_jam_kerja');
    
    Route::post('/entitas_departement', 'Backend\JamKerjaController@entitas_departement')->name('be.entitas_departement');
    
     Route::get('/koreksi', 'Backend\KoreksiController@koreksi')->name('be.Koreksi');
    Route::get('/tambah_koreksi/{id}', 'Backend\KoreksiController@tambah_koreksi')->name('be.tambah_Koreksi');
    Route::get('/tambah_koreksi_pajak/{id}', 'Backend\KoreksiController@tambah_koreksi_pajak')->name('be.tambah_Koreksi_pajak');
    Route::post('/simpan_koreksi/{id}', 'Backend\KoreksiController@simpan_koreksi')->name('be.simpan_Koreksi');
    Route::get('/edit_koreksi/{id}', 'Backend\KoreksiController@edit_koreksi')->name('be.edit_Koreksi');
    Route::post('/update_koreksi/{id}', 'Backend\KoreksiController@update_koreksi')->name('be.update_Koreksi');
    Route::get('/hapus_koreksi/{id}', 'Backend\KoreksiController@hapus_koreksi')->name('be.hapus_Koreksi');
    
     /*sync_absen*/
    Route::get('/sync_mesin', 'Backend\SyncMesinController@sync_mesin')->name('be.sync_mesin');
    Route::get('/sync_mesin_absen', 'Backend\SyncMesinController@syncData')->name('be.sync_mesin_absen');
    /*absen*/
    Route::get('/absen', 'Backend\AbsenController@absen')->name('be.absen');
    Route::get('/cari_absen', 'Backend\AbsenController@cari_absen')->name('be.cari_absen');

    /*ajuan*/
    Route::get('/ajuan', 'Backend\RekapAjuanController@cari_ajuan')->name('be.ajuan');
    Route::get('/cari_ajuan', 'Backend\RekapAjuanController@cari_ajuan')->name('be.cari_ajuan');
    Route::get('/excel_ajuan', 'Backend\RekapAjuanController@excel_ajuan')->name('be.excel_ajuan');

    /*rekap_absen*/
    Route::get('/rekap_absen', 'Backend\AbsenController@rekap_absen')->name('be.rekap_absen');
    Route::get('/cari_rekap_absen', 'Backend\AbsenController@cari_rekap_absen')->name('be.cari_rekap_absen');
    Route::get('/export', 'Backend\AbsenController@export')->name('be.export');

    Route::get('/cek_absen', 'Backend\AbsenController@cari_cek_absen')->name('be.cek_absen');
    Route::get('/cari_cek_absen', 'Backend\AbsenController@cari_cek_absen')->name('be.cek_cari_absen');
   
    Route::get('/cek_absen_hr', 'Backend\AbsenController@cari_cek_absen_hr')->name('be.cek_cari_absen_hr');
    Route::get('/cari_cek_absen_hr', 'Backend\AbsenController@cari_cek_absen_hr')->name('be.cek_cari_absen_hr');
    Route::get('/edit_cek_absen_hr/{id}', 'Backend\AbsenController@edit_cek_absen_hr')->name('be.edit_cari_absen_hr');
    Route::get('/hapus_cari_absen_hr/{id}', 'Backend\AbsenController@hapus_cari_absen_hr')->name('be.hapus_cari_absen_hr');
    Route::post('/simpan_cek_absen_hr/{id}', 'Backend\AbsenController@simpan_cek_absen_hr')->name('be.simpan_cek_absen_hr');
     /*rekapabsen*/
    Route::get('/rekapabsen', 'Backend\RekapAbsenController@view')->name('be.rekapabsen');
    Route::get('/generate_all_periode', 'Backend\RekapAbsenController@generate_all_periode')->name('be.generate_all_periode');
    Route::get('/generate_periode_aktif', 'Backend\RekapAbsenController@generate_periode_aktif')->name('be.generate_periode_aktif');
    Route::get('/jsontest', 'CronJobController@jsontest')->name('be.jsontest');
    Route::get('/hitung_rekap_absen_hari_ini/{id}', 'CronJobController@hitung_rekap_absen_hari_ini')->name('be.hitung_rekap_absen_hari_ini');
    Route::get('/generate_rekap_absen_hari_ini/{id}', 'CronJobController@generate_rekap_absen_hari_ini')->name('be.generate_rekap_absen_hari_ini');
    Route::get('/generate_rekap_absen', 'Backend\RekapAbsenController@generate_rekap_absen')->name('be.generate_rekap_absen');
    Route::get('/hitung_rekap_absen', 'Backend\RekapAbsenController@hitung_rekap_absen')->name('be.hitung_rekap_absen');
	Route::get('/generate_rekap_absen_tanggal', 'Backend\RekapAbsenController@generate_rekap_absen_tanggal')->name('be.generate_rekap_absen_tanggal');
    Route::get('/hitung_rekap_absen_tanggal', 'Backend\RekapAbsenController@hitung_rekap_absen_tanggal')->name('be.hitung_rekap_absen_tanggal');
    Route::get('/list_entitas_periode_absen', 'Backend\RekapAbsenController@list_entitas_periode_absen')->name('be.list_entitas_periode_absen');
	 
	/*rekap_lembur*/
    Route::get('/rekap_lembur', 'Backend\RekapLemburController@view')->name('be.rekap_lembur');
    Route::get('/cari_rekap_lembur', 'Backend\RekapLemburController@cari_rekap_lembur')->name('be.cari_rekap_lembur');
    Route::get('/export_lembur', 'Backend\RekapLemburController@export')->name('be.export_lembur');

	 Route::get('/rekap_izin', 'Backend\RekapizinController@view')->name('be.rekap_izin');
    Route::get('/cari_rekap_izin', 'Backend\RekapizinController@cari_rekap_izin')->name('be.cari_rekap_izin');
    Route::get('/export_izin', 'Backend\RekapizinController@export')->name('be.export_izin');

	 Route::get('/rekap_perdin', 'Backend\RekapperdinController@view')->name('be.rekap_perdin');
    Route::get('/cari_rekap_perdin', 'Backend\RekapperdinController@cari_rekap_perdin')->name('be.cari_rekap_perdin');
    Route::get('/export_perdin', 'Backend\RekapperdinController@export')->name('be.export_perdin');

    Route::get('/cek_lembur', 'Backend\RekapLemburController@cek_lembur')->name('be.cek_lembur');
    Route::get('/cari_cek_lembur', 'Backend\RekapLemburController@cari_cek_lembur')->name('be.cek_cari_lembur');


    /*chat*/
	
 
   // Route::get('/generategaji', 'Backend\DepartemenController@generategaji')->name('be.generategaji');

   Route::get('/klarifikasi_gaji', 'Backend\ChatController@klarifikasi_gaji')->name('be.klarifikasi_gaji');
   Route::get('/klarifikasi_absen', 'Backend\ChatController@klarifikasi_absen')->name('be.klarifikasi_absen');
   Route::get('/selesai_klarifikasi/{id}', 'Backend\ChatController@selesai_klarifikasi')->name('be.selesai_klarifikasi');
   Route::get('/edit_chat_room/{id}', 'Backend\ChatController@edit_chat_room')->name('be.edit_chat_room');
   Route::post('/update_klarifikasi/{id}', 'Backend\ChatController@update_klarifikasi')->name('be.update_klarifikasi');
    Route::get('/chat', 'Backend\ChatController@chat_list')->name('be.chat');
    Route::get('/selesai_chat/{id}', 'Backend\ChatController@selesai_chat')->name('be.selesai_chat');
    Route::get('/view_chat/{id}', 'Backend\ChatController@chat_list')->name('be.view_chat');
    Route::get('/content_chat/{id}', 'Backend\ChatController@content_chat')->name('be.content_chat');
    Route::get('/send_chat/{id}', 'Backend\ChatController@send_chat')->name('be.send_chat');
    /*dept*/
    Route::get('/departemen', 'Backend\DepartemenController@departemen')->name('be.departemen');
    Route::get('/tambah_departemen', 'Backend\DepartemenController@tambah_departemen')->name('be.tambah_departemen');
    Route::post('/simpan_departemen', 'Backend\DepartemenController@simpan_departemen')->name('be.simpan_departemen');
    Route::get('/edit_departemen/{id}', 'Backend\DepartemenController@edit_departemen')->name('be.edit_departemen');
    Route::post('/update_departemen/{id}', 'Backend\DepartemenController@update_departemen')->name('be.update_departemen');
    Route::get('/hapus_departemen/{id}', 'Backend\DepartemenController@hapus_departemen')->name('be.hapus_departemen');
    /*lokasi*/
    Route::get('/lokasi', 'Backend\LokasiController@lokasi')->name('be.lokasi');
    Route::get('/tambah_lokasi', 'Backend\LokasiController@tambah_lokasi')->name('be.tambah_lokasi');
    Route::post('/simpan_lokasi', 'Backend\LokasiController@simpan_lokasi')->name('be.simpan_lokasi');
    Route::get('/edit_lokasi/{id}', 'Backend\LokasiController@edit_lokasi')->name('be.edit_lokasi');
    Route::post('/update_lokasi/{id}', 'Backend\LokasiController@update_lokasi')->name('be.update_lokasi');
    Route::get('/hapus_lokasi/{id}', 'Backend\LokasiController@hapus_lokasi')->name('be.hapus_lokasi');
    /*rumpun jabatan*/
    Route::get('/rumpun_jabatan', 'Backend\RumpunJabatanController@rumpun_jabatan')->name('be.rumpun_jabatan');
    Route::get('/tambah_rumpun_jabatan', 'Backend\RumpunJabatanController@tambah_rumpun_jabatan')->name('be.tambah_rumpun_jabatan');
    Route::post('/simpan_rumpun_jabatan', 'Backend\RumpunJabatanController@simpan_rumpun_jabatan')->name('be.simpan_rumpun_jabatan');
    Route::get('/edit_rumpun_jabatan/{id}', 'Backend\RumpunJabatanController@edit_rumpun_jabatan')->name('be.edit_rumpun_jabatan');
    Route::post('/update_rumpun_jabatan/{id}', 'Backend\RumpunJabatanController@update_rumpun_jabatan')->name('be.update_rumpun_jabatan');
    Route::get('/hapus_rumpun_jabatan/{id}', 'Backend\RumpunJabatanController@hapus_rumpun_jabatan')->name('be.hapus_rumpun_jabatan');
    /*grup jabatan*/
    Route::get('/grup_jabatan', 'Backend\GrupJabatanController@grup_jabatan')->name('be.grup_jabatan');
    Route::get('/tambah_grup_jabatan', 'Backend\GrupJabatanController@tambah_grup_jabatan')->name('be.tambah_grup_jabatan');
    Route::post('/simpan_grup_jabatan', 'Backend\GrupJabatanController@simpan_grup_jabatan')->name('be.simpan_grup_jabatan');
    Route::get('/edit_grup_jabatan/{id}', 'Backend\GrupJabatanController@edit_grup_jabatan')->name('be.edit_grup_jabatan');
    Route::post('/update_grup_jabatan/{id}', 'Backend\GrupJabatanController@update_grup_jabatan')->name('be.update_grup_jabatan');
    Route::get('/hapus_grup_jabatan/{id}', 'Backend\GrupJabatanController@hapus_grup_jabatan')->name('be.hapus_grup_jabatan');
    /*jabatan*/
    Route::get('/jabatan', 'Backend\JabatanController@jabatan')->name('be.jabatan');
    Route::get('/tambah_jabatan', 'Backend\JabatanController@tambah_jabatan')->name('be.tambah_jabatan');
    Route::post('/simpan_jabatan', 'Backend\JabatanController@simpan_jabatan')->name('be.simpan_jabatan');
    Route::get('/edit_jabatan/{id}', 'Backend\JabatanController@edit_jabatan')->name('be.edit_jabatan');
    Route::post('/update_jabatan/{id}', 'Backend\JabatanController@update_jabatan')->name('be.update_jabatan');
    Route::get('/hapus_jabatan/{id}', 'Backend\JabatanController@hapus_jabatan')->name('be.hapus_jabatan');
   
   
	Route::get('/parameter_cuti', 'Backend\CutiParameterController@parameter_reset_cuti')->name('be.parameter_cuti');
    Route::get('/tambah_parameter_reset_cuti', 'Backend\CutiParameterController@tambah_parameter_reset_cuti')->name('be.tambah_parameter_reset_cuti');
    Route::post('/simpan_parameter_reset_cuti', 'Backend\CutiParameterController@simpan_parameter_reset_cuti')->name('be.simpan_parameter_reset_cuti');
    Route::get('/edit_parameter_reset_cuti/{id}', 'Backend\CutiParameterController@edit_parameter_reset_cuti')->name('be.edit_parameter_reset_cuti');
    Route::post('/update_parameter_reset_cuti/{id}', 'Backend\CutiParameterController@update_parameter_reset_cuti')->name('be.update_parameter_reset_cuti');
    Route::get('/hapus_parameter_reset_cuti/{id}', 'Backend\CutiParameterController@hapus_parameter_reset_cuti')->name('be.hapus_parameter_reset_cuti');
    
    Route::get('/kantor', 'Backend\KantorController@kantor')->name('be.kantor');
    Route::get('/tambah_kantor', 'Backend\KantorController@tambah_kantor')->name('be.tambah_kantor');
    Route::post('/simpan_kantor', 'Backend\KantorController@simpan_kantor')->name('be.simpan_Kantor');
    Route::get('/edit_kantor/{id}', 'Backend\KantorController@edit_kantor')->name('be.edit_kantor');
    Route::post('/update_kantor/{id}', 'Backend\KantorController@update_kantor')->name('be.update_kantor');
    Route::get('/hapus_kantor/{id}', 'Backend\KantorController@hapus_kantor')->name('be.hapus_kantor');
     /*status pekerjaan*/
    Route::get('/status_pekerjaan', 'Backend\StatusPekerjaanController@status_pekerjaan')->name('be.status_pekerjaan');
    Route::get('/tambah_status_pekerjaan', 'Backend\StatusPekerjaanController@tambah_status_pekerjaan')->name('be.tambah_status_pekerjaan');
    Route::post('/simpan_status_pekerjaan', 'Backend\StatusPekerjaanController@simpan_status_pekerjaan')->name('be.simpan_status_pekerjaan');
    Route::get('/edit_status_pekerjaan/{id}', 'Backend\StatusPekerjaanController@edit_status_pekerjaan')->name('be.edit_status_pekerjaan');
    Route::post('/update_status_pekerjaan/{id}', 'Backend\StatusPekerjaanController@update_status_pekerjaan')->name('be.update_status_pekerjaan');
    Route::get('/hapus_status_pekerjaan/{id}', 'Backend\StatusPekerjaanController@hapus_status_pekerjaan')->name('be.hapus_status_pekerjaan');
    /*cluster*/
    Route::get('/cluster', 'Backend\ClusterController@cluster')->name('be.cluster');
    Route::get('/tambah_cluster', 'Backend\ClusterController@tambah_cluster')->name('be.tambah_cluster');
    Route::post('/simpan_cluster', 'Backend\ClusterController@simpan_cluster')->name('be.simpan_cluster');
    Route::get('/edit_cluster/{id}', 'Backend\ClusterController@edit_cluster')->name('be.edit_cluster');
    Route::post('/update_cluster/{id}', 'Backend\ClusterController@update_cluster')->name('be.update_cluster');
    Route::get('/hapus_cluster/{id}', 'Backend\ClusterController@hapus_cluster')->name('be.hapus_cluster');
    /*grade*/
    Route::get('/grade', 'Backend\GradeController@grade')->name('be.grade');
    Route::get('/tambah_grade', 'Backend\GradeController@tambah_grade')->name('be.tambah_grade');
    Route::post('/simpan_grade', 'Backend\GradeController@simpan_grade')->name('be.simpan_grade');
    Route::get('/edit_grade/{id}', 'Backend\GradeController@edit_grade')->name('be.edit_grade');
    Route::post('/update_grade/{id}', 'Backend\GradeController@update_grade')->name('be.update_grade');
    Route::get('/hapus_grade/{id}', 'Backend\GradeController@hapus_grade')->name('be.hapus_grade');
    
    Route::get('/karyawan_grade', 'Backend\KaryawanGradeController@grade')->name('be.karyawan_grade');
    Route::get('/tambah_karyawan_grade', 'Backend\KaryawanGradeController@tambah_karyawan_grade')->name('be.tambah_karyawan_grade');
    Route::post('/simpan_karyawan_grade', 'Backend\KaryawanGradeController@simpan_karyawan_grade')->name('be.simpan_karyawan_grade');
    Route::get('/edit_karyawan_grade/{id}', 'Backend\KaryawanGradeController@edit_karyawan_grade')->name('be.edit_karyawan_grade');
    Route::post('/update_karyawan_grade/{id}', 'Backend\KaryawanGradeController@update_karyawan_grade')->name('be.update_karyawan_grade');
    Route::get('/hapus_karyawan_grade/{id}', 'Backend\KaryawanGradeController@hapus_karyawan_grade')->name('be.hapus_karyawan_grade');
    
    /*divisi*/
    Route::get('/divisi', 'Backend\DivisiController@divisi')->name('be.divisi');
    Route::get('/tambah_divisi', 'Backend\DivisiController@tambah_divisi')->name('be.tambah_divisi');
    Route::post('/simpan_divisi', 'Backend\DivisiController@simpan_divisi')->name('be.simpan_divisi');
    Route::get('/edit_divisi/{id}', 'Backend\DivisiController@edit_divisi')->name('be.edit_divisi');
    Route::post('/update_divisi/{id}', 'Backend\DivisiController@update_divisi')->name('be.update_divisi');
    Route::get('/hapus_divisi/{id}', 'Backend\DivisiController@hapus_divisi')->name('be.hapus_divisi');
   
    /*divisi_new*/
    Route::get('/divisi_new', 'Backend\Divisi_newController@divisi_new')->name('be.divisi_new');
    Route::get('/tambah_divisi_new', 'Backend\Divisi_newController@tambah_divisi_new')->name('be.tambah_divisi_new');
    Route::post('/simpan_divisi_new', 'Backend\Divisi_newController@simpan_divisi_new')->name('be.simpan_divisi_new');
    Route::get('/edit_divisi_new/{id}', 'Backend\Divisi_newController@edit_divisi_new')->name('be.edit_divisi_new');
    Route::post('/update_divisi_new/{id}', 'Backend\Divisi_newController@update_divisi_new')->name('be.update_divisi_new');
    Route::get('/hapus_divisi_new/{id}', 'Backend\Divisi_newController@hapus_divisi_new')->name('be.hapus_divisi_new');
    
    /*directorat*/
    Route::get('/directorat', 'Backend\DirectoratController@directorat')->name('be.directorat');
    Route::get('/tambah_directorat', 'Backend\DirectoratController@tambah_directorat')->name('be.tambah_directorat');
    Route::post('/simpan_directorat', 'Backend\DirectoratController@simpan_directorat')->name('be.simpan_directorat');
    Route::get('/edit_directorat/{id}', 'Backend\DirectoratController@edit_directorat')->name('be.edit_directorat');
    Route::post('/update_directorat/{id}', 'Backend\DirectoratController@update_directorat')->name('be.update_directorat');
    Route::get('/hapus_directorat/{id}', 'Backend\DirectoratController@hapus_directorat')->name('be.hapus_directorat');
    
    /*gaji*/     
    
    Route::get('/gapok_pekanan', 'Backend\GajiPokokPekananController@view')->name('be.gapok_pekanan');
    Route::get('/lihat_gapok_pekanan/{id}', 'Backend\GajiPokokPekananController@lihat')->name('be.lihat_gapok_pekanan');
    Route::get('/edit_gapok_pekanan/{id}', 'Backend\GajiPokokPekananController@edit')->name('be.edit_gapok_pekanan');
    Route::get('/hapus_gapok_pekanan/{id}', 'Backend\GajiPokokPekananController@hapus')->name('be.hapus_gapok_pekanan');
    Route::get('/tambah_excel_gapok_pekanan', 'Backend\GajiPokokPekananController@tambah_excel')->name('be.tambah_excel_gapok_pekanan');
    Route::get('/tambah_gapok_pekanan', 'Backend\GajiPokokPekananController@tambah')->name('be.tambah_gapok_pekanan');
    Route::post('/simpan_excel_gapok_pekanan_gaji', 'Backend\GajiPokokPekananController@simpan_excel')->name('be.simpan_excel_gapok_pekanan_gaji');
    Route::get('/excel_empty_data_pekanan_gaji', 'Backend\GajiPokokPekananController@excel_empty_data')->name('be.excel_empty_data_pekanan_gaji');
     Route::get('/excel_exist_data_pekanan_gaji', 'Backend\GajiPokokPekananController@excel_exist_data')->name('be.excel_exist_data_pekanan_gaji');
     Route::post('/simpan_excel_gapok_pekanan', 'Backend\GajiPokokPekananController@simpan_excel')->name('be.simpan_excel_gapok_pekanan');
     Route::post('/simpan_gapok_pekanan', 'Backend\GajiPokokPekananController@simpan')->name('be.simpan_gapok_pekanan');
     Route::post('/update_gapok_pekanan', 'Backend\GajiPokokPekananController@update')->name('be.update_gapok_pekanan');
   
    
    Route::get('/gapok/{type}', 'Backend\GajiPokokController@view')->name('be.gapok');
    Route::get('/tambah_gapok_karyawan/{type}', 'Backend\GajiPokokController@tambah')->name('be.tambah_gapok');
    Route::get('/tambah_excel_gapok/{type}', 'Backend\GajiPokokController@tambah_excel')->name('be.tambah_excel_gapok');
    Route::post('/simpan_excel_gapok/{type}', 'Backend\GajiPokokController@simpan_excel')->name('be.simpan_excel_gapok');
    Route::get('/excel_empty_data/{type}', 'Backend\GajiPokokController@excel_empty_data')->name('be.excel_empty_data');
    Route::get('/excel_exist_data/{type}', 'Backend\GajiPokokController@excel_exist_data')->name('be.excel_exist_data');
    Route::post('/simpan_gapok_karyawan/{type}', 'Backend\GajiPokokController@simpan')->name('be.simpan_gapok');
    Route::post('/update_gapok_karyawan/{type}/', 'Backend\GajiPokokController@update')->name('be.update_gapok');
    Route::get('/hapus_gapok/{type}/{id}', 'Backend\GajiPokokController@hapus')->name('be.hapus_gapok');
    Route::get('/edit_gapok_karyawan/{type}/{id}', 'Backend\GajiPokokController@edit')->name('be.edit_gapok');
    Route::get('/lihat_gapok_karyawan/{type}/{id}', 'Backend\GajiPokokController@lihat')->name('be.lihat_gapok');
    
    
    Route::get('/preview_non_ajuan/{id}', 'Backend\GajiPreviewController@preview_non_ajuan')->name('be.preview_non_ajuan');
    Route::get('/preview_ajuan/{id}', 'Backend\GajiPreviewController@preview_ajuan')->name('be.preview_ajuan');
    Route::get('/preview_direksi/{id}', 'Backend\GajiPreviewController@preview_direksi')->name('be.preview_direksi');
    Route::get('/transaksi/{page}/{subpage}', 'Backend\GajiPreviewController@view')->name('be.transaksi');
    Route::get('/previewgaji/{id}', 'Backend\GajiPreviewController@view')->name('be.previewgaji');
    Route::post('/simpan_appr_gaji', 'Backend\GajiPreviewController@simpan_appr_gaji')->name('be.simpan_appr_gaji');
    Route::post('/simpan_konfirm_gaji', 'Backend\GajiPreviewController@simpan_konfirm_gaji')->name('be.simpan_konfirm_gaji');
    Route::post('/simpan_konfirm_gaji_hr', 'Backend\GajiPreviewController@simpan_konfirm_gaji_hr')->name('be.simpan_konfirm_gaji_hr');
    Route::get('/save_keterangan_edit_ajuan', 'Backend\GajiPreviewController@save_keterangan_edit_ajuan')->name('be.save_keterangan_edit_ajuan'); 
    Route::get('/slip_gaji/', 'Backend\GajiSlipController@view')->name('be.slip_gaji');
    Route::post('/karyawan_gaji/', 'Backend\GajiSlipController@karyawan_gaji')->name('be.karyawan_gaji');
    
    Route::get('/previewthr/{id}', 'Backend\ThrController@view')->name('be.previewthr');
    Route::get('/tambah_generate_thr', 'Backend\ThrController@tambah_generate_thr')->name('be.tambah_generate_thr');
    Route::post('/post_generate_thr', 'Backend\ThrController@post_generate_thr')->name('be.post_generate_thr');
    Route::get('/proses_generate_thr/{id}', 'Backend\ThrController@proses_generate_thr')->name('be.proses_generate_thr');
    Route::get('/hitung_thr/{id}', 'Backend\ThrController@hitung_thr')->name('be.hitung_thr');
    
    
    Route::get('/nonaktifgenerate/{id}', 'Backend\GajiGenerateController@nonaktifgenerate')->name('be.nonaktifgenerate');
    Route::get('/generate_gaji_sebelumnya', 'Backend\GajiGenerateController@generate_gaji_sebelumnya')->name('be.generate_gaji_sebelumnya');
    Route::get('/generategaji', 'Backend\GajiGenerateController@index')->name('be.generategaji');
    Route::get('/regenerate/{id}', 'Backend\GajiGenerateController@regenerate')->name('be.regenerate');
	Route::get('/regenerate_pajak/{id}', 'Backend\GajiGenerateController@regenerate_pajak')->name('be.regenerate_pajak');
	Route::get('/regenerate_field/{id}', 'Backend\GajiGenerateController@regenerate_field')->name('be.regenerate_field');
	Route::get('/hitung_pajak/{id}', 'Backend\GajiGenerateController@hitung_pajak')->name('be.hitung_pajak');
    Route::get('/revisi_data_generate/{id}', 'Backend\GajiGenerateController@revisi_data_generate')->name('be.revisi_data_generate');
    Route::post('/send_revisi/', 'Backend\GajiGenerateController@send_revisi')->name('be.send_revisi');
    Route::get('/revisi_generate/{id}', 'Backend\GajiGenerateController@revisi_generate')->name('be.revisi_generate');
    Route::post('/repost_generate/{id}', 'Backend\GajiGenerateController@repost_generate')->name('be.repost_generate');
    Route::get('/tambah_generate_gaji', 'Backend\GajiGenerateController@tambah')->name('be.tambah_generate_gaji');
    Route::get('/proses_generate/{id}', 'Backend\GajiGenerateController@proses')->name('be.proses_generate');
    Route::get('/getkaryawanprosesgenerate/{id}', 'Backend\GajiGenerateController@getkaryawanprosesgenerate')->name('be.getkaryawanprosesgenerate');
    Route::get('/getkaryawanprosesgenerate_thr/{id}', 'Backend\ThrController@getkaryawanprosesgenerate_thr')->name('be.getkaryawanprosesgenerate_thr');
    Route::post('/post_generate', 'Backend\GajiGenerateController@post_generate')->name('be.post_generate');
    Route::get('/hitung_absen/{id}', 'Backend\GajiGenerateController@hitung_absen')->name('be.hitung_absen');
    Route::get('/hitung_gaji/{id}', 'Backend\GajiGenerateController@hitung')->name('be.hitung_gaji');
    Route::get('/hitung_gaji_pekanan/{id}', 'Backend\GajiGenerateController@hitung_pekanan')->name('be.hitung_gaji_pekanan');
    
    Route::get('/master_tunjangan', 'Backend\Gaji\MasterTunjanganController@index')->name('be.master_tunjangan');
    Route::get('/master_beban', 'Backend\GajiGenerateController@index')->name('be.master_beban');
   // Route::get('/master_potongan', 'Backend\GajigenerateController@index')->name('be.master_beban');
    Route::get('/rekapgaji', 'Backend\GajiRekapController@view')->name('be.gajirekap');
    //Route::get('/rekapgaji', 'Backend\GajiRekapController@view')->name('be.sliprekap');
    Route::get('/master_tunjangan', 'Backend\Gaji\Master_tunjanganController@master_tunjangan')->name('be.master_tunjangan');
    Route::get('/tambah_master_tunjangan', 'Backend\Gaji\Master_tunjanganController@tambah')->name('be.tambah_master_tunjangan');
    Route::post('/simpan_master_tunjangan', 'Backend\Gaji\Master_tunjanganController@simpan')->name('be.simpan_master_tunjangan');
    Route::get('/edit_master_tunjangan/{id}', 'Backend\Gaji\Master_tunjanganController@edit')->name('be.edit_master_tunjangan');
    Route::post('/update_master_tunjangan/{id}', 'Backend\Gaji\Master_tunjanganController@update')->name('be.update_master_tunjangan');
    Route::get('/hapus_master_tunjangan/{id}', 'Backend\Gaji\Master_tunjanganController@hapus')->name('be.hapus_master_tunjangan');

 Route::get('/master_beban', 'Backend\Gaji\Master_bebanController@master_beban')->name('be.master_beban');
    Route::get('/tambah_master_beban', 'Backend\Gaji\Master_bebanController@tambah')->name('be.tambah_master_beban');
    Route::post('/simpan_master_beban', 'Backend\Gaji\Master_bebanController@simpan')->name('be.simpan_master_beban');
    Route::get('/edit_master_beban/{id}', 'Backend\Gaji\Master_bebanController@edit')->name('be.edit_master_beban');
    Route::post('/update_master_beban/{id}', 'Backend\Gaji\Master_bebanController@update')->name('be.update_master_beban');
    Route::get('/hapus_master_beban/{id}', 'Backend\Gaji\Master_bebanController@hapus')->name('be.hapus_master_beban');


 


 Route::get('/master_pajak_ptkp', 'Backend\Gaji\Master_pajakptkpController@master_pajak_ptkp')->name('be.master_pajak_ptkp');
    Route::get('/tambah_master_pajak_ptkp', 'Backend\Gaji\Master_pajakptkpController@tambah')->name('be.tambah_master_pajak_ptkp');
    Route::post('/simpan_master_pajak_ptkp', 'Backend\Gaji\Master_pajakptkpController@simpan')->name('be.simpan_master_pajak_ptkp');
    Route::get('/edit_master_pajak_ptkp/{id}', 'Backend\Gaji\Master_pajakptkpController@edit')->name('be.edit_master_pajak_ptkp');
    Route::post('/update_master_pajak_ptkp/{id}', 'Backend\Gaji\Master_pajakptkpController@update')->name('be.update_master_pajak_ptkp');
    Route::get('/hapus_master_potongan/{id}', 'Backend\Gaji\Master_potonganController@hapus')->name('be.hapus_master_potongan');
    Route::get('/hapus_master_pajak_ptkp/{id}', 'Backend\Gaji\Master_pajakptkpController@hapus')->name('be.hapus_master_pajak_ptkp');
Route::get('/master_potongan', 'Backend\Gaji\Master_potonganController@master_potongan')->name('be.master_potongan');
    Route::get('/tambah_master_potongan', 'Backend\Gaji\Master_potonganController@tambah')->name('be.tambah_master_potongan');
    Route::post('/simpan_master_potongan', 'Backend\Gaji\Master_potonganController@simpan')->name('be.simpan_master_potongan');
    Route::get('/edit_master_potongan/{id}', 'Backend\Gaji\Master_potonganController@edit')->name('be.edit_master_potongan');
    Route::post('/update_master_potongan/{id}', 'Backend\Gaji\Master_potonganController@update')->name('be.update_master_potongan');
    Route::get('/hapus_master_potongan/{id}', 'Backend\Gaji\Master_potonganController@hapus')->name('be.hapus_master_potongan');

	/*Route::get('/master_potongan_absen', 'Backend\Gaji\Master_potongan_absenController@master_potongan_absen')->name('be.master_potongan_absen');
    Route::get('/tambah_master_potongan_absen', 'Backend\Gaji\Master_potongan_absenController@tambah')->name('be.tambah_master_potongan_absen');
    Route::post('/simpan_master_potongan_absen', 'Backend\Gaji\Master_potongan_absenController@simpan')->name('be.simpan_master_potongan_absen');
    Route::get('/edit_master_potongan_absen/{id}', 'Backend\Gaji\Master_potongan_absenController@edit')->name('be.edit_master_potongan_absen');
    Route::post('/update_master_potongan_absen/{id}', 'Backend\Gaji\Master_potongan_absenController@update')->name('be.update_master_potongan_absen');
    Route::get('/hapus_master_potongan_absen/{id}', 'Backend\Gaji\Master_potongan_absenController@hapus')->name('be.hapus_master_potongan_absen');*/
	
	Route::get('/master_potongan_absen', 'Backend\Gaji\Master_potongan_absenController@master_potongan_absen')->name('be.master_potongan_absen');
    Route::get('/master_potongan_absen_tambah', 'Backend\Gaji\Master_potongan_absenController@master_potongan_absen_tambah')->name('be.master_potongan_absen_tambah');
    Route::post('/master_potongan_absen_simpan', 'Backend\Gaji\Master_potongan_absenController@master_potongan_absen_simpan')->name('be.master_potongan_absen_simpan');
    Route::get('/master_potongan_absen_edit/{id}', 'Backend\Gaji\Master_potongan_absenController@master_potongan_absen_edit')->name('be.master_potongan_absen_edit');
    Route::post('/master_potongan_absen_update/{id}', 'Backend\Gaji\Master_potongan_absenController@master_potongan_absen_update')->name('be.master_potongan_absen_update');
    Route::get('/master_potongan_absen_hapus/{id}', 'Backend\Gaji\Master_potongan_absenController@master_potongan_absen_hapus')->name('be.master_potongan_absen_hapus');

Route::get('/master_potongan_absen_pekanan', 'Backend\Gaji\Master_potongan_absen_pekananController@master_potongan_absen')->name('be.master_potongan_absen_pekanan');
    Route::get('/tambah_master_potongan_absen_pekanan', 'Backend\Gaji\Master_potongan_absen_pekananController@tambah')->name('be.tambah_master_potongan_absen_pekanan');
    Route::post('/simpan_master_potongan_absen_pekanan', 'Backend\Gaji\Master_potongan_absen_pekananController@simpan')->name('be.simpan_master_potongan_absen_pekanan');
    Route::get('/edit_master_potongan_absen_pekanan/{id}', 'Backend\Gaji\Master_potongan_absen_pekananController@edit')->name('be.edit_master_potongan_absen_pekanan');
    Route::post('/update_master_potongan_absen_pekanan/{id}', 'Backend\Gaji\Master_potongan_absen_pekananController@update')->name('be.update_master_potongan_absen_pekanan');
    Route::get('/hapus_master_potongan_absen_pekanan/{id}', 'Backend\Gaji\Master_potongan_absen_pekananController@hapus')->name('be.hapus_master_potongan_absen_pekanan');


Route::get('/master_pajak_status', 'Backend\Gaji\Master_pajak_statusController@master_pajak_status')->name('be.master_pajak_status');
    Route::get('/tambah_master_pajak_status', 'Backend\Gaji\Master_pajak_statusController@tambah')->name('be.tambah_master_pajak_status');
    Route::post('/simpan_master_pajak_status', 'Backend\Gaji\Master_pajak_statusController@simpan')->name('be.simpan_master_pajak_status');
    Route::get('/edit_master_pajak_status/{id}', 'Backend\Gaji\Master_pajak_statusController@edit')->name('be.edit_master_pajak_status');
    Route::post('/update_master_pajak_status/{id}', 'Backend\Gaji\Master_pajak_statusController@update')->name('be.update_master_pajak_status');
    Route::get('/hapus_master_pajak_status/{id}', 'Backend\Gaji\Master_pajak_statusController@hapus')->name('be.hapus_master_pajak_status');

Route::get('/master_status', 'Backend\Gaji\Master_statusController@master_status')->name('be.master_status');
    Route::get('/tambah_master_status', 'Backend\Gaji\Master_statusController@tambah')->name('be.tambah_master_status');
    Route::post('/simpan_master_status', 'Backend\Gaji\Master_statusController@simpan')->name('be.simpan_master_status');
    Route::get('/edit_master_status/{id}', 'Backend\Gaji\Master_statusController@edit')->name('be.edit_master_status');
    Route::post('/update_master_status/{id}', 'Backend\Gaji\Master_statusController@update')->name('be.update_master_status');
    Route::get('/hapus_master_status/{id}', 'Backend\Gaji\Master_statusController@hapus')->name('be.hapus_master_status');

    Route::get('/sturktur_organisasi', 'Backend\Struktur_organisasiController@index')->name('be.struktur_organisasi');
    
    Route::get('/hirarki_jabatan', 'Backend\JabatanAtasanController@jabatan')->name('be.hirarki_jabatan');
    Route::get('/tambah_atasan', 'Backend\JabatanAtasanController@tambah')->name('be.tambah_atasan');
    Route::post('/simpan_atasan', 'Backend\JabatanAtasanController@simpan')->name('be.simpan_atasan');
    Route::get('/edit_atasan/{id}', 'Backend\JabatanAtasanController@edit')->name('be.edit_atasan');
    Route::get('/hapus_atasan/{id}', 'Backend\JabatanAtasanController@hapus')->name('be.hapus_atasan');
    Route::post('/update_atasan/{id}', 'Backend\JabatanAtasanController@update')->name('be.update_atasan');
    /*pengajuan_bpjs*/
    Route::get('/pengajuan_bpjs', 'Backend\Pengajuan_bpjsController@pengajuan_bpjs')->name('be.pengajuan_bpjs');
    Route::get('/selesai_pengajuan_bpjs/{id}', 'Backend\Pengajuan_bpjsController@selesai_pengajuan_bpjs')->name('be.selesai_pengajuan_bpjs');
    Route::get('/tambah_pengajuan_bpjs', 'Backend\Pengajuan_bpjsController@tambah_pengajuan_bpjs')->name('be.tambah_pengajuan_bpjs');
    Route::post('/simpan_pengajuan_bpjs', 'Backend\Pengajuan_bpjsController@simpan_pengajuan_bpjs')->name('be.simpan_pengajuan_bpjs');
    Route::get('/edit_pengajuan_bpjs/{id}', 'Backend\Pengajuan_bpjsController@edit_pengajuan_bpjs')->name('be.edit_pengajuan_bpjs');
    Route::post('/update_pengajuan_bpjs/{id}', 'Backend\Pengajuan_bpjsController@update_pengajuan_bpjs')->name('be.update_pengajuan_bpjs');
    Route::get('/hapus_pengajuan_bpjs/{id}', 'Backend\Pengajuan_bpjsController@hapus_pengajuan_bpjs')->name('be.hapus_pengajuan_bpjs');
    
    Route::get('/ajuan_pergantian_hari_libur', 'Backend\Ajuan_pergantian_hari_liburController@pergantian_hari_libur')->name('be.ajuan_pergantian_hari_libur');
    Route::get('/selesai_ajuan_pergantian_hari_libur/{id}', 'Backend\Ajuan_pergantian_hari_liburController@selesai_pergantian_hari_libur')->name('be.selesai_ajuan_pergantian_hari_libur');
    Route::get('/tambah_ajuan_pergantian_hari_libur', 'Backend\Ajuan_pergantian_hari_liburController@tambah_pergantian_hari_libur')->name('be.tambah_ajuan_pergantian_hari_libur');
    Route::post('/simpan_ajuan_pergantian_hari_libur', 'Backend\Ajuan_pergantian_hari_liburController@simpan_pergantian_hari_libur')->name('be.simpan_ajuan_pergantian_hari_libur');
    Route::get('/edit_ajuan_pergantian_hari_libur/{id}', 'Backend\Ajuan_pergantian_hari_liburController@edit_pergantian_hari_libur')->name('be.edit_ajuan_pergantian_hari_libur');
    Route::post('/update_ajuan_pergantian_hari_libur/{id}', 'Backend\Ajuan_pergantian_hari_liburController@update_pergantian_hari_libur')->name('be.update_ajuan_pergantian_hari_libur');
    Route::get('/hapus_ajuan_pergantian_hari_libur/{id}', 'Backend\Ajuan_pergantian_hari_liburController@hapus_pergantian_hari_libur')->name('be.hapus_ajuan_pergantian_hari_libur');
    /*pangkat*/
    Route::get('/anggota_club/{id}', 'Backend\ClubController@anggota_club')->name('be.anggota_club');
    Route::get('/hapus_anggota_club/{id}/{id_anggota}', 'Backend\ClubController@hapus_anggota_club')->name('be.hapus_anggota_club');
    Route::get('/foto_kegiatan_club/{id}/{id_anggota}', 'Backend\ClubController@foto_kegiatan_club')->name('be.foto_kegiatan_club');
    Route::get('/tambah_foto_kegiatan_club/{id}/{id_anggota}', 'Backend\ClubController@tambah_foto_kegiatan_club')->name('be.tambah_foto_kegiatan_club');
    Route::post('/simpan_foto_kegiatan_club/{id}/{id_anggota}', 'Backend\ClubController@simpan_foto_kegiatan_club')->name('be.simpan_foto_kegiatan_club');
    Route::get('/hapus_foto_club/{id}/{id_anggota}/{id_foto}', 'Backend\ClubController@hapus_foto_club')->name('be.hapus_foto_club');
    Route::get('/tambah_anggota_club/{id}', 'Backend\ClubController@tambah_anggota_club')->name('be.tambah_anggota_club');
    Route::post('/simpan_anggota_club/{id}', 'Backend\ClubController@simpan_anggota_club')->name('be.simpan_anggota_club');
    Route::get('/kegiatan_club/{id}', 'Backend\ClubController@kegiatan_club')->name('be.kegiatan_club');
    Route::get('/tambah_kegiatan_club/{id}', 'Backend\ClubController@tambah_kegiatan_club')->name('be.tambah_kegiatan_club');
    Route::post('/simpan_kegiatan_club/{id}', 'Backend\ClubController@simpan_kegiatan_club')->name('be.simpan_kegiatan_club');
    Route::get('/club', 'Backend\ClubController@club')->name('be.club');
    Route::get('/tambah_club', 'Backend\ClubController@tambah_club')->name('be.tambah_club');
    Route::post('/simpan_club', 'Backend\ClubController@simpan_club')->name('be.simpan_club');
    Route::get('/edit_club/{id}', 'Backend\ClubController@edit_club')->name('be.edit_club');
    Route::get('/hapus_club/{id}', 'Backend\ClubController@hapus_club')->name('be.hapus_club');
    
    
    Route::get('/pangkat', 'Backend\PangkatController@pangkat')->name('be.pangkat');
    Route::get('/tambah_pangkat', 'Backend\PangkatController@tambah_pangkat')->name('be.tambah_pangkat');
    Route::post('/simpan_pangkat', 'Backend\PangkatController@simpan_pangkat')->name('be.simpan_pangkat');
    Route::get('/edit_pangkat/{id}', 'Backend\PangkatController@edit_pangkat')->name('be.edit_pangkat');
    Route::post('/update_pangkat/{id}', 'Backend\PangkatController@update_pangkat')->name('be.update_pangkat');
    Route::get('/hapus_pangkat/{id}', 'Backend\PangkatController@hapus_pangkat')->name('be.hapus_pangkat');
    /*karyawan*/
    Route::get('/finddirectorat', 'Backend\KaryawanController@finddirectorat')->name('be.finddirectorat');
    Route::get('/finddivisi', 'Backend\KaryawanController@finddivisi')->name('be.finddivisi');
    Route::get('/findseksi', 'Backend\KaryawanController@findseksi')->name('be.findseksi');
    Route::get('/finddepartement', 'Backend\KaryawanController@finddepartement')->name('be.finddepartement');
    Route::get('/findjabatan', 'Backend\KaryawanController@findjabatan')->name('be.findjabatan');
    Route::get('/historis_kerja_karyawan', 'Backend\KaryawanController@historis_kerja_karyawan')->name('be.historis_kerja_karyawan');
    Route::get('/input_absen/{pin}', 'Backend\KaryawanController@input_absen')->name('be.input_absen');
    Route::post('/simpan_input_absen/{pin}', 'Backend\KaryawanController@simpan_input_absen')->name('be.simpan_input_absen');
    Route::get('/input_absen_hr/', 'Backend\KaryawanController@input_absen_hr')->name('be.input_absen_hr');
    Route::get('/export_absen/', 'Backend\KaryawanController@export_absen')->name('be.export_absen');
    Route::post('/simpan_input_absen_hr/', 'Backend\KaryawanController@simpan_input_absen_hr')->name('be.simpan_input_absen_hr');
    Route::get('/input_absen_hr/', 'Backend\AbsenController@input_absen_hr')->name('be.input_absen_hr');
    Route::get('/export_absen/', 'Backend\KaryawanController@export_absen')->name('be.export_absen');
    Route::post('/simpan_input_absen_hr/', 'Backend\AbsenController@simpan_input_absen_hr')->name('be.simpan_input_absen_hr');
    Route::post('/ajax_input_absen/', 'Backend\KaryawanController@ajax_input_absen')->name('be.ajax_input_absen');
    Route::get('/recruitment', 'Backend\RecruitmentController@recruitment')->name('be.recruitment');
    Route::get('/karyawan', 'Backend\KaryawanController@karyawan')->name('be.karyawan');
    Route::get('/get_data_karyawan', 'Backend\KaryawanController@get_data_karyawan')->name('be.get_data_karyawan');
    Route::get('/karyawanresign', 'Backend\KaryawanController@karyawanresign')->name('be.karyawanresign');
    Route::get('/tambah_karyawan', 'Backend\KaryawanController@tambah_karyawan')->name('be.tambah_karyawan');
    Route::post('/simpan_karyawan', 'Backend\KaryawanController@simpan_karyawan')->name('be.simpan_karyawan');
    Route::get('/export_excel_karyawan', 'Backend\KaryawanController@excel')->name('be.export_excel_karyawan');
    Route::post('/download_excel_karyawan', 'Backend\KaryawanController@download_excel')->name('be.download_excel_karyawan');
    Route::get('/excel_karyawan', 'Backend\KaryawanController@excel_karyawan')->name('be.excel_karyawan');
    Route::get('/excel_karyawan_resign', 'Backend\KaryawanController@excel_karyawan_resign')->name('be.excel_karyawan_resign');
    Route::get('/report_absen_karyawan', 'Backend\KaryawanController@report_absen_karyawan')->name('be.report_absen_karyawan');
    Route::get('/file_karyawan', 'Backend\KaryawanController@file_karyawan')->name('be.file_karyawan');
    Route::get('/hapus_file_karyawan/{id}/{id2}', 'Backend\KaryawanController@hapus_file_karyawan')->name('be.hapus_file_karyawan');
    Route::get('/view_karyawan/{id}', 'Backend\KaryawanController@view_karyawan')->name('be.view_karyawan');
    Route::get('/edit_karyawan/{id}', 'Backend\KaryawanController@edit_karyawan')->name('be.edit_karyawan');
    Route::post('/update_karyawan/{id}', 'Backend\KaryawanController@update_karyawan')->name('be.update_karyawan');
    Route::get('/hapus_karyawan/{id}', 'Backend\KaryawanController@hapus_karyawan')->name('be.hapus_karyawan');
    Route::get('/generate_nik', 'Backend\KaryawanController@generate_nik')->name('be.generate_nik');
    Route::get('/aktif/{id}', 'Backend\KaryawanController@aktif')->name('be.aktif');
    /*user*/
    Route::get('/user', 'Backend\UserController@user')->name('be.user');
    Route::get('/tambah_user', 'Backend\UserController@tambah_user')->name('be.tambah_user');
    Route::post('/simpan_user', 'Backend\UserController@simpan_user')->name('be.simpan_user');
    Route::get('/edit_user/{id}', 'Backend\UserController@edit_user')->name('be.edit_user');
    Route::post('/update_user/{id}', 'Backend\UserController@update_user')->name('be.update_user');
    Route::get('/hapus_user/{id}', 'Backend\UserController@hapus_user')->name('be.hapus_user');
    
    Route::get('/akun', 'Backend\AkunController@akun')->name('be.akun');
    Route::get('/tambah_akun', 'Backend\AkunController@tambah_akun')->name('be.tambah_akun');
    Route::post('/simpan_akun', 'Backend\AkunController@simpan_akun')->name('be.simpan_akun');
    Route::get('/edit_akun/{id}', 'Backend\AkunController@edit_akun')->name('be.edit_akun');
    Route::post('/update_akun/{id}', 'Backend\AkunController@update_akun')->name('be.update_akun');
    Route::get('/hapus_akun/{id}', 'Backend\AkunController@hapus_akun')->name('be.hapus_akun');
    
    /*admin*/
    Route::get('/list_admin', 'Backend\AdminController@admin')->name('be.admin');
    Route::get('/lihat_admin/{id}', 'Backend\AdminController@lihat')->name('be.lihat_admin');
    Route::get('/tambah_admin', 'Backend\AdminController@tambah_admin')->name('be.tambah_admin');
    Route::post('/simpan_admin', 'Backend\AdminController@simpan_admin')->name('be.simpan_Admin');
    Route::get('/edit_admin/{id}', 'Backend\AdminController@edit_admin')->name('be.edit_admin');
    Route::post('/update_admin/{id}', 'Backend\AdminController@update_admin')->name('be.update_admin');
    Route::get('/hapus_admin/{id}', 'Backend\AdminController@hapus_admin')->name('be.hapus_admin');
   
	Route::get('/list_master_gaji_berjangka/{id}', 'Backend\KoperasiController@koperasi')->name('be.koperasi');
	Route::get('/tambah_gaji_berjangka/{id}', 'Backend\KoperasiController@tambah_koperasi')->name('be.tambah_koperasi');
	Route::get('/tambah_excel_gaji_berjangka/{id}', 'Backend\KoperasiController@tambah_excel_koperasi')->name('be.tambah_excel_koperasi');
	Route::get('/excel_empty_data_gaji_berjangka/{id}', 'Backend\KoperasiController@excel_empty_data')->name('be.excel_empty_data_koperasi');
	Route::get('/excel_exist_data_gaji_berjangka/{id}', 'Backend\KoperasiController@excel_exist_data')->name('be.excel_exist_data_koperasi');
	Route::post('/simpan_excel_koperasi/{id}', 'Backend\KoperasiController@simpan_excel')->name('be.simpan_excel_koperasi');
	Route::post('/simpan_gaji_berjangka/', 'Backend\KoperasiController@simpan_koperasi')->name('be.simpan_koperasi');
    Route::get('/edit_gaji_berjangka/{id}/{page}', 'Backend\KoperasiController@edit_koperasi')->name('be.edit_koperasi');
    Route::post('/update_gaji_berjangka/{id}', 'Backend\KoperasiController@update_koperasi')->name('be.update_koperasi');
    Route::get('/hapus_gaji_berjangka/{id}', 'Backend\KoperasiController@hapus_koperasi')->name('be.hapus_koperasi');
   
   	Route::get('/list_pajak', 'Backend\PajakController@pajak')->name('be.pajak');
    Route::get('/tambah_pajak', 'Backend\PajakController@tambah_pajak')->name('be.tambah_pajak');
    Route::get('/tambah_excel_pajak', 'Backend\PajakController@tambah_excel_pajak')->name('be.tambah_excel_pajak');
    Route::get('/excel_empty_data_pajak', 'Backend\PajakController@excel_empty_data')->name('be.excel_empty_data_pajak');
    Route::get('/excel_exist_data_pajak', 'Backend\PajakController@excel_exist_data')->name('be.excel_exist_data_pajak');
    Route::post('/simpan_excel_pajak', 'Backend\PajakController@simpan_excel')->name('be.simpan_excel_pajak');
    Route::post('/simpan_pajak', 'Backend\PajakController@simpan_pajak')->name('be.simpan_pajak');
    Route::get('/edit_pajak/{id}', 'Backend\PajakController@edit_pajak')->name('be.edit_pajak');
    Route::post('/update_pajak/{id}', 'Backend\PajakController@update_pajak')->name('be.update_pajak');
    Route::get('/hapus_pajak/{id}', 'Backend\PajakController@hapus_pajak')->name('be.hapus_pajak');
   
   	Route::get('/list_libur_shift', 'Backend\Libur_ShiftController@libur_shift')->name('be.libur_shift');
    Route::get('/tambah_libur_shift', 'Backend\Libur_ShiftController@tambah_libur_shift')->name('be.tambah_libur_shift');
    Route::post('/simpan_libur_shift', 'Backend\Libur_ShiftController@simpan_libur_shift')->name('be.simpan_libur_shift');
    Route::get('/edit_libur_shift/{id}', 'Backend\Libur_ShiftController@edit_libur_shift')->name('be.edit_libur_shift');
    Route::post('/update_libur_shift/{id}', 'Backend\Libur_ShiftController@update_libur_shift')->name('be.update_libur_shift');
    Route::get('/hapus_libur_shift/{id}', 'Backend\Libur_ShiftController@hapus_libur_shift')->name('be.hapus_libur_shift');
   
   	Route::get('/shift_karyawan', 'Backend\ShiftController@shift_karyawan')->name('be.shift_karyawan');
   	
   	
   	Route::get('/list_shift', 'Backend\ShiftController@shift')->name('be.shift');
    Route::get('/tambah_shift', 'Backend\ShiftController@tambah_shift')->name('be.tambah_shift');
    Route::get('/tambah_shift_excel', 'Backend\ShiftController@tambah_shift_excel')->name('be.tambah_shift_excel');
    Route::post('/simpan_excel_shift', 'Backend\ShiftController@simpan_excel_shift')->name('be.simpan_excel_shift');
    Route::get('/excel_shift', 'Backend\ShiftController@excel_shift')->name('be.excel_shift');
    Route::post('/simpan_shift', 'Backend\ShiftController@simpan_shift')->name('be.simpan_shift');
    Route::get('/edit_shift/{id}', 'Backend\ShiftController@edit_shift')->name('be.edit_shift');
    Route::post('/update_shift/{id}', 'Backend\ShiftController@update_shift')->name('be.update_shift');
    Route::get('/hapus_shift/{id}', 'Backend\ShiftController@hapus_shift')->name('be.hapus_shift');
   
   
    Route::get('/list_filepajak', 'Backend\FilePajakController@filepajak')->name('be.filepajak');
    Route::get('/tambah_filepajak', 'Backend\FilePajakController@tambah_filepajak')->name('be.tambah_filepajak');
    Route::post('/simpan_filepajak', 'Backend\FilePajakController@simpan_filepajak')->name('be.simpan_filepajak');
    Route::post('/simpan_multifilepajak', 'Backend\FilePajakController@simpan_multifilepajak')->name('be.simpan_multifilepajak');
    Route::get('/edit_filepajak/{id}', 'Backend\FilePajakController@edit_filepajak')->name('be.edit_filepajak');
    Route::post('/update_filepajak/{id}', 'Backend\FilePajakController@update_filepajak')->name('be.update_filepajak');
    Route::get('/hapus_filepajak/{id}', 'Backend\FilePajakController@hapus_filepajak')->name('be.hapus_filepajak');
    
    
    Route::get('/list_bank', 'Backend\BankController@bank')->name('be.bank');
    Route::get('/tambah_bank', 'Backend\BankController@tambah_bank')->name('be.tambah_bank');
    Route::post('/simpan_bank', 'Backend\BankController@simpan_bank')->name('be.simpan_bank');
    Route::get('/edit_bank/{id}', 'Backend\BankController@edit_bank')->name('be.edit_bank');
    Route::post('/update_bank/{id}', 'Backend\BankController@update_bank')->name('be.update_bank');
    Route::get('/hapus_bank/{id}', 'Backend\BankController@hapus_bank')->name('be.hapus_bank');
    
    Route::get('/list_jenis_surat', 'Backend\Jenis_suratController@jenis_surat')->name('be.jenis_surat');
    Route::get('/tambah_jenis_surat', 'Backend\Jenis_suratController@tambah_jenis_surat')->name('be.tambah_jenis_surat');
    Route::post('/simpan_jenis_surat', 'Backend\Jenis_suratController@simpan_jenis_surat')->name('be.simpan_jenis_surat');
    Route::get('/edit_jenis_surat/{id}', 'Backend\Jenis_suratController@edit_jenis_surat')->name('be.edit_jenis_surat');
    Route::post('/update_jenis_surat/{id}', 'Backend\Jenis_suratController@update_jenis_surat')->name('be.update_jenis_surat');
    Route::get('/hapus_jenis_surat/{id}', 'Backend\Jenis_suratController@hapus_jenis_surat')->name('be.hapus_jenis_surat');
    
     Route::get('/list_jenis_reward', 'Backend\Jenis_rewardController@jenis_reward')->name('be.jenis_reward');
    Route::get('/tambah_jenis_reward', 'Backend\Jenis_rewardController@tambah_jenis_reward')->name('be.tambah_jenis_reward');
    Route::post('/simpan_jenis_reward', 'Backend\Jenis_rewardController@simpan_jenis_reward')->name('be.simpan_jenis_reward');
    Route::get('/edit_jenis_reward/{id}', 'Backend\Jenis_rewardController@edit_jenis_reward')->name('be.edit_jenis_reward');
    Route::post('/update_jenis_reward/{id}', 'Backend\Jenis_rewardController@update_jenis_reward')->name('be.update_jenis_reward');
    Route::get('/hapus_jenis_reward/{id}', 'Backend\Jenis_rewardController@hapus_jenis_reward')->name('be.hapus_jenis_reward');
    
     Route::get('/list_jenis_sanksi', 'Backend\Jenis_SanksiController@jenis_sanksi')->name('be.jenis_sanksi');
    Route::get('/tambah_jenis_sanksi', 'Backend\Jenis_SanksiController@tambah_jenis_sanksi')->name('be.tambah_jenis_sanksi');
    Route::post('/simpan_jenis_sanksi', 'Backend\Jenis_SanksiController@simpan_jenis_sanksi')->name('be.simpan_jenis_sanksi');
    Route::get('/edit_jenis_sanksi/{id}', 'Backend\Jenis_SanksiController@edit_jenis_sanksi')->name('be.edit_jenis_sanksi');
    Route::post('/update_jenis_sanksi/{id}', 'Backend\Jenis_SanksiController@update_jenis_sanksi')->name('be.update_jenis_sanksi');
    Route::get('/hapus_jenis_sanksi/{id}', 'Backend\Jenis_SanksiController@hapus_jenis_sanksi')->name('be.hapus_jenis_sanksi');
    
	Route::get('/list_bank_entitas', 'Backend\BankEntitasController@bank')->name('be.bank_entitas');
	Route::get('/tambah_bank_entitas', 'Backend\BankEntitasController@tambah_bank')->name('be.tambah_bank_entitas');
	Route::post('/simpan_bank_entitas', 'Backend\BankEntitasController@simpan_bank')->name('be.simpan_bank_entitas');
	Route::get('/edit_bank_entitas/{id}', 'Backend\BankEntitasController@edit_bank')->name('be.edit_bank_entitas');
	Route::post('/update_bank_entitas/{id}', 'Backend\BankEntitasController@update_bank')->name('be.update_bank_entitas');
	Route::get('/hapus_bank_entitas/{id}', 'Backend\BankEntitasController@hapus_bank')->name('be.hapus_bank_entitas');
	
	
	Route::get('/karyawan_entitas', 'Backend\LokasiController@karyawan_entitas')->name('be.karyawan_entitas');
    
    Route::get('/list_role', 'Backend\RoleController@role')->name('be.role');
    Route::get('/tambah_role', 'Backend\RoleController@tambah_role')->name('be.tambah_role');
    Route::post('/simpan_role', 'Backend\RoleController@simpan_role')->name('be.simpan_role');
    Route::get('/edit_role/{id}', 'Backend\RoleController@edit_role')->name('be.edit_role');
    Route::post('/update_role/{id}', 'Backend\RoleController@update_role')->name('be.update_role');
    Route::get('/hapus_role/{id}', 'Backend\RoleController@hapus_role')->name('be.hapus_role');
    /*kontrak*/
    Route::get('/histori_kontrak_kerja', 'Backend\KontrakController@histori_kontrak_kerja')->name('be.histori_kontrak_kerja');
    Route::get('/kontrak', 'Backend\KontrakController@kontrak')->name('be.kontrak');
    Route::get('/tambah_kontrak', 'Backend\KontrakController@tambah_kontrak')->name('be.tambah_kontrak');
    Route::post('/simpan_kontrak', 'Backend\KontrakController@simpan_kontrak')->name('be.simpan_kontrak');
    Route::get('/view_kontrak/{id}', 'Backend\KontrakController@view_kontrak')->name('be.view_kontrak');
    Route::get('/edit_kontrak/{id}', 'Backend\KontrakController@edit_kontrak')->name('be.edit_kontrak');
    Route::post('/update_kontrak/{id}', 'Backend\KontrakController@update_kontrak')->name('be.update_kontrak');
    Route::get('/hapus_kontrak/{id}', 'Backend\KontrakController@hapus_kontrak')->name('be.hapus_kontrak');

    /*permit*/
    Route::get('/tarik_absen', 'Backend\PermitController@tarik_absen')->name('be.tarik_absen');
    Route::get('/historis_absen', 'Backend\PermitController@historis_absen')->name('be.historis_absen');
    Route::get('/list_ajuan/{type}', 'Backend\PermitController@list_ajuan')->name('be.list_ajuan');
    Route::get('/lihat/{id}', 'Backend\PermitController@lihat')->name('be.lihat');
    Route::post('/simpan_perubahan_permit/{id}', 'Backend\PermitController@simpan_perubahan_permit')->name('be.simpan_perubahan_permit');
    Route::get('/delete_pengajuan/{id}', 'Backend\PermitController@delete_pengajuan')->name('be.delete_pengajuan');
    Route::get('/pengajuan/{type}', 'Backend\PermitController@pengajuan')->name('be.pengajuan');
    Route::post('/simpan_pengajuan/{type}', 'Backend\PermitController@simpan_pengajuan')->name('be.simpan_pengajuan');
    
     Route::get('/perdin_appr/{type}', 'Backend\PerdinApprController@perdin_appr')->name('be.perdin_appr');
     Route::get('/print_perdin/{type}', 'Backend\PerdinApprController@print_perdin')->name('be.print_perdin');
    Route::get('/approve_admin_perdin', 'Backend\PerdinApprController@approve_admin_perdin')->name('be.approve_admin_perdin');
    Route::get('/decline_admin_perdin', 'Backend\PerdinApprController@decline_admin_perdin')->name('be.decline_admin_perdin');
    Route::get('/approve_keuangan_perdin', 'Backend\PerdinApprController@approve_keuangan_perdin')->name('be.approve_keuangan_perdin');
    Route::get('/decline_keuangan_perdin', 'Backend\PerdinApprController@decline_keuangan_perdin')->name('be.decline_keuangan_perdin');
    Route::get('/lihat_perdin_appr/{id}', 'Backend\PerdinApprController@lihat')->name('be.lihat_perdin_appr');
    Route::post('/simpan_perdin_appr/{type}', 'Backend\PerdinApprController@simpan_perdin_appr')->name('be.simpan_perdin_appr');
    
    
    Route::get('/hr_appr', 'Backend\HrApprController@hr_appr')->name('be.hr_appr');
    Route::get('/lihat_hr_appr/{id}', 'Backend\HrApprController@lihat')->name('be.lihat_hr_appr');
    Route::get('/daftarKaryawan/{id}', 'Backend\HrApprController@daftarKaryawan')->name('be.daftarKaryawan');
    Route::post('/simpan_hr_appr/{type}', 'Backend\HrApprController@simpan_hr_appr')->name('be.simpan_hr_appr');
    
    Route::get('/konfirmasi/{type}', 'Backend\KonfirmasiController@konfirmasi')->name('be.konfirmasi');
    Route::get('/lihat_konfirmasi/{type}', 'Backend\KonfirmasiController@lihat')->name('be.lihat_konfirmasi');
    
    Route::get('/migrasi_hr_appr/', 'Backend\HrApprController@migrasi')->name('be.migrasi_hr_appr');
    
    Route::get('/absensi/', 'HomeController@absensi')->name('be.absensi');
});

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
/*Route::get('/logout', function () {je
    //return view('auth.login');
    //$sqllokasi="select * from m_lokasi where active=1";
    //$lokasi=DB::connection()->select($sqllokasi);
    Session::flush();
    $sqlslider="select * from m_slider where active=1";
    $slider=DB::connection()->select($sqlslider);
    return view('auth.login', compact('slider'));
});*/
Route::get('/landingpage', 'Auth\LoginController@landingpage')->name('landingpage');

Route::get('/ga', 'GAController@ga')->name('ga');
Route::get('/ga_pembelian', 'GAController@ga_pembelian')->name('ga_pembelian');
Route::get('/list_ga', 'GAController@list_ga')->name('list_ga');
Route::get('/cari_list_ga', 'GAController@cari_list_ga')->name('cari_list_ga');
Route::post('/simpan_ga_pembelian', 'GAController@simpan_ga_pembelian')->name('simpan_ga_pembelian');

Route::get('/ga_pinjaman', 'GAController@ga_pinjaman')->name('ga_pinjaman');
Route::post('/simpan_ga_pinjaman', 'GAController@simpan_ga_pinjaman')->name('simpan_ga_pinjaman');

Route::get('/ga_perdin', 'GAController@ga_perdin')->name('ga_perdin');