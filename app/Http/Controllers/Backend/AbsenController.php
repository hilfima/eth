<?php

namespace App\Http\Controllers\Backend;

use App\absen_excel;
use App\absen_xls;
use App\cek_absen_xls;
use App\Http\Controllers\Controller;
use App\rekap_absen2_xls;
use App\rekap_absen_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use DateTime;;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class AbsenController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function input_absen_hr()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,p_karyawan.p_karyawan_id,users.role FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $sqlmesin="SELECT * FROM m_mesin_absen";
        $mesin=DB::connection()->select($sqlmesin);
		$date = date('Y-m-d');
       
        return view('backend.absen.input_absen_hr', compact('user','mesin'));
    }

    public function simpan_input_absen_hr(Request $request){
		
		try{
        	

           $help = new Helper_function();
			$id_karyawan = $request->get('nama');
			$date = $request->get('tgl_absen_awal');
			for($j=0;$j<$help->hitungHari($request->get('tgl_absen_awal'),$request->get('tgl_absen_akhir'));$j++){
        	
            $date_time_awal=date('Y-m-d',strtotime($date)).' '.$request->get('jam_masuk').'';
        	for($i=0;$i<count($id_karyawan);$i++){
        		$kar = $id_karyawan[$i];
				$sqlkaryawan = "SELECT c.no_absen,d.mesin_id
					FROM p_karyawan_pekerjaan b
					
					LEFT JOIN p_karyawan_absen c on c.p_karyawan_id=b.p_karyawan_id
					LEFT JOIN m_mesin_absen d on d.m_lokasi_id=b.m_lokasi_id

					WHERE b.p_karyawan_id='$kar'
				"	;
           		$karyawan=DB::connection()->select($sqlkaryawan);
           		//print_r($karyawan);
           		 $sql="SELECT * FROM absen_log "
            	
            	."where pin=".$karyawan[0]->no_absen
            	."and ver=". $request->get('ver')."
            	and date_time >= '".date('Y-m-d',strtotime($date))."'
            	and date_time <= '".date('Y-m-d',strtotime($date))." 23:59'
            	"
            	;
            	$data=DB::connection()->select($sql);
            	//print_r($data);
            	if(count($data)){
            		$iduser=Auth::user()->id;
        	
            		 DB::connection()->table("absen_log")
		                ->where("absen_log_id",$data[0]->absen_log_id)
		                ->update([
		                    "mesin_id" => $request->get('mesin'),
		                    "pin" => $karyawan[0]->no_absen,
		                    "date_time" => $date_time_awal,
		                    "ver" => $request->get('ver'),
		                    "status_absen_id" => $request->get('ver'),
		                    "updated_at" => date('Y-m-d H:i:s'),
		                    "updated_by"=>$iduser,
		                    "time_before_update" => $data[0]->date_time,
		                ]);
				}else{
					
		            DB::connection()->table("absen_log")
		                ->insert([
		                    "mesin_id" => $request->get('mesin'),
		                    "pin" => $karyawan[0]->no_absen,
		                    "date_time" => $date_time_awal,
		                    "ver" => $request->get('ver'),
		                    "status_absen_id" => $request->get('ver'),
		                    "created_at" => date('Y-m-d H:i:s'),
		                ]);
				}
				
			}
			$date = $help->tambah_tanggal($date,1);
			}
            return redirect()->route('be.input_absen_hr')->with('success',' Absen Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
         
            return redirect()->back()->with('error',$e);
        }
	}
    public function absen()
    {
        $tgl_awal=date('Y-m-d');
        $tgl_akhir=date('Y-m-d');
        $tahun=date('Y');
        $bulan=date('mm');
        $sqlabsen="SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_masuk,
jk.jam_keluar,case when to_char(date_time,'HH24:MI')>'07:30' then 'Terlambat' else 'OK' end as keterangan,f.nama as nmlokasi
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
ORDER BY nama,to_char(a.date_time,'dd-mm-yyyy'),nmlokasi";
        $absen=DB::connection()->select($sqlabsen);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.absen.absen',compact('absen','tgl_awal','tgl_akhir','bulan','tahun','user'));
    }

    public function cari_absen(Request $request)
    {
        //$action=$request->get('Cari');
        //echo $action;die;
        if($request->get('Cari')=='Cari'){
            $periode_absen=$request->get('periode_gajian');

            DB::connection()->table("absen_temp")
                ->delete();

            $sqldataperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
            $dataperiode=DB::connection()->select($sqldataperiode);

            $periode_gajian=$dataperiode[0]->type;
            $tgl_awal=date('Y-m-d',strtotime($dataperiode[0]->tgl_awal));
            $tgl_akhir=date('Y-m-d',strtotime($dataperiode[0]->tgl_akhir));
            $tanggal_awal=date('d',strtotime($dataperiode[0]->tgl_awal));
            $tanggal_akhir=date('d',strtotime($dataperiode[0]->tgl_akhir));
            //echo $tgl_akhir-$tgl_awal;die;
            //$d = $tgl_akhir->diff($tgl_awal)->days + 1;
            $datetime1 = new DateTime($tgl_awal);
            $datetime2 = new DateTime($tgl_akhir);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');//now do whatever you like with $days
            //echo $days;die;
            //$awal=$tanggal_awal;
            /*for($i=$tanggal_awal;$i<$days;$i++){
                $days=$days++;
                echo $days;
            }die;*/

            $days=$tanggal_awal;
            if($days>31){
                $days1=1;
            }
            else{
                $days1=$days;
            }

            $days2=$days1+1;
            if($days2>31){
                $days2=1;
            }
            else{
                $days2=$days2;
            }

            $days3=$days2+1;
            if($days3>31){
                $days3=1;
            }
            else{
                $days3=$days3;
            }

            $days4=$days3+1;
            if($days4>31){
                $days4=1;
            }
            else{
                $days4=$days4;
            }

            $days5=$days4+1;
            if($days5>31){
                $days5=1;
            }
            else{
                $days5=$days5;
            }

            $days6=$days5+1;
            if($days6>31){
                $days6=1;
            }
            else{
                $days6=$days6;
            }

            $days7=$days6+1;
            if($days7>31){
                $days7=1;
            }
            else{
                $days7=$days7;
            }

            $days8=$days7+1;
            if($days8>31){
                $days8=1;
            }
            else{
                $days8=$days8;
            }

            $days9=$days8+1;
            if($days9>31){
                $days9=1;
            }
            else{
                $days9=$days9;
            }

            $days10=$days9+1;
            if($days10>31){
                $days10=1;
            }
            else{
                $days10=$days10;
            }

            $days11=$days10+1;
            if($days11>31){
                $days11=1;
            }
            else{
                $days11=$days11;
            }

            $days12=$days11+1;
            if($days12>31){
                $days12=1;
            }
            else{
                $days12=$days12;
            }

            $days13=$days12+1;
            if($days12>31){
                $days12=1;
            }
            else{
                $days12=$days12;
            }

            $days14=$days13+1;
            if($days14>31){
                $days14=1;
            }
            else{
                $days14=$days14;
            }

            $days15=$days14+1;
            if($days15>31){
                $days15=1;
            }
            else{
                $days15=$days15;
            }

            $days16=$days15+1;
            if($days16>31){
                $days16=1;
            }
            else{
                $days16=$days16;
            }

            $days17=$days16+1;
            if($days17>31){
                $days17=1;
            }
            else{
                $days17=$days17;
            }

            $days18=$days17+1;
            if($days18>31){
                $days18=1;
            }
            else{
                $days18=$days18;
            }

            $days19=$days18+1;
            if($days19>31){
                $days19=1;
            }
            else{
                $days19=$days19;
            }

            $days20=$days19+1;
            if($days20>31){
                $days20=1;
            }
            else{
                $days20=$days20;
            }

            $days21=$days20+1;
            if($days21>31){
                $days21=1;
            }
            else{
                $days21=$days21;
            }

            $days22=$days21+1;
            if($days22>31){
                $days22=1;
            }
            else{
                $days22=$days22;
            }

            $days23=$days22+1;
            if($days23>31){
                $days23=1;
            }
            else{
                $days23=$days23;
            }

            $days24=$days23+1;
            if($days24>31){
                $days24=1;
            }
            else{
                $days24=$days24;
            }

            $days25=$days24+1;
            if($days25>31){
                $days25=1;
            }
            else{
                $days25=$days25;
            }

            $days26=$days25+1;
            if($days26>31){
                $days25=1;
            }
            else{
                $days26=$days26;
            }

            $days27=$days26+1;
            if($days27>31){
                $days27=1;
            }
            else{
                $days27=$days27;
            }

            $days28=$days27+1;
            if($days28>31){
                $days28=1;
            }
            else{
                $days28=$days28;
            }

            $days29=$days28+1;
            if($days29>31){
                $days29=1;
            }
            else{
                $days29=$days29;
            }

            $days30=$days29+1;
            if($days30>31){
                $days30=1;
            }
            else{
                $days30=$days30;
            }

            $days31=$days30+1;
            if($days31>31){
                $days31=1;
            }
            else{
                $days31=$days31;
            }
            $sqllokasi="SELECT * FROM m_lokasi WHERE 1=1 and active=1";
            $lokasi=DB::connection()->select($sqllokasi);
            $jam_masuk=$lokasi[0]->jam_masuk;
            $jam_masuk2=date('h:i',strtotime($jam_masuk));
            //echo $jam_masuk2;die;

            $sqlabsen="with hole1 as(
with hole as(
SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'yyyy-mm-dd')::date as tgl_absen,
to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jam_keluar as jam_keluar,case when to_char(date_time,'HH24:MI')>='".$jam_masuk2."' then 1 else 0 end as telat
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id and c.active=1
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1 
and d.m_lokasi_id NOT IN(5)
GROUP BY a.pin,c.nik,c.nama,a.date_time,jk.jam_keluar
ORDER BY nama,to_char(a.date_time,'yyyy-mm-dd')::date)
SELECT distinct pin,tgl_absen,coalesce(jam_masuk,'00:00:00')||'-'||coalesce(jam_keluar,'00:00:00') as jam_absen,telat
FROM hole)
select pin,nik,nama,case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as periode_gaji,periode_gajian,
count(pin) as jml_masuk, sum(telat) as terlambat, 
max(case when date_part('day', tgl_absen)=".$days1." then  jam_absen else '00:00:00' end) tgl1,
max(case when date_part('day', tgl_absen)=".$days2." then  jam_absen else '00:00:00' end) tgl2,
max(case when date_part('day', tgl_absen)=".$days3." then  jam_absen else '00:00:00' end) tgl3,
max(case when date_part('day', tgl_absen)=".$days4." then  jam_absen else '00:00:00' end) tgl4,
max(case when date_part('day', tgl_absen)=".$days5." then  jam_absen else '00:00:00' end) tgl5,
max(case when date_part('day', tgl_absen)=".$days6." then  jam_absen else '00:00:00' end) tgl6,
max(case when date_part('day', tgl_absen)=".$days7." then  jam_absen else '00:00:00' end) tgl7,
max(case when date_part('day', tgl_absen)=".$days8." then  jam_absen else '00:00:00' end) tgl8,
max(case when date_part('day', tgl_absen)=".$days9." then  jam_absen else '00:00:00' end) tgl9,
max(case when date_part('day', tgl_absen)=".$days10." then  jam_absen else '00:00:00' end) tgl10,
max(case when date_part('day', tgl_absen)=".$days11." then  jam_absen else '00:00:00' end) tgl11,
max(case when date_part('day', tgl_absen)=".$days12." then  jam_absen else '00:00:00' end) tgl12,
max(case when date_part('day', tgl_absen)=".$days13." then  jam_absen else '00:00:00' end) tgl13,
max(case when date_part('day', tgl_absen)=".$days14." then  jam_absen else '00:00:00' end) tgl14,
max(case when date_part('day', tgl_absen)=".$days15." then  jam_absen else '00:00:00' end) tgl15,
max(case when date_part('day', tgl_absen)=".$days16." then  jam_absen else '00:00:00' end) tgl16,
max(case when date_part('day', tgl_absen)=".$days17." then  jam_absen else '00:00:00' end) tgl17,
max(case when date_part('day', tgl_absen)=".$days18." then  jam_absen else '00:00:00' end) tgl18,
max(case when date_part('day', tgl_absen)=".$days19." then  jam_absen else '00:00:00' end) tgl19,
max(case when date_part('day', tgl_absen)=".$days20." then  jam_absen else '00:00:00' end) tgl20,
max(case when date_part('day', tgl_absen)=".$days21." then  jam_absen else '00:00:00' end) tgl21,
max(case when date_part('day', tgl_absen)=".$days22." then  jam_absen else '00:00:00' end) tgl22,
max(case when date_part('day', tgl_absen)=".$days23." then  jam_absen else '00:00:00' end) tgl23,
max(case when date_part('day', tgl_absen)=".$days24." then  jam_absen else '00:00:00' end) tgl24,
max(case when date_part('day', tgl_absen)=".$days25." then  jam_absen else '00:00:00' end) tgl25,
max(case when date_part('day', tgl_absen)=".$days26." then  jam_absen else '00:00:00' end) tgl26,
max(case when date_part('day', tgl_absen)=".$days27." then  jam_absen else '00:00:00' end) tgl27,
max(case when date_part('day', tgl_absen)=".$days28." then  jam_absen else '00:00:00' end) tgl28,
max(case when date_part('day', tgl_absen)=".$days29." then  jam_absen else '00:00:00' end) tgl29,
max(case when date_part('day', tgl_absen)=".$days30." then  jam_absen else '00:00:00' end) tgl30,
max(case when date_part('day', tgl_absen)=".$days31." then  jam_absen else '00:00:00' end) tgl31
from hole1
left join p_karyawan_absen b on b.no_absen=hole1.pin
left join p_karyawan c on c.p_karyawan_id=b.p_karyawan_id and c.active=1
left join p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
where 1=1 and periode_gajian=$periode_gajian
and c.p_karyawan_id NOT IN(148,188,219,199,194)
and d.m_departemen_id NOT IN(17) and d.m_lokasi_id NOT IN(5)
GROUP BY pin,nik,nama,periode_gajian
ORDER BY c.nama";
           // echo $sqlabsen;die;
            $absen=DB::connection()->select($sqlabsen);

            $sqlharilibur="SELECT sum(jumlah) as jumlah FROM m_hari_libur WHERE tanggal>='".$tgl_awal."' and tanggal<='".$tgl_akhir."'";
            $harilibur=DB::connection()->select($sqlharilibur);
            $jml_harilibur=$harilibur[0]->jumlah;

            $awal_cuti = date('d-m-Y',strtotime($tgl_awal));//'30-04-2021';
            $akhir_cuti = date('d-m-Y',strtotime($tgl_akhir));//'12-05-2021';

            // tanggalnya diubah formatnya ke Y-m-d
            $awal_cuti = date_create_from_format('d-m-Y', $awal_cuti);
            $awal_cuti = date_format($awal_cuti, 'Y-m-d');
            $awal_cuti = strtotime($awal_cuti);

            $akhir_cuti = date_create_from_format('d-m-Y', $akhir_cuti);
            $akhir_cuti = date_format($akhir_cuti, 'Y-m-d');
            $akhir_cuti = strtotime($akhir_cuti);

            $haricuti = array();
            $sabtuminggu = array();

            for ($i=$awal_cuti; $i <= $akhir_cuti; $i += (60 * 60 * 24)) {
                if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                    $haricuti[] = $i;
                } else {
                    $sabtuminggu[] = $i;
                }

            }
            $jumlah_cuti = count($haricuti)-$jml_harilibur+1;
            $jumlah_sabtuminggu = count($sabtuminggu);
            $abtotal = $jumlah_cuti + $jumlah_sabtuminggu+$jml_harilibur;
            /*echo "<pre>";
            echo "<h1>Sistem Cuti Online</h1>";
            echo "<hr>";
            echo "Mulai Masuk : " . date('d-m-Y', $awal_cuti) . "<br>";
            echo "Terakhir Masuk : " . date('d-m-Y', $akhir_cuti) . "<br>";
            echo "Jumlah Hari Masuk : " . $jumlah_cuti ."<br>";
            echo "Jumlah Hari Libur Nasional : " . $jml_harilibur ."<br>";
            echo "Jumlah Sabtu/Minggu : " . $jumlah_sabtuminggu ."<br>";
            echo "Total Hari : " . $abtotal ."<br>";
            echo "<h1>Hari Kerja</h1>";
        echo "<hr>";
            foreach ($haricuti as $value) {
                echo date('d-m-Y', $value)  . " -> " . strftime("%A, %d %B %Y", date($value)) . "\n" . "<br>";
            }
            die;*/

            foreach ($absen as $absen){
                $sqlcuti="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(5,6,7,8,9,10) ";
                $datacuti=DB::connection()->select($sqlcuti);
                $cuti=0;
                if(!empty($datacuti)){
                    $cuti=$datacuti[0]->lama;
                }

                $sqlizin="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(4,12,13,14,15,16,17,20,21,23,24) ";
                $dataizin=DB::connection()->select($sqlizin);
                $izin=0;
                if(!empty($dataizin)){
                    $izin=$dataizin[0]->lama;
                }

                $sqlipg="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(3) ";
                $dataipg=DB::connection()->select($sqlipg);
                $ipg=0;
                if(!empty($dataipg)){
                    $ipg=$dataipg[0]->lama;
                }

                DB::connection()->table("absen_temp")
                    ->insert([
                        "nik" => $absen->nik,
                        "nama" => $absen->nama,
                        "periode" => $absen->periode_gajian,
                        "masuk" => $absen->jml_masuk,
                        "terlambat"=>$absen->terlambat,
                        "cuti"=>$cuti,
                        "izin"=>$izin,
                        "ipg"=>$ipg,
                        "a"=>$absen->tgl1,
                        "b"=>$absen->tgl2,
                        "c"=>$absen->tgl3,
                        "d"=>$absen->tgl4,
                        "e"=>$absen->tgl5,
                        "f"=>$absen->tgl6,
                        "g" => $absen->tgl7,
                        "h" => $absen->tgl8,
                        "i" => $absen->tgl9,
                        "j" => $absen->tgl10,
                        "k" => $absen->tgl11,
                        "l" => $absen->tgl12,
                        "m" => $absen->tgl13,
                        "n" => $absen->tgl14,
                        "o" => $absen->tgl15,
                        "p" => $absen->tgl16,
                        "q" => $absen->tgl17,
                        "r" => $absen->tgl18,
                        "s" => $absen->tgl19,
                        "t" => $absen->tgl20,
                        "u" => $absen->tgl21,
                        "v" => $absen->tgl22,
                        "w" => $absen->tgl23,
                        "x" => $absen->tgl24,
                        "y" => $absen->tgl25,
                        "z" => $absen->tgl26,
                        "aa" => $absen->tgl27,
                        "ab" => $absen->tgl28,
                        "ac" => $absen->tgl29,
                        "ad" => $absen->tgl30,
                        "ae" => $absen->tgl31,
                        "created_at" => date("Y-m-d"),
                        "jml_hari_kerja"=>$jumlah_cuti,
                        "jml_hari_libur"=>$jml_harilibur,
                        "total_hari"=>$abtotal,
                    ]);
            }

            $iduser=Auth::user()->id;
            $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
            $user=DB::connection()->select($sqluser);

            $tahun=date('Y');
            $bulan=date('mm');
            $periode_absen=$request->get('periode');

            $sqlperiode="SELECT m_periode_absen.*,
