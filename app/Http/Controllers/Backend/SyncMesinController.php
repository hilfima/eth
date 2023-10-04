<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;

class SyncMesinController extends Controller
{
    public function sync_mesin()
    {
        $tgl_awal=date('Y-m-d');
        $tgl_akhir=date('Y-m-d');
        $sqlsync="SELECT absen_log.*,m_lokasi.nama as nmlokasi,p_karyawan.nama as nmkaryawan,m_status_absen.nama as sts_absen
 FROM absen_log
LEFT JOIN m_mesin_absen on m_mesin_absen.mesin_id=absen_log.mesin_id
LEFT JOIN m_status_absen on m_status_absen.status_absen_id=absen_log.status_absen_id
LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_mesin_absen.m_lokasi_id
LEFT JOIN p_karyawan_absen on p_karyawan_absen.no_absen=absen_log.pin
LEFT JOIN p_karyawan on p_karyawan.p_karyawan_id=p_karyawan_absen.p_karyawan_id
WHERE to_char(absen_log.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(absen_log.date_time,'yyyy-mm-dd')<='".$tgl_akhir."'
ORDER BY p_karyawan.nama asc";
        $sync=DB::connection()->select($sqlsync);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        /*return view('backend.sync_mesin_absen.sync_mesin', [
            'simpanan' => DB::table('absen_log')
                ->leftJoin('m_mesin_absen', 'anggota.anggota_id', '=', 'simpanan.anggota_id')
                ->leftJoin('m_status_absen', 'jenis_simpanan.jenis_simpanan_id', '=', 'simpanan.jenis_simpanan_id')
                ->leftJoin('m_lokasi', 'lokasi.lokasi_id', '=', 'anggota.lokasi_id')
                ->leftJoin('p_karyawan_absen', 'departemen.departemen_id', '=', 'anggota.departemen_id')
                ->leftJoin('p_karyawan', 'departemen.departemen_id', '=', 'anggota.departemen_id')
                ->where('simpanan.active','1')
                ->orderBy('p_karyawan.nama','asc')
                ->paginate(10)
        ]);*/

        return view('backend.sync_mesin_absen.sync_mesin',compact('sync','user'));
    }


    public function sync_mesin_absen1(Request $request){
        try {
            $return = FALSE;
            //$mesin = Mesin::where('is_default', 1);
            /*echo '<pre>';
            var_dump($mesin);
                echo '</pre>';die;*/
            $sqlmesin="SELECT * FROM m_mesin_absen WHERE is_default=1 ORDER BY mesin_id";
            $mesin=DB::connection()->select($sqlmesin);
            /*echo '<pre>';
                var_dump($mesin);
            echo '</pre>';die;*/
            foreach($mesin as $mesin):
                $IP = $mesin->ip;
                $Key = $mesin->password;
                $port = $mesin->port;
                $nama = $mesin->nama;
                //if($IP != "") {
                $Connect = fsockopen($IP, $port, $errno, $errstr, 1);
                //echo $IP.'-'.$Key.'-'.$port.'-'.$nama;die;
                if($Connect) {
                    $soapRequest = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $Key . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
                    $newLine = "\r\n";
                    fputs($Connect, "POST /iWsService HTTP/1.0" . $newLine);
                    fputs($Connect, "Content-Type: text/xml" . $newLine);
                    fputs($Connect, "Content-Length: " . strlen($soapRequest) . $newLine . $newLine);
                    fputs($Connect, $soapRequest . $newLine);
                    $buffer = "";
                    while($Response = fgets($Connect, 1024)) {
                        $buffer = $buffer . $Response;
                    }
                    $buffer = parse_data($buffer, "<GetAttLogResponse>","</GetAttLogResponse>");
                    $buffer = explode("\r\n", $buffer);
                    for($i = 0; $i < count($buffer); $i++){
                        $data = parse_data($buffer[$i], "<Row>", "</Row>");
                        $PIN = parse_data($data, "<PIN>", "</PIN>");
                        $DateTime = parse_data($data, "<DateTime>", "</DateTime>");
                        $Verified = parse_data($data, "<Verified>", "</Verified>");
                        $Status = parse_data($data, "<Status>", "</Status>");
                        $cekDataAbsen = AbsenLog::where('pin', $PIN)->where('date_time', $DateTime)->count();
                        if ($cekDataAbsen > 0 && $PIN && $DateTime) {
                            $absen = new AbsenLog();
                            $absen->pin = $PIN;
                            $absen->mesin_id = $mesin->mesin_id;
                            $absen->date_time = $DateTime;
                            $absen->ver = $Verified;
                            //$absen->status_absen_id = UNPROCESSED;
                            $absen->status_absen_id = $Status;
                            $absen->save();
                        }
                    }
                    if($buffer) {
                        $return = TRUE;
                    } else {
                        $return = FALSE;
                    }
                }
                else{
                    $return = FALSE;
                }
                //}
            endforeach;
        } catch (\Exception $e) {
            //$return = FALSE;
            return redirect()->back()->with('error',$e);
        }
        //return $return;
        return redirect()->route('be.sync_mesin')->with('success','Absen Berhasil di Sync!');
    }

    public function syncData(Request $request) {
        $absenLog = new AbsenLog();
        $sync = $absenLog->syncData();
        $message = 'Data Gagal di tarik.';
        if($sync):
            $message = 'Data Berhasil di tarik.';
        endif;
        return response()->json([
            'message' => $message
        ]);
    }

    public function syncDataFromLogModal(Request $request) {
        $year = array();
        for($i = date('Y') - 1;$i <= date('Y'); $i++):
            $year[$i] = $i;
        endfor;
        return response()->json([
            'success' => true,
            'payload' => (String) view('backend.absen.modal-sync_absen', compact('year'))
        ]);
    }

    public function syncDataFromLog(Request $request) {
        $month = sprintf('%02s',$request->month);
        $countData = 0;
        $absenLog = new AbsenLog;
        $getSumAbsenLog = $absenLog->getSummaryDataLogAbsensi($month, $request->year);
        foreach($getSumAbsenLog as $row):
            $getDataPegawai = Pegawai::where('mesin_user_id', '=', $row->pin)->first();
            if(isset($getDataPegawai->pegawai_id)):
                $pegawai_id = $getDataPegawai->pegawai_id;
                $absen = new Absen;
                $absen->pegawai_id = $pegawai_id;
                $absen->check_in = $row->check_in;
                $absen->check_out = $row->check_out;
                $absen->work_hours = $row->work_hours;
                $absen->save();
                $countData++;
            endif;
        endforeach;
        $absenLogUpdateProcessed = new absenLog;
        $absenLogUpdateProcessed->updateProcessed($month, $request->year);
        if($countData > 0):
            $request->session()->flash('success', '<b>' . $countData . '</b> Data Berhasil Di Sinkron.');
        else:
            $request->session()->flash('error', 'Data sudah ter-update.');
        endif;
        return redirect()->route('absen.index');
    }

}
