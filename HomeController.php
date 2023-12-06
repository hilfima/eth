<?php

namespace App\Http\Controllers;

use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use Hash;
use Database;

use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;

class HomeController extends Controller
{
    /*
     *
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function form_biodata_kandidat(Request $request)
    {
        return view('form_biodata_kandidat',compact('request'));
    }
    public function undermaintenance()
    {
    	
        return view('undermaintenance');
    }
    public function index()
    {
        $all = array();
        $help = new Helper_function();
        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role FROM users
        
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";

        $user = DB::connection()->select($sqluser);
        //print_r($user);die;
        if ($user[0]->role == -1) {
        } else if ($user[0]->role != 2) {
            return redirect()->route('admin');
        }
        if ($user[0]->foto == null) {
            //echo 'masuk';die;
            $foto = 'user.png';
        } else {
            //echo 'masuk212';die;
            $foto = $user[0]->foto;
        }
		
        $sqlidkar = "select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan 
        			left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
        			left join m_jabatan on m_jabatan.m_jabatan_id = a.m_jabatan_id
        			left join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_kontrak.active=1
        			left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
        			where user_id=$iduser";
        $idkar = DB::connection()->select($sqlidkar);
        $karyawan = $idkar[0];
        $him = $idkar[0]->karyawan_id;
        $id = $idkar[0]->karyawan_id;
        //echo print_r($idkar);die;
        

        
		
        //	print_r($data);
        $sqlnoabsen = "select * from p_karyawan_absen where p_karyawan_id=$id";
        $datanoabsen = DB::connection()->select($sqlnoabsen);
        $noabsen = $datanoabsen[0]->no_absen;

        /*$sqlstatus="select * from p_karyawan_kontrak where p_karyawan_id=$id";
        $datastatus=DB::connection()->select($sqlstatus);
        $status=$datastatus[0]->m_jenis_pekerjaan_id;*/

        $sqlpa = "select m_jabatan.m_pangkat_id from p_karyawan_pekerjaan 
LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
where p_karyawan_id=$id

";
        //echo $sqlpa;die;
        $pa = DB::connection()->select($sqlpa);
        $pangkat = 0;
        if (!empty($pa['m_pangkat_id'][0])) {
            $pangkat = $pa['m_pangkat_id'][0];
        }

        $tgl_awal = date('Y-m-d');
        $tgl_akhir = date('Y-m-d');
        $sqlabsen = "SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_masuk,
case when to_char(date_time,'HH24:MI')>'07:30' then 'Terlambat' else 'OK' end as keterangan,f.nama as nmlokasi
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
WHERE to_char(a.date_time,'yyyy-mm-dd')>='" . $tgl_awal . "' and to_char(a.date_time,'yyyy-mm-dd')<='" . $tgl_akhir . "' and a.ver=1 
and to_char(date_time,'HH24:MI')>'07:30'
ORDER BY nama,to_char(a.date_time,'dd-mm-yyyy'),nmlokasi";
        $absen = DB::connection()->select($sqlabsen);

        $sqlabsenin = "SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_masuk,
case when to_char(date_time,'HH24:MI')>'07:30' then 'Terlambat' else 'OK' end as keterangan,f.nama as nmlokasi
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
WHERE to_char(a.date_time,'yyyy-mm-dd')='" . $tgl_awal . "'  and a.ver=1 
and a.pin=$noabsen
ORDER BY nama,to_char(a.date_time,'dd-mm-yyyy'),nmlokasi";
        $absenin = DB::connection()->select($sqlabsenin);

        $sqlabsenout = "SELECT DISTINCT a.pin,c.nik,c.nama,to_char(a.date_time,'dd-mm-yyyy') as tgl_absen,to_char(a.date_time,'HH24:MI:SS') as jam_keluar,f.nama as nmlokasi
FROM absen_log a
LEFT JOIN p_karyawan_absen b on b.no_absen=a.pin
LEFT JOIN p_karyawan c on c.p_karyawan_id=b.p_karyawan_id
LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id
LEFT JOIN m_lokasi e on e.m_lokasi_id=d.m_lokasi_id
LEFT JOIN m_mesin_absen f on f.mesin_id=a.mesin_id
WHERE to_char(a.date_time,'yyyy-mm-dd')='" . $tgl_awal . "'  and a.ver=2 
and a.pin=$noabsen
ORDER BY nama,to_char(a.date_time,'dd-mm-yyyy'),nmlokasi";
        $absenout = DB::connection()->select($sqlabsenout);


       

        $sqlultah = "SELECT nama_Lengkap,tgl_lahir, EXTRACT(year FROM AGE(now(), tgl_lahir)) as usia FROM p_recruitment 
        			join p_karyawan on p_karyawan.p_recruitment_id = p_recruitment.p_recruitment_id 
                    WHERE to_char(tgl_lahir,'mm') = to_char(now(),'mm') and to_char(tgl_lahir,'dd') = to_char(now(),'dd') and p_karyawan.active=1 ORDER BY to_char(tgl_lahir,'%d %m'),tgl_lahir ";
        $ultah = DB::connection()->select($sqlultah);

        $sql = "SELECT * from m_qoute where tgl_awal<='" . date('Y-m-d') . "' and tgl_akhir>='" . date('Y-m-d') . "' order by  random() ";
        $qoute = DB::connection()->select($sql);

        //$tahun=date('Y');
        //$sqlcuti="SELECT * FROM get_list_cuti_tahunan_new($tahun,$id) ";
        //$cuti=DB::connection()->select($sqlcuti);

        $sqlumurkerja = "SELECT AGE(CURRENT_DATE, p_karyawan.tgl_bergabung)::VARCHAR AS umur FROM p_karyawan where p_karyawan_id=$id";
        $umurkerja = DB::connection()->select($sqlumurkerja);

        $sqldw = "SELECT * FROM get_data_karyawan() WHERE p_karyawan_id=$id";
        $dw = DB::connection()->select($sqldw);


        date_default_timezone_set('Asia/Jakarta');
        $usersave = User::find(Auth::user()->id);
        $usersave->last_login = date('Y-m-d H:i:s');
        $usersave->save();
       


        $id_jabatan = $idkar[0]->m_jabatan_id;
        $id_karyawan = $idkar[0]->p_karyawan_id;
        