case when periode=1 then 'Januari'
when periode=2 then 'Februari'
when periode=3 then 'Maret'
when periode=4 then 'April'
when periode=5 then 'Mei'
when periode=6 then 'Juni'
when periode=7 then 'Juli'
when periode=8 then 'Agustus'
when periode=9 then 'September'
when periode=10 then 'Oktober'
when periode=11 then 'November'
when periode=12 then 'Desember' end as bulan,
case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
FROM m_periode_absen WHERE tahun='".$tahun."'
ORDER BY tahun,periode";
            $periode=DB::connection()->select($sqlperiode);

            $jml=date('d',strtotime($tgl_akhir))-date('d',strtotime($tgl_awal))+1;
            if($jml==0){
                $jml=1;
            };
            //echo $jml;die;
            $sqlrekapabsen="select absen_temp.*, case when periode=0 then 'Pekanan' else 'Bulanan' end as periode_gaji,departemen 
                            from absen_temp 
                            left join get_data_karyawan() gdk on gdk.nik=absen_temp.nik
                            order by nama";
            $rekapabsen=DB::connection()->select($sqlrekapabsen);

            return view('backend.absen.rekap_absen',compact('absen','tgl_awal','tgl_akhir','user','days1','days2','days3','days4','days5','days6','days7','days8','days9','days10','days11','days12','days13','days14','days15','days16','days17','days18','days19','days20','days21','days22','days23','days24','days25','days26','days27','days28','days29','days30','days31','periode','rekapabsen','periode_absen','dataperiode'));
        }
        else if($request->get('Cari')=='RekapExcel'){
            $periode_absen=$request->get('periode_gajian');

            DB::connection()->table("absen_temp")
                ->delete();

            $sqldataperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
            $dataperiode=DB::connection()->select($sqldataperiode);

            $periode_gajian=$dataperiode[0]->type;
            $tgl_awal=date('Y-m-d',strtotime($dataperiode[0]->tgl_awal));
            $tgl_akhir=date('Y-m-d',strtotime($dataperiode[0]->tgl_akhir));
            $tanggal_awal=date('d',strtotime($dataperiode[0]->tgl_awal));
            $tanggal_akhir=date('d',strtotime($dataperiode[0]->tgl_akhir));
            //echo $tgl_akhir-$tgl_awal;die;
            //$d = $tgl_akhir->diff($tgl_awal)->days + 1;
            $datetime1 = new DateTime($tgl_awal);
            $datetime2 = new DateTime($tgl_akhir);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');//now do whatever you like with $days
            //echo $days;die;
            //$awal=$tanggal_awal;
            /*for($i=$tanggal_awal;$i<$days;$i++){
                $days=$days++;
                echo $days;
            }die;*/

            $days=$tanggal_awal;
            if($days>31){
                $days1=1;
            }
            else{
                $days1=$days;
            }

            $days2=$days1+1;
            if($days2>31){
                $days2=1;
            }
            else{
                $days2=$days2;
            }

            $days3=$days2+1;
            if($days3>31){
                $days3=1;
            }
            else{
                $days3=$days3;
            }

            $days4=$days3+1;
            if($days4>31){
                $days4=1;
            }
            else{
                $days4=$days4;
            }

            $days5=$days4+1;
            if($days5>31){
                $days5=1;
            }
            else{
                $days5=$days5;
            }

            $days6=$days5+1;
            if($days6>31){
                $days6=1;
            }
            else{
                $days6=$days6;
            }

            $days7=$days6+1;
            if($days7>31){
                $days7=1;
            }
            else{
                $days7=$days7;
            }

            $days8=$days7+1;
            if($days8>31){
                $days8=1;
            }
            else{
                $days8=$days8;
            }

            $days9=$days8+1;
            if($days9>31){
                $days9=1;
            }
            else{
                $days9=$days9;
            }

            $days10=$days9+1;
            if($days10>31){
                $days10=1;
            }
            else{
                $days10=$days10;
            }

            $days11=$days10+1;
            if($days11>31){
                $days11=1;
            }
            else{
                $days11=$days11;
            }

            $days12=$days11+1;
            if($days12>31){
                $days12=1;
            }
            else{
                $days12=$days12;
            }

            $days13=$days12+1;
            if($days12>31){
                $days12=1;
            }
            else{
                $days12=$days12;
            }

            $days14=$days13+1;
            if($days14>31){
                $days14=1;
            }
            else{
                $days14=$days14;
            }

            $days15=$days14+1;
            if($days15>31){
                $days15=1;
            }
            else{
                $days15=$days15;
            }

            $days16=$days15+1;
            if($days16>31){
                $days16=1;
            }
            else{
                $days16=$days16;
            }

            $days17=$days16+1;
            if($days17>31){
                $days17=1;
            }
            else{
                $days17=$days17;
            }

            $days18=$days17+1;
            if($days18>31){
                $days18=1;
            }
            else{
                $days18=$days18;
            }

            $days19=$days18+1;
            if($days19>31){
                $days19=1;
            }
            else{
                $days19=$days19;
            }

            $days20=$days19+1;
            if($days20>31){
                $days20=1;
            }
            else{
                $days20=$days20;
            }

            $days21=$days20+1;
            if($days21>31){
                $days21=1;
            }
            else{
                $days21=$days21;
            }

            $days22=$days21+1;
            if($days22>31){
                $days22=1;
            }
            else{
                $days22=$days22;
            }

            $days23=$days22+1;
            if($days23>31){
                $days23=1;
            }
            else{
                $days23=$days23;
            }

            $days24=$days23+1;
            if($days24>31){
                $days24=1;
            }
            else{
                $days24=$days24;
            }

            $days25=$days24+1;
            if($days25>31){
                $days25=1;
            }
            else{
                $days25=$days25;
            }

            $days26=$days25+1;
            if($days26>31){
                $days25=1;
            }
            else{
                $days26=$days26;
            }

            $days27=$days26+1;
            if($days27>31){
                $days27=1;
            }
            else{
                $days27=$days27;
            }

            $days28=$days27+1;
            if($days28>31){
                $days28=1;
            }
            else{
                $days28=$days28;
            }

            $days29=$days28+1;
            if($days29>31){
                $days29=1;
            }
            else{
                $days29=$days29;
            }

            $days30=$days29+1;
            if($days30>31){
                $days30=1;
            }
            else{
                $days30=$days30;
            }

            $days31=$days30+1;
            if($days31>31){
                $days31=1;
            }
            else{
                $days31=$days31;
            }

            $sqllokasi="SELECT * FROM m_lokasi WHERE 1=1 and active=1";
            $lokasi=DB::connection()->select($sqllokasi);
            $jam_masuk=$lokasi[0]->jam_masuk;
            $jam_masuk2=date('h:i',strtotime($jam_masuk));
            //echo $jam_masuk2;die;

            $sqlabsen="with hole1 as(
with hole as(
SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'yyyy-mm-dd')::date as tgl_absen,
to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jam_keluar as jam_keluar,case when to_char(date_time,'HH24:MI')>='".$jam_masuk2."' then 1 else 0 end as telat
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id and c.active=1
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
GROUP BY a.pin,c.nik,c.nama,a.date_time,jk.jam_keluar
ORDER BY nama,to_char(a.date_time,'yyyy-mm-dd')::date)
SELECT distinct pin,tgl_absen,coalesce(jam_masuk,'00:00:00')||'-'||coalesce(jam_keluar,'00:00:00') as jam_absen,telat
FROM hole)
select pin,nik,nama,case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as periode_gaji,periode_gajian,
count(pin) as jml_masuk, sum(telat) as terlambat, 
max(case when date_part('day', tgl_absen)=".$days1." then  jam_absen else '00:00:00' end) tgl1,
max(case when date_part('day', tgl_absen)=".$days2." then  jam_absen else '00:00:00' end) tgl2,
max(case when date_part('day', tgl_absen)=".$days3." then  jam_absen else '00:00:00' end) tgl3,
max(case when date_part('day', tgl_absen)=".$days4." then  jam_absen else '00:00:00' end) tgl4,
max(case when date_part('day', tgl_absen)=".$days5." then  jam_absen else '00:00:00' end) tgl5,
max(case when date_part('day', tgl_absen)=".$days6." then  jam_absen else '00:00:00' end) tgl6,
max(case when date_part('day', tgl_absen)=".$days7." then  jam_absen else '00:00:00' end) tgl7,
max(case when date_part('day', tgl_absen)=".$days8." then  jam_absen else '00:00:00' end) tgl8,
max(case when date_part('day', tgl_absen)=".$days9." then  jam_absen else '00:00:00' end) tgl9,
max(case when date_part('day', tgl_absen)=".$days10." then  jam_absen else '00:00:00' end) tgl10,
max(case when date_part('day', tgl_absen)=".$days11." then  jam_absen else '00:00:00' end) tgl11,
max(case when date_part('day', tgl_absen)=".$days12." then  jam_absen else '00:00:00' end) tgl12,
max(case when date_part('day', tgl_absen)=".$days13." then  jam_absen else '00:00:00' end) tgl13,
max(case when date_part('day', tgl_absen)=".$days14." then  jam_absen else '00:00:00' end) tgl14,
max(case when date_part('day', tgl_absen)=".$days15." then  jam_absen else '00:00:00' end) tgl15,
max(case when date_part('day', tgl_absen)=".$days16." then  jam_absen else '00:00:00' end) tgl16,
max(case when date_part('day', tgl_absen)=".$days17." then  jam_absen else '00:00:00' end) tgl17,
max(case when date_part('day', tgl_absen)=".$days18." then  jam_absen else '00:00:00' end) tgl18,
max(case when date_part('day', tgl_absen)=".$days19." then  jam_absen else '00:00:00' end) tgl19,
max(case when date_part('day', tgl_absen)=".$days20." then  jam_absen else '00:00:00' end) tgl20,
max(case when date_part('day', tgl_absen)=".$days21." then  jam_absen else '00:00:00' end) tgl21,
max(case when date_part('day', tgl_absen)=".$days22." then  jam_absen else '00:00:00' end) tgl22,
max(case when date_part('day', tgl_absen)=".$days23." then  jam_absen else '00:00:00' end) tgl23,
max(case when date_part('day', tgl_absen)=".$days24." then  jam_absen else '00:00:00' end) tgl24,
max(case when date_part('day', tgl_absen)=".$days25." then  jam_absen else '00:00:00' end) tgl25,
max(case when date_part('day', tgl_absen)=".$days26." then  jam_absen else '00:00:00' end) tgl26,
max(case when date_part('day', tgl_absen)=".$days27." then  jam_absen else '00:00:00' end) tgl27,
max(case when date_part('day', tgl_absen)=".$days28." then  jam_absen else '00:00:00' end) tgl28,
max(case when date_part('day', tgl_absen)=".$days29." then  jam_absen else '00:00:00' end) tgl29,
max(case when date_part('day', tgl_absen)=".$days30." then  jam_absen else '00:00:00' end) tgl30,
max(case when date_part('day', tgl_absen)=".$days31." then  jam_absen else '00:00:00' end) tgl31
from hole1
left join p_karyawan_absen b on b.no_absen=hole1.pin
left join p_karyawan c on c.p_karyawan_id=b.p_karyawan_id and c.active=1
left join p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
where 1=1 and periode_gajian=$periode_gajian
and c.p_karyawan_id NOT IN(148,188,219,199,194)
and d.m_departemen_id NOT IN(17) and d.m_lokasi_id NOT IN(5)
GROUP BY pin,nik,nama,periode_gajian
ORDER BY c.nama";
            //echo $sqlabsen;die;
            $absen=DB::connection()->select($sqlabsen);

            $sqlharilibur="SELECT sum(jumlah) as jumlah FROM m_hari_libur WHERE tanggal>='".$tgl_awal."' and tanggal<='".$tgl_akhir."'";
            $harilibur=DB::connection()->select($sqlharilibur);
            $jml_harilibur=$harilibur[0]->jumlah;

            $awal_cuti = date('d-m-Y',strtotime($tgl_awal));//'30-04-2021';
            $akhir_cuti = date('d-m-Y',strtotime($tgl_akhir));//'12-05-2021';

            // tanggalnya diubah formatnya ke Y-m-d
            $awal_cuti = date_create_from_format('d-m-Y', $awal_cuti);
            $awal_cuti = date_format($awal_cuti, 'Y-m-d');
            $awal_cuti = strtotime($awal_cuti);

            $akhir_cuti = date_create_from_format('d-m-Y', $akhir_cuti);
            $akhir_cuti = date_format($akhir_cuti, 'Y-m-d');
            $akhir_cuti = strtotime($akhir_cuti);

            $haricuti = array();
            $sabtuminggu = array();

            for ($i=$awal_cuti; $i <= $akhir_cuti; $i += (60 * 60 * 24)) {
                if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                    $haricuti[] = $i;
                } else {
                    $sabtuminggu[] = $i;
                }

            }
            $jumlah_cuti = count($haricuti)-$jml_harilibur+1;
            $jumlah_sabtuminggu = count($sabtuminggu);
            $abtotal = $jumlah_cuti + $jumlah_sabtuminggu+$jml_harilibur;
            /*echo "<pre>";
            echo "<h1>Sistem Cuti Online</h1>";
            echo "<hr>";
            echo "Mulai Masuk : " . date('d-m-Y', $awal_cuti) . "<br>";
            echo "Terakhir Masuk : " . date('d-m-Y', $akhir_cuti) . "<br>";
            echo "Jumlah Hari Masuk : " . $jumlah_cuti ."<br>";
            echo "Jumlah Hari Libur Nasional : " . $jml_harilibur ."<br>";
            echo "Jumlah Sabtu/Minggu : " . $jumlah_sabtuminggu ."<br>";
            echo "Total Hari : " . $abtotal ."<br>";
            echo "<h1>Hari Kerja</h1>";
        echo "<hr>";
            foreach ($haricuti as $value) {
                echo date('d-m-Y', $value)  . " -> " . strftime("%A, %d %B %Y", date($value)) . "\n" . "<br>";
            }
            die;*/

            foreach ($absen as $absen){
                $sqlcuti="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(5,6,7,8,9,10) ";
                $datacuti=DB::connection()->select($sqlcuti);
                $cuti=0;
                if(!empty($datacuti)){
                    $cuti=$datacuti[0]->lama;
                }

                $sqlizin="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(4,12,13,14,15,16,17,20,21,23,24) ";
                $dataizin=DB::connection()->select($sqlizin);
                $izin=0;
                if(!empty($dataizin)){
                    $izin=$dataizin[0]->lama;
                }

                $sqlipg="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(3) ";
                $dataipg=DB::connection()->select($sqlipg);
                $ipg=0;
                if(!empty($dataipg)){
                    $ipg=$dataipg[0]->lama;
                }

                DB::connection()->table("absen_temp")
                    ->insert([
                        "nik" => $absen->nik,
                        "nama" => $absen->nama,
                        "periode" => $absen->periode_gajian,
                        "masuk" => $absen->jml_masuk,
                        "terlambat"=>$absen->terlambat,
                        "cuti"=>$cuti,
                        "izin"=>$izin,
                        "ipg"=>$ipg,
                        "a"=>$absen->tgl1,
                        "b"=>$absen->tgl2,
                        "c"=>$absen->tgl3,
                        "d"=>$absen->tgl4,
                        "e"=>$absen->tgl5,
                        "f"=>$absen->tgl6,
                        "g" => $absen->tgl7,
                        "h" => $absen->tgl8,
                        "i" => $absen->tgl9,
                        "j" => $absen->tgl10,
                        "k" => $absen->tgl11,
                        "l" => $absen->tgl12,
                        "m" => $absen->tgl13,
                        "n" => $absen->tgl14,
                        "o" => $absen->tgl15,
                        "p" => $absen->tgl16,
                        "q" => $absen->tgl17,
                        "r" => $absen->tgl18,
                        "s" => $absen->tgl19,
                        "t" => $absen->tgl20,
                        "u" => $absen->tgl21,
                        "v" => $absen->tgl22,
                        "w" => $absen->tgl23,
                        "x" => $absen->tgl24,
                        "y" => $absen->tgl25,
                        "z" => $absen->tgl26,
                        "aa" => $absen->tgl27,
                        "ab" => $absen->tgl28,
                        "ac" => $absen->tgl29,
                        "ad" => $absen->tgl30,
                        "ae" => $absen->tgl31,
                        "created_at" => date("Y-m-d"),
                        "jml_hari_kerja"=>$jumlah_cuti,
                        "jml_hari_libur"=>$jml_harilibur,
                        "total_hari"=>$abtotal,
                    ]);
            }

            //echo $jml;die;
            $sqlrekapabsen="select absen_temp.*, case when periode=0 then 'Pekanan' else 'Bulanan' end as periode_gaji,departemen 
                            from absen_temp 
                            left join get_data_karyawan() gdk on gdk.nik=absen_temp.nik
                            order by nama";
            $rekapabsen=DB::connection()->select($sqlrekapabsen);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NIK');
            $sheet->setCellValue('B1', 'Nama');
            $sheet->setCellValue('C1', 'Departemen');
            $sheet->setCellValue('D1', $days1);
            $sheet->setCellValue('E1', $days2);
            $sheet->setCellValue('F1', $days3);
            $sheet->setCellValue('G1', $days4);
            $sheet->setCellValue('H1', $days5);
            $sheet->setCellValue('I1', $days6);
            $sheet->setCellValue('J1', $days7);
            $sheet->setCellValue('K1', $days8);
            $sheet->setCellValue('L1', $days9);
            $sheet->setCellValue('M1', $days10);
            $sheet->setCellValue('N1', $days11);
            $sheet->setCellValue('O1', $days12);
            $sheet->setCellValue('P1', $days13);
            $sheet->setCellValue('Q1', $days14);
            $sheet->setCellValue('R1', $days15);
            $sheet->setCellValue('S1', $days16);
            $sheet->setCellValue('T1', $days17);
            $sheet->setCellValue('U1', $days18);
            $sheet->setCellValue('v1', $days19);
            $sheet->setCellValue('W1', $days20);
            $sheet->setCellValue('X1', $days21);
            $sheet->setCellValue('Y1', $days22);
            $sheet->setCellValue('Z1', $days23);
            $sheet->setCellValue('AA1', $days24);
            $sheet->setCellValue('AB1', $days25);
            $sheet->setCellValue('AC1', $days26);
            $sheet->setCellValue('AD1', $days27);
            $sheet->setCellValue('AE1', $days28);
            $sheet->setCellValue('AF1', $days29);
            $sheet->setCellValue('AG1', $days30);
            $sheet->setCellValue('AH1', $days31);
            $sheet->setCellValue('AI1', 'Absen Masuk');
            $sheet->setCellValue('AJ1', 'Cuti');
            $sheet->setCellValue('AK1', 'IPG');
            $sheet->setCellValue('AL1', 'IHK');
            $sheet->setCellValue('AM1', 'Total');
            $sheet->setCellValue('AN1', 'Terlambat');
            $rows = 2;

            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->getColumnDimension('O')->setAutoSize(true);
            $sheet->getColumnDimension('P')->setAutoSize(true);
            $sheet->getColumnDimension('Q')->setAutoSize(true);
            $sheet->getColumnDimension('R')->setAutoSize(true);
            $sheet->getColumnDimension('S')->setAutoSize(true);
            $sheet->getColumnDimension('T')->setAutoSize(true);
            $sheet->getColumnDimension('U')->setAutoSize(true);
            $sheet->getColumnDimension('V')->setAutoSize(true);
            $sheet->getColumnDimension('W')->setAutoSize(true);
            $sheet->getColumnDimension('X')->setAutoSize(true);
            $sheet->getColumnDimension('Y')->setAutoSize(true);
            $sheet->getColumnDimension('Z')->setAutoSize(true);
            $sheet->getColumnDimension('AA')->setAutoSize(true);
            $sheet->getColumnDimension('AB')->setAutoSize(true);
            $sheet->getColumnDimension('AC')->setAutoSize(true);
            $sheet->getColumnDimension('AD')->setAutoSize(true);
            $sheet->getColumnDimension('AE')->setAutoSize(true);
            $sheet->getColumnDimension('AF')->setAutoSize(true);
            $sheet->getColumnDimension('AG')->setAutoSize(true);
            $sheet->getColumnDimension('AH')->setAutoSize(true);
            $sheet->getColumnDimension('AI')->setAutoSize(true);
            $sheet->getColumnDimension('AJ')->setAutoSize(true);
            $sheet->getColumnDimension('AK')->setAutoSize(true);
            $sheet->getColumnDimension('AL')->setAutoSize(true);
            $sheet->getColumnDimension('AM')->setAutoSize(true);
            $sheet->getColumnDimension('AN')->setAutoSize(true);


            foreach($rekapabsen as $absen){

                $sqlmanager="SELECT * FROM get_data_karyawan() WHERE nik='".$absen->nik."' ";
                $datamanager=DB::connection()->select($sqlmanager);
                $manager=$datamanager[0]->m_pangkat_id;

                $sheet->setCellValue('A' . $rows, $absen->nik);
                $sheet->setCellValue('B' . $rows, $absen->nama);
                $sheet->setCellValue('C' . $rows, $absen->departemen);
                if(substr($absen->a,0,5)>'07:30' && $manager!=5){
                    $sheet->setCellValue('D' . $rows, $absen->a);
                    $spreadsheet->getActiveSheet()->getStyle('D'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->a,0,5)>'07:30' && $manager==5){
                    $sheet->setCellValue('D' . $rows, $absen->a);
                    $spreadsheet->getActiveSheet()->getStyle('D'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFFFF',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->a,0,5)=='00:00'){
                    $sheet->setCellValue('D' . $rows, $absen->a);
                    $spreadsheet->getActiveSheet()->getStyle('D'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('D' . $rows, $absen->a);
                }

                if(substr($absen->b,0,5)>'07:30'){
                    $sheet->setCellValue('E' . $rows, $absen->b);
                    $spreadsheet->getActiveSheet()->getStyle('E'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->b,0,5)=='00:00'){
                    $sheet->setCellValue('E' . $rows, $absen->b);
                    $spreadsheet->getActiveSheet()->getStyle('E'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('E' . $rows, $absen->b);
                }

                if(substr($absen->c,0,5)>'07:30'){
                    $sheet->setCellValue('F' . $rows, $absen->c);
                    $spreadsheet->getActiveSheet()->getStyle('F'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->c,0,5)=='00:00'){
                    $sheet->setCellValue('F' . $rows, $absen->c);
                    $spreadsheet->getActiveSheet()->getStyle('F'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('F' . $rows, $absen->c);
                }

                if(substr($absen->d,0,5)>'07:30'){
                    $sheet->setCellValue('G' . $rows, $absen->d);
                    $spreadsheet->getActiveSheet()->getStyle('G'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->d,0,5)=='00:00'){
                    $sheet->setCellValue('G' . $rows, $absen->d);
                    $spreadsheet->getActiveSheet()->getStyle('G'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('G' . $rows, $absen->d);
                }

                if(substr($absen->e,0,5)>'07:30'){
                    $sheet->setCellValue('H' . $rows, $absen->e);
                    $spreadsheet->getActiveSheet()->getStyle('H'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->e,0,5)=='00:00'){
                    $sheet->setCellValue('H' . $rows, $absen->e);
                    $spreadsheet->getActiveSheet()->getStyle('H'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('H' . $rows, $absen->e);
                }

                if(substr($absen->f,0,5)>'07:30'){
                    $sheet->setCellValue('I' . $rows, $absen->f);
                    $spreadsheet->getActiveSheet()->getStyle('I'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->f,0,5)=='00:00'){
                    $sheet->setCellValue('I' . $rows, $absen->f);
                    $spreadsheet->getActiveSheet()->getStyle('I'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('I' . $rows, $absen->f);
                }

                if(substr($absen->g,0,5)>'07:30'){
                    $sheet->setCellValue('J' . $rows, $absen->g);
                    $spreadsheet->getActiveSheet()->getStyle('J'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->g,0,5)=='00:00'){
                    $sheet->setCellValue('J' . $rows, $absen->g);
                    $spreadsheet->getActiveSheet()->getStyle('J'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('J' . $rows, $absen->g);
                }

                if(substr($absen->h,0,5)>'07:30'){
                    $sheet->setCellValue('K' . $rows, $absen->h);
                    $spreadsheet->getActiveSheet()->getStyle('K'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                if(substr($absen->h,0,5)=='00:00'){
                    $sheet->setCellValue('K' . $rows, $absen->h);
                    $spreadsheet->getActiveSheet()->getStyle('K'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('K' . $rows, $absen->h);
                }

                if(substr($absen->i,0,5)>'07:30'){
                    $sheet->setCellValue('L' . $rows, $absen->i);
                    $spreadsheet->getActiveSheet()->getStyle('L'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->i,0,5)=='00:00'){
                    $sheet->setCellValue('L' . $rows, $absen->i);
                    $spreadsheet->getActiveSheet()->getStyle('L'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('L' . $rows, $absen->i);
                }

                if(substr($absen->j,0,5)>'07:30'){
                    $sheet->setCellValue('M' . $rows, $absen->j);
                    $spreadsheet->getActiveSheet()->getStyle('M'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->j,0,5)=='00:00'){
                    $sheet->setCellValue('M' . $rows, $absen->j);
                    $spreadsheet->getActiveSheet()->getStyle('M'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('M' . $rows, $absen->j);
                }

                if(substr($absen->k,0,5)>'07:30'){
                    $sheet->setCellValue('N' . $rows, $absen->k);
                    $spreadsheet->getActiveSheet()->getStyle('N'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->k,0,5)=='00:00'){
                    $sheet->setCellValue('N' . $rows, $absen->k);
                    $spreadsheet->getActiveSheet()->getStyle('N'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('N' . $rows, $absen->k);
                }

                if(substr($absen->l,0,5)>'07:30'){
                    $sheet->setCellValue('O' . $rows, $absen->l);
                    $spreadsheet->getActiveSheet()->getStyle('O'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->l,0,5)=='00:00'){
                    $sheet->setCellValue('O' . $rows, $absen->l);
                    $spreadsheet->getActiveSheet()->getStyle('O'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('O' . $rows, $absen->l);
                }

                if(substr($absen->m,0,5)>'07:30'){
                    $sheet->setCellValue('P' . $rows, $absen->m);
                    $spreadsheet->getActiveSheet()->getStyle('P'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                if(substr($absen->m,0,5)=='00:00'){
                    $sheet->setCellValue('P' . $rows, $absen->m);
                    $spreadsheet->getActiveSheet()->getStyle('P'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('P' . $rows, $absen->m);
                }

                if(substr($absen->n,0,5)>'07:30'){
                    $sheet->setCellValue('Q' . $rows, $absen->n);
                    $spreadsheet->getActiveSheet()->getStyle('Q'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->n,0,5)=='00:00'){
                    $sheet->setCellValue('Q' . $rows, $absen->n);
                    $spreadsheet->getActiveSheet()->getStyle('Q'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('Q' . $rows, $absen->n);
                }

                if(substr($absen->o,0,5)>'07:30'){
                    $sheet->setCellValue('R' . $rows, $absen->o);
                    $spreadsheet->getActiveSheet()->getStyle('R'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->o,0,5)=='00:00'){
                    $sheet->setCellValue('R' . $rows, $absen->o);
                    $spreadsheet->getActiveSheet()->getStyle('R'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('R' . $rows, $absen->o);
                }

                if(substr($absen->p,0,5)>'07:30' && $manager!=5){
                    $sheet->setCellValue('S' . $rows, $absen->p);
                    $spreadsheet->getActiveSheet()->getStyle('S'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->p,0,5)>'07:30' && $manager==5){
                    $sheet->setCellValue('S' . $rows, $absen->p);
                    $spreadsheet->getActiveSheet()->getStyle('S'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFFFF',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->p,0,5)=='00:00'){
                    $sheet->setCellValue('S' . $rows, $absen->p);
                    $spreadsheet->getActiveSheet()->getStyle('S'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('S' . $rows, $absen->p);
                }

                if(substr($absen->q,0,5)>'07:30' && $manager!=5){
                    $sheet->setCellValue('T' . $rows, $absen->q);
                    $spreadsheet->getActiveSheet()->getStyle('T'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->q,0,5)>'07:30' && $manager==5){
                    $sheet->setCellValue('T' . $rows, $absen->q);
                    $spreadsheet->getActiveSheet()->getStyle('T'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFFFF',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->q,0,5)=='00:00'){
                    $sheet->setCellValue('T' . $rows, $absen->q);
                    $spreadsheet->getActiveSheet()->getStyle('T'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('T' . $rows, $absen->q);
                }

                if(substr($absen->r,0,5)>'07:30'){
                    $sheet->setCellValue('U' . $rows, $absen->r);
                    $spreadsheet->getActiveSheet()->getStyle('U'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->r,0,5)=='00:00'){
                    $sheet->setCellValue('U' . $rows, $absen->r);
                    $spreadsheet->getActiveSheet()->getStyle('U'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('U' . $rows, $absen->r);
                }

                if(substr($absen->s,0,5)>'07:30'){
                    $sheet->setCellValue('V' . $rows, $absen->s);
                    $spreadsheet->getActiveSheet()->getStyle('V'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->s,0,5)=='00:00'){
                    $sheet->setCellValue('V' . $rows, $absen->s);
                    $spreadsheet->getActiveSheet()->getStyle('V'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('V' . $rows, $absen->s);
                }

                if(substr($absen->t,0,5)>'07:30'){
                    $sheet->setCellValue('W' . $rows, $absen->t);
                    $spreadsheet->getActiveSheet()->getStyle('W'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->t,0,5)=='00:00'){
                    $sheet->setCellValue('W' . $rows, $absen->t);
                    $spreadsheet->getActiveSheet()->getStyle('W'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('W' . $rows, $absen->t);
                }

                if(substr($absen->u,0,5)>'07:30'){
                    $sheet->setCellValue('X' . $rows, $absen->u);
                    $spreadsheet->getActiveSheet()->getStyle('X'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->u,0,5)=='00:00'){
                    $sheet->setCellValue('X' . $rows, $absen->u);
                    $spreadsheet->getActiveSheet()->getStyle('X'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('X' . $rows, $absen->u);
                }

                if(substr($absen->v,0,5)>'07:30'){
                    $sheet->setCellValue('Y' . $rows, $absen->v);
                    $spreadsheet->getActiveSheet()->getStyle('Y'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->v,0,5)=='00:00'){
                    $sheet->setCellValue('Y' . $rows, $absen->v);
                    $spreadsheet->getActiveSheet()->getStyle('Y'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('Y' . $rows, $absen->v);
                }

                if(substr($absen->w,0,5)>'07:30'){
                    $sheet->setCellValue('Z' . $rows, $absen->w);
                    $spreadsheet->getActiveSheet()->getStyle('Z'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->w,0,5)=='00:00'){
                    $sheet->setCellValue('Z' . $rows, $absen->w);
                    $spreadsheet->getActiveSheet()->getStyle('Z'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('Z' . $rows, $absen->w);
                }

                if(substr($absen->x,0,5)>'07:30'){
                    $sheet->setCellValue('AA' . $rows, $absen->x);
                    $spreadsheet->getActiveSheet()->getStyle('AA'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->x,0,5)=='00:00'){
                    $sheet->setCellValue('AA' . $rows, $absen->x);
                    $spreadsheet->getActiveSheet()->getStyle('AA'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('AA' . $rows, $absen->x);
                }

                if(substr($absen->y,0,5)>'07:30'){
                    $sheet->setCellValue('AB' . $rows, $absen->y);
                    $spreadsheet->getActiveSheet()->getStyle('AB'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->y,0,5)=='00:00'){
                    $sheet->setCellValue('AB' . $rows, $absen->y);
                    $spreadsheet->getActiveSheet()->getStyle('AB'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('AB' . $rows, $absen->y);
                }

                if(substr($absen->z,0,5)>'07:30'){
                    $sheet->setCellValue('AC' . $rows, $absen->z);
                    $spreadsheet->getActiveSheet()->getStyle('AC'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->z,0,5)=='00:00'){
                    $sheet->setCellValue('AC' . $rows, $absen->z);
                    $spreadsheet->getActiveSheet()->getStyle('AC'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('AC' . $rows, $absen->z);
                }

                if(substr($absen->aa,0,5)>'07:30'){
                    $sheet->setCellValue('AD' . $rows, $absen->aa);
                    $spreadsheet->getActiveSheet()->getStyle('AD'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->aa,0,5)=='00:00'){
                    $sheet->setCellValue('AD' . $rows, $absen->aa);
                    $spreadsheet->getActiveSheet()->getStyle('AD'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('AD' . $rows, $absen->aa);
                }

                if(substr($absen->ab,0,5)>'07:30'){
                    $sheet->setCellValue('AE' . $rows, $absen->ab);
                    $spreadsheet->getActiveSheet()->getStyle('AE'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->ab,0,5)=='00:00'){
                    $sheet->setCellValue('AE' . $rows, $absen->ab);
                    $spreadsheet->getActiveSheet()->getStyle('AE'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('AE' . $rows, $absen->ab);
                }

                if(substr($absen->ac,0,5)>'07:30'){
                    $sheet->setCellValue('AF' . $rows, $absen->ac);
                    $spreadsheet->getActiveSheet()->getStyle('AF'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->ac,0,5)=='00:00'){
                    $sheet->setCellValue('AF' . $rows, $absen->ac);
                    $spreadsheet->getActiveSheet()->getStyle('AF'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('AF' . $rows, $absen->ac);
                }

                if(substr($absen->ad,0,5)>'07:30'){
                    $sheet->setCellValue('AG' . $rows, $absen->ad);
                    $spreadsheet->getActiveSheet()->getStyle('AG'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->ad,0,5)=='00:00'){
                    $sheet->setCellValue('AG' . $rows, $absen->ad);
                    $spreadsheet->getActiveSheet()->getStyle('AG'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('AG' . $rows, $absen->ad);
                }

                if(substr($absen->ae,0,5)>'07:30'){
                    $sheet->setCellValue('AH' . $rows, $absen->ae);
                    $spreadsheet->getActiveSheet()->getStyle('AH'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FF0000',
                            ]
                        ],
                    ]);
                }
                else if(substr($absen->ae,0,5)=='00:00'){
                    $sheet->setCellValue('AH' . $rows, $absen->ae);
                    $spreadsheet->getActiveSheet()->getStyle('AH'.$rows)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFF00',
                            ]
                        ],
                    ]);
                }
                else{
                    $sheet->setCellValue('AH' . $rows, $absen->ae);
                }
                $sheet->setCellValue('AI' . $rows, $absen->masuk);
                $sheet->setCellValue('AJ' . $rows, $absen->cuti);
                $sheet->setCellValue('AK' . $rows, $absen->ipg);
                $sheet->setCellValue('AL' . $rows, $absen->izin);
                $sheet->setCellValue('AM' . $rows, $absen->masuk+$absen->cuti+$absen->ipg+$absen->izin);
                $sheet->setCellValue('AN' . $rows, $absen->terlambat);
                //echo substr($absen->a,0,5);die;
                $rows++;
            }

            $spreadsheet->getActiveSheet()->getStyle('A1:AN1')->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFDBE2F1',
                    ]
                ],
            ]);

            $tgl_awal_=date('d-m-Y',strtotime($dataperiode[0]->tgl_awal));
            $tgl_akhir_=date('d-m-Y',strtotime($dataperiode[0]->tgl_akhir));

            $type = 'xlsx';
            $fileName = "absen ".$tgl_awal_." - ".$tgl_akhir_.".".$type;
            if($type == 'xlsx') {
                $writer = new Xlsx($spreadsheet);
            } else if($type == 'xls') {
                $writer = new Xls($spreadsheet);
            }
            $writer->save("export/".$fileName);
            header("Content-Type: application/vnd.ms-excel");
            return redirect(url('/')."/export/".$fileName);
        }
    }

    public function rekap_absen(Request $request)
    {
        $tgl_awal=date('Y-m-d');
        $tgl_akhir=date('Y-m-d');
        $tahun=date('Y');
        $bulan=date('mm');
        $periode_gajian=2;
        $periode_absen=$request->get('periode');
        //echo $periode_absen;die;

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlperiode="SELECT m_periode_absen.*,
case when periode=1 then 'Januari'
when periode=2 then 'Februari'
when periode=3 then 'Maret'
when periode=4 then 'April'
when periode=5 then 'Mei'
when periode=6 then 'Juni'
when periode=7 then 'Juli'
when periode=8 then 'Agustus'
when periode=9 then 'September'
when periode=10 then 'Oktober'
when periode=11 then 'November'
when periode=12 then 'Desember' end as bulan,
case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
FROM m_periode_absen WHERE tahun='".$tahun."'
ORDER BY tahun,periode";
        $periode=DB::connection()->select($sqlperiode);

        $jml=date('d',strtotime($tgl_akhir))-date('d',strtotime($tgl_awal));
        //echo $jml;die;
        if($jml==0){
            $jml=1;
        };
        $jmlawal=date('d',strtotime($tgl_awal));
        $jmlakhir=date('d',strtotime($tgl_akhir));
        $days=31;

        $days=$tgl_awal;
        if($days>31){
            $days1=1;
        }
        else{
            $days1=$days;
        }

        $days2=$days1+1;
        if($days2>31){
            $days2=1;
        }
        else{
            $days2=$days2;
        }

        $days3=$days2+1;
        if($days3>31){
            $days3=1;
        }
        else{
            $days3=$days3;
        }

        $days4=$days3+1;
        if($days4>31){
            $days4=1;
        }
        else{
            $days4=$days4;
        }

        $days5=$days4+1;
        if($days5>31){
            $days5=1;
        }
        else{
            $days5=$days5;
        }

        $days6=$days5+1;
        if($days6>31){
            $days6=1;
        }
        else{
            $days6=$days6;
        }

        $days7=$days6+1;
        if($days7>31){
            $days7=1;
        }
        else{
            $days7=$days7;
        }

        $days8=$days7+1;
        if($days8>31){
            $days8=1;
        }
        else{
            $days8=$days8;
        }

        $days9=$days8+1;
        if($days9>31){
            $days9=1;
        }
        else{
            $days9=$days9;
        }

        $days10=$days9+1;
        if($days10>31){
            $days10=1;
        }
        else{
            $days10=$days10;
        }

        $days11=$days10+1;
        if($days11>31){
            $days11=1;
        }
        else{
            $days11=$days11;
        }

        $days12=$days11+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days13=$days12+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days14=$days13+1;
        if($days14>31){
            $days14=1;
        }
        else{
            $days14=$days14;
        }

        $days15=$days14+1;
        if($days15>31){
            $days15=1;
        }
        else{
            $days15=$days15;
        }

        $days16=$days15+1;
        if($days16>31){
            $days16=1;
        }
        else{
            $days16=$days16;
        }

        $days17=$days16+1;
        if($days17>31){
            $days17=1;
        }
        else{
            $days17=$days17;
        }

        $days18=$days17+1;
        if($days18>31){
            $days18=1;
        }
        else{
            $days18=$days18;
        }

        $days19=$days18+1;
        if($days19>31){
            $days19=1;
        }
        else{
            $days19=$days19;
        }

        $days20=$days19+1;
        if($days20>31){
            $days20=1;
        }
        else{
            $days20=$days20;
        }

        $days21=$days20+1;
        if($days21>31){
            $days21=1;
        }
        else{
            $days21=$days21;
        }

        $days22=$days21+1;
        if($days22>31){
            $days22=1;
        }
        else{
            $days22=$days22;
        }

        $days23=$days22+1;
        if($days23>31){
            $days23=1;
        }
        else{
            $days23=$days23;
        }

        $days24=$days23+1;
        if($days24>31){
            $days24=1;
        }
        else{
            $days24=$days24;
        }

        $days25=$days24+1;
        if($days25>31){
            $days25=1;
        }
        else{
            $days25=$days25;
        }

        $days26=$days25+1;
        if($days26>31){
            $days25=1;
        }
        else{
            $days26=$days26;
        }

        $days27=$days26+1;
        if($days27>31){
            $days27=1;
        }
        else{
            $days27=$days27;
        }

        $days28=$days27+1;
        if($days28>31){
            $days28=1;
        }
        else{
            $days28=$days28;
        }

        $days29=$days28+1;
        if($days29>31){
            $days29=1;
        }
        else{
            $days29=$days29;
        }

        $days30=$days29+1;
        if($days30>31){
            $days30=1;
        }
        else{
            $days30=$days30;
        }

        $days31=$days30+1;
        if($days31>31){
            $days31=1;
        }
        else{
            $days31=$days31;
        }

        $periode_absen='';

        return view('backend.absen.rekap_absen',compact('tgl_awal','tgl_akhir','bulan','tahun','user','periode_gajian','jml','jmlawal','jmlakhir','periode','periode_absen','days','days1','days2','days3','days4','days5','days6','days7','days8','days9','days10','days11','days12','days13','days14','days15','days16','days17','days18','days19','days20','days21','days22','days23','days24','days25','days26','days27','days28','days29','days30','days31','periode_absen'));
    }

    public function cari_rekap_absen(Request $request)
    {
        $periode_absen=$request->get('periode_gajian');

        DB::connection()->table("absen_temp")
            ->delete();

        $sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
        $periode=DB::connection()->select($sqlperiode);

        $periode_gajian=$periode[0]->type;
        $tgl_awal=date('Y-m-d',strtotime($periode[0]->tgl_awal));
        $tgl_akhir=date('Y-m-d',strtotime($periode[0]->tgl_akhir));
        $tanggal_awal=date('d',strtotime($periode[0]->tgl_awal));
        $tanggal_akhir=date('d',strtotime($periode[0]->tgl_akhir));
        //echo $tgl_akhir-$tgl_awal;die;
        //$d = $tgl_akhir->diff($tgl_awal)->days + 1;
        $datetime1 = new DateTime($tgl_awal);
        $datetime2 = new DateTime($tgl_akhir);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');//now do whatever you like with $days
        //echo $days;die;
        //$awal=$tanggal_awal;
        /*for($i=$tanggal_awal;$i<$days;$i++){
            $days=$days++;
            echo $days;
        }die;*/

        $days=$tanggal_awal;
        if($days>31){
            $days1=1;
        }
        else{
            $days1=$days;
        }

        $days2=$days1+1;
        if($days2>31){
            $days2=1;
        }
        else{
            $days2=$days2;
        }

        $days3=$days2+1;
        if($days3>31){
            $days3=1;
        }
        else{
            $days3=$days3;
        }

        $days4=$days3+1;
        if($days4>31){
            $days4=1;
        }
        else{
            $days4=$days4;
        }

        $days5=$days4+1;
        if($days5>31){
            $days5=1;
        }
        else{
            $days5=$days5;
        }

        $days6=$days5+1;
        if($days6>31){
            $days6=1;
        }
        else{
            $days6=$days6;
        }

        $days7=$days6+1;
        if($days7>31){
            $days7=1;
        }
        else{
            $days7=$days7;
        }

        $days8=$days7+1;
        if($days8>31){
            $days8=1;
        }
        else{
            $days8=$days8;
        }

        $days9=$days8+1;
        if($days9>31){
            $days9=1;
        }
        else{
            $days9=$days9;
        }

        $days10=$days9+1;
        if($days10>31){
            $days10=1;
        }
        else{
            $days10=$days10;
        }

        $days11=$days10+1;
        if($days11>31){
            $days11=1;
        }
        else{
            $days11=$days11;
        }

        $days12=$days11+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days13=$days12+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days14=$days13+1;
        if($days14>31){
            $days14=1;
        }
        else{
            $days14=$days14;
        }

        $days15=$days14+1;
        if($days15>31){
            $days15=1;
        }
        else{
            $days15=$days15;
        }

        $days16=$days15+1;
        if($days16>31){
            $days16=1;
        }
        else{
            $days16=$days16;
        }

        $days17=$days16+1;
        if($days17>31){
            $days17=1;
        }
        else{
            $days17=$days17;
        }

        $days18=$days17+1;
        if($days18>31){
            $days18=1;
        }
        else{
            $days18=$days18;
        }

        $days19=$days18+1;
        if($days19>31){
            $days19=1;
        }
        else{
            $days19=$days19;
        }

        $days20=$days19+1;
        if($days20>31){
            $days20=1;
        }
        else{
            $days20=$days20;
        }

        $days21=$days20+1;
        if($days21>31){
            $days21=1;
        }
        else{
            $days21=$days21;
        }

        $days22=$days21+1;
        if($days22>31){
            $days22=1;
        }
        else{
            $days22=$days22;
        }

        $days23=$days22+1;
        if($days23>31){
            $days23=1;
        }
        else{
            $days23=$days23;
        }

        $days24=$days23+1;
        if($days24>31){
            $days24=1;
        }
        else{
            $days24=$days24;
        }

        $days25=$days24+1;
        if($days25>31){
            $days25=1;
        }
        else{
            $days25=$days25;
        }

        $days26=$days25+1;
        if($days26>31){
            $days25=1;
        }
        else{
            $days26=$days26;
        }

        $days27=$days26+1;
        if($days27>31){
            $days27=1;
        }
        else{
            $days27=$days27;
        }

        $days28=$days27+1;
        if($days28>31){
            $days28=1;
        }
        else{
            $days28=$days28;
        }

        $days29=$days28+1;
        if($days29>31){
            $days29=1;
        }
        else{
            $days29=$days29;
        }

        $days30=$days29+1;
        if($days30>31){
            $days30=1;
        }
        else{
            $days30=$days30;
        }

        $days31=$days30+1;
        if($days31>31){
            $days31=1;
        }
        else{
            $days31=$days31;
        }

        $sqlabsen="with hole1 as(
with hole as(
SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'yyyy-mm-dd')::date as tgl_absen,
to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jam_keluar as jam_keluar,case when to_char(date_time,'HH24:MI')>'07:30' then 1 else 0 end as telat
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
GROUP BY a.pin,c.nik,c.nama,a.date_time,jk.jam_keluar
ORDER BY nama,to_char(a.date_time,'yyyy-mm-dd')::date)
SELECT pin,tgl_absen,coalesce(jam_masuk,'00:00:00')||'-'||coalesce(jam_keluar,'00:00:00') as jam_absen,telat
FROM hole)
select pin,nik,nama,case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as periode_gaji,periode_gajian,
count(pin) as jml_masuk, sum(telat) as terlambat, 
max(case when date_part('day', tgl_absen)=".$days." then  jam_absen else '00:00:00' end) tgl1,
max(case when date_part('day', tgl_absen)=".$days2." then  jam_absen else '00:00:00' end) tgl2,
max(case when date_part('day', tgl_absen)=".$days3." then  jam_absen else '00:00:00' end) tgl3,
max(case when date_part('day', tgl_absen)=".$days4." then  jam_absen else '00:00:00' end) tgl4,
max(case when date_part('day', tgl_absen)=".$days5." then  jam_absen else '00:00:00' end) tgl5,
max(case when date_part('day', tgl_absen)=".$days6." then  jam_absen else '00:00:00' end) tgl6,
max(case when date_part('day', tgl_absen)=".$days7." then  jam_absen else '00:00:00' end) tgl7,
max(case when date_part('day', tgl_absen)=".$days8." then  jam_absen else '00:00:00' end) tgl8,
max(case when date_part('day', tgl_absen)=".$days9." then  jam_absen else '00:00:00' end) tgl9,
max(case when date_part('day', tgl_absen)=".$days10." then  jam_absen else '00:00:00' end) tgl10,
max(case when date_part('day', tgl_absen)=".$days11." then  jam_absen else '00:00:00' end) tgl11,
max(case when date_part('day', tgl_absen)=".$days12." then  jam_absen else '00:00:00' end) tgl12,
max(case when date_part('day', tgl_absen)=".$days13." then  jam_absen else '00:00:00' end) tgl13,
max(case when date_part('day', tgl_absen)=".$days14." then  jam_absen else '00:00:00' end) tgl14,
max(case when date_part('day', tgl_absen)=".$days15." then  jam_absen else '00:00:00' end) tgl15,
max(case when date_part('day', tgl_absen)=".$days16." then  jam_absen else '00:00:00' end) tgl16,
max(case when date_part('day', tgl_absen)=".$days17." then  jam_absen else '00:00:00' end) tgl17,
max(case when date_part('day', tgl_absen)=".$days18." then  jam_absen else '00:00:00' end) tgl18,
max(case when date_part('day', tgl_absen)=".$days19." then  jam_absen else '00:00:00' end) tgl19,
max(case when date_part('day', tgl_absen)=".$days20." then  jam_absen else '00:00:00' end) tgl20,
max(case when date_part('day', tgl_absen)=".$days21." then  jam_absen else '00:00:00' end) tgl21,
max(case when date_part('day', tgl_absen)=".$days22." then  jam_absen else '00:00:00' end) tgl22,
max(case when date_part('day', tgl_absen)=".$days23." then  jam_absen else '00:00:00' end) tgl23,
max(case when date_part('day', tgl_absen)=".$days24." then  jam_absen else '00:00:00' end) tgl24,
max(case when date_part('day', tgl_absen)=".$days25." then  jam_absen else '00:00:00' end) tgl25,
max(case when date_part('day', tgl_absen)=".$days26." then  jam_absen else '00:00:00' end) tgl26,
max(case when date_part('day', tgl_absen)=".$days27." then  jam_absen else '00:00:00' end) tgl27,
max(case when date_part('day', tgl_absen)=".$days28." then  jam_absen else '00:00:00' end) tgl28,
max(case when date_part('day', tgl_absen)=".$days29." then  jam_absen else '00:00:00' end) tgl29,
max(case when date_part('day', tgl_absen)=".$days30." then  jam_absen else '00:00:00' end) tgl30,
max(case when date_part('day', tgl_absen)=".$days31." then  jam_absen else '00:00:00' end) tgl31
from hole1
left join p_karyawan_absen b on b.no_absen=hole1.pin
left join p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
left join p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
where 1=1 and periode_gajian=$periode_gajian
and d.m_lokasi_id NOT IN(5)
GROUP BY pin,nik,nama,periode_gajian
ORDER BY c.nama";
        //echo $sqlabsen;die;
        $absen=DB::connection()->select($sqlabsen);

        foreach ($absen as $absen){

            $sqlkaryawan="SELECT * FROM p_karyawan_absen where no_absen=$absen->pin";
            $karyawan=DB::connection()->select($sqlkaryawan);
            $idkar=$karyawan[0]->p_karyawan_id;

            $sqlcuti="SELECT coalesce(sum(lama),0) as cuti FROM t_permit a
LEFT JOIN m_jenis_ijin b on b.m_jenis_ijin_id=a.m_jenis_ijin_id
WHERE a.active=1 and b.tipe=3 and a.p_karyawan_id=$idkar";
            $datacuti=DB::connection()->select($sqlcuti);
            $cuti=$datacuti[0]->p_karyawan_id;

            $sqlizin="SELECT coalesce(sum(lama),0) as ihk FROM t_permit a
LEFT JOIN m_jenis_ijin b on b.m_jenis_ijin_id=a.m_jenis_ijin_id
WHERE a.active=1 and b.tipe=1 and a.m_jenis_ijin_id not in(1) and a.p_karyawan_id=$idkar";
            $dataizin=DB::connection()->select($sqlizin);
            $izin=$dataizin[0]->p_karyawan_id;

            $sqlipg="SELECT coalesce(sum(lama),0) as ipg FROM t_permit a
WHERE a.active=1 and a.m_jenis_ijin_id=1 and a.p_karyawan_id=$idkar";
            $dataipg=DB::connection()->select($sqlipg);
            $ipg=$dataipg[0]->p_karyawan_id;



            DB::connection()->table("absen_temp")
                ->insert([
                    "nik" => $absen->nik,
                    "nama" => $absen->nama,
                    "periode" => $absen->periode_gajian,
                    "masuk" => $absen->jml_masuk,
                    "terlambat"=>$absen->terlambat,
                    "a"=>$absen->tgl1,
                    "b"=>$absen->tgl2,
                    "c"=>$absen->tgl2,
                    "d"=>$absen->tgl4,
                    "e"=>$absen->tgl5,
                    "f"=>$absen->tgl6,
                    "g" => $absen->tgl7,
                    "h" => $absen->tgl8,
                    "i" => $absen->tgl9,
                    "j" => $absen->tgl10,
                    "k" => $absen->tgl11,
                    "l" => $absen->tgl12,
                    "m" => $absen->tgl13,
                    "n" => $absen->tgl14,
                    "o" => $absen->tgl15,
                    "p" => $absen->tgl16,
                    "q" => $absen->tgl17,
                    "r" => $absen->tgl18,
                    "s" => $absen->tgl19,
                    "t" => $absen->tgl20,
                    "u" => $absen->tgl21,
                    "v" => $absen->tgl22,
                    "w" => $absen->tgl23,
                    "x" => $absen->tgl24,
                    "y" => $absen->tgl25,
                    "z" => $absen->tgl26,
                    "aa" => $absen->tgl27,
                    "ab" => $absen->tgl28,
                    "ac" => $absen->tgl29,
                    "ad" => $absen->tgl30,
                    "ae" => $absen->tgl31,
                    "cuti" => $cuti,
                    "izin" => $izin,
                    "ipg" => $ipg,
                    "created_at" => date("Y-m-d"),
                ]);
        }

        $tahun=date('Y');
        $bulan=date('mm');

        $sqlperiode="SELECT m_periode_absen.*,
case when periode=1 then 'Januari'
when periode=2 then 'Februari'
when periode=3 then 'Maret'
when periode=4 then 'April'
when periode=5 then 'Mei'
when periode=6 then 'Juni'
when periode=7 then 'Juli'
when periode=8 then 'Agustus'
when periode=9 then 'September'
when periode=10 then 'Oktober'
when periode=11 then 'November'
when periode=12 then 'Desember' end as bulan,
case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
FROM m_periode_absen WHERE tahun='".$tahun."'
ORDER BY tahun,periode";
        $periode=DB::connection()->select($sqlperiode);

        $jml=date('d',strtotime($tgl_akhir))-date('d',strtotime($tgl_awal))+1;
        if($jml==0){
            $jml=1;
        };
        //echo $jml;die;
        $sqlrekapabsen="select absen_temp.*, case when periode=0 then 'Pekanan' else 'Bulanan' end as periode_gaji from absen_temp order by nama";
        $rekapabsen=DB::connection()->select($sqlrekapabsen);
        //echo $days;die;

        $nama_file = 'Rekap Absen ' . date('d-m-Y', strtotime($tgl_awal)) . ':' . date('d-m-Y', strtotime($tgl_akhir));

        $param['rekapabsen'] = $rekapabsen;
        $param['jml'] = $jml;
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['days'] = $days;
        $param['tanggal_awal'] = $tanggal_awal;
        $param['tanggal_akhir'] = $tanggal_akhir;
        $param['periode'] = $periode;

        return Excel::download(new rekap_absen_xls($param), $nama_file . '.xlsx');
    }

    public function export(Request $request)
    {
        $periode_absen=$request->get('periode_gajian');

        DB::connection()->table("absen_temp")
            ->delete();

        $sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
        $periode=DB::connection()->select($sqlperiode);

        $periode_gajian=$periode[0]->type;
        $tgl_awal=date('Y-m-d',strtotime($periode[0]->tgl_awal));
        $tgl_akhir=date('Y-m-d',strtotime($periode[0]->tgl_akhir));
        $tanggal_awal=date('d',strtotime($periode[0]->tgl_awal));
        $tanggal_akhir=date('d',strtotime($periode[0]->tgl_akhir));
        //echo $tgl_akhir-$tgl_awal;die;
        //$d = $tgl_akhir->diff($tgl_awal)->days + 1;
        $datetime1 = new DateTime($tgl_awal);
        $datetime2 = new DateTime($tgl_akhir);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');//now do whatever you like with $days
        //echo $days;die;
        //$awal=$tanggal_awal;
        /*for($i=$tanggal_awal;$i<$days;$i++){
            $days=$days++;
            echo $days;
        }die;*/

        $days=$tanggal_awal;
        if($days>31){
            $days1=1;
        }
        else{
            $days1=$days;
        }

        $days2=$days1+1;
        if($days2>31){
            $days2=1;
        }
        else{
            $days2=$days2;
        }

        $days3=$days2+1;
        if($days3>31){
            $days3=1;
        }
        else{
            $days3=$days3;
        }

        $days4=$days3+1;
        if($days4>31){
            $days4=1;
        }
        else{
            $days4=$days4;
        }

        $days5=$days4+1;
        if($days5>31){
            $days5=1;
        }
        else{
            $days5=$days5;
        }

        $days6=$days5+1;
        if($days6>31){
            $days6=1;
        }
        else{
            $days6=$days6;
        }

        $days7=$days6+1;
        if($days7>31){
            $days7=1;
        }
        else{
            $days7=$days7;
        }

        $days8=$days7+1;
        if($days8>31){
            $days8=1;
        }
        else{
            $days8=$days8;
        }

        $days9=$days8+1;
        if($days9>31){
            $days9=1;
        }
        else{
            $days9=$days9;
        }

        $days10=$days9+1;
        if($days10>31){
            $days10=1;
        }
        else{
            $days10=$days10;
        }

        $days11=$days10+1;
        if($days11>31){
            $days11=1;
        }
        else{
            $days11=$days11;
        }

        $days12=$days11+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days13=$days12+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days14=$days13+1;
        if($days14>31){
            $days14=1;
        }
        else{
            $days14=$days14;
        }

        $days15=$days14+1;
        if($days15>31){
            $days15=1;
        }
        else{
            $days15=$days15;
        }

        $days16=$days15+1;
        if($days16>31){
            $days16=1;
        }
        else{
            $days16=$days16;
        }

        $days17=$days16+1;
        if($days17>31){
            $days17=1;
        }
        else{
            $days17=$days17;
        }

        $days18=$days17+1;
        if($days18>31){
            $days18=1;
        }
        else{
            $days18=$days18;
        }

        $days19=$days18+1;
        if($days19>31){
            $days19=1;
        }
        else{
            $days19=$days19;
        }

        $days20=$days19+1;
        if($days20>31){
            $days20=1;
        }
        else{
            $days20=$days20;
        }

        $days21=$days20+1;
        if($days21>31){
            $days21=1;
        }
        else{
            $days21=$days21;
        }

        $days22=$days21+1;
        if($days22>31){
            $days22=1;
        }
        else{
            $days22=$days22;
        }

        $days23=$days22+1;
        if($days23>31){
            $days23=1;
        }
        else{
            $days23=$days23;
        }

        $days24=$days23+1;
        if($days24>31){
            $days24=1;
        }
        else{
            $days24=$days24;
        }

        $days25=$days24+1;
        if($days25>31){
            $days25=1;
        }
        else{
            $days25=$days25;
        }

        $days26=$days25+1;
        if($days26>31){
            $days25=1;
        }
        else{
            $days26=$days26;
        }

        $days27=$days26+1;
        if($days27>31){
            $days27=1;
        }
        else{
            $days27=$days27;
        }

        $days28=$days27+1;
        if($days28>31){
            $days28=1;
        }
        else{
            $days28=$days28;
        }

        $days29=$days28+1;
        if($days29>31){
            $days29=1;
        }
        else{
            $days29=$days29;
        }

        $days30=$days29+1;
        if($days30>31){
            $days30=1;
        }
        else{
            $days30=$days30;
        }

        $days31=$days30+1;
        if($days31>31){
            $days31=1;
        }
        else{
            $days31=$days31;
        }
        $sqllokasi="SELECT * FROM m_lokasi WHERE 1=1 and active=1";
        $lokasi=DB::connection()->select($sqllokasi);
        $jam_masuk=$lokasi[0]->jam_masuk;

        $sqlabsen="with hole1 as(
with hole as(
SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'yyyy-mm-dd')::date as tgl_absen,
to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jam_keluar as jam_keluar,case when to_char(date_time,'HH24:MI')>'".$jam_masuk."' then 1 else 0 end as telat
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id and c.active=1
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
GROUP BY a.pin,c.nik,c.nama,a.date_time,jk.jam_keluar
ORDER BY nama,to_char(a.date_time,'yyyy-mm-dd')::date)
SELECT pin,tgl_absen,coalesce(jam_masuk,'00:00:00')||'-'||coalesce(jam_keluar,'00:00:00') as jam_absen,telat
FROM hole)
select pin,nik,nama,case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as periode_gaji,periode_gajian,
count(pin) as jml_masuk, sum(telat) as terlambat, 
max(case when date_part('day', tgl_absen)=".$days." then  jam_absen else '00:00:00' end) tgl1,
max(case when date_part('day', tgl_absen)=".$days2." then  jam_absen else '00:00:00' end) tgl2,
max(case when date_part('day', tgl_absen)=".$days3." then  jam_absen else '00:00:00' end) tgl3,
max(case when date_part('day', tgl_absen)=".$days4." then  jam_absen else '00:00:00' end) tgl4,
max(case when date_part('day', tgl_absen)=".$days5." then  jam_absen else '00:00:00' end) tgl5,
max(case when date_part('day', tgl_absen)=".$days6." then  jam_absen else '00:00:00' end) tgl6,
max(case when date_part('day', tgl_absen)=".$days7." then  jam_absen else '00:00:00' end) tgl7,
max(case when date_part('day', tgl_absen)=".$days8." then  jam_absen else '00:00:00' end) tgl8,
max(case when date_part('day', tgl_absen)=".$days9." then  jam_absen else '00:00:00' end) tgl9,
max(case when date_part('day', tgl_absen)=".$days10." then  jam_absen else '00:00:00' end) tgl10,
max(case when date_part('day', tgl_absen)=".$days11." then  jam_absen else '00:00:00' end) tgl11,
max(case when date_part('day', tgl_absen)=".$days12." then  jam_absen else '00:00:00' end) tgl12,
max(case when date_part('day', tgl_absen)=".$days13." then  jam_absen else '00:00:00' end) tgl13,
max(case when date_part('day', tgl_absen)=".$days14." then  jam_absen else '00:00:00' end) tgl14,
max(case when date_part('day', tgl_absen)=".$days15." then  jam_absen else '00:00:00' end) tgl15,
max(case when date_part('day', tgl_absen)=".$days16." then  jam_absen else '00:00:00' end) tgl16,
max(case when date_part('day', tgl_absen)=".$days17." then  jam_absen else '00:00:00' end) tgl17,
max(case when date_part('day', tgl_absen)=".$days18." then  jam_absen else '00:00:00' end) tgl18,
max(case when date_part('day', tgl_absen)=".$days19." then  jam_absen else '00:00:00' end) tgl19,
max(case when date_part('day', tgl_absen)=".$days20." then  jam_absen else '00:00:00' end) tgl20,
max(case when date_part('day', tgl_absen)=".$days21." then  jam_absen else '00:00:00' end) tgl21,
max(case when date_part('day', tgl_absen)=".$days22." then  jam_absen else '00:00:00' end) tgl22,
max(case when date_part('day', tgl_absen)=".$days23." then  jam_absen else '00:00:00' end) tgl23,
max(case when date_part('day', tgl_absen)=".$days24." then  jam_absen else '00:00:00' end) tgl24,
max(case when date_part('day', tgl_absen)=".$days25." then  jam_absen else '00:00:00' end) tgl25,
max(case when date_part('day', tgl_absen)=".$days26." then  jam_absen else '00:00:00' end) tgl26,
max(case when date_part('day', tgl_absen)=".$days27." then  jam_absen else '00:00:00' end) tgl27,
max(case when date_part('day', tgl_absen)=".$days28." then  jam_absen else '00:00:00' end) tgl28,
max(case when date_part('day', tgl_absen)=".$days29." then  jam_absen else '00:00:00' end) tgl29,
max(case when date_part('day', tgl_absen)=".$days30." then  jam_absen else '00:00:00' end) tgl30,
max(case when date_part('day', tgl_absen)=".$days31." then  jam_absen else '00:00:00' end) tgl31
from hole1
left join p_karyawan_absen b on b.no_absen=hole1.pin
left join p_karyawan c on c.p_karyawan_id=b.p_karyawan_id and c.active=1
left join p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
where 1=1 and periode_gajian=$periode_gajian
and d.m_lokasi_id NOT IN(5)
GROUP BY pin,nik,nama,periode_gajian
ORDER BY c.nama";
        //echo $sqlabsen;die;
        $absen=DB::connection()->select($sqlabsen);

        $sqlharilibur="SELECT sum(jumlah) as jumlah FROM m_hari_libur WHERE tanggal>='".$tgl_awal."' and tanggal<='".$tgl_akhir."'";
        $harilibur=DB::connection()->select($sqlharilibur);
        $jml_harilibur=$harilibur[0]->jumlah;

        $awal_cuti = date('d-m-Y',strtotime($tgl_awal));//'30-04-2021';
        $akhir_cuti = date('d-m-Y',strtotime($tgl_akhir));//'12-05-2021';

        // tanggalnya diubah formatnya ke Y-m-d
        $awal_cuti = date_create_from_format('d-m-Y', $awal_cuti);
        $awal_cuti = date_format($awal_cuti, 'Y-m-d');
        $awal_cuti = strtotime($awal_cuti);

        $akhir_cuti = date_create_from_format('d-m-Y', $akhir_cuti);
        $akhir_cuti = date_format($akhir_cuti, 'Y-m-d');
        $akhir_cuti = strtotime($akhir_cuti);

        $haricuti = array();
        $sabtuminggu = array();

        for ($i=$awal_cuti; $i <= $akhir_cuti; $i += (60 * 60 * 24)) {
            if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                $haricuti[] = $i;
            } else {
                $sabtuminggu[] = $i;
            }

        }
        $jumlah_cuti = count($haricuti)-$jml_harilibur+1;
        $jumlah_sabtuminggu = count($sabtuminggu);
        $abtotal = $jumlah_cuti + $jumlah_sabtuminggu+$jml_harilibur;
        /*echo "<pre>";
        echo "<h1>Sistem Cuti Online</h1>";
        echo "<hr>";
        echo "Mulai Masuk : " . date('d-m-Y', $awal_cuti) . "<br>";
        echo "Terakhir Masuk : " . date('d-m-Y', $akhir_cuti) . "<br>";
        echo "Jumlah Hari Masuk : " . $jumlah_cuti ."<br>";
        echo "Jumlah Hari Libur Nasional : " . $jml_harilibur ."<br>";
        echo "Jumlah Sabtu/Minggu : " . $jumlah_sabtuminggu ."<br>";
        echo "Total Hari : " . $abtotal ."<br>";
        echo "<h1>Hari Kerja</h1>";
    echo "<hr>";
        foreach ($haricuti as $value) {
            echo date('d-m-Y', $value)  . " -> " . strftime("%A, %d %B %Y", date($value)) . "\n" . "<br>";
        }
        die;*/

        foreach ($absen as $absen){
            $sqlcuti="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(5,6,7,8,9,10,11) ";
            $datacuti=DB::connection()->select($sqlcuti);
            $cuti=0;
            if(!empty($datacuti)){
                $cuti=$datacuti[0]->lama;
            }

            $sqlizin="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(2,3,4,12,13,14,15,16,17,18) ";
            $dataizin=DB::connection()->select($sqlizin);
            $izin=0;
            if(!empty($dataizin)){
                $izin=$dataizin[0]->lama;
            }

            $sqlipg="select coalesce(sum(lama),0) as lama from t_permit WHERE p_karyawan_id=(select p_karyawan_id from  p_karyawan where nik='".$absen->nik."')
                      and tgl_awal>='".$tgl_awal."' and tgl_akhir<='".$tgl_akhir."' and m_jenis_ijin_id IN(1) ";
            $dataipg=DB::connection()->select($sqlipg);
            $ipg=0;
            if(!empty($dataipg)){
                $ipg=$dataipg[0]->lama;
            }

            DB::connection()->table("absen_temp")
                ->insert([
                    "nik" => $absen->nik,
                    "nama" => $absen->nama,
                    "periode" => $absen->periode_gajian,
                    "masuk" => $absen->jml_masuk,
                    "terlambat"=>$absen->terlambat,
                    "cuti"=>$cuti,
                    "izin"=>$izin,
                    "ipg"=>$ipg,
                    "a"=>$absen->tgl1,
                    "b"=>$absen->tgl2,
                    "c"=>$absen->tgl2,
                    "d"=>$absen->tgl4,
                    "e"=>$absen->tgl5,
                    "f"=>$absen->tgl6,
                    "g" => $absen->tgl7,
                    "h" => $absen->tgl8,
                    "i" => $absen->tgl9,
                    "j" => $absen->tgl10,
                    "k" => $absen->tgl11,
                    "l" => $absen->tgl12,
                    "m" => $absen->tgl13,
                    "n" => $absen->tgl14,
                    "o" => $absen->tgl15,
                    "p" => $absen->tgl16,
                    "q" => $absen->tgl17,
                    "r" => $absen->tgl18,
                    "s" => $absen->tgl19,
                    "t" => $absen->tgl20,
                    "u" => $absen->tgl21,
                    "v" => $absen->tgl22,
                    "w" => $absen->tgl23,
                    "x" => $absen->tgl24,
                    "y" => $absen->tgl25,
                    "z" => $absen->tgl26,
                    "aa" => $absen->tgl27,
                    "ab" => $absen->tgl28,
                    "ac" => $absen->tgl29,
                    "ad" => $absen->tgl30,
                    "ae" => $absen->tgl31,
                    "created_at" => date("Y-m-d"),
                    "jml_hari_kerja"=>$jumlah_cuti,
                    "jml_hari_libur"=>$jml_harilibur,
                    "total_hari"=>$abtotal,
                ]);
        }

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $tahun=date('Y');
        $bulan=date('mm');
        $periode_absen=$request->get('periode');

        $sqlperiode="SELECT m_periode_absen.*,
case when periode=1 then 'Januari'
when periode=2 then 'Februari'
when periode=3 then 'Maret'
when periode=4 then 'April'
when periode=5 then 'Mei'
when periode=6 then 'Juni'
when periode=7 then 'Juli'
when periode=8 then 'Agustus'
when periode=9 then 'September'
when periode=10 then 'Oktober'
when periode=11 then 'November'
when periode=12 then 'Desember' end as bulan,
case when type=0 then 'Pekanan' else 'Bulanan' end as tipe
FROM m_periode_absen WHERE tahun='".$tahun."'
ORDER BY tahun,periode";
        $periode=DB::connection()->select($sqlperiode);

        $jml=date('d',strtotime($tgl_akhir))-date('d',strtotime($tgl_awal))+1;
        if($jml==0){
            $jml=1;
        };
        //echo $jml;die;
        $sqlrekapabsen="select absen_temp.*, case when periode=0 then 'Pekanan' else 'Bulanan' end as periode_gaji from absen_temp order by nama";
        $rekapabsen=DB::connection()->select($sqlrekapabsen);

        $nama_file = 'Rekap Absen ' . date('d-m-Y', strtotime($tgl_awal)) . ':' . date('d-m-Y', strtotime($tgl_akhir));

        $param['rekapabsen'] = $rekapabsen;
        $param['jml'] = $jml;
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['days'] = $days;
        $param['tanggal_awal'] = $tanggal_awal;
        $param['tanggal_akhir'] = $tanggal_akhir;
        $param['periode'] = $periode;
        $param['tgl_awal'] = $tgl_awal;
        $param['tgl_akhir'] = $tgl_akhir;
        //echo $tanggal_awal;die;

        //return Excel::download(new absen_excel, $nama_file . '.xlsx');
        return Excel::download(new rekap_absen_xls($param), $nama_file . '.xlsx');
        //return view('backend.absen.absen_excel1',compact('rekapabsen','days','tanggal_awal','tanggal_akhir','tgl_awal','tgl_akhir','user'));

        /*$days=$tanggal_awal;
        //echo $days;die;
        if($days>31){
            $days1=1;
        }
        else{
            $days1=$days;
        }

        $days2=$days1+1;
        if($days2>31){
            $days2=1;
        }
        else{
            $days2=$days2;
        }

        $days3=$days2+1;
        if($days3>31){
            $days3=1;
        }
        else{
            $days3=$days3;
        }

        $days4=$days3+1;
        if($days4>31){
            $days4=1;
        }
        else{
            $days4=$days4;
        }

        $days5=$days4+1;
        if($days5>31){
            $days5=1;
        }
        else{
            $days5=$days5;
        }

        $days6=$days5+1;
        if($days6>31){
            $days6=1;
        }
        else{
            $days6=$days6;
        }

        $days7=$days6+1;
        if($days7>31){
            $days7=1;
        }
        else{
            $days7=$days7;
        }

        $days8=$days7+1;
        if($days8>31){
            $days8=1;
        }
        else{
            $days8=$days8;
        }

        $days9=$days8+1;
        if($days9>31){
            $days9=1;
        }
        else{
            $days9=$days9;
        }

        $days10=$days9+1;
        if($days10>31){
            $days10=1;
        }
        else{
            $days10=$days10;
        }

        $days11=$days10+1;
        if($days11>31){
            $days11=1;
        }
        else{
            $days11=$days11;
        }

        $days12=$days11+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days13=$days12+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days14=$days13+1;
        if($days14>31){
            $days14=1;
        }
        else{
            $days14=$days14;
        }

        $days15=$days14+1;
        if($days15>31){
            $days15=1;
        }
        else{
            $days15=$days15;
        }

        $days16=$days15+1;
        if($days16>31){
            $days16=1;
        }
        else{
            $days16=$days16;
        }

        $days17=$days16+1;
        if($days17>31){
            $days17=1;
        }
        else{
            $days17=$days17;
        }

        $days18=$days17+1;
        if($days18>31){
            $days18=1;
        }
        else{
            $days18=$days18;
        }

        $days19=$days18+1;
        if($days19>31){
            $days19=1;
        }
        else{
            $days19=$days19;
        }

        $days20=$days19+1;
        if($days20>31){
            $days20=1;
        }
        else{
            $days20=$days20;
        }

        $days21=$days20+1;
        if($days21>31){
            $days21=1;
        }
        else{
            $days21=$days21;
        }

        $days22=$days21+1;
        if($days22>31){
            $days22=1;
        }
        else{
            $days22=$days22;
        }

        $days23=$days22+1;
        if($days23>31){
            $days23=1;
        }
        else{
            $days23=$days23;
        }

        $days24=$days23+1;
        if($days24>31){
            $days24=1;
        }
        else{
            $days24=$days24;
        }

        $days25=$days24+1;
        if($days25>31){
            $days25=1;
        }
        else{
            $days25=$days25;
        }

        $days26=$days25+1;
        if($days26>31){
            $days25=1;
        }
        else{
            $days26=$days26;
        }

        $days27=$days26+1;
        if($days27>31){
            $days27=1;
        }
        else{
            $days27=$days27;
        }

        $days28=$days27+1;
        if($days28>31){
            $days28=1;
        }
        else{
            $days28=$days28;
        }

        $days29=$days28+1;
        if($days29>31){
            $days29=1;
        }
        else{
            $days29=$days29;
        }

        $days30=$days29+1;
        if($days30>31){
            $days30=1;
        }
        else{
            $days30=$days30;
        }

        $days31=$days30+1;
        if($days31>31){
            $days31=1;
        }
        else{
            $days31=$days31;
        }

        $absenArray[] = ['No','NIK', 'Nama','Periode Gajian','Total Hari','Jumlah Hari Masuk','Jumlah Libur','Absen Masuk', 'Terlambat',
            $days1,$days2,$days3,$days4,$days5,$days6,$days7,$days8,$days9,$days10,$days11,$days12,$days13,$days14,$days15,
            $days16,$days17,$days18,$days19,$days20,$days21,$days22,$days23,$days24,$days25,$days26,$days27,$days28,
            $days29,$days30,$days31];
        $no=0;
        foreach ($rekapabsen as $k) {
            $no++;
            $absenArray[] = array(
                'no' => $no,
                'nik' => $k->nik,
                'nama' => $k->nama,
                'periode' => $k->periode_gaji,
                'totalhari' => $k->total_hari,
                'jumlahharikerja' => $k->jml_hari_kerja,
                'jumlahharilibur' => $k->jml_hari_libur,
                'masuk' => $k->masuk,
                'terlambat' => $k->terlambat,
                'a' => $k->a,
                'b' => $k->a,
                'c' => $k->a,
                'd' => $k->a,
                'e' => $k->a,
                'f' => $k->a,
                'g' => $k->a,
                'h' => $k->a,
                'i' => $k->a,
                'j' => $k->a,
                'k' => $k->a,
                'l' => $k->a,
                'm' => $k->a,
                'n' => $k->a,
                'o' => $k->a,
                'p' => $k->a,
                'q' => $k->a,
                'r' => $k->a,
                's' => $k->a,
                't' => $k->a,
                'u' => $k->a,
                'v' => $k->a,
                'w' => $k->a,
                'x' => $k->a,
                'y' => $k->a,
                'z' => $k->a,
                'aa' => $k->a,
                'ab' => $k->a,
                'ac' => $k->a,
                'ad' => $k->a,
                'ae' => $k->a,
            );
        }

        // Generate and return the spreadsheet
        Excel::create('Absen Bulan - '.$bulan.' - '.$tahun, function($excel) use ($absenArray) {
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Export');
            $excel->setCreator('-')->setCompany('ES-iOS');
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('Absen', function($sheet) use ($absenArray) {
                $sheet->setFontSize(12);
                $sheet->fromArray($absenArray, null, 'A1', false, false);
            });
        })->download('xlsx');
        */
    }

    public function excel(Request $request){
        //echo 'masuk';die;
        $periode_absen=$request->get('periode_gajian');

        DB::connection()->table("absen_temp")
            ->delete();

        $sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
        $periode=DB::connection()->select($sqlperiode);

        $periode_gajian=$periode[0]->type;
        $tgl_awal=date('Y-m-d',strtotime($periode[0]->tgl_awal));
        $tgl_akhir=date('Y-m-d',strtotime($periode[0]->tgl_akhir));
        $tanggal_awal=date('d',strtotime($periode[0]->tgl_awal));
        $tanggal_akhir=date('d',strtotime($periode[0]->tgl_akhir));
        //echo $tgl_akhir-$tgl_awal;die;
        //$d = $tgl_akhir->diff($tgl_awal)->days + 1;
        $datetime1 = new DateTime($tgl_awal);
        $datetime2 = new DateTime($tgl_akhir);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');//now do whatever you like with $days
        //echo $days;die;
        //$awal=$tanggal_awal;
        /*for($i=$tanggal_awal;$i<$days;$i++){
            $days=$days++;
            echo $days;
        }die;*/

        $days=$tanggal_awal;
        if($days>31){
            $days1=1;
        }
        else{
            $days1=$days;
        }

        $days2=$days1+1;
        if($days2>31){
            $days2=1;
        }
        else{
            $days2=$days2;
        }

        $days3=$days2+1;
        if($days3>31){
            $days3=1;
        }
        else{
            $days3=$days3;
        }

        $days4=$days3+1;
        if($days4>31){
            $days4=1;
        }
        else{
            $days4=$days4;
        }

        $days5=$days4+1;
        if($days5>31){
            $days5=1;
        }
        else{
            $days5=$days5;
        }

        $days6=$days5+1;
        if($days6>31){
            $days6=1;
        }
        else{
            $days6=$days6;
        }

        $days7=$days6+1;
        if($days7>31){
            $days7=1;
        }
        else{
            $days7=$days7;
        }

        $days8=$days7+1;
        if($days8>31){
            $days8=1;
        }
        else{
            $days8=$days8;
        }

        $days9=$days8+1;
        if($days9>31){
            $days9=1;
        }
        else{
            $days9=$days9;
        }

        $days10=$days9+1;
        if($days10>31){
            $days10=1;
        }
        else{
            $days10=$days10;
        }

        $days11=$days10+1;
        if($days11>31){
            $days11=1;
        }
        else{
            $days11=$days11;
        }

        $days12=$days11+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days13=$days12+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days14=$days13+1;
        if($days14>31){
            $days14=1;
        }
        else{
            $days14=$days14;
        }

        $days15=$days14+1;
        if($days15>31){
            $days15=1;
        }
        else{
            $days15=$days15;
        }

        $days16=$days15+1;
        if($days16>31){
            $days16=1;
        }
        else{
            $days16=$days16;
        }

        $days17=$days16+1;
        if($days17>31){
            $days17=1;
        }
        else{
            $days17=$days17;
        }

        $days18=$days17+1;
        if($days18>31){
            $days18=1;
        }
        else{
            $days18=$days18;
        }

        $days19=$days18+1;
        if($days19>31){
            $days19=1;
        }
        else{
            $days19=$days19;
        }

        $days20=$days19+1;
        if($days20>31){
            $days20=1;
        }
        else{
            $days20=$days20;
        }

        $days21=$days20+1;
        if($days21>31){
            $days21=1;
        }
        else{
            $days21=$days21;
        }

        $days22=$days21+1;
        if($days22>31){
            $days22=1;
        }
        else{
            $days22=$days22;
        }

        $days23=$days22+1;
        if($days23>31){
            $days23=1;
        }
        else{
            $days23=$days23;
        }

        $days24=$days23+1;
        if($days24>31){
            $days24=1;
        }
        else{
            $days24=$days24;
        }

        $days25=$days24+1;
        if($days25>31){
            $days25=1;
        }
        else{
            $days25=$days25;
        }

        $days26=$days25+1;
        if($days26>31){
            $days25=1;
        }
        else{
            $days26=$days26;
        }

        $days27=$days26+1;
        if($days27>31){
            $days27=1;
        }
        else{
            $days27=$days27;
        }

        $days28=$days27+1;
        if($days28>31){
            $days28=1;
        }
        else{
            $days28=$days28;
        }

        $days29=$days28+1;
        if($days29>31){
            $days29=1;
        }
        else{
            $days29=$days29;
        }

        $days30=$days29+1;
        if($days30>31){
            $days30=1;
        }
        else{
            $days30=$days30;
        }

        $days31=$days30+1;
        if($days31>31){
            $days31=1;
        }
        else{
            $days31=$days31;
        }

        $sqlabsen="with hole1 as(
with hole as(
SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'yyyy-mm-dd')::date as tgl_absen,
to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jam_keluar as jam_keluar,case when to_char(date_time,'HH24:MI')>'07:30' then 1 else 0 end as telat
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
GROUP BY a.pin,c.nik,c.nama,a.date_time,jk.jam_keluar
ORDER BY nama,to_char(a.date_time,'yyyy-mm-dd')::date)
SELECT pin,tgl_absen,coalesce(jam_masuk,'00:00:00')||'-'||coalesce(jam_keluar,'00:00:00') as jam_absen,telat
FROM hole)
select pin,nik,nama,case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as periode_gaji,periode_gajian,
count(pin) as jml_masuk, sum(telat) as terlambat, 
max(case when date_part('day', tgl_absen)=".$days." then  jam_absen else '00:00:00' end) tgl1,
max(case when date_part('day', tgl_absen)=".$days2." then  jam_absen else '00:00:00' end) tgl2,
max(case when date_part('day', tgl_absen)=".$days3." then  jam_absen else '00:00:00' end) tgl3,
max(case when date_part('day', tgl_absen)=".$days4." then  jam_absen else '00:00:00' end) tgl4,
max(case when date_part('day', tgl_absen)=".$days5." then  jam_absen else '00:00:00' end) tgl5,
max(case when date_part('day', tgl_absen)=".$days6." then  jam_absen else '00:00:00' end) tgl6,
max(case when date_part('day', tgl_absen)=".$days7." then  jam_absen else '00:00:00' end) tgl7,
max(case when date_part('day', tgl_absen)=".$days8." then  jam_absen else '00:00:00' end) tgl8,
max(case when date_part('day', tgl_absen)=".$days9." then  jam_absen else '00:00:00' end) tgl9,
max(case when date_part('day', tgl_absen)=".$days10." then  jam_absen else '00:00:00' end) tgl10,
max(case when date_part('day', tgl_absen)=".$days11." then  jam_absen else '00:00:00' end) tgl11,
max(case when date_part('day', tgl_absen)=".$days12." then  jam_absen else '00:00:00' end) tgl12,
max(case when date_part('day', tgl_absen)=".$days13." then  jam_absen else '00:00:00' end) tgl13,
max(case when date_part('day', tgl_absen)=".$days14." then  jam_absen else '00:00:00' end) tgl14,
max(case when date_part('day', tgl_absen)=".$days15." then  jam_absen else '00:00:00' end) tgl15,
max(case when date_part('day', tgl_absen)=".$days16." then  jam_absen else '00:00:00' end) tgl16,
max(case when date_part('day', tgl_absen)=".$days17." then  jam_absen else '00:00:00' end) tgl17,
max(case when date_part('day', tgl_absen)=".$days18." then  jam_absen else '00:00:00' end) tgl18,
max(case when date_part('day', tgl_absen)=".$days19." then  jam_absen else '00:00:00' end) tgl19,
max(case when date_part('day', tgl_absen)=".$days20." then  jam_absen else '00:00:00' end) tgl20,
max(case when date_part('day', tgl_absen)=".$days21." then  jam_absen else '00:00:00' end) tgl21,
max(case when date_part('day', tgl_absen)=".$days22." then  jam_absen else '00:00:00' end) tgl22,
max(case when date_part('day', tgl_absen)=".$days23." then  jam_absen else '00:00:00' end) tgl23,
max(case when date_part('day', tgl_absen)=".$days24." then  jam_absen else '00:00:00' end) tgl24,
max(case when date_part('day', tgl_absen)=".$days25." then  jam_absen else '00:00:00' end) tgl25,
max(case when date_part('day', tgl_absen)=".$days26." then  jam_absen else '00:00:00' end) tgl26,
max(case when date_part('day', tgl_absen)=".$days27." then  jam_absen else '00:00:00' end) tgl27,
max(case when date_part('day', tgl_absen)=".$days28." then  jam_absen else '00:00:00' end) tgl28,
max(case when date_part('day', tgl_absen)=".$days29." then  jam_absen else '00:00:00' end) tgl29,
max(case when date_part('day', tgl_absen)=".$days30." then  jam_absen else '00:00:00' end) tgl30,
max(case when date_part('day', tgl_absen)=".$days31." then  jam_absen else '00:00:00' end) tgl31
from hole1
left join p_karyawan_absen b on b.no_absen=hole1.pin
left join p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
left join p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
where 1=1 and periode_gajian=$periode_gajian
and d.m_lokasi_id NOT IN(5)
GROUP BY pin,nik,nama,periode_gajian
ORDER BY c.nama";
        //echo $sqlabsen;die;
        $absen=DB::connection()->select($sqlabsen);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

// sheet peratama
        $sheet->setTitle('Sheet 1');
        $sheet->setCellValue('A1', 'First Name');
        $sheet->setCellValue('B1', 'Last Name');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');
    }

    public function excels(Request $request){
        //echo 'masuk';die;
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("HRMS");
        $PageNo=0;
        $Col='A';
        $ColStart='A';
        $RowExcel=0;

        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($PageNo);
        $Sheet=$objPHPExcel->getActiveSheet();
        $Sheet->setTitle('Lap Absen');
        $Sheet->getColumnDimension('A')->setAutoSize(true);
        $Sheet->getColumnDimension('B')->setAutoSize(true);
        $Sheet->getColumnDimension('C')->setAutoSize(true);
        $Sheet->getColumnDimension('D')->setAutoSize(true);
        $Sheet->getColumnDimension('E')->setAutoSize(true);
        $Sheet->getColumnDimension('F')->setAutoSize(true);
        $Sheet->getColumnDimension('G')->setAutoSize(true);

        //HEADER
        $RowExcel++;$ColTmp=$Col;$RowExcelHeader=$RowExcel;
        $Sheet->setCellValue($Col.$RowExcel, 'KODE PRODUK');
        $Col++; $Sheet->setCellValue($Col.$RowExcel, 'NAMA PRODUK');
        $Col++; $Sheet->setCellValue($Col.$RowExcel, 'QTY');
//$Col++; $Sheet->setCellValue($Col.$RowExcel, 'HPP');
//$Col++; $Sheet->setCellValue($Col.$RowExcel, 'TOTAL HPP');
        $Col++; $Sheet->setCellValue($Col.$RowExcel, 'NAMA LOKATOR');
        $Col++; $Sheet->setCellValue($Col.$RowExcel, 'NAMA GUDANG');
        $Sheet->getStyle($ColTmp.$RowExcel.':'.$Col.$RowExcel)->applyFromArray(array(
            "font"=> array( "bold" => true),
            "alignment" => array("horizontal" => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                "vertical" => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        ));

        ob_clean();
        ob_start();

// Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Lap Absen');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="lap_absen.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function cek_absen(Request $request)
    {
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $nama='';
        $tgl_awal=date('Y-m-d',strtotime($request->get('tgl_awal')));
        $tgl_akhir=date('Y-m-d',strtotime($request->get('tgl_akhir')));
        
         $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND p_karyawan_pekerjaan.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
        
        $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 $whereLokasi order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers);

        $iduser=Auth::user()->id;
       

        return view('backend.absen.cek_absen',compact('id','users','tgl_awal','tgl_akhir','user','nama'));
    }

    public function edit_cek_absen_hr(Request $request,$id)
    {
    	$list_karyawan = "SELECT f.nama,a.mesin_id,a.date_time
					FROM absen_log a
					
					LEFT JOIN p_karyawan_absen c on a.pin=c.no_absen
					LEFT JOIN p_karyawan f on f.p_karyawan_id=c.p_karyawan_id
					LEFT JOIN p_karyawan_pekerjaan b on c.p_karyawan_id=b.p_karyawan_id

					WHERE absen_log_id='$id'";
		$list_karyawan=DB::connection()->select($list_karyawan);
		$info = $list_karyawan[0];
		$sql = "Select * from m_mesin_absen";
		$dmesin	=DB::connection()->select($sql);
		
    	$help = new Helper_function();
    	 $iduser=Auth::user()->id;
        $sqluser="SELECT *  FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
			where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		return view('backend.absen.edit_absen_hr',compact('id','help','info','dmesin','user'));
    }public function hapus_cari_absen_hr(Request $request,$id)
    {
    	 $idUser=Auth::user()->id;
    	DB::connection()->table("absen_log")
		                ->where("absen_log_id",$id)
		                ->update([
		                    
		                    "status_absen_id" => 3,
		                    "active" => 0,
		                    "updated_at" => date('Y-m-d H:i:s'),
		                    "updated_by"=>$idUser,
		                     ]);
    	return redirect()->route('be.cek_cari_absen')->with('success',' Absen Karyawan Berhasil di input!');
    }public function simpan_cek_absen_hr(Request $request,$id)
    {
    	
		try{
        	

           
			
           		 $sql="SELECT * FROM absen_log "
            	
            	."where absen_log_id = $id"
            	;
            	$data=DB::connection()->select($sql);
            	//print_r($data);
            	
            	if(count($data)){
            	 $date_time_awal=date('Y-m-d',strtotime($request->get('tgl_absen'))).' '.$request->get('jam_masuk').'';
            	 
        	
            		 DB::connection()->table("absen_log")
		                ->where("absen_log_id",$id)
		                ->update([
		                    "mesin_id" => $request->get('mesin'),
		                    "date_time" => $date_time_awal,
		                    "status_absen_id" => 3,
		                    "time_before_update" => $data[0]->date_time,
		                    "updated_at" => date('Y-m-d H:i:s'),
		                    "updated_by"=>$idUser,
		                     ]);
				}
				
			
			
            return redirect()->route('be.cek_cari_absen')->with('success',' Absen Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
         
            return redirect()->back()->with('error',$e);
        }
    	
    }
	public function cari_cek_absen_hr(Request $request)
    {
    	$help = new Helper_function();
    	 $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       $id = null;
        //$id=$idkar[0]->p_karyawan_id;
        //echo 'masuk';die;
        $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers);
        $tgl_awal=date('Y-m-d',strtotime($request->get('tgl_awal')));
        $tgl_akhir=date('Y-m-d',strtotime($request->get('tgl_akhir')));
        
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
			where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$nama=($request->get('nama'));
    	$rekap = array();
    	$list_karyawan = array();
    	$mesin = array();
    	if($tgl_awal>='2000-01-01'){
			die;
    	$sqlabsen = "select * ,(select jam_masuk from absen where absen.tgl_awal<=a.date_time and  absen.tgl_akhir>=a.date_time and absen.m_lokasi_id = c.m_lokasi_id limit 1) as jam_masuk,users.name as updated_by
		from absen_log a 
		left join p_karyawan_absen b on b.no_absen = a.pin  
		left join p_karyawan_pekerjaan c on b.p_karyawan_id = c.p_karyawan_id  
		left join users  on a.updated_by = users.id  
		
		where a.date_time>='$tgl_awal' and a.date_time<='$tgl_akhir 23:59:59' and c.p_karyawan_id = $nama";
		$rekap = array();
		
// 		$absen=DB::connection()->select($sqlabsen);
// 		foreach($absen as $absen){
// 			$date = date('Y-m-d',strtotime($absen->date_time));
// 			$time = date('H:i:s',strtotime($absen->date_time));
// 			$time2 = date('H:i:s',strtotime($absen->date_time));
// 			if($absen->ver==1){
				
// 				$rekap[$absen->p_karyawan_id][$date]['a']['masuk'] = $time;
					
// 				if(!isset($rekap[$absen->p_karyawan_id]['total']['absen_masuk']))
// 				$rekap[$absen->p_karyawan_id]['total']['absen_masuk'] = 1;
// 				else
// 				$rekap[$absen->p_karyawan_id]['total']['absen_masuk'] += 1;
				
// 				$lokasi_id = $absen->m_lokasi_id;	
				
// 				if($time> $absen->jam_masuk){
					
// 					$rekap[$absen->p_karyawan_id][$date]['a']['terlambat'] = 1;
// 					if(isset($rekap[$absen->p_karyawan_id]['total']['terlambat']))
// 					$rekap[$absen->p_karyawan_id]['total']['terlambat'] += 1;
// 					else
// 					$rekap[$absen->p_karyawan_id]['total']['terlambat'] = 1;
// 				}
// 				else	
// 				$rekap[$absen->p_karyawan_id][$date]['a']['terlambat'] = 0;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['status_masuk'] = $absen->status_absen_id;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['updated_at_masuk'] = $absen->updated_at;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['updated_by_masuk'] = $absen->updated_by;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['time_before_update_masuk'] = $absen->time_before_update;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['mesin_id'] = $absen->mesin_id;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['absen_log_id_masuk'] = $absen->absen_log_id;
				
// 			}
// 			else if($absen->ver==2){
// 			$rekap[$absen->p_karyawan_id][$date]['a']['keluar'] = $time;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['absen_log_id_keluar'] = $absen->absen_log_id;
			
// 			$rekap[$absen->p_karyawan_id][$date]['a']['status_keluar'] = $absen->status_absen_id;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['updated_at_keluar'] = $absen->updated_at;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['updated_by_keluar'] = $absen->updated_by;
// 			$rekap[$absen->p_karyawan_id][$date]['a']['time_before_update_keluar'] = $absen->time_before_update;
				
// 			}
			
// 		}
// 		$sqllembur = "Select m_jenis_ijin.nama as nama_ijin,m_jenis_ijin.tipe,t_permit.m_jenis_ijin_id,t_permit.lama,t_permit.keterangan,t_permit.p_karyawan_id,t_permit.tgl_awal,t_permit.tgl_akhir,t_permit.jam_awal
// 		 from t_permit 
// 		 join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id 
// 		where ((t_permit.tgl_awal>='$tgl_awal' and t_permit.tgl_awal<='$tgl_akhir 23:59') or 
// 					(t_permit.tgl_akhir>='$tgl_awal' and t_permit.tgl_akhir<='$tgl_akhir 23:59')) and 
// 			t_permit.p_karyawan_id = $nama
// 			and t_permit.active=1
// 			ORDER BY t_permit.p_karyawan_id asc";
// 		$lembur=DB::connection()->select($sqllembur);
// 		$karyawan = array();
		
// 		//var_dump($lembur); die;
// 		foreach($lembur as $lembur){
// 			if(!in_array($lembur->p_karyawan_id,$karyawan))
// 			$karyawan[] = $lembur->p_karyawan_id; 
			
// 			$date = $lembur->tgl_awal;
// 			for($i = 0; $i <= $help->hitunghari($lembur->tgl_awal,$lembur->tgl_akhir); $i++){
// 				$rekap[$lembur->p_karyawan_id][$date]['ci']['nama_ijin'] = $lembur->nama_ijin;
// 				$rekap[$lembur->p_karyawan_id][$date]['ci']['keterangan'] = $lembur->keterangan;
// 				$rekap[$lembur->p_karyawan_id][$date]['ci']['lama'] = $lembur->lama;
// 				$rekap[$lembur->p_karyawan_id][$date]['ci']['jam_awal'] = $lembur->jam_awal;
// 				$rekap[$lembur->p_karyawan_id][$date]['ci']['tipe'] = $lembur->tipe; 
// 				$rekap[$lembur->p_karyawan_id][$date]['ci']['m_jenis_ijin_id'] = $lembur->m_jenis_ijin_id; 
// 				$date = $help->tambah_tanggal($date,1);
				
					
// 			}
			
// 		}
		
// 			$id_lokasi = Auth::user()->user_entitas;
//         if($id_lokasi and $id_lokasi!=-1) 
// 			$whereLokasi = "AND b.m_lokasi_id in($id_lokasi)";					
// 		else
// 			$whereLokasi = "";	
// 		$list_karyawan = "SELECT c.no_absen as pin,d.mesin_id,b.p_karyawan_id
// 					FROM p_karyawan_pekerjaan b
					
// 					LEFT JOIN p_karyawan_absen c on c.p_karyawan_id=b.p_karyawan_id
// 					LEFT JOIN m_mesin_absen d on d.m_lokasi_id=b.m_lokasi_id

// 					WHERE b.p_karyawan_id='$nama' $whereLokasi";
// 		$list_karyawan=DB::connection()->select($list_karyawan);
// 		if(count($list_karyawan))
// 			$list_karyawan = $list_karyawan[0];
// 		$sql = "Select * from m_mesin_absen";
// 		$dmesin	=DB::connection()->select($sql);
// 		foreach($dmesin as $dmesin){
// 			$mesin[$dmesin->mesin_id]=$dmesin->nama;
// 		}
        $rekap = $help->rekap_absen($tgl_awal,$tgl_akhir,$tgl_awal,$tgl_akhir);
    		
    		$list_karyawan = $rekap['list_karyawan'] ;
    		
    		$hari_libur = $rekap['hari_libur'] ;
    		$hari_libur_shift = $rekap['hari_libur_shift'] ;
    		$tgl_awal_lembur = $rekap['tgl_awal_lembur'] ;
    		$tgl_awal = $rekap['tgl_awal'] ;
    		$tgl_akhir = $rekap['tgl_akhir'] ;
    		$mesin                 = $rekap['mesin'];
		
		}
    	if($request->get('Cari')=='Cari'){
        	$sqllistabsen = "";
        	
        	return view('backend.absen.cek_absen_hr',compact('id','users','tgl_awal','tgl_akhir','user','nama','rekap','help','list_karyawan','mesin'));
           
    	
		}else if($request->get('Cari')=='Cari'){
           
            
		}else{
        	return view('backend.absen.cek_absen_hr',compact('id','users','tgl_awal','tgl_akhir','user','nama','rekap','help','list_karyawan','mesin'));
			
		}
        
    }
	public function cari_cek_absen(Request $request)
    {
    	$help = new Helper_function();
    	 $iduser=Auth::user()->id;
       // $sqlidkar="select * from p_karyawan where user_id=$iduser";
       // $idkar=DB::connection()->select($sqlidkar);
       // $id=$idkar[0]->p_karyawan_id;
       $id =null;
        //echo 'masuk';die;
        	$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND p_karyawan_pekerjaan.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
        $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 $whereLokasi and p_karyawan.active=1 order by p_karyawan.nama";
        $users=DB::connection()->select($sqlusers);
        $tgl_awal=date('Y-m-d',strtotime($request->get('tgl_awal')));
        $tgl_akhir=date('Y-m-d',strtotime($request->get('tgl_akhir')));
        if($tgl_akhir=='1970-01-01'){
			$tgl_akhir =date('Y-m-d');
		}
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
			where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$nama=($request->get('nama'));
    	$rekap = array();
    	$list_karyawan = array();
    	$mesin = array();
    	if($tgl_awal>='2015-01-01' ){
    	    if($nama){
			$sqlusers="SELECT c.periode_gajian FROM p_karyawan_pekerjaan c
                WHERE 1=1 and c.active=1 and p_karyawan_id=$nama";
	        $search_type=DB::connection()->select($sqlusers);
	        $type = $search_type[0]->periode_gajian;
			$rekap = $help->rekap_absen($tgl_awal,$tgl_akhir,$tgl_awal,$tgl_akhir,$type,null,$nama,null,$request);
			$list_karyawan = $rekap['list_karyawan'] ;
			//print_r($list_karyawan);
			//die;
			$hari_libur = $rekap['hari_libur'] ;
			$hari_libur_shift = $rekap['hari_libur_shift'] ;
			$mesin = $rekap['mesin'] ;
    		$tanggallibur = $rekap['tanggallibur'] ;
			
			}else{
            $rekap = $help->rekap_absen($tgl_awal,$tgl_akhir,$tgl_awal,$tgl_akhir,-1,null,null,null,$request);
    		
    		$list_karyawan = $rekap['list_karyawan'] ;
    		
    		$hari_libur = $rekap['hari_libur'] ;
    		$hari_libur_shift = $rekap['hari_libur_shift'] ;
    		$tgl_awal_lembur = $rekap['tgl_awal_lembur'] ;
    		$tgl_awal = $rekap['tgl_awal'] ;
    		$tgl_akhir = $rekap['tgl_akhir'] ;
    		$mesin                 = $rekap['mesin'];
    		$tanggallibur = $rekap['tanggallibur'] ;
    	    }
		
		}
		$sql = "Select * from m_mesin_absen";
		$dmesin	=DB::connection()->select($sql);
		foreach($dmesin as $dmesin){
			$mesin[$dmesin->mesin_id]=$dmesin->nama;
		}
		$entitas= DB::connection()->select('select * from m_lokasi where active=1 and sub_entitas=0');  
		$sql = "Select * from m_mesin_absen ";
        $list_mesin    = DB::connection()->select($sql);
    	if($request->get('Cari')=='Cari'){
        	$sqllistabsen = "";
        	
        	return view('backend.absen.cek_absen_2',compact('id','entitas','request','list_mesin','users','tgl_awal','tgl_akhir','user','nama','rekap','help','list_karyawan','mesin','hari_libur','hari_libur','tanggallibur','hari_libur_shift'));
           
    	
		}else{
        	return view('backend.absen.cek_absen_2',compact('id','entitas','request','list_mesin','users','tgl_awal','tgl_akhir','user','nama','rekap','help','list_karyawan','mesin'));
			
		}
        
    }

    public function cari_cek_absenLAST(Request $request)
    {

        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        //echo 'masuk';die;
        if($request->get('Cari')=='Cari'){
            $tgl_awal=date('Y-m-d',strtotime($request->get('tgl_awal')));
            $tgl_akhir=date('Y-m-d',strtotime($request->get('tgl_akhir')));
            $nama=($request->get('nama'));
            $sqlabsen="SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jk.jam_keluar,
case when to_char(date_time,'HH24:MI')>'07:31:00' and e.m_lokasi_id<>6 then 'Terlambat' 
when to_char(date_time,'HH24:MI')>'08:31:00' and e.m_lokasi_id=6 then 'Terlambat' 
else 'OK' end as keterangan,f.nama as nmlokasi
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
and c.p_karyawan_id=$nama
ORDER BY to_char(a.date_time,'dd-mm-yyyy') desc";
            $absen=DB::connection()->select($sqlabsen);
            //echo $sqlabsen;die;

            $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
            $user=DB::connection()->select($sqluser);

            $sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 order by p_karyawan.nama";
            $users=DB::connection()->select($sqlusers);

            return view('backend.absen.cek_absen',compact('absen','tgl_awal','tgl_akhir','user','nama','users'));
        }
        else if($request->get('Cari')=='Excel'){
            $tgl_awal=date('Y-m-d',strtotime($request->get('tgl_awal')));
            $tgl_akhir=date('Y-m-d',strtotime($request->get('tgl_akhir')));
            $nama=$request->get('nama');
            $nama_file = 'Absen '.date('d-m-Y',strtotime($tgl_awal)).':'.date('d-m-Y',strtotime($tgl_akhir));

            $sqlabsen="SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_masuk,jk.jam_keluar,
case when to_char(date_time,'HH24:MI')>'07:31:00' and e.m_lokasi_id<>6 then 'Terlambat' 
when to_char(date_time,'HH24:MI')>'08:31:00' and e.m_lokasi_id=6 then 'Terlambat' 
else 'OK' end as keterangan,f.nama as nmlokasi, 
case when d.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as periode_gaji
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
LEFT JOIN (SELECT DISTINCT a.pin,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,to_char(a.date_time,'yyyy-mm-dd') as tgl
FROM absen_log a
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=2) as jk on jk.pin=a.pin
and jk.tgl=to_char(a.date_time,'yyyy-mm-dd')
WHERE to_char(a.date_time,'yyyy-mm-dd')>='".$tgl_awal."' and to_char(a.date_time,'yyyy-mm-dd')<='".$tgl_akhir."' and a.ver=1
and c.p_karyawan_id=$nama
ORDER BY to_char(a.date_time,'dd-mm-yyyy') desc";
            $absen=DB::connection()->select($sqlabsen);

            $param['absen'] = $absen;
            return Excel::download(new cek_absen_xls($param), $nama_file. '.xlsx');
        }
    }
}
