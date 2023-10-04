<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class RekapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function rekap($page)
    {
    	
        $help = new Helper_function();
        if ($page == 'entitas') {
            $sql = "select 
			(SELECT count(*) from p_karyawan_pekerjaan c join p_karyawan d on d.p_karyawan_id = c.p_karyawan_id and d.active=1 
			WHERE m_lokasi.m_lokasi_id = c.m_lokasi_id) as jumlah 
			
			,m_lokasi.nama
			from m_lokasi 


			where m_lokasi.active = 1 and sub_entitas =0
		";
            $karyawan_entitas = DB::connection()->select($sql);

            foreach ($karyawan_entitas as $ke) {

                $data1[] = array("label" => $ke->nama, "value" => $ke->jumlah);
            }
            $graph['entitas'] = json_encode($data1);
        } else if ($page == 'masuk_resign') {

            $bulan_ini_awal = date('Y-m-') . '1';
            $bulan_ini_akhir = $help->tambah_bulan($bulan_ini_awal, 1);
            $sql = "select 
			(SELECT count(*) from p_karyawan_pekerjaan c 
			join p_karyawan d on d.p_karyawan_id = c.p_karyawan_id and d.active=1 
			LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=d.p_karyawan_id
			WHERE m_lokasi.m_lokasi_id = c.m_lokasi_id and h.tgl_awal>='$bulan_ini_awal' and h.tgl_awal <= '$bulan_ini_akhir' 
			) as jumlah_masuk,
			(SELECT count(*) from p_karyawan_pekerjaan c 
			join p_karyawan d on d.p_karyawan_id = c.p_karyawan_id and d.active=0 
			LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=d.p_karyawan_id
			WHERE m_lokasi.m_lokasi_id = c.m_lokasi_id and h.tgl_akhir>='$bulan_ini_awal' and h.tgl_akhir <= '$bulan_ini_akhir'
			) as jumlah_keluar 
			
			,m_lokasi.nama
			from m_lokasi 


			where m_lokasi.active = 1 and sub_entitas =0
		";

            $karyawan_entitas = DB::connection()->select($sql);

            foreach ($karyawan_entitas as $ke) {

                $datac1[] = array("y" => $ke->nama, "a" => $ke->jumlah_masuk, "b" => $ke->jumlah_keluar);
            }
            $graph['masuk_resign'] = json_encode($datac1);
            //print_r($graph);die;
        } else if ($page == 'pangkat') {

            $sql = "select (SELECT count(*) from p_karyawan_pekerjaan c join m_jabatan on m_jabatan.m_jabatan_id = c.m_jabatan_id join p_karyawan d on d.p_karyawan_id = c.p_karyawan_id and d.active=1 WHERE m_pangkat.m_pangkat_id= m_jabatan.m_pangkat_id) as jumlah,m_pangkat.nama
from m_pangkat 
where active= 1
order by m_pangkat_id desc
";
            $karyawan_pangkat = DB::connection()->select($sql);

            foreach ($karyawan_pangkat as $ke) {

                $data2[] = array("y" => $ke->nama, "a" => $ke->jumlah);
            }


            $graph['pangkat'] = json_encode($data2);
        } else if ($page == 'pernikahan') {
            //print_r($graph);die;
            $sql = "select (SELECT count(*) from p_recruitment c 
			join p_karyawan d on d.p_recruitment_id = c.p_recruitment_id and d.active=1 
		WHERE c.m_status_id= m_status.m_status_id
		) as jumlah,m_status.nama
		from m_status 
		where active=1
		";
            $karyawan_pernikahan = DB::connection()->select($sql);

            foreach ($karyawan_pernikahan as $ke) {

                $data3[] = array("y" => $ke->nama, "a" => $ke->jumlah);
            }

            $graph['pernikahan'] = json_encode($data3);
        } else if ($page == 'kelamin') {

            $sql = "select (SELECT count(*) from p_recruitment c 
	join p_karyawan d on d.p_recruitment_id = c.p_recruitment_id and d.active=1 
		WHERE c.m_jenis_kelamin_id= m_jenis_kelamin.m_jenis_kelamin_id
	) as jumlah,m_jenis_kelamin.nama