        return view('home', compact('id', 'pangkat', 'absen', 'user', 'absenin', 'absenout',  'ultah', 'foto',  'umurkerja', 'dw', 'help',  'idkar', 'all', 'qoute'));
    }

    public function admin()
    {
        $iduser = Auth::user()->id;
        $sqlidkar = "select * from p_karyawan where user_id=$iduser";
        $idkar = DB::connection()->select($sqlidkar);
        //$id=$idkar[0]->p_karyawan_id;
		
        $sqljmlkaryawan = "SELECT count(COALESCE(p_karyawan_id,0)) as jmlkaryawan
                    FROM p_karyawan
                    WHERE 1=1 and active=1";
        $jmlkaryawan = DB::connection()->select($sqljmlkaryawan);

        $sqljmldivisi = "SELECT count(COALESCE(m_divisi_id,0)) as jmldivisi
                    FROM m_divisi
                    WHERE 1=1 and active=1";
        $jmldivisi = DB::connection()->select($sqljmldivisi);

        $sqljmllokasi = "SELECT count(COALESCE(m_lokasi_id,0)) as jmllokasi
                    FROM m_lokasi
                    WHERE 1=1 and active=1 and sub_entitas=0";
        $jmllokasi = DB::connection()->select($sqljmllokasi);

        $sqljmldepartemen = "SELECT count(COALESCE(m_departemen_id,0)) as jmldepartemen
                    FROM m_departemen
                    WHERE 1=1 and active=1";
        $jmldepartemen = DB::connection()->select($sqljmldepartemen);

        /*$sqljmllaki = "SELECT count(b.m_jenis_kelamin_id) as laki FROM p_karyawan a
LEFT JOIN p_recruitment b on b.p_recruitment_id=a.p_recruitment_id
WHERE a.active=1 and b.m_jenis_kelamin_id=1";
        $jmllaki = DB::connection()->select($sqljmllaki);

        $sqljmlwanita = "SELECT count(b.m_jenis_kelamin_id) as wanita FROM p_karyawan a
LEFT JOIN p_recruitment b on b.p_recruitment_id=a.p_recruitment_id
WHERE a.active=1 and b.m_jenis_kelamin_id=2";
        $jmlwanita = DB::connection()->select($sqljmlwanita);*/

        $help = new Helper_function();
        $tgl = $help->tambah_bulan(date('Y-m-') . '1', 1);
		
        $sqltotalkontrak = "SELECT count(*) as total 
                    FROM p_karyawan_kontrak
                    LEFT JOIN p_karyawan on p_karyawan.p_karyawan_id=p_karyawan_kontrak.p_karyawan_id
                    LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
                    LEFT JOIN m_divisi on m_divisi.m_divisi_id=p_karyawan_pekerjaan.m_divisi_id
                    LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                    WHERE 1=1 and p_karyawan_kontrak.active=1  and p_karyawan_kontrak.m_status_pekerjaan_id NOT IN(10) 
                    and p_karyawan_kontrak.tgl_akhir <'" . $tgl . "' and p_karyawan.active=1 ";
        $totalkontrak = DB::connection()->select($sqltotalkontrak);

       
		
		
		$sqluser="SELECT p_recruitment.foto,role FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

 
        // echo print_r($list_karyawan);die;
        return view('admin', compact('jmlkaryawan', 'jmldivisi', 'jmllokasi', 'jmldepartemen',  'totalkontrak',  'help', 'user'));
    }

    public function absensi(Request $request)
    { 
    $help = new Helper_function();
        $tgl_awal = (date('Y-m-d'));
        $tgl_akhir = date('Y-m-d');
        die;
		$rekap = $help->rekap_absen($tgl_awal,$tgl_akhir,$tgl_awal,$tgl_akhir,-1);
		$list_karyawan = $rekap['list_karyawan'] ;
		
		$hari_libur = $rekap['hari_libur'] ;
		$hari_libur_shift = $rekap['hari_libur_shift'] ;
		$tgl_awal_lembur = $rekap['tgl_awal_lembur'] ;
		$tgl_awal = $rekap['tgl_awal'] ;
		$tgl_akhir = $rekap['tgl_akhir'] ;
		return view('absensi',compact(   'rekap', 'list_karyawan',  'help'));
    }

    public function historis(Request $request)
    {
		$help = new Helper_function();
		DB::enableQueryLog();
		$query = (DB::getQueryLog());
		$help->historis($request->historis_page);
    }
    public function password()
    {

        return view('password');
    }
    public function simpan_password(Request $request)
    {

        DB::beginTransaction();
        try {
            $iduser = Auth::user()->id;
            $password = $request->get("password");
            if ($password != '' || $password != null) {

                DB::connection()->table("users")
                    ->where("id", $iduser)
                    ->update([
                        "password" => Hash::make($password),
                        "updated_at" => date("Y-m-d"),
                    ]);
                DB::commit();
            }
            return redirect()->route('password')->with('success', ' Password Berhasil di Ubah!');
        } catch (\Exeception $e) {
            //DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }

    public function ultah()
    {
        $sqlultah = "SELECT a.nik,a.nama,b.tgl_lahir,d.nama as nmlokasi,e.nama as nmdept,EXTRACT(year FROM AGE(now(), tgl_lahir)) || ' Tahun' as usia 
FROM p_karyawan a
LEFT JOIN p_recruitment b on b.p_recruitment_id=a.p_recruitment_id
LEFT JOIN p_karyawan_pekerjaan c on c.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_lokasi d on d.m_lokasi_id=c.m_lokasi_id
LEFT JOIN m_departemen e on e.m_departemen_id=c.m_departemen_id
WHERE date_part('month',b.tgl_lahir)=" . date('m');
        $ultah = DB::connection()->select($sqlultah);
        //var_dump($ultah);die;
        return response()->json($ultah);
    }

    public function news()
    {
        $sqlnews = "SELECT * FROM hr_care a WHERE a.active=1";
        $news = DB::connection()->select($sqlnews);

        return response()->json($news);
    }
    
    
    
    public function recovery_password($id){
        $users = DB::connection()->select("select * from users where id=$id");
        if($users[0]->status_recovery==0){
            DB::connection()->table("users")->where("id",$id)->update(
                [
                    "status_recovery"=>1,
                    "recovery_password"=>$users[0]->password,
                    "password"=>Hash::make(123456),
                    
                ]
                );
        }else{
             DB::connection()->table("users")->where("id",$id)->update(
                [
                    "status_recovery"=>($users[0]->status_recovery==1?2:1),
                    "recovery_password"=>$users[0]->password,
                    "password"=>$users[0]->recovery_password,
                    
                ]
                );
        }
        return redirect()->back()->with('success', ' Password Berhasil di recovery!');
    }
    public function generate_cuti(){
    	 $help = new Helper_function();
        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role FROM users
        
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";

        $user = DB::connection()->select($sqluser);
        $sqlidkar = "select * from p_karyawan 
        			join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id  and p_karyawan_kontrak.active=1
        			join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id
        			where user_id=$iduser";
        $idkar = DB::connection()->select($sqlidkar);
        $him = $idkar[0]->p_karyawan_id;
        $id = $idkar[0]->p_karyawan_id;
        
        
    	$sqlfasilitas = "SELECT * FROM p_karyawan 
				join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
				WHERE 1=1 and a.active=1 and p_karyawan.p_karyawan_id=$id ";
            $karyawan = DB::connection()->select($sqlfasilitas);
            $iduser = Auth::user()->id;
            $help = new Helper_function();
            foreach ($karyawan as $karyawan) {
                $date         = $karyawan->tgl_bergabung;
                $dateBesar     = $karyawan->tgl_bergabung;
                $id_karyawan     = $karyawan->p_karyawan_id;
                $x = 1;
                while ($date <= date('Y-m-d')) {
                    if ($x == 1) {
                        $nominal = 0;
                    } else {
                        $nominal = 12;
                    }
                    if ($x == 3) {
                        $tahun_date = explode('-', ($date))[0];
                        $date = $tahun_date . '-1-1';
                    }

                    $generate_check = $help->tambah_bulan($date, 12);

                    $sqlfasilitas = "SELECT * FROM m_cuti
		                WHERE 1=1 and active=1 and p_karyawan_id = $id_karyawan ";
                    $count = DB::connection()->select($sqlfasilitas);


                    if (count($count)) {
                        DB::connection()->table("m_cuti")
                            ->where('m_cuti_id', $count[0]->m_cuti_id)
                            ->update([
                                "tanggal" => ($date),
                                "tgl_generete_add" => ($generate_check),
                            ]);
                    } else {

                        DB::connection()->table("m_cuti")
                            ->insert([

                                "p_karyawan_id" => ($karyawan->p_karyawan_id),
                                "tgl_generete_add" => ($generate_check),
                            ]);
                    }
                    //dia akan direset kalau setelah desember adalah tanggal tahun
                    //jenis 1 adalah penambahan plafon tahunan
                    // jenis 2 adalah reset plafon
                    $ex_date = explode('-', $date);
                    $ex_generate_check = explode('-', $generate_check);
                    $tgl_reset = $ex_generate_check[0] . '-12-31';
                    DB::connection()->table("t_cuti")
                        ->insert([
                            "p_karyawan_id" => ($karyawan->p_karyawan_id),
                            "nominal" => ($nominal),
                            "keterangan" => $x == 1 ? 'Karyawan Bergabung ke Perusahaan' : 'Penambahan Cuti Tahun ' . $ex_date[0],
                            "tanggal" => ($date),
                            "jenis" => $x == 1 ? 0 : (1),
                            "tgl_reset" => $x == 1 ? null : $tgl_reset,
                            "tahun" => $x == 1 ? null : $ex_date[0],

                            "create_date" => date('Y-m-d'),
                            "create_by" => $iduser,
                        ]);
                    if ($x != 1) {

                        DB::connection()->table("t_cuti")
                            ->insert([
                                "p_karyawan_id" => ($karyawan->p_karyawan_id),
                                "nominal" => ($nominal),
                                "keterangan" => $x == 1 ? 'Karyawan Bergabung ke Perusahaan' : 'Reset Cuti Tahunan ' . $ex_date[0],
                                "tanggal" => ($tgl_reset),
                                "jenis" => $x == 1 ? 0 : (2),
                                "tgl_reset" => $x == 1 ? null : $tgl_reset,
                                "tahun" => $x == 1 ? null : $ex_date[0],

                                "create_date" => date('Y-m-d'),
                                "create_by" => $iduser,
                            ]);
                    }




                    $x++;
                    $date = $generate_check;
                }

                $date = $help->tambah_tanggal($dateBesar, 1);
                $x = 1;
                while ($date < date('Y-m-d')) {
                    if ($x == 1) {
                        $nominal = 0;
                    } else {
                        $nominal = 10;
                    }
                    $generate_check = $help->tambah_bulan($date, 12 * 5);
                    $sqlfasilitas = "SELECT * FROM m_cuti
		                WHERE 1=1 and active=1 and p_karyawan_id = $id_karyawan ";
                    $count = DB::connection()->select($sqlfasilitas);


                    if (count($count)) {
                        DB::connection()->table("m_cuti")
                            ->where('m_cuti_id', $count[0]->m_cuti_id)
                            ->update([
                                "tanggal" => ($date),
                                "tgl_generete_add_besar" => ($generate_check),
                            ]);
                    } else {

                        DB::connection()->table("m_cuti")
                            ->insert([

                                "p_karyawan_id" => ($karyawan->p_karyawan_id),
                                "tgl_generete_add_besar" => ($generate_check),
                            ]);
                    }
                    //dia akan direset kalau setelah desember adalah tanggal tahun
                    //jenis 1 adalah penambahan plafon tahunan
                    // jenis 2 adalah reset plafon
                    $ex_date = explode('-', $date);
                    $tgl_reset = $generate_check;
                    if ($x != 1) {
                        $ke = $x - 1;
                        DB::connection()->table("t_cuti")
                            ->insert([
                                "p_karyawan_id" => ($karyawan->p_karyawan_id),
                                "nominal" => ($nominal),
                                "keterangan" => 'Penambahan Cuti Besar ke-' . $ke,
                                "tanggal" => ($date),
                                "jenis" => $x == 1 ? 0 : (3),
                                "tgl_reset" => $x == 1 ? null : $tgl_reset,
                                "tahun" => $x == 1 ? null : $ke,

                                "create_date" => date('Y-m-d'),
                                "create_by" => $iduser,
                            ]);
                        DB::connection()->table("t_cuti")
                            ->insert([
                                "p_karyawan_id" => ($karyawan->p_karyawan_id),
                                "nominal" => ($nominal),
                                "keterangan" => 'Reset Cuti Besar ke-' . $ke,
                                "tanggal" => ($tgl_reset),
                                "jenis" => $x == 1 ? 0 : (4),
                                "tgl_reset" => $x == 1 ? null : $tgl_reset,
                                "tahun" => $x == 1 ? null : $ke,

                                "create_date" => date('Y-m-d'),
                                "create_by" => $iduser,
                            ]);
                    }




                    $x++;
                    $date = $generate_check;
                }
            }
    }
    public function generate_update_faskes($id_karyawan = null)
    {
        $iduser = Auth::user()->id;
        $help = new Helper_function();
        $date = date('Y-m-d');
        
        $sqlfasilitas = "SELECT *
    					
    					FROM m_faskes
    					Join p_karyawan on m_faskes.p_karyawan_id  = p_karyawan.p_karyawan_id and p_karyawan.active=1
    					join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
						join p_karyawan_gapok b on b.p_karyawan_id = p_karyawan.p_karyawan_id and b.active=1
                
		                WHERE 1=1 and m_faskes.active=1 and generate_add_date<='$date'";
        $faskes = DB::connection()->select($sqlfasilitas);
        foreach ($faskes as $karyawan) {
           
            $date = $karyawan->tanggal;
            if ($karyawan->periode_gajian == 1) {
                $gapok = $karyawan->gapok;
                $grade = $karyawan->tunjangan_grade;
                $nominal = $gapok + $grade;
            } else {
            	 $nominal =  $karyawan->upah_harian * 22;
            }
            $generate_check = $help->tambah_bulan($date, 12);
            DB::connection()->table("m_faskes")
                ->where('m_faskes_id', $karyawan->m_faskes_id)
                ->update([
                    "tanggal" => (date('Y-m-d')),
                    "generate_add_date" => ($generate_check),
                ]);

            DB::connection()->table("t_faskes")
                ->insert([
                    "p_karyawan_id" => ($karyawan->p_karyawan_id),
                    "nominal" => ($nominal),
                    "keterangan" => 'Reset Plafon',
                    "tanggal_pengajuan" => (date('Y-m-d')),
                    "jenis" => (1),
                    "appr_status" => (1),
                    "create_date" => date('Y-m-d'),
                    "create_by" => $iduser,
                ]);
            $sqlfasilitas = "SELECT * FROM p_karyawan_faskes
		                WHERE 1=1 and p_karyawan_id =" . $karyawan->p_karyawan_id;
            $count = DB::connection()->select($sqlfasilitas);
            if (count($count)) {

                $saldo =  $nominal;
                DB::connection()->table("p_karyawan_faskes")
                    ->where('p_karyawan_faskes_id', $count[0]->p_karyawan_faskes_id)
                    ->update([
                        "saldo" => ($saldo),

                    ]);
            } else {
                $saldo = $nominal;
                DB::connection()->table("p_karyawan_faskes")
                    ->insert([
                        "p_karyawan_id" => ($karyawan->p_karyawan_id),
                        "saldo" => ($saldo)
                    ]);
            }
        }
    }
    public function generate_faskes($id_karyawan = null)
    {
        $where = '';
        if (!$id_karyawan) {
            DB::connection()->table("m_faskes")->delete();
            DB::connection()->table("t_faskes")->delete();
            DB::connection()->table("p_karyawan_faskes")->delete();
        } else {
            $where = ' and p_karyawan.p_karyawan_id=' . $id_karyawan;
        }
        $iduser = Auth::user()->id;
        $help = new Helper_function();
        $sqlfasilitas = "SELECT * FROM p_karyawan 
				join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
				join p_karyawan_gapok b on b.p_karyawan_id = p_karyawan.p_karyawan_id and b.active=1
                WHERE 1=1 and a.active=1 $where";
        $karyawan = DB::connection()->select($sqlfasilitas);
        foreach ($karyawan as $karyawan) {
            $date = $karyawan->tgl_bergabung;
            $x = 1;

            if ($karyawan->periode_gajian == 1) {
                $gapok = $karyawan->gapok;
                $grade = $karyawan->tunjangan_grade;
                $nominala = $gapok + $grade;
            } else {

                $nominala =  $karyawan->upah_harian * 22;
            }

            while ($date < date('Y-m-d')) {
                if ($x == 1) {
                    $nominal = 0;
                } else {
                    $nominal = $nominala;
                }
                $id_karyawan = $karyawan->p_karyawan_id;
                $sqlfasilitas = "SELECT * FROM m_faskes
		                WHERE 1=1 and active=1 and p_karyawan_id = $id_karyawan ";
                $count = DB::connection()->select($sqlfasilitas);
                if ($x == 1)
                    $generate_check = $help->tambah_bulan($date, 3);
                else
                    $generate_check = $help->tambah_bulan($date, 12);
                if (count($count)) {
                    DB::connection()->table("m_faskes")
                        ->where('m_faskes_id', $count[0]->m_faskes_id)
                        ->update([
                            "tanggal" => ($date),
                            "generate_add_date" => ($generate_check),
                        ]);
                } else {

                    DB::connection()->table("m_faskes")
                        ->insert([

                            "p_karyawan_id" => ($karyawan->p_karyawan_id),
                            "tanggal" => ($date),
                            "generate_add_date" => ($generate_check),
                        ]);
                }
                
                DB::connection()->table("t_faskes")
                    ->insert([
                        "p_karyawan_id" => ($karyawan->p_karyawan_id),
                        "nominal" => ($nominal),
                        "keterangan" => $x == 1 ? 'Karyawan Bergabung ke Perusahaan' : 'Penambahan Plafon',
                        "tanggal_pengajuan" => ($date),
                        "jenis" => (1),
                        "appr_status" => (1),
                        "create_date" => date('Y-m-d'),
                        "create_by" => $iduser,
                    ]);
                $sqlfasilitas = "SELECT * FROM p_karyawan_faskes
		                WHERE 1=1 and p_karyawan_id = $id_karyawan ";
                $count = DB::connection()->select($sqlfasilitas);
                if (count($count)) {

                    $saldo = $count[0]->saldo + $nominal;
                    DB::connection()->table("p_karyawan_faskes")
                        ->where('p_karyawan_faskes_id', $count[0]->p_karyawan_faskes_id)
                        ->update([
                            "saldo" => ($saldo),

                        ]);
                } else {
                    $saldo = $nominal;
                    DB::connection()->table("p_karyawan_faskes")
                        ->insert([
                            "p_karyawan_id" => ($karyawan->p_karyawan_id),
                            "saldo" => ($saldo)
                        ]);
                }
                $x++;
                $date = $generate_check;
            }
        }
    }
    
    public function optimasi_jabstruk(){
        
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan 
        	join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        	join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
        	
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id = $idkar[0]->p_karyawan_id;
       // echo 'id_jabatan='.$idkar[0]->m_jabatan_id;
        //echo '<br>';
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$m_jabatan_id = $idkar[0]->m_jabatan_id;
		$help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$atasan_layer = $jabstruk['atasan_layer'];
		
		if(isset($atasan_layer[2])){
		$atasan = $atasan_layer[1];
		}else{
		$atasan = $atasan_layer[1];
		}
		$atasan_temp = $atasan;
	
		$atasandireksi = $jabstruk['direksi'];
		
		$sql = "select * from m_jabatan where active = 1 and m_jabatan_id in($bawahan)";
    	$jabatan=DB::connection()->select($sql);
    	
		
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasandireksi) ";
		$apprdireksi=DB::connection()->select($sqlappr);
	    $select_atasan1 = '<div class="form-group">
	                                <label>Atasan 1(Atasan Langsung)</label>
	                              <select class="form-control select2" id="tgl_absen" name="atasan"  value="" required="" style="width: 100%">
	                              	<option value="">- Pilih Atasan 1-</option>
	                              	';
	                              	foreach($appr as $atasan){
	                              	$select_atasan1 .='<option value="'.$atasan->p_karyawan_id.'">'.$atasan->nama_lengkap.'</option>';
	                              	}
	                              $select_atasan1 .='</select>
	                            </div>';
         $select_atasandireksi ='<div class="form-group">
	                                <label>Atasan 2(Direksi)</label>
	                              <select class="form-control select2" id="tgl_absen" name="atasan2"  value="" required="" style="width: 100%">
	                              	<option value="">- Pilih Atasan 2-</option>
	                              	';
	                              	foreach($apprdireksi as $atasan){
	                              	$select_atasandireksi .='<option value="'.$atasan->p_karyawan_id.'">'.$atasan->nama_lengkap.'</option>';
	                              	 }
	                              $select_atasandireksi .='</select>
	                            </div>';
	    $option_atasan1 = '';
	                              	foreach($appr as $atasan){
	                              	$option_atasan1 .='<option value="'.$atasan->p_karyawan_id.'">'.$atasan->nama_lengkap.'</option>';
	                              	}
	                             
         $option_atasandireksi ='';
	                              	foreach($apprdireksi as $atasan){
	                              	$option_atasandireksi .='<option value="'.$atasan->p_karyawan_id.'">'.$atasan->nama_lengkap.'</option>';
	                              	 }
	                   $option_jabatan = "<option value='-1'>Posisi Baru</option>";
	           foreach($jabatan as $kar){
                                $option_jabatan .=' <option value="'.$kar->m_jabatan_id.'">'.$kar->nama.'</option>';
									}                   
		
		$return['atasan']=$atasan_temp;
		$return['appr']=$appr;
		$return['apprdireksi']=$apprdireksi;
		$return['select_atasan1']=$select_atasan1;
		$return['select_atasandireksi']=$select_atasandireksi;
		$return['option_atasan1']=$option_atasan1;
		$return['option_atasandireksi']=$option_atasandireksi;
		$return['option_jabatan']=$option_jabatan;
        
        return $return;
        
    }
    
    public function optimasi_today_info(){
        
        $help = new Helper_function();
        $iduser = Auth::user()->id;
        $sqlidkar = "select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan 
        			left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
        			left join m_jabatan on m_jabatan.m_jabatan_id = a.m_jabatan_id
        			left join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_kontrak.active=1
        			left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
        			where user_id=$iduser";
        $idkar = DB::connection()->select($sqlidkar);
        

        $karyawan = $idkar[0];
        $id_jabatan = $idkar[0]->m_jabatan_id;
        $him = $idkar[0]->karyawan_id;
        $id = $idkar[0]->karyawan_id;
        
        $id_karyawan = $idkar[0]->karyawan_id;
        
        
        $sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and  ((periode_aktif=1 and tgl_akhir >= '".date('Y-m-d')."')  or (periode_aktif=0 and tgl_akhir <= '".date('Y-m-d')."' )) order by tgl_akhir desc limit 1";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
					$data = array();
					$tgl_awal_gaji = null;
					$tgl_akhir_gaji = null;
					$tgl_akhir_gaji2 = null;
					$tgl_awal_gaji2 = null;
					$tgl_awal_cut_off = null;
			}else{
				
			
			
			
			
					$tgl_awal_gaji = $gapen[0]->tgl_awal;
					
					$tgl_akhir_gaji = $gapen[0]->tgl_akhir;;
					$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
					$tgl_akhir_gaji2 = $help->tambah_tanggal($tgl_akhir_gaji,3);
					$tgl_awal_cut_off  = $tgl_awal_gaji;
				



				$tgl_akhir_cut_off  = $tgl_akhir_gaji;
				$tgl_cut_off ='';
				if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
					$tgl_cut_off = $tgl_awal_cut_off;
				else
					$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);
			}
			
        $today = array();
        $sql = "select * from t_gratifikasi where p_karyawan_yg_melaporkan = $id_karyawan and status=3 and active=1";
        $gratifikasi = DB::connection()->select($sql);
        foreach($gratifikasi as $gratifikasi){
            $today[] = array('Gratifikasi '.$gratifikasi->keterangan. 'dari '.$gratifikasi->dari.' harus diberikan kepada perusahaan',route('fe.laporan_gratifikasi'));
        }
        $sql = "select * from t_gratifikasi where p_karyawan_yg_melaporkan = $id_karyawan and status=2 and active=1";
        $gratifikasi = DB::connection()->select($sql);
        foreach($gratifikasi as $gratifikasi){
            $today[] = array('Gratifikasi '.$gratifikasi->keterangan. 'dari '.$gratifikasi->dari.' belum mengkonfirmasi kepemilikan',route('fe.laporan_gratifikasi'));
        }
        $sql = "select * from t_mudepro where p_karyawan_id = $id_karyawan and appr_status=3 and active=1";
        $gratifikasi = DB::connection()->select($sql);
        foreach($gratifikasi as $gratifikasi){
            $today[] = array('Pengajuan Mutasi Demosi promosi masih pending, hubungi atasan terkait','javascript:void(0)');
        }
        $sql = "select * from t_mudepro where appr_by = $id_karyawan and appr_status=3 and  (status=2 or status=22) and active=1";
        $gratifikasi = DB::connection()->select($sql);
        foreach($gratifikasi as $gratifikasi){
            $today[] = array('Pengajuan Mutasi Demosi promosi masih pending, lakukan persetujuan di Persetujuan > Persetujuan Mutasi Demosi Promosi','javascript:void(0)');
        }
        
        
        
        $sql = "select * from chat_room left join p_karyawan on p_karyawan.p_karyawan_id = chat_room.p_karyawan_create_id 
        where chat_room.appr = $id_karyawan and ( appr_hr_status=2) and selesai=1 and active=1";
        $klarifikasi = DB::connection()->select($sql);
        foreach($klarifikasi as $klarifikasi){
            $today[] = array('Klarifikasi Absen/Gaji karyawan atas nama '.$klarifikasi->nama.' pada tanggal '.$klarifikasi->tanggal.' menunggu konfirmasi atasan, silahkan cek Persetujuan > Persetujuan Klarifikasi Absen ','javascript:void(0)');
        }
    
        $sql = "SELECT *
             from t_agenda_perusahaan a
             join t_agenda_perusahaan_karyawan b on a.t_agenda_perusahaan_id = b.t_agenda_perusahaan_id
    		WHERE a.active=1 and konfirmasi_kehadiran is null and tgl_akhir>='" . date('Y-m-d') . "' 
    		and p_karyawan_id = $id_karyawan
    ";
        $agenda = DB::connection()->select($sql);
    
    
        if (count($agenda)) {
            foreach ($agenda as $agenda) {
                if (($agenda->tgl_awal) == ($agenda->tgl_akhir)) {
                    $tgl =  $help->tgl_indo($agenda->tgl_akhir);
                } else
    	if (date('Y-m', strtotime($agenda->tgl_awal) == date('Y-m', strtotime($agenda->tgl_akhir)))) {
                    $tgl = date('d', strtotime($agenda->tgl_awal)) . ' - ' . date('d', strtotime($agenda->tgl_akhir)) . ' ' . $help->bulan(date('m', strtotime($agenda->tgl_akhir))) . ' ' . date('Y', strtotime($agenda->tgl_akhir));
                } else {
                    $tgl = $help->tgl_indo($agenda->tgl_awal); ?> <?= $agenda->tgl_awal != $agenda->tgl_akhir ? ' s/d ' . $help->tgl_indo($agenda->tgl_akhir) : '';
                                                        }
    
                                                        $today[] = array('Anda belum mengkonfirmasi kehadiran acara <b>' . $agenda->nama_agenda . '</b>, yang akan dilaksanakan pada <br>' .
                                                            'Tanggal : ' . $tgl . '<br>' .
                                                            'Jam : ' . (date('H:i', strtotime($agenda->waktu_mulai))) . ' - ' . ($agenda->waktu_selesai != '00:00:00' ? date('H:i', strtotime($agenda->waktu_selesai)) : 'Selesai') . '<br>*********************************<br>
            			Akses menu di Perusahaan --> Agenda Perusahaan 
    ', route('fe.lihat_agenda_perusahaan', $agenda->t_agenda_perusahaan_id));
                                                    }
                                                }
    
    
                                                if ($idkar[0]->tgl_akhir == null) {
                                                } else if ($idkar[0]->tgl_akhir >= date('Y-m-d')) {
                                                } else
                                                    $today[] = array('Kontrak anda sudah habis silahkan hubungi tim HC', 'javascript:void(0)');
    
                                                $sql = "select * from t_permit 
    					join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id
    					where p_karyawan_id=$id_karyawan and 
    					((status_appr_1 = 3 and t_permit.m_jenis_ijin_id not in (22)) 
    						or (status_appr_2 = 3 and t_permit.m_jenis_ijin_id in (22)) )
    					and t_permit.active=1
    					and tgl_awal>='$tgl_awal_gaji' and tgl_awal<='$tgl_akhir_gaji' ";
                                                $permit = DB::connection()->select($sql);
                                                foreach ($permit as $permit) {
    
                                                    $today[] = array('Pengajuan Anda tanggal ' . $help->tgl_indo($permit->tgl_awal) . ' dengan keterangan ' . $permit->nama . ' belum disetujui, silahkan hubungi Atasan', 'javascript:void(0)');
                                                }
    
                                                //print_r($today);
    
    
                                                $sqljabatan = "SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
    						FROM m_jabatan_atasan a 
    						join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
    						where m_atasan_id = $id_jabatan ";
                                                //echo $sqljabatan;
                                                $jabatan = DB::connection()->select($sqljabatan);
                                                $return = array();
                                                //echo 'jallo';
                                                $e = count($jabatan);
                                                if ($e) {
                                                    $sqlidkar = "select * from p_karyawan 
    				join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
    				join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_kontrak.active=1
    		
    					where p_karyawan.p_karyawan_id in ((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan)))) 
    					and p_karyawan_kontrak.tgl_akhir<'" . $help->tambah_tanggal(date('Y-m-d'), 30) . "' 
    					and p_karyawan.active=1
    					";
                                                    $kar = DB::connection()->select($sqlidkar);
                                                    foreach ($kar as $kar) {
    
                                                        $today[] = array('Kontrak ' . $kar->nama . ' habis tanggal ' . $help->tgl_indo($kar->tgl_akhir) . ' silahkan hubungi tim HC', 'javascript:void(0)');
                                                    }
    
                                                    $sql = "select *,m_jenis_ijin.nama as nama_ijin,p_karyawan.nama as nama_lengkap from t_permit 
    						join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id
    						join p_karyawan on p_karyawan.p_karyawan_id = t_permit.p_karyawan_id
    						where (appr_1=$id_karyawan or appr_2=$id_karyawan) and ((status_appr_1 = 3 and t_permit.m_jenis_ijin_id not in (22)) 
    						or (status_appr_2 = 3 and t_permit.m_jenis_ijin_id in (22)) ) and tgl_awal>='$tgl_awal_gaji' and tgl_awal<='$tgl_akhir_gaji'
    						
    						and t_permit.active=1
    						
    						";
                                                    $permit = DB::connection()->select($sql);
                                                    foreach ($permit as $permit) {
    
                                                        $today[] = array('Pengajuan Karyawan ' . $permit->nama_lengkap . ' tanggal ' . $help->tgl_indo($permit->tgl_awal) . ' dengan keterangan ' . $permit->nama_ijin . ' belum disetujui, silahkan cek persetujuan', 'javascript:void(0)');
                                                    }
                                                    $permit = DB::connection()->select("select * from t_agenda_perusahaan join t_agenda_perusahaan_karyawan on t_agenda_perusahaan.t_agenda_perusahaan_id = t_agenda_perusahaan_karyawan.t_agenda_perusahaan_id and p_karyawan_id = $id_karyawan where t_agenda_perusahaan.active=1 and type='pelatihan' and konfirmasi_kehadiran is null");
                                                    foreach ($permit as $permit) {
                                                        $today[] = array('Anda Belum Mengkonfirmasi Jadwal Penugasan Pelatihan  ' . $permit->nama_agenda, 'javascript:void(0)');
                                                    }
                                                }
                                                $content_today = "";
         if(count($today)):
                        for($t=0;$t<count($today);$t++):
                        $content_today.='	<a href="'.$today[$t][1].'" class="dash-card text-dark">
	                            <div class="dash-card-container">
	                                <div class="dash-card-icon text-danger">
	                                    <i class="fa fa-suitcase"></i>
	                                </div>
	                                <div class="dash-card-content">
	                                    <h6 class="mb-0">
	                                        <p>'. $today[$t][0] .'</p>
	                                    </h6>
	                                </div>
	                            </div>
	                            <hr>
                            </a>';
                            endfor;

                            endif;
        $return['today_count'] = '<span class="badge badge-'.(count($today) ? 'danger' : 'light').'">'. count($today) .'</span>';
        $return['content'] = $content_today;
        echo json_encode($return);
        
    }
    
    public function optimasi_total_cuti(){
        $help = new Helper_function();
        $iduser = Auth::user()->id;
        $sqlidkar = "select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan 
        			left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
        			left join m_jabatan on m_jabatan.m_jabatan_id = a.m_jabatan_id
        			left join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_kontrak.active=1
        			left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
        			where user_id=$iduser";
        $idkar = DB::connection()->select($sqlidkar);
        $karyawan = $idkar[0];
        $him = $idkar[0]->karyawan_id;
        $id = $idkar[0]->karyawan_id;
        $sqlfasilitas = "SELECT * FROM m_cuti
                WHERE 1=1 and active=1 and p_karyawan_id = $id ";
        $cuti = DB::connection()->select($sqlfasilitas);

        if (!count($cuti)) {
            
            HomeController::generate_cuti();
            $sqlfasilitas = "SELECT * FROM m_cuti
                WHERE 1=1 and active=1 and p_karyawan_id = $id ";
            $cuti = DB::connection()->select($sqlfasilitas);
        } else {
            if ($cuti[0]->tgl_generete_add <= date('Y-m-d')) {
                $date_awal = $cuti[0]->tgl_generete_add;

                $bulan_date = explode('-', ($date_awal))[1];
                $nominal = 13 - $bulan_date;

                $tahun_date = explode('-', ($date_awal))[0];
                $date = ($tahun_date + 1) . '-1-1';

                DB::connection()->table("t_cuti")
                    ->insert([
                        "p_karyawan_id" => ($id),
                        "nominal" => ($nominal),
                        "keterangan" => 'Penambahan Cuti Tahun ' . $tahun_date,
                        "tanggal" => ($date_awal),
                        "jenis" => 1,
                        "tgl_reset" => $date,
                        "tahun" => $tahun_date,

                        "create_date" => date('Y-m-d'),
                        "create_by" => $iduser,
                    ]);


                DB::connection()->table("m_cuti")
                    ->where('m_cuti_id', $cuti[0]->m_cuti_id)
                    ->update([
                        "tgl_generete_add" => ($date),
                    ]);
            }else
			
			if($help->hitungBulan($idkar[0]->tgl_bergabung,date('Y-m-d'))>13){
				$c = DB::connection()->select("select count(*) from t_cuti where p_karyawan_id = $id and jenis=1 and tahun = '".date('Y')."'");
				if(!$c[0]->count){
					//ini teh perlu di close cek kalau misal ga ada database terus masuk kesini, otomatis, dia nambah 12 bukan 
					$nominal=12;
					
					$tahun_date = date('Y');
					$date_awal = date('Y-m-d');
					$date = ($tahun_date+1).'-1-1';
					DB::connection()->table("t_cuti")
                    ->insert([
                        "p_karyawan_id" => ($id),
                        "nominal" => ($nominal),
                        "keterangan" => 'Penambahan Cuti Tahun ' . $tahun_date,
                        "tanggal" => ($date_awal),
                        "jenis" => 1,
                        "tgl_reset" => $date,
                        "tahun" => $tahun_date,

                        "create_date" => date('Y-m-d'),
                        "create_by" => $iduser,
                    ]);


                DB::connection()->table("m_cuti")
                    ->where('m_cuti_id', $cuti[0]->m_cuti_id)
                    ->update([
                        "tgl_generete_add" => ($date),
                    ]);
				}
			}
			
            if ($cuti[0]->tgl_generete_add_besar <= date('Y-m-d')) {
                $date_awal = $cuti[0]->tgl_generete_add_besar;
                $date = $help->tambah_bulan($date_awal, (12 * 5));
                $nominal = 10;
                $data = DB::connection()->select("select max(tahun) from t_cuti where jenis=3 and p_karyawan_id = $id");
                $tahun_date = ($data[0]->max) + 1;
                DB::connection()->table("t_cuti")
                    ->insert([
                        "p_karyawan_id" => ($id),
                        "nominal" => ($nominal),
                        "keterangan" => 'Penambahan Cuti Besar ke-' . $tahun_date,
                        "tanggal" => ($date_awal),
                        "jenis" => 3,
                        "tgl_reset" => $date,
                        "tahun" => $tahun_date,

                        "create_date" => date('Y-m-d'),
                        "create_by" => $iduser,
                    ]);


                DB::connection()->table("m_cuti")
                    ->where('m_cuti_id', $cuti[0]->m_cuti_id)
                    ->update([
                        "tgl_generete_add_besar" => ($date),
                    ]);
            }
        }
         $help = new Helper_function();
        $cuti = $help->query_cuti2($idkar);
        $date2 = $cuti['date'];
        $all = $cuti['all'];
        $tanggal_loop = $cuti['tanggal_loop'];
        
        $no=0;$nominal=0;
		$tahun = array();
		$tahunbesar = array();
		$datasisa = array();
		$ipg = array();
		$potong_gaji = array();
		$hutang = 0;
		$jumlah = 0;
		foreach ($tanggal_loop as $i=> $loop) {
			if ($all[$i]['tanggal']<=date('Y-m-d')) {
				$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date2,$i,$nominal,$jumlah,$ipg,$potong_gaji);
				$datasisa =$return['datasisa'];
				$hutang =$return['hutang'];
				$nominal =$return['nominal'];
				$jumlah =$return['jumlah'];
				$ipg =$return['ipg'];
				$potong_gaji =$return['potong_gaji'];
			}
		}


										if (isset($datasisa)) {
											asort($datasisa);
											$totalcuti = 0;
											foreach ($datasisa as $value => $key) {
												$tahun = $value;
												if ($value > 2000)
													$value = 'Sisa Cuti Tahun ' . $value;
												else
													$value = 'Sisa Cuti Besar ke ' . $value;
												//echo $value.' : 	'.$key.'<br>';
												if ($key or $tahun >= date('Y') - 1) {
													$totalcuti += $key;
										?>


									<h4 class="card-title"><?= $value ?> <span class="card-text" style="font-weight: 700;">: <?= $key ?> <span class="info-box-number"></span></span></h4>
									

								<?php }
											}
											if ($hutang) {
								?>
								<h4 class="card-title">Hutang Cuti <span class="card-text" style="font-weight: 700;">: <?= $hutang ?> <span class="info-box-number"></span></span></h4>
						<?php }
										}
						?>
						<h4 class="card-title">Total Keseluruhan Cuti <span class="card-text" style="font-weight: 700;">: <?= $totalcuti ?> <span class="info-box-number"></span></span></h4>