from m_jenis_kelamin
WHERE m_jenis_kelamin.active= 1

";
            $karyawan_kelamin = DB::connection()->select($sql);
            foreach ($karyawan_kelamin as $ke) {

                $data4[] = array("y" => $ke->nama, "a" => $ke->jumlah);
            }
            $graph['kelamin'] = json_encode($data4);
        } else if ($page == 'status_kerja') {
            $sql = "select (SELECT count(*) from p_karyawan a 
				JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id
					WHERE h.m_status_pekerjaan_id= m_status_pekerjaan.m_status_pekerjaan_id and a.active=1
				) as jumlah,m_status_pekerjaan.nama
			from m_status_pekerjaan
			WHERE m_status_pekerjaan.active= 1

			";
            $karyawan_status_kerja = DB::connection()->select($sql);
            foreach ($karyawan_status_kerja as $ke) {

                $data5[] = array("y" => $ke->nama, "a" => $ke->jumlah);
            }
            $graph['status_kerja'] = json_encode($data5);
        } else if ($page == 'bulanan') {
            $sql = "SELECT * FROM m_periode_absen WHERE 1=1  and tipe_periode='absen' and active = 1 and type = 1
			order by tgl_awal,tgl_akhir
			";

            $periode_absen = DB::connection()->select($sql);
            $data_pekanan = array();
            $data_bulanan = array();
            foreach ($periode_absen as $perabsen) {
                $cuti = isset($rekap[$perabsen->periode_absen_id]['total']['cuti']) ? $rekap[$perabsen->periode_absen_id]['total']['cuti'] : 0;
                $ipg = isset($rekap[$perabsen->periode_absen_id]['total']['ipg']) ? $rekap[$perabsen->periode_absen_id]['total']['ipg'] : 0;
                $ihk = isset($rekap[$perabsen->periode_absen_id]['total']['ihk']) ? $rekap[$perabsen->periode_absen_id]['total']['ihk'] : 0;
                $ipd = isset($rekap[$perabsen->periode_absen_id]['total']['ipd']) ? $rekap[$perabsen->periode_absen_id]['total']['ipd'] : 0;
                $terlambat = isset($rekap[$perabsen->periode_absen_id]['total']['terlambat']) ? $rekap[$perabsen->periode_absen_id]['total']['terlambat'] : 0;
                $absen_masuk = isset($rekap[$perabsen->periode_absen_id]['total']['absen_masuk']) ? $rekap[$perabsen->periode_absen_id]['total']['absen_masuk'] : 0;
                $lembur2 = isset($rekap[$perabsen->periode_absen_id]['total']['lembur']) ? $rekap[$perabsen->periode_absen_id]['total']['lembur'] : 0;

                if ($perabsen->type == 0) {
                    $bulan = '';
                    if ($help->bulan(date('m', strtotime($perabsen->tgl_awal))) != $help->bulan(date('m', strtotime($perabsen->tgl_akhir))))
                        $bulan = $help->bulan(date('m', strtotime($perabsen->tgl_awal)));
                    $data_pekanan[] = array(
                        "y" =>
                        date('d', strtotime($perabsen->tgl_awal)) . ' ' . $bulan . ' s/d ' .
                            date('d', strtotime($perabsen->tgl_akhir)) . ' ' . $help->bulan(date('m', strtotime($perabsen->tgl_akhir))) . ' ' .
                            $perabsen->tahun,
                        "absen" => $absen_masuk,
                        "terlambat" => $terlambat,
                        "perdin" => $ipd,
                        "Izin" => $ihk + $ipg,
                        "lembur" => $lembur2,
                    );
                } else {
                    $data_bulanan[] = array(
                        "y" => $help->bulan($perabsen->periode) . ' ' . $perabsen->tahun,
                        "absen" => $absen_masuk,
                        "terlambat" => $terlambat,
                        "perdin" => $ipd,
                        "ihk" => $ihk,
                        "ipg" => $ipg,
                        "cuti" => $cuti, "lembur" => $lembur2,
                    );
                }
            }
            //ykeys: ['a', 'b'],
            //labels: ['Total Sales', 'Total Revenue'],

            $datalabel = ['Absen', 'Telambat', 'Perjalanan Dinas', 'IHK', 'IPG', 'Cuti', 'Lembur'];
            $ykeys = ['absen', 'terlambat', 'perdin', 'ihk', 'ipg', 'cuti', 'lembur'];

            $graph['bulanan']['data'] = json_encode($data_bulanan);
            $graph['bulanan']['labelabsen'] = json_encode($datalabel);
            $graph['bulanan']['ykeys'] = json_encode($ykeys);
        } else if ($page == 'pekanan') {
            $sql = "SELECT * FROM m_periode_absen WHERE 1=1  and tipe_periode='absen' and active = 1 and type = 0
			order by tgl_awal,tgl_akhir
			";

            $periode_absen = DB::connection()->select($sql);
            $data_pekanan = array();
            $data_bulanan = array();
            foreach ($periode_absen as $perabsen) {
                $cuti = isset($rekap[$perabsen->periode_absen_id]['total']['cuti']) ? $rekap[$perabsen->periode_absen_id]['total']['cuti'] : 0;
                $ipg = isset($rekap[$perabsen->periode_absen_id]['total']['ipg']) ? $rekap[$perabsen->periode_absen_id]['total']['ipg'] : 0;
                $ihk = isset($rekap[$perabsen->periode_absen_id]['total']['ihk']) ? $rekap[$perabsen->periode_absen_id]['total']['ihk'] : 0;
                $ipd = isset($rekap[$perabsen->periode_absen_id]['total']['ipd']) ? $rekap[$perabsen->periode_absen_id]['total']['ipd'] : 0;
                $terlambat = isset($rekap[$perabsen->periode_absen_id]['total']['terlambat']) ? $rekap[$perabsen->periode_absen_id]['total']['terlambat'] : 0;
                $absen_masuk = isset($rekap[$perabsen->periode_absen_id]['total']['absen_masuk']) ? $rekap[$perabsen->periode_absen_id]['total']['absen_masuk'] : 0;
                $lembur2 = isset($rekap[$perabsen->periode_absen_id]['total']['lembur']) ? $rekap[$perabsen->periode_absen_id]['total']['lembur'] : 0;

                if ($perabsen->type == 0) {
                    $bulan = '';
                    if ($help->bulan(date('m', strtotime($perabsen->tgl_awal))) != $help->bulan(date('m', strtotime($perabsen->tgl_akhir))))
                        $bulan = $help->bulan(date('m', strtotime($perabsen->tgl_awal)));
                    $data_pekanan[] = array(
                        "y" =>
                        date('d', strtotime($perabsen->tgl_awal)) . ' ' . $bulan . ' s/d ' .
                            date('d', strtotime($perabsen->tgl_akhir)) . ' ' . $help->bulan(date('m', strtotime($perabsen->tgl_akhir))) . ' ' .
                            $perabsen->tahun,
                        "absen" => $absen_masuk,
                        "terlambat" => $terlambat,
                        "perdin" => $ipd,
                        "Izin" => $ihk + $ipg,
                        "lembur" => $lembur2,
                    );
                } else {
                    $data_bulanan[] = array(
                        "y" => $help->bulan($perabsen->periode) . ' ' . $perabsen->tahun,
                        "absen" => $absen_masuk,
                        "terlambat" => $terlambat,
                        "perdin" => $ipd,
                        "ihk" => $ihk,
                        "ipg" => $ipg,
                        "cuti" => $cuti, "lembur" => $lembur2,
                    );
                }
            }
            $datalabel = ['Absen', 'Telambat', 'Perjalanan Dinas', 'Izin', 'Lembur'];
            $ykeys = ['absen', 'terlambat', 'perdin', 'Izin', 'lembur'];
            $graph['pekanan']['data'] = json_encode($data_pekanan);
            $graph['pekanan']['labelabsen'] = json_encode($datalabel);
            $graph['pekanan']['ykeys'] = json_encode($ykeys);
        }
        return view('backend.rekap.rekap', compact('graph','page','help'));
    }
}