<?php
    }
    
    public function optimasi_rekap_absen(){
        $help = new Helper_function();
        $iduser = Auth::user()->id;
        $sqlidkar = "select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan 
        			left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
        			left join m_jabatan on m_jabatan.m_jabatan_id = a.m_jabatan_id
        			left join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_kontrak.active=1
        			left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
        			where user_id=$iduser";
        $idkar = DB::connection()->select($sqlidkar);
        $karyawan = $idkar[0];
        $him = $idkar[0]->karyawan_id;
        $id = $idkar[0]->karyawan_id;
        $sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."' ";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
					$data = array();
					$tgl_awal_gaji = null;
					$tgl_akhir_gaji = null;
					$tgl_akhir_gaji2 = null;
					$tgl_awal_gaji2 = null;
					$tgl_awal_cut_off = null;
			}else{
				
			
			
			
			
					$tgl_awal_gaji = $gapen[0]->tgl_awal;
					
					$tgl_akhir_gaji = $gapen[0]->tgl_akhir;;
					$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
					$tgl_akhir_gaji2 = $help->tambah_tanggal($tgl_akhir_gaji,3);
					$tgl_awal_cut_off  = $tgl_awal_gaji;
				



				$tgl_akhir_cut_off  = $tgl_akhir_gaji;
				$tgl_cut_off ='';
				if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
					$tgl_cut_off = $tgl_awal_cut_off;
				else
					$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);

		

        //echo $tgl_akhir_gaji;die;

        $tgl_awal = $tgl_awal_gaji;
        $tgl_akhir = date('Y-m-d');
        $sqlusers = "SELECT c.periode_gajian FROM p_karyawan_pekerjaan c
                WHERE 1=1 and c.active=1 and p_karyawan_id=$id";
        $search_type = DB::connection()->select($sqlusers);
        $type = $search_type[0]->periode_gajian;
        if($idkar[0]->m_pangkat_id==6){
             $rekap = $help->rekap_absen_optimasi($tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir, $type, null,$id);

        $return = $help->total_rekap_absen($rekap, $id);


        }else{
             $rekap = $help->rekap_absen($tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir, $type, null,$id);

        $return = $help->total_rekap_absen($rekap, $id);


        }
       

        //if($no==5)
        //break;
        $masuk = $return['total']['masuk'];
        $cuti = $return['total']['cuti'];
        $fingerprint = $return['total']['fingerprint'];
        $ipg = $return['total']['ipg'];
        $ihk = $return['total']['izin'];
        $ipd = $return['total']['ipd'];
        $ipc = $return['total']['ipc'];
        $sakit = $return['total']['sakit'];
        $alpha = $return['total']['alpha'];
        $idt = $return['total']['idt'];
        $ipm = $return['total']['ipm'];
        $pm = $return['total']['pm'];
        $terlambat = $return['total']['terlambat'];
        $masuk_libur = $return['total']['masuk_libur'];
        $ijin_libur = $return['total']['ijin_libur'];
        $absen_masuk = $return['total']['Total Absen'];
        $tmasuk = $return['total']['Total Masuk'];
        $hari_kerja = $return['total']['hari_kerja'];

        $data["data"] = array($absen_masuk, $terlambat, $ipd, $ihk, $ipg, $sakit, $cuti, $masuk_libur, $ipc, $ijin_libur, $fingerprint);
        $data["label"] = ['Absen', 'Telambat', 'Perjalanan Dinas', 'IHK', 'IPG', 'Sakit', 'Cuti', 'Masuk  Hari Libur', 'Izin Potong Cuti', 'Masuk Izin Hari Libur', 'IPM', 'PM', 'IDT', 'Belum Absen'];
        $data["sum"] = $absen_masuk + $ipd + $ihk + $ipg + $cuti + $sakit + $ijin_libur + $ipc;
        //echo ($absen_masuk/$hari_kerja*100);die;
        $data["data_presentase"] =
            array((round($absen_masuk / $hari_kerja * 100, 2)),
                (round($terlambat / $hari_kerja * 100, 2)),
                (round($ipd / $hari_kerja * 100, 2)),
                (round($ihk / $hari_kerja * 100, 2)),
                (round($ipg / $hari_kerja * 100, 2)),
                (round($cuti / $hari_kerja * 100, 2)),
                round((($hari_kerja - $data["sum"]) / $hari_kerja) * 100, 2),
                $fingerprint
            );
        $data["data_presentase"] =
            array((round($absen_masuk, 2)),
                (round($terlambat, 2)),
                (round($ipd, 2)),
                (round($ihk, 2)),
                (round($ipg, 2)),
                (round($sakit, 2)),
                (round($cuti, 2)),
                (round($masuk_libur, 2)),
                (round($ipc, 2)),
                (round($ijin_libur, 2)),
                (round($ipm, 2)),
                (round($pm, 2)),
                (round($idt, 2)),
                round($alpha + $fingerprint, 2),


            );
		}
        
        if(!isset($data['label'])){?>
								Belum terdapat Periode Aktif, Silahkan Hubungi HC.
								<?php }else{?>
								
								<canvas id="pieChart"></canvas>
								<div class="text-center">

									<div class="text-bold" style="font-weight: 700">Keterangan:</div>
									<table id="ket" style="width: 100%; border:0" border="0">
										<tbody>

											<?php
											$warna = [
												'#E65A26', '#a1a1a1', '#0078AA', '#112B3C', '#FCD900', '#247881', '#5584AC', '#008E89', '#3E007C', 'teal', 'rgba(255, 206, 86, 1)', 'rgba(255, 99, 132, 1)',
												'#3E007C',
												'#DA1212',
												'rgba(75, 192, 192, 1)',
												'rgba(153, 102, 255, 1)',
												'rgba(153, 102, 255, 1)',
												'rgba(153, 102, 255, 1)',
												'rgba(255, 159, 64, 1)',
											];
											for ($i = 0; $i < count($data['label']); $i++) {

												echo '<tr>
										<td style="background: ' . $warna[$i] . ';width: 100%; display: block;">&nbsp;</td>
										<td style="text-align:left; ">&nbsp; ' . $data['label'][$i] . '</td>
										<td>' . $data['data_presentase'][$i] . '</td>
										';
												$i++;
												if (isset($data['label'][$i])) {

													echo '
										<td style="background: ' . $warna[$i] . ';width: 100%; display: block;">&nbsp;</td>
										<td style="text-align:left"> &nbsp;' . $data['label'][$i] . '</td>
										<td>' . $data['data_presentase'][$i] . '</td>
									</tr>	';
												}
											} ?>




										</tbody>
									</table>
								</div>

								<?php }?>
								<?php if(isset($data['label'])){?>
								
<script>
	var ctx = document.getElementById('pieChart').getContext('2d');
	var pieChart = new Chart(ctx, {
		type: 'pie',
		data: {
			labels: <?= json_encode($data['label']) ?>,
			datasets: [{

				data: <?= json_encode($data['data_presentase']) ?>,
				backgroundColor: <?= json_encode($warna); ?>,
				borderWidth: 1
			}]
		},
		options: {
			responsive: true,
			legend: {
				display: false
			}
		}
	});
</script>
<?php }
    }

    public function optimasi_fasilitas(){
        $help = new Helper_function();
        $iduser = Auth::user()->id;
        $sqlidkar = "select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan 
        			left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
        			left join m_jabatan on m_jabatan.m_jabatan_id = a.m_jabatan_id
        			left join p_karyawan_kontrak on p_karyawan_kontrak.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_kontrak.active=1
        			left join p_karyawan_gapok on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan_gapok.active=1
        			where user_id=$iduser";
        $idkar = DB::connection()->select($sqlidkar);
        $karyawan = $idkar[0];
        $him = $idkar[0]->karyawan_id;
        $id = $idkar[0]->karyawan_id;
        $sqlfasilitas = "SELECT * FROM p_karyawan_faskes
                WHERE 1=1 and active=1 and p_karyawan_id = $id ";
        $fasilitas = DB::connection()->select($sqlfasilitas);

        if (!count($fasilitas)) {
            HomeController::generate_faskes($id);
            $sqlfasilitas = "SELECT * FROM p_karyawan_faskes
                WHERE 1=1 and active=1 and p_karyawan_id = $id ";
            $fasilitas = DB::connection()->select($sqlfasilitas);
        } else {
            HomeController::generate_update_faskes();
            
            if($help->hitungBulan($idkar[0]->tgl_bergabung,date('Y-m-d'))>3){
            	$tahun_date = date('Y');
            	$count = DB::connection()->select("select count(*) from t_faskes where jenis = 1 and tanggal_pengajuan >='$tahun_date-01-01'  and tanggal_pengajuan <='$tahun_date-12-31' and p_karyawan_id = $id ");
            	if(!$count[0]->count){
            		
            	if ($karyawan->periode_gajian == 1) {
	                $gapok = $karyawan->gapok;
	                $grade = $karyawan->tunjangan_grade;
	                $nominal = $gapok + $grade;
	            } else {
	            	 $nominal =  $karyawan->upah_harian * 22;
	            }
            	$generate_check = ($tahun_date+1).'-01-01';
				DB::connection()->table("m_faskes")
                ->where('p_karyawan_id', $id)
                ->update([
                    "tanggal" => ("$tahun_date-01-01"),
                    "generate_add_date" => ($generate_check),
                ]);
				
	            DB::connection()->table("t_faskes")
	                ->insert([ 
	                   
	                    "p_karyawan_id" => ($karyawan->p_karyawan_id),
	                    "nominal" => ($nominal),
	                    "keterangan" => 'Reset Plafon',
	                    "tanggal_pengajuan" => ("$tahun_date-01-01"),
	                    "jenis" => (1),
	                    "appr_status" => (1),
	                    "create_date" => date('Y-m-d'),
	                    "create_by" => $iduser,
	                ]);
	                
	            $sqlfasilitas = "SELECT * FROM p_karyawan_faskes
		                WHERE 1=1 and p_karyawan_id =" . $karyawan->p_karyawan_id;
	            $count = DB::connection()->select($sqlfasilitas);
	            if (count($count)) {

	                $saldo = $nominal;
	                DB::connection()->table("p_karyawan_faskes")
	                    ->where('p_karyawan_faskes_id', $count[0]->p_karyawan_faskes_id)
	                    ->update([
	                        "saldo" => ($saldo),

	                    ]);
	            } else {
	                $saldo = $nominal;
	                DB::connection()->table("p_karyawan_faskes")
	                    ->insert([
	                        "p_karyawan_id" => ($karyawan->p_karyawan_id),
	                        "saldo" => ($saldo)
	                    ]);
	            }
			}
            	}
        }
    }
    public function import_jabatan(){
        DB::beginTransaction();
        try {
        DB::enableQueryLog();
       
        
    $path = 'Data Jabatan SJP.xlsx';
    
        $inputFileType = ucfirst(pathinfo($path, PATHINFO_EXTENSION));;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($path);
            //die;
            
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        echo '<pre>';
    //print_r($sheetData);
    $list_entitas = array();
    $list_entitas_value = array();
    $list_directorat = array();
    $list_directorat_value = array();
    $list_divisi_value = array();
    $list_divisi = array();
    $list_departement_value = array();
    $list_departement = array();
    $list_seksi_value = array();
    $list_seksi = array();
    $list_jabatan_value = array();
    $list_jabatan = array();

    
    for($i=2;$i<63;$i++){
        $entitas = $sheetData[$i]['B'];
        echo $entitas;
        $pos = strpos($entitas, "-",1);
        if($pos){
            $exentitas = explode("-",$entitas);
            $id_entitas = $exentitas[0];
        }else{
            if(in_array($entitas,$list_entitas_value)){
                $id_entitas = $list_entitas[$entitas];
            }else{
                $data['nama'] = $entitas;
                $data['active'] = 1;
                $data['create_date'] = date('Y-m-d');
                DB::connection()->table("m_lokasi")->insert($data);
                $seq = DB::connection()->select('select * from seq_m_lokasi');
                $id_entitas = $seq[0]->last_value;
                $list_entitas_value[]=$entitas;
                $list_entitas[$entitas]=$id_entitas;
            }
        }
        // echo '-';
        unset($data);
        echo '-->';
        echo  $id_entitas;
        echo '<br>';
         $directorat = $sheetData[$i]['C'];
       echo  $directorat;
        $pos = strpos($directorat, "-",1);
        if($pos){
            $exdirectorat = explode("-",$directorat);
            $id_directorat = $exdirectorat[0];

            $datae['m_lokasi_id'] = $id_entitas;
            DB::connection()->table("m_directorat")->where("m_directorat_id",$id_directorat)->update($datae);
        }else{
            if(in_array($directorat,$list_directorat_value)){
                $id_directorat = $list_directorat[$directorat];
            }else{
                $data['nama_directorat'] = $directorat;
                $data['create_date'] = date('Y-m-d');
                $data['m_lokasi_id'] = $id_entitas;
                $data['active'] = 1;
                DB::connection()->table("m_directorat")->insert($data);
                $seq = DB::connection()->select('select * from seq_m_directorat');
                $id_directorat = $seq[0]->last_value;
                $list_directorat_value[]=$directorat;
                $list_directorat[$directorat]=$id_directorat;
            }
        }
        // echo '-';
        echo '-->';
        echo  $id_directorat;
        echo '<br>';
        unset($data);
         
          $divisi = $sheetData[$i]['D'];
        $divisi;
        $pos = strpos($divisi, "-",1);
        if($pos){
            $exdivisi = explode("-",$divisi);
            $id_divisi = $exdivisi[0];
            $datadr['m_directorat_id'] = $id_directorat;
            DB::connection()->table("m_divisi_new")->where("m_divisi_new_id",$id_divisi)->update($datadr);
        }else{
            if(in_array($divisi,$list_divisi_value)){
                $id_divisi = $list_divisi[$divisi];
            }else{
                $data['nama_divisi'] = $divisi;
                $data['create_date'] = date('Y-m-d');
                $data['m_directorat_id'] = $id_directorat;
                $data['active'] = 1;
                DB::connection()->table("m_divisi_new")->insert($data);
                $seq = DB::connection()->select('select * from seq_m_divisi_new');
                $id_divisi = $seq[0]->last_value;
                $list_divisi_value[]=$divisi;
                $list_divisi[$divisi]=$id_divisi;
            }
        }
        // echo '-';
         $id_divisi;
         unset($data);
         
          $departement = $sheetData[$i]['E'];
         
        $departement;
        $pos = strpos($departement, "-",1);
        if($pos){
            $exdepartement = explode("-",$departement);
            $id_departement = $exdepartement[0];
            
            $datadn['m_divisi_new_id'] = $id_divisi;
            DB::connection()->table("m_divisi")->where("m_divisi_id",$id_departement)->update($datadn);
        }else{
            if(in_array($departement,$list_departement_value)){
                $id_departement = $list_departement[$departement];
            }else{
                $data['nama'] = $departement;
                //$data['create_date'] = date('Y-m-d');
                $data['m_divisi_new_id'] = $id_divisi;
                $data['active'] = 1;
                DB::connection()->table("m_divisi")->insert($data);
                $seq = DB::connection()->select('select * from seq_divisi');
                $id_departement = $seq[0]->last_value;
                $list_departement_value[]=$departement;
                $list_departement[$departement]=$id_departement;
            }
        }
        // echo '-';
         $id_departement;
         unset($data);
         $seksi = $sheetData[$i]['F'];
        $seksi;
        if($seksi !="-")
        $pos = strpos($seksi, "-",1);
        else
        $pos =0;
       
        if($pos and $seksi!="'-"){
            $exseksi = explode("-",$seksi);
            $id_seksi = $exseksi[0];
            
            $datas['m_divisi_id'] = $id_departement;
            DB::connection()->table("m_departemen")->where("m_departemen_id",$id_seksi)->update($datas);
        }else{
            if(in_array($seksi,$list_seksi_value)){
                $id_seksi = $list_seksi[$seksi];
            }else{
                $data['nama'] = $seksi;
                $data['create_date'] = date('Y-m-d');
                $data['m_divisi_id'] = $id_departement;
                $data['active'] = 1;
                DB::connection()->table("m_departemen")->insert($data);
                $seq = DB::connection()->select('select * from seq_m_departemen');
                $id_seksi = $seq[0]->last_value;
                $list_seksi_value[]=$seksi;
                $list_seksi[$seksi]=$id_seksi;
            }
        }
        // echo '-';
         $id_seksi;
         unset($data);
          $jabatan = $sheetData[$i]['G'];
        $jabatan;
        $pos = strpos($jabatan, "-",1);
        if($pos){
            $exjabatan = explode("-",$jabatan);
            $id_jabatan = $exjabatan[0];

            $dataj['m_departemen_id'] = $id_seksi;
            DB::connection()->table("m_jabatan")->where("m_jabatan_id",$id_jabatan)->update($dataj);
        }else{
            if(in_array($jabatan,$list_jabatan_value)){
                $id_jabatan = $list_jabatan[$jabatan];
            }else{
                $data['nama'] = $jabatan;
                $data['create_date'] = date('Y-m-d');
                $data['m_departemen_id'] = $id_seksi;
                $data['active'] = 1;
                DB::connection()->table("m_jabatan")->insert($data);
                $seq = DB::connection()->select('select * from seq_m_jabatan');
                $id_jabatan = $seq[0]->last_value;
                $list_jabatan_value[]=$jabatan;
                $list_jabatan[$jabatan]=$id_jabatan;
            }
        }
        // echo '-';
         $id_jabatan;
        
    }
   
       DB::commit();

    //echo '<img src="'.url('bower_components/qrcode/qrcode.php?s=qrh&d='.$code).'"  width="250px"><br>';
        
    } catch (\Exeception $e) {
        DB::rollback();
        return redirect()->back()->with('error', $e);
    }
}
    public function absen_migration(){
        $databse = new Database();
        $sqlQuery = "SELECT mesin_id,pin,date_time,ver,created_at,status_absen_id,active,absen_log_id 
                         FROM " . $this->db_table . " WHERE 1=1 AND cast(date_time as date)='".$tglnow."' and permit is null";
                         
		$data = $this->conn->query($sqlQuery);
    }
}
