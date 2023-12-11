<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;
use Maatwebsite\Excel\Excel;
use Mail;
use Response;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;

use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use DateTime;;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;
use \Debugbar;

class KaryawanController extends Controller
{
    /**
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
    public function karyawan()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access,periode_gaji_role FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND c.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";

        $where_periode ="";
        if($user[0]->periode_gaji_role and $user[0]->periode_gaji_role!=2){
                if($user[0]->periode_gaji_role==-1)
                $user[0]->periode_gaji_role=0;
            $where_periode ="and b.periode_gajian = ".$user[0]->periode_gaji_role;

        }
        if(Auth::user()->role==-1){
            $hak_akses =  json_decode(json_encode(array(0=>array("_view"=>1,"_delete"=>1,"_add"=>1,"_edit"=>1))));
        }else{
        $hak_akses = DB::connection()->select(
            "select * from users
            
            left join m_role on m_role.m_role_id=users.role
            left join users_admin on users_admin.m_role_id  = m_role.m_role_id
            left join m_menu on users_admin.menu_id = m_menu.m_menu_id
            where link='be.karyawan'  and users.id=$iduser
            
            ");
        }
        $sqlkaryawan="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap,case when a.active=1 then 'Active' else 'Non Active' end as status,case when b.periode_gajian=1 then 'Bulanan' else 'Pekanan' end as periode_gajian ,j.nama as nama_kantor,
c.nama as nmlokasi,b.kantor,b.kota,d.nama as nmdivisi,f.nama as nmdept,g.nama as nmjabatan,
h.tgl_awal,h.tgl_akhir,m.no_absen,i.nama as nmstatus,pajak_onoff,bank,norek,nama_bank,g.job as jobweight , k.nama_grade as grade,
tgl_bergabung,n.nama_directorat as nmdirectorat,l.nama_divisi,z.nama as nama_mesin
--(select tgl_awal from p_karyawan_kontrak  where p_karyawan_kontrak.p_karyawan_id = a.p_karyawan_id order by tgl_awal asc limit 1) as tgl_awal
FROM p_karyawan a
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id

LEFT JOIN m_bank r on r.m_bank_id=b.m_bank_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
LEFT JOIN m_directorat n on n.m_directorat_id=b.m_directorat_id
LEFT JOIN m_divisi_new l on l.m_divisi_new_id=b.m_divisi_new_id
LEFT JOIN m_divisi d on d.m_divisi_id=b.m_divisi_id
LEFT JOIN m_departemen f on f.m_departemen_id=b.m_departemen_id
LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
LEFT JOIN m_karyawan_grade k on g.job>=k.job_min and g.job<= k.job_max
LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id and h.active=1
LEFT JOIN m_status_pekerjaan i on i.m_status_pekerjaan_id=h.m_status_pekerjaan_id
LEFT JOIN m_office j on b.m_kantor_id=j.m_office_id 

LEFT JOIN m_mesin_absen z on j.m_mesin_absen_seharusnya_id=z.mesin_id
LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
                    WHERE 1=1 $whereLokasi $where_periode and a.active=1 order by a.nama";
        $karyawan=DB::connection()->select($sqlkaryawan);
        
        //print_r($hak_akses);die;
        return view('backend.karyawan.karyawan', compact('karyawan','user','hak_akses'));
    }
 	public function get_data_karyawan(Request $request)
    {
       if($request->value)
			$where = "and a.p_karyawan_id=".$request->value;			
		
        $sqlkaryawan="SELECT *
FROM p_karyawan a
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id

                    WHERE 1=1 $where and a.active=1 order by a.nama";
        $karyawan=DB::connection()->select($sqlkaryawan);
        echo json_encode($karyawan[0]);
    }
 
    public function tambah_karyawan()
    {
        $sqlagama="SELECT * FROM m_agama WHERE active=1 ORDER BY nama ASC ";
        $agama=DB::connection()->select($sqlagama);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkota="SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
        $kota=DB::connection()->select($sqlkota);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);
 		
 		$sql="SELECT * FROM m_bank WHERE active=1 ORDER BY nama_bank ASC ";
		 $bank=DB::connection()->select($sql);

        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi 
                      FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id
                      WHERE m_jabatan.active=1 ORDER BY m_jabatan.nama ASC ";
        $jabatan=DB::connection()->select($sqljabatan);

        $sqlgrade="SELECT * FROM m_grade WHERE active=1 ORDER BY nama ASC ";
        $grade=DB::connection()->select($sqlgrade);

        $sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
        $pangkat=DB::connection()->select($sqlpangkat);

        $sqldivisi="SELECT * FROM m_divisi WHERE active=1 ORDER BY nama ASC ";
        $divisi=DB::connection()->select($sqldivisi);

        $sqlstspekerjaan="SELECT * FROM m_status_pekerjaan WHERE active=1 ORDER BY nama ASC ";
        $stspekerjaan=DB::connection()->select($sqlstspekerjaan);

        $sqlkota="SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
        $kota=DB::connection()->select($sqlkota);
 		
 		$sqlgrade="SELECT * FROM m_karyawan_grade WHERE active=1 ORDER BY nama_grade ASC ";
        $karyawan_grade=DB::connection()->select($sqlgrade);
        
        $sqlkantor="SELECT * FROM m_office WHERE active=1 ORDER BY nama ASC ";
        $kantor=DB::connection()->select($sqlkantor);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
//        $user=DB::connection()->select($sqluser);

		return view('backend.karyawan.tambah_karyawan',compact('lokasi','kota','jk','departemen','jabatan','grade','karyawan_grade','stspekerjaan','agama','pangkat','divisi','bank','kantor'));
    }

    public function simpan_karyawan(Request $request){
        DB::beginTransaction();
        try{
        	DB::enableQueryLog();
            $iduser=Auth::user()->id;
            $sqlidrec="SELECT max(p_recruitment_id)+1 as p_recruitment_id FROM p_recruitment";
            $datarec=DB::connection()->select($sqlidrec);
            $idrec=$datarec[0]->p_recruitment_id;
            $bln=date('m');
            $thn=date('y');
            $koderec='1.REC.'.$bln.'.'.$thn.'.'.$idrec;

            DB::connection()->table("p_recruitment")
                ->insert([
                    "p_recruitment_id" => $idrec,
                    "kode" => $koderec,
                    "nama_lengkap" => $request->get("nama_lengkap"),
                    "nama_panggilan" => $request->get("nama_panggilan"),
                    "m_jenis_kelamin_id" => $request->get("jk"),
                    "email" => $request->get("email"),
                    "no_hp" => $request->get("no_hp"),
                    "tempat_lahir" => $request->get("tempat_lahir"),
                    "tgl_lahir" => date('Y-m-d',strtotime($request->get("tgl_lahir"))),
                    "m_status_id" => $request->get("status_pernikahan"),
                    "no_ktp" => $request->get("ktp"),
                    "alamat_ktp" => $request->get("alamat_ktp"),
                    "alamat_tinggal" => $request->get("alamat_ktp"),
                  
                    "active" => 1,
                    "create_date" => date("Y-m-d"),
                    "create_by" => $iduser,
                ]);

            $sqlidkar="SELECT max(p_karyawan_id)+1 as p_karyawan_id FROM p_karyawan";
            $datakar=DB::connection()->select($sqlidkar);
            $idkar=$datakar[0]->p_karyawan_id;

            //$blnkontrak=date('m',strtotime($request->get("tgl_awal")));
            //$thnkontrak=date('Y',strtotime($request->get("tgl_awal")));
            $lokasi=$request->get("lokasi");
            $blnkontrak=date('m',strtotime($request->get("tgl_awal")));
            $thnkontrak=date('y',strtotime($request->get("tgl_awal")));
            $thnkontrak2=date('Y',strtotime($request->get("tgl_awal")));
            //echo $thnkontrak;die;
            //$kodenik=$kar->kode_nik;
            $sqlokasi1="SELECT * FROM m_lokasi WHERE m_lokasi_id=$lokasi";
            //echo $sql;
            $datalokasi1=DB::connection()->select($sqlokasi1);
            $kodenik1=$datalokasi1[0]->kode_nik;
            $sql="SELECT count(*)+1 as count FROM p_karyawan
WHERE to_char(tgl_bergabung,'yy-mm')='".$thnkontrak.'-'.$blnkontrak."' and nik ilike '".$kodenik1.$thnkontrak.$blnkontrak."%' ";
            //echo $sql;die;
            $datasql=DB::connection()->select($sql);

            $jumlah=$datasql[0]->count;
            if(strlen($jumlah)==1){
                $nik='0'.$jumlah;
            }
            else{
                $nik=$jumlah;
            }

            $sqlokasi="SELECT * FROM m_lokasi WHERE m_lokasi_id=$lokasi";
            //echo $sql;
            $datalokasi=DB::connection()->select($sqlokasi);

            $kodenik=$datalokasi[0]->kode_nik;
            $nik=$kodenik.$thnkontrak.$blnkontrak.$nik;
            //echo $nik;die;

            /*$sqlcek="select * from p_karyawan where nik='".$nik."' ";
            $cek=DB::connection()->select($sqlcek);
            if(!empty($cek)){
                $sql="SELECT count(*)+1 as count FROM p_karyawan
WHERE nik = '".$nik."' ";
                //echo $sql;
                $datasql=DB::connection()->select($sql);

                $jumlah=$datasql[0]->count;
                if(strlen($jumlah)==1){
                    $nik='0'.$jumlah;
                }
                else{
                    $nik=$jumlah;
                }
                $urut=$nik+1;
                $nik=$kodenik.$thnkontrak.$blnkontrak.$urut;
            }*/

            /*$sqlnik="SELECT count(*) as count FROM p_karyawan_kontrak WHERE to_char(tgl_awal,'yyyy-mm')='".$thnkontrak.'-'.$blnkontrak."'  ";
            $datanik=DB::connection()->select($sqlnik);
            $jumlah=$datanik[0]->count+1;
            if(strlen($jumlah)==1){
                $nik='0'.$jumlah;
            }
            else{
                $nik=$jumlah;
            }
            $nik=$thnkontrak.$blnkontrak.$nik;*/
			
				$blnkontrak2 = $blnkontrak+1;
				if($blnkontrak2>=13){
				    $blnkontrak2 = '1';
				    $thnkontrak2 = $thnkontrak2+1;
				}$sql="SELECT count(*) as count FROM p_karyawan
						
            left join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
						WHERE tgl_bergabung >='$thnkontrak2-$blnkontrak-01' and tgl_bergabung <'$thnkontrak2-$blnkontrak2-01' and m_lokasi_id = $lokasi ";
	            //echo $sql;die;
	            $datasql=DB::connection()->select($sql);
	            $jumlah=($datasql[0]->count)+1;
	            if(strlen($jumlah)==1){
	                $nik='0'.$jumlah;
	            }
	            else{
	                $nik=$jumlah;
	            }
				;
	            $sqlokasi="SELECT * FROM m_lokasi WHERE m_lokasi_id=$lokasi";
	            //echo $sql;
	            $datalokasi=DB::connection()->select($sqlokasi);
	            $kodenik=$datalokasi[0]->kode_nik;
	            $nik=$kodenik.$thnkontrak.$blnkontrak.$nik;
	            $loop = true;
	            while($loop){
	            	
	            $sqlokasi="SELECT * FROM p_karyawan WHERE nik='$nik'";
	            $datalokasi=DB::connection()->select($sqlokasi);
	            if(count($datalokasi)){
	            	$jumlah+=1;
	            	 if(strlen($jumlah)==1){
		                $nik='0'.$jumlah;
			            }
			            else{
			                $nik=$jumlah;
			            }
						;
					$nik=$kodenik.$thnkontrak.$blnkontrak.$nik;
	            }else{
	            	$loop=false;
	            }
	            }
            DB::connection()->table("p_karyawan")
                ->insert([
                    "p_karyawan_id" => $idkar,
                    "p_recruitment_id" => $idrec,
                    "nik" => $nik,
                    "nama" => $request->get("nama_lengkap"),
                    "email_perusahaan" => $request->get("email"),
                    "tgl_bergabung" => date('Y-m-d',strtotime($request->get("tgl_bergabung"))),
                    "ukuran_baju"=>$request->get("ukuran_baju"),
                    "pendidikan"=>$request->get("pendidikan"),
                    "jurusan"=>$request->get("jurusan"),
                    "nama_sekolah"=>$request->get("nama_sekolah"),
                    "no_hp2"=>$request->get("no_hp"),
                    "domisili"=>$request->get("domisili"),
                    "jumlah_anak"=>$request->get("jumlah_anak"),
                    "active" => $request->get("status"),
                    "create_date" => date("Y-m-d"),
                    "create_by" => $iduser,
                ]);

            DB::connection()->table("p_karyawan_pekerjaan")
                ->insert([
                    "p_karyawan_id" => $idkar,
                    "m_lokasi_id" => $request->get("lokasi"),
                    "m_departemen_id" => $request->get("departemen"),
                    "m_jabatan_id" => $request->get("jabatan"),
                    "m_divisi_id" => $request->get("divisi"),
                    "is_shift" => $request->get("is_shift"),
                    
                    "kota" => $request->get("kotakerja"),
                    "periode_gajian" => $request->get("periode_gajian"),
                    "m_bank_id" => $request->get("bank"),
                    "bpjs_kantor" => $request->get("bpjs_kantor"),
                     "tgl_bpjs_kantor" => $request->get("tgl_bpjs_kantor")?$request->get("tgl_bpjs_kantor"):null,
                    "pajak_onoff" => $request->get("pajakonoff"),
                    "norek" => $request->get("norek"),
                    "m_kantor_id" => $request->get("kantor"),
                    "m_karyawan_grade_id" => $request->get("grade"),
                    "active" => 1,
                    "create_date" => date("Y-m-d"),
                    "create_by" => $iduser,
                ]);

            DB::connection()->table("p_karyawan_kartu")
                ->insert([
                    "p_karyawan_id" => $idkar,
                    "kartu_keluarga" => $request->get("kk"),
                    "ktp" => $request->get("ktp"),
                    "no_npwp" => $request->get("npwp"),
                    "no_bpjsks" => $request->get("bpjsks"),
                    "no_bpjstk" => $request->get("bpjstk"),
                    "no_sima" => $request->get("sima"),
                    "no_simc" => $request->get("simc"),
                    "no_pasport" => $request->get("pasport"),
                    "active" => 1,
                    "create_date" => date("Y-m-d"),
                    "create_by" => $iduser,
                ]);

            if($request->get("status_pekerjaan")=='10'){
                $tgl_akhir=null;
            }
            else{
                $tgl_akhir=date('Y-m-d',strtotime($request->get("tgl_akhir")));
            }
            DB::connection()->table("p_karyawan_kontrak")
                ->insert([
                    "p_karyawan_id" => $idkar,
                    "tgl_awal" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
                    "tgl_akhir" => $tgl_akhir,
                    "m_status_pekerjaan_id" => $request->get("status_pekerjaan"),
                    "keterangan" => $request->get("keterangan"),
                    "active" => 1,
                    "create_date" => date("Y-m-d"),
                    "create_by" => $iduser,
                ]);

            DB::connection()->table("p_karyawan_absen")
                ->insert([
                    "p_karyawan_id" => $idkar,
                    "no_absen" => $request->get("no_absen"),
                    "active" => 1,
                    "create_date" => date("Y-m-d"),
                    "create_by" => $iduser,
                ]);
            $query = (DB::getQueryLog());$help = new Helper_function();
			$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
            DB::commit();
            return redirect()->route('be.karyawan')->with('success',' Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
	public function excel_karyawan()
    {


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
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


        $i = 0;



        $sheet->setCellValue($help->toAlpha($i) . '1', 'No');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NIK');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama Karyawan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Entitas');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Kantor');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Kota');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Divisi');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Departement');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jabatan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Pajak On Off');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Bank');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No Rek');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Periode Gajian');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Status Pekerjaan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No Absen');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Job Weight');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Grade');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Masuk');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Keluar');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No KTP');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No Kartu Keluarga');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No SIM A');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No SIM C');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No NPWP');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No BPJS Kesehatan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No BPJS Ketenagakerjaan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jenis Kelamin');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Status Menikah');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jumlah Anak');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Status Tanggungan');$i++;
        if(Auth::user()->role==-1)
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Office ID');$i++;


        $rows = 2;
		

		$sqlkaryawan="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap,case when b.periode_gajian=1 then 'Bulanan' else 'Pekanan' end as periode_gaji,j.nama as nama_kantor,
c.nama as nmlokasi,b.kantor,b.kota,d.nama as nmdivisi,f.nama as nmdept,g.nama as nmjabatan,
h.tgl_awal,h.tgl_akhir,m.no_absen,i.nama as nmstatus,pajak_onoff,bank,norek,nama_bank,g.job as jobweight , k.nama_grade as grade,l.*,z.m_jenis_kelamin_id,z.m_status_id,a.jumlah_anak,case when m_status_id=0 then 'Belum Menikah' else
			'Sudah Menikah' end as status_pernikahan,y.nama as jenis_kelamin,a.tgl_bergabung,j.m_office_id 
FROM p_karyawan a
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_recruitment z on a.p_recruitment_id=z.p_recruitment_id
LEFT JOIN m_jenis_kelamin y on z.m_jenis_kelamin_id=y.m_jenis_kelamin_id

LEFT JOIN m_bank r on r.m_bank_id=b.m_bank_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
LEFT JOIN m_divisi d on d.m_divisi_id=b.m_divisi_id
LEFT JOIN m_departemen f on f.m_departemen_id=b.m_departemen_id
LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
LEFT JOIN m_karyawan_grade k on g.job>=k.job_min and g.job<= k.job_max
LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id  and h.active=1
LEFT JOIN p_karyawan_kartu l on l.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_status_pekerjaan i on i.m_status_pekerjaan_id=h.m_status_pekerjaan_id
LEFT JOIN m_office j on b.m_kantor_id=j.m_office_id 

LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
                    WHERE 1=1 and a.active=1 order by a.nama";
        $karyawan=DB::connection()->select($sqlkaryawan);
        if (!empty($karyawan)) {

            $no = 0;
            $nominal = 0;
            foreach ($karyawan as $list_karyawan) {
                
                $no++;
                $i=0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $no);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nik);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_lengkap);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmlokasi);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_kantor);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->kota);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmdivisi);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmdept);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmjabatan);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->pajak_onoff);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_bank);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, "'".$list_karyawan->norek);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->periode_gaji);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmstatus);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_absen);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->jobweight);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->grade);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->tgl_bergabung);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->tgl_akhir);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->ktp);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->kartu_keluarga);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_sima);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_simc);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_npwp);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_bpjsks);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_bpjstk);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->jenis_kelamin);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->status_pernikahan);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->jumlah_anak);$i++;
                if($list_karyawan->m_jenis_kelamin_id==2){
                	$sheet->setCellValue($help->toAlpha($i) . $rows, "TK0");$i++;
                	
                }else{
                	$sheet->setCellValue($help->toAlpha($i) . $rows, ($list_karyawan->m_status_id?"K":"TK").str_ireplace(array("-", ' ','anak',"belum"),array(0,"","",""),$list_karyawan->jumlah_anak));$i++;
                }
             
                $rows++;
            }
        }

       

        $type = 'xlsx';
        $fileName = "Rekap Karyawan.xlsx";
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }public function excel_karyawan_resign()
    {


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
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


        $i = 0;



        $sheet->setCellValue($help->toAlpha($i) . '1', 'No');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NIK');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama Karyawan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Entitas');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Kantor');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Kota');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Divisi');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Departement');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jabatan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Pajak On Off');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Bank');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No Rek');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Periode Gajian');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Status Pekerjaan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No Absen');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Job Weight');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Grade');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Masuk');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Keluar');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No KTP');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No Kartu Keluarga');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No SIM A');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No SIM C');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No NPWP');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No BPJS Kesehatan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No BPJS Ketenagakerjaan');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jenis Kelamin');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Status Menikah');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jumlah Anak');$i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Status Tanggungan');$i++;


        $rows = 2;
		

		$sqlkaryawan="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap,case when b.periode_gajian=1 then 'Bulanan' else 'Pekanan' end as periode_gaji,j.nama as nama_kantor,
c.nama as nmlokasi,b.kantor,b.kota,d.nama as nmdivisi,f.nama as nmdept,g.nama as nmjabatan,
h.tgl_awal,h.tgl_akhir,m.no_absen,i.nama as nmstatus,pajak_onoff,bank,norek,nama_bank,g.job as jobweight , k.nama_grade as grade,l.*,z.m_jenis_kelamin_id,z.m_status_id,a.jumlah_anak,case when m_status_id=0 then 'Belum Menikah' else
			'Sudah Menikah' end as status_pernikahan,y.nama as jenis_kelamin
			FROM p_karyawan a
			LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN p_recruitment z on a.p_recruitment_id=z.p_recruitment_id
			LEFT JOIN m_jenis_kelamin y on z.m_jenis_kelamin_id=y.m_jenis_kelamin_id

			LEFT JOIN m_bank r on r.m_bank_id=b.m_bank_id
			LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
			LEFT JOIN m_divisi d on d.m_divisi_id=b.m_divisi_id
			LEFT JOIN m_departemen f on f.m_departemen_id=b.m_departemen_id
			LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
			LEFT JOIN m_karyawan_grade k on g.job>=k.job_min and g.job<= k.job_max
			LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id  and h.active=1
			LEFT JOIN p_karyawan_kartu l on l.p_karyawan_id=a.p_karyawan_id
			LEFT JOIN m_status_pekerjaan i on i.m_status_pekerjaan_id=h.m_status_pekerjaan_id
			LEFT JOIN m_office j on b.m_kantor_id=j.m_office_id 

			LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
                    WHERE 1=1 and a.active=0 order by a.nama";
        $karyawan=DB::connection()->select($sqlkaryawan);
        if (!empty($karyawan)) {

            $no = 0;
            $nominal = 0;
            foreach ($karyawan as $list_karyawan) {
                
                $no++;
                $i=0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $no);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nik);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_lengkap);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmlokasi);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_kantor);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->kota);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmdivisi);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmdept);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmjabatan);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->pajak_onoff);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_bank);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, "'".$list_karyawan->norek);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->periode_gaji);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmstatus);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_absen);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->jobweight);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->grade);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->tgl_awal);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->tgl_akhir);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->ktp);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->kartu_keluarga);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_sima);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_simc);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_npwp);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_bpjsks);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->no_bpjstk);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->jenis_kelamin);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->status_pernikahan);$i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->jumlah_anak);$i++;
                if($list_karyawan->m_jenis_kelamin_id==2){
                	$sheet->setCellValue($help->toAlpha($i) . $rows, "TK0");$i++;
                	
                }else{
                	$sheet->setCellValue($help->toAlpha($i) . $rows, ($list_karyawan->m_status_id?"K":"TK").str_ireplace(array("-", ' ','anak',"belum"),array(0,"","",""),$list_karyawan->jumlah_anak));$i++;
                }
             
                $rows++;
            }
        }

       

        $type = 'xlsx';
        $fileName = "Rekap Karyawan.xlsx";
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }public function excel(Request $request)
    {
    	$karyawan = DB::connection()->select("select * from p_karyawan where active=1 order by nama");
    	$lokasi = DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas = 0  order by nama");
    	return view('backend.karyawan.export_excel_karyawan',compact('karyawan','lokasi','request'));
    }public function hapus_file_karyawan(Request $request,$type,$id_kartu)
    { 
    		 DB::beginTransaction();
        try{
        	DB::connection()->table("p_karyawan_kartu")
				->where("p_karyawan_kartu_id",$id_kartu)
				->update(['file_'.$type=>'']);
				DB::commit();
				 return redirect()->route('be.file_karyawan',['tipe='.$type])->with('success',' Karyawan Berhasil di Ubah!');
		}catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }			
    }public function report_absen_karyawan (Request $request)
    {
    	Debugbar::error('hi');
		$tgl_awal=date('Y-m-d');
		$tgl_akhir=date('Y-m-d');
		
		if($request->get('tgl_awal')){
			$tgl_awal=$request->get('tgl_awal');
			$tgl_akhir=$request->get('tgl_akhir');
		}
		$tahun=date('Y');
		$bulan=date('mm');
		$periode_gajian=$request->get('periode_gajian');
		$periode_absen=$request->get('periode');
		$rekapget = $request->get('rekapget');
		//echo $periode_absen;die;
		$iduser=Auth::user()->id;
		$sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
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
		ORDER BY tahun desc,periode desc,type";
		$periode=DB::connection()->select($sqlperiode);
		$entitas=DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas=0");
		$help = new Helper_function();
		if(($request->get('periode_gajian'))){
			
			$periode_absen=$request->get('periode_gajian');
			$sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
			$periodetgl=DB::connection()->select($sqlperiode);
			$type = $periodetgl[0]->type;
			$where = " d.periode_gajian = ".$type; 
			$appendwhere = "and";
			
			$periode_gajian=$periode[0]->type;
			
		}else{
			
			$where = '';
			$appendwhere = "";
		}
		$iduser=Auth::user()->id;
		$sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
		left join m_role on m_role.m_role_id=users.role
		left join p_karyawan on p_karyawan.user_id=users.id
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
		left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
		where users.id=$iduser";
		$user=DB::connection()->select($sqluser);
		$rekap = $help->rekap_absen($tgl_awal,$tgl_akhir,$tgl_awal,$tgl_akhir,-1,$user,null,null,$request);
		$list_karyawan = $rekap['list_karyawan'] ;
		
		$hari_libur = $rekap['hari_libur'] ;
		$hari_libur_shift = $rekap['hari_libur_shift'] ;
		$tgl_awal = $rekap['tgl_awal'] ;
		$tgl_akhir = $rekap['tgl_akhir'] ;
		
		if($request->get('Cari')=="RekapExcel"){
			
				return RekapAbsenController::exports($param);
			
		}else{
			
				return view('backend.rekap_absen.rekap_absen_atasan',compact('tgl_awal','tgl_akhir','bulan','tahun','periode','periode_absen','periode_gajian','help','tgl_awal','tgl_akhir','list_karyawan','rekap','entitas','user','rekapget','hari_libur','hari_libur_shift'));
			
		}
		
    }public function file_karyawan(Request $request)
    {
    	$karyawan = DB::connection()->select("select * from p_karyawan 
LEFT JOIN p_karyawan_kartu n on n.p_karyawan_id=p_karyawan.p_karyawan_id  where p_karyawan.active=1 order by p_karyawan.nama");
    	$lokasi = DB::connection()->select("select * from m_lokasi where active=1 and sub_entitas = 0  order by nama");
    	return view('backend.karyawan.file_karyawan',compact('karyawan','lokasi','request'));
    }public function download_excel(Request $request)
    {
		

        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
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


       $type = $request->excel;
       $where = '';
       $join = '';
       if($request->lokasi){
       	$join =" left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id ";
       	$where =" and m_lokasi_id = ".$request->lokasi;
       }
       if($request->karyawan){
       	$where = " and p_karyawan.p_karyawan_id = ".$request->karyawan;
       }
       
       
		if($type=='riwayat_pekerjaan'){
			$sql = "SELECT *, p_karyawan.active FROM p_karyawan_riwayat_pekerjaan
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_riwayat_pekerjaan.p_karyawan_id
			$join
			WHERE p_karyawan.active=1 and p_karyawan_riwayat_pekerjaan.active=1 $where ORDER BY p_karyawan_riwayat_pekerjaan.p_karyawan_id,awal_periode ASC ";
			$data = DB::connection()->select($sql);
			$array = array(
				"NIK"=>"nik",
				"Nama Karyawan"=>"nama",
				"Nama Perusahaan"=>"nama_perusahaan",
				"Awal Periode"=>"awal_periode",
				"Akhir Periode"=>"akhir_periode",
				"Posisi Kerja"=>"posisi_kerja",
				"Deskripsi Kerja"=>"deskripsi_kerja",
			);
			
		}else if($type=='pendidikan'){
			$sql = "SELECT *,p_karyawan_pendidikan.nama_sekolah as nmsekolah,p_karyawan_pendidikan.jurusan as nmjurusan FROM p_karyawan_pendidikan 
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_pendidikan.p_karyawan_id
			$join
			WHERE p_karyawan.active=1 and  p_karyawan_pendidikan.active=1  $where  ORDER BY p_karyawan_pendidikan.p_karyawan_id,tahun_lulus,p_karyawan_pendidikan.nama_sekolah ASC ";
		
			$data = DB::connection()->select($sql);
			
			$array = array(
				"NIK"=>"nik",
				"Nama Karyawan"=>"nama",
				"Jenjang"=>"jenjang",
				"Nama Sekolah"=>"nmsekolah",
				"Jurusan"=>"nmjurusan",
				"Tahun Lulus"=>"tahun_lulus",
				"Alamat Sekolah"=>"alamat_sekolah",
				"Kota Sekolah"=>"kota_sekolah",
			);
			
		}else if($type=='kursus'){
			$sql = "SELECT *,case sertifikat when 1 then 'Ya' else 'Tidak' end as sertifikat FROM p_karyawan_kursus
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_kursus.p_karyawan_id
			$join
			WHERE p_karyawan.active=1 and  p_karyawan_kursus.active=1  $where  ORDER BY p_karyawan_kursus.p_karyawan_id,p_karyawan_kursus.nama_kursus ASC ";
		
			$data = DB::connection()->select($sql);
			
			$array = array(
				"NIK"=>"nik",
				"Nama Karyawan"=>"nama",
				
				"Nama Kursus"=>"nama_kursus",
				"Penyelenggara"=>"penyelenggara",
				"Tanggal Awal Pelatihan"=>"tanggal_awal_pelatihan",
				"Tanggal Akhir Pelatihan"=>"tanggal_akhir_pelatihan",
				"sertifikat"=>"sertifikat",
			);
		}else if($type=='keluarga'){
			$sql = "SELECT *, p_karyawan.nama as nama_lengkap, p_karyawan_keluarga.nama as nama_keluarga FROM p_karyawan_keluarga
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_keluarga.p_karyawan_id
			$join
			WHERE p_karyawan.active=1 and  p_karyawan_keluarga.active=1  $where  ORDER BY p_karyawan_keluarga.p_karyawan_id,p_karyawan_keluarga.tgl_lahir ASC ";
		
			$data = DB::connection()->select($sql);
			
			$array = array(
				"NIK"=>"nik",
				"Nama Karyawan"=>"nama_lengkap",
				
				"Hubungan"=>"hubungan",
				"Nama"=>"nama_keluarga",
				"No Hp"=>"no_hp",
				"Tanggal Lahir"=>"tgl_lahir",
			);
		}else if($type=='pakaian'){
			$sql = "SELECT *
	    , p_karyawan.nama as nama_lengkap, c.nama as nama_keluarga,CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN p_karyawan.nama
	   				ELSE c.nama END as nama
	   				,
	   				CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN 'Diri Sendiri'
	   				ELSE c.hubungan END as hubungan
	   				
	     FROM p_karyawan_pakaian 
	     	left join p_karyawan  on p_karyawan_pakaian.p_karyawan_id = p_karyawan.p_karyawan_id 
	     	left join p_karyawan_keluarga c on p_karyawan_pakaian.p_karyawan_keluarga_id = c.p_karyawan_keluarga_id 
	     	$join
	     WHERE p_karyawan.active=1 and   tipe=2 and p_karyawan_pakaian.active=1
	      $where 
	      ORDER BY p_karyawan_pakaian.p_karyawan_id ASC ";
		
			$data = DB::connection()->select($sql);
			
			$array = array(
				"NIK"=>"nik",
				"Nama Karyawan"=>"nama_lengkap",
				
				"Nama Hubungan"=>"nama_keluarga",
				"Hubungan"=>"hubungan",
				"Hamis"=>"gamis",
				"Kemeja"=>"kemeja",
				"Kaos"=>"kaos",
				"Jaket"=>"jaket",
				"Celana"=>"celana",
				"Sepatu"=>"sepatu",
			);
			
		}else if($type=='award'){
			$sql = "SELECT * FROM p_karyawan_award 
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_award.p_karyawan_id
			left join m_jenis_reward on m_jenis_reward.m_jenis_reward_id = p_karyawan_award.m_jenis_reward_id
			$join
	     WHERE  p_karyawan.active=1 and   p_karyawan_award.active=1
	      $where 
	      ORDER BY p_karyawan_award.p_karyawan_id,tgl_award ASC ";
		
			$data = DB::connection()->select($sql);
			
			$array = array(
				"NIK"=>"nik",
				"Nama Karyawan"=>"nama",
				
				"Nama Jenis Reward"=>"nama_jenis_reward",
				"Hadiah"=>"hadiah",
				"Tangal Award"=>"tgl_award",
			);
			
		}else if($type=='sanksi'){
			$sql = "SELECT * FROM p_karyawan_sanksi 
			left join m_jenis_sanksi on p_karyawan_sanksi.m_jenis_sanksi_id = m_jenis_sanksi.m_jenis_sanksi_id 
			join p_karyawan on p_karyawan.p_karyawan_id = p_karyawan_sanksi.p_karyawan_id
			$join 
			WHERE p_karyawan.active=1 and  p_karyawan_sanksi.active=1  $where 
			ORDER BY tgl_awal_sanksi ASC ";
		
		
			$data = DB::connection()->select($sql);
			
			$array = array(
				"NIK"=>"nik",
				"Nama Karyawan"=>"nama",
				
				"Nama Sanksi"=>"nama_sanksi",
				"Tanggal Awal Sanksi"=>"tgl_awal_sanksi",
				"Tanggal Akhir Sanksi"=>"tgl_akhir_sanksi",
				"Alasan Sanksi"=>"alasan_sanksi",
			);
			
							
		}else{
			$sql = "SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap,case when b.periode_gajian=1 then 'Bulanan' else 'Pekanan' end as periode_gaji,j.nama as nama_kantor,
c.nama as nmlokasi,b.kantor,b.kota,d.nama as nmdivisi,f.nama as nmdept,g.nama as nmjabatan,
h.tgl_awal,h.tgl_akhir,m.no_absen,i.nama as nmstatus,pajak_onoff,bank,norek,nama_bank,g.job as jobweight , k.nama_grade as grade,l.*,z.m_jenis_kelamin_id,z.m_status_id,a.jumlah_anak,case when m_status_id=0 then 'Belum Menikah' else
			'Sudah Menikah' end as status_pernikahan,y.nama as jenis_kelamin
FROM p_karyawan a
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_recruitment z on a.p_recruitment_id=z.p_recruitment_id
LEFT JOIN m_jenis_kelamin y on z.m_jenis_kelamin_id=y.m_jenis_kelamin_id

LEFT JOIN m_bank r on r.m_bank_id=b.m_bank_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
LEFT JOIN m_divisi d on d.m_divisi_id=b.m_divisi_id
LEFT JOIN m_departemen f on f.m_departemen_id=b.m_departemen_id
LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
LEFT JOIN m_karyawan_grade k on g.job>=k.job_min and g.job<= k.job_max
LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id  and h.active=1
LEFT JOIN p_karyawan_kartu l on l.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_status_pekerjaan i on i.m_status_pekerjaan_id=h.m_status_pekerjaan_id
LEFT JOIN m_office j on b.m_kantor_id=j.m_office_id 

LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
                    WHERE p_karyawan.active=1 and  1=1 and a.active=1 
			
			 $where 
			order by a.nama ";
		
		
			$data = DB::connection()->select($sql);
			
			$array = array(
				"NIK"=>"nik",
				"Nama Karyawan"=>"nama_lengkap",
				
				"Entitas"=>"nmlokasi",
				"Kantor"=>"nama_kantor",
				"Kota"=>"kota",
				"Divisi"=>"nmdivisi",
				"Departement"=>"nmdept",
				"Jabatan"=>"nmjabatan",
				"Pajak On Off"=>"pajak_onoff",
				"Bank"=>"nama_bank",
				"No Rek"=>"norek",
				"Periode Gajian"=>"periode_gaji",
				"Status Pekerjaan"=>"nmstatus",
				"No Absen"=>"no_absen",
				"Job Weight"=>"jobweight",
				"Grade"=>"grade",
				"Tanggal Masuk"=>"tgl_awal",
				"Tanggal Keluar"=>"tgl_akhir",
				"No KTP"=>"ktp",
				"No Kartu Keluarga"=>"kartu_keluarga",
				"No SIM A"=>"no_sima",
				"No SIM C"=>"no_simc",
				"No NPWP"=>"no_npwp",
				"No BPJS Kesehatan"=>"no_bpjsks",
				"No BPJS Ketenagakerjaan"=>"no_bpjstk",
				"Jenis Kelamin"=>"jenis_kelamin",
				"Status Menikah"=>"status_pernikahan",
				"Jumlah Anak"=>"jumlah_anak",
			);
		}
		$i = 0;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'No');$i++;
		foreach($array as $key => $value){
		        $sheet->setCellValue($help->toAlpha($i) . '1', $key);$i++;
			
		}
        


        $rows = 2;
		

        if (!empty($data)) {

            $no = 0;
            $nominal = 0;
            foreach ($data as $data) {
                
                $no++;
                $i=0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $no);$i++;
                foreach($array as $key => $value){
//                	$sheet->setCellValue($help->toAlpha($i) . $rows, $data->$value);
                	if($value=='no_hp'){
                		$sheet->setCellValueExplicit($help->toAlpha($i) . $rows, $data->$value, DataType::TYPE_STRING);
                	}else{
                	$sheet->setCellValue($help->toAlpha($i) . $rows, $data->$value);
                		
                	}
                	$i++;
             	}
                $rows++;
            }
        }

       

        $type = 'xlsx';
        $fileName = "Rekap Karyawan.xlsx";
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function view_karyawan($id)
    {
        $sqlkaryawan="
        SELECT a.p_karyawan_id,a.nik,foto,b.nama_lengkap,b.nama_panggilan,c.nama as nmjk,d.nama as nmkota,e.nama as nmagama,g.nama as nmlokasi,h.nama as nmjabatan,i.nama as nmdept,j.nama as nmdivisi,l.nama as nmstatus,m.no_absen,
	f.is_shift,	case when f.is_shift=0 then 'Shift' else
			'Non Shift' end as shift,k.tgl_awal,k.tgl_akhir,a.tgl_bergabung,a.email_perusahaan,k.keterangan,
	case when a.active=1 then 'Active' else
			'Non Active' end as status,o.nama as nmpangkat,b.alamat_ktp,a.pendidikan,a.jurusan,a.nama_sekolah,a.domisili,a.jumlah_anak,b.tempat_lahir,
	case when m_status_id=0 then 'Belum Menikah' else
			'Sudah Menikah' end as status_pernikahan,b.no_hp,c.nama as jenis_kelamin,e.nama as agama,b.email,b.tgl_lahir,f.kantor,n.ktp,n.no_npwp,n.no_bpjstk,n.no_bpjsks,b.m_status_id,b.m_kota_id,b.m_jenis_kelamin_id,b.m_agama_id,f.m_lokasi_id,f.m_jabatan_id,k.m_status_pekerjaan_id,f.m_departemen_id,f.m_divisi_id,a.active,f.kota,n.no_sima,n.no_simc,n.no_pasport,
	case when f.periode_gajian=1 then 'Bulanan' else
			'Pekanan' end as periode,f.periode_gajian,p.nama as nama_kantor,q.nama_grade,f.bank,f.norek,r.nama_bank,
			m_directorat_id ,f.m_divisi_new_id ,f.m_departemen_id,z.nama as nama_mesin
FROM p_karyawan a
LEFT JOIN p_recruitment b on b.p_recruitment_id=a.p_recruitment_id
LEFT JOIN m_jenis_kelamin c on c.m_jenis_kelamin_id=b.m_jenis_kelamin_id
LEFT JOIN m_kota d on d.m_kota_id=b.m_kota_id
LEFT JOIN m_agama e on e.m_agama_id=b.m_agama_id
LEFT JOIN p_karyawan_pekerjaan f on f.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_bank r on r.m_bank_id=f.m_bank_id
LEFT JOIN m_lokasi g on g.m_lokasi_id=f.m_lokasi_id
LEFT JOIN m_jabatan h on h.m_jabatan_id=f.m_jabatan_id
LEFT JOIN m_pangkat o on o.m_pangkat_id=h.m_pangkat_id
LEFT JOIN m_departemen i on i.m_departemen_id=f.m_departemen_id
LEFT JOIN m_divisi j on j.m_divisi_id=f.m_divisi_id
LEFT JOIN p_karyawan_kontrak k on k.p_karyawan_id=a.p_karyawan_id  and k.active=1
LEFT JOIN m_status_pekerjaan l on l.m_status_pekerjaan_id=k.m_status_pekerjaan_id
LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_karyawan_kartu n on n.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_office p on p.m_office_id=f.m_kantor_id
LEFT JOIN m_mesin_absen z on p.m_mesin_absen_seharusnya_id=z.mesin_id
LEFT JOIN m_karyawan_grade q on q.m_karyawan_grade_id=f.m_karyawan_grade_id
WHERE a.p_karyawan_id=$id";
        $karyawan=DB::connection()->select($sqlkaryawan);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
$sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkota="SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
        $kota=DB::connection()->select($sqlkota);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);

        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi 
                      FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id
                      WHERE m_jabatan.active=1 ORDER BY m_jabatan.nama ASC";
        $jabatan=DB::connection()->select($sqljabatan);

        $sqlgrade="SELECT * FROM m_grade WHERE active=1 ORDER BY nama ASC ";
        $grade=DB::connection()->select($sqlgrade);
        
        $where = "";
        if($karyawan[0]->m_lokasi_id)
        $where = "and m_lokasi_id = ".$karyawan[0]->m_lokasi_id;
        $sqlgrade="SELECT * FROM m_directorat WHERE active=1 $where ORDER BY nama_directorat ASC ";
        $directorat=DB::connection()->select($sqlgrade);
        
        $where = "";
        if($karyawan[0]->m_directorat_id)
        $where = "and m_directorat_id = ".$karyawan[0]->m_directorat_id;
        
        $sqlgrade="SELECT * FROM m_divisi_new WHERE active=1 $where ORDER BY nama_divisi ASC ";
        $divisi_new=DB::connection()->select($sqlgrade);

        $sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
        $pangkat=DB::connection()->select($sqlpangkat);

        $where = "";
        if($karyawan[0]->m_divisi_new_id)
        $where = "and m_divisi_new_id = ".$karyawan[0]->m_divisi_new_id;
        
        $sqldivisi="SELECT * FROM m_divisi WHERE active=1 $where ORDER BY nama ASC ";
        $divisi=DB::connection()->select($sqldivisi);
        
        $where = "";
        if($karyawan[0]->m_divisi_id)
        $where = "and m_divisi_id = ".$karyawan[0]->m_divisi_id;
        
        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 $where ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);

        $where = "";
        if($karyawan[0]->m_departemen_id)
        $where = "and m_departemen_id = ".$karyawan[0]->m_departemen_id. '';
        
        $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi 
                      FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id
                      WHERE m_jabatan.active=1 $where ORDER BY m_jabatan.nama ASC";
        $jabatan=DB::connection()->select($sqljabatan);
         $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);
        return view('backend.karyawan.view_karyawan', compact('karyawan','user','divisi','pangkat','divisi_new','directorat','departemen','jabatan','lokasi'));
    }

    public function edit_karyawan($id)
    {
        $sqlkaryawan="SELECT a.p_karyawan_id,a.nik,foto,b.nama_lengkap,b.nama_panggilan,c.nama as nmjk,d.nama as nmkota,e.nama as nmagama,g.nama as nmlokasi,h.nama as nmjabatan,i.nama as nmdept,j.nama as nmdivisi,l.nama as nmstatus,m.no_absen,n.kartu_keluarga,pajak_onoff,f.is_shift, f.m_mesin_absen_id,

	case when f.is_shift=0 then 'Shift' else
			'Non Shift' end as shift,k.tgl_awal,k.tgl_akhir,a.tgl_bergabung,a.email_perusahaan,k.keterangan,
	case when a.active=1 then 'Active' else
			'Non Active' end as status,o.nama as nmpangkat,b.alamat_ktp,a.pendidikan,a.jurusan,a.nama_sekolah,a.domisili,a.jumlah_anak,b.tempat_lahir,
	case when m_status_id=0 then 'Belum Menikah' else
			'Sudah Menikah' end as status_pernikahan,b.no_hp,c.nama as jenis_kelamin,e.nama as agama,b.email,b.tgl_lahir,f.kantor,n.ktp,n.no_npwp,n.no_bpjstk,n.no_bpjsks,b.m_status_id,b.m_kota_id,b.m_jenis_kelamin_id,b.m_agama_id,f.m_lokasi_id,f.m_jabatan_id,k.m_status_pekerjaan_id,f.m_departemen_id,f.m_divisi_id,a.active,f.kota,n.no_sima,n.no_simc,n.no_pasport,n.*,
	case when f.periode_gajian=1 then 'Bulanan' else
			'Pekanan' end as periode_absen,f.periode_gajian,p.nama as nama_kantor,q.nama_grade,f.bank,r.nama_bank,f.norek,r.m_bank_id,f.m_kantor_id,f.m_karyawan_grade_id,f.is_shift,bpjs_kantor,tgl_bpjs_kantor,f.m_divisi_new_id,m_directorat_id
FROM p_karyawan a
LEFT JOIN p_recruitment b on b.p_recruitment_id=a.p_recruitment_id
LEFT JOIN m_jenis_kelamin c on c.m_jenis_kelamin_id=b.m_jenis_kelamin_id
LEFT JOIN m_kota d on d.m_kota_id=b.m_kota_id
LEFT JOIN m_agama e on e.m_agama_id=b.m_agama_id
LEFT JOIN p_karyawan_pekerjaan f on f.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_lokasi g on g.m_lokasi_id=f.m_lokasi_id
LEFT JOIN m_jabatan h on h.m_jabatan_id=f.m_jabatan_id
LEFT JOIN m_pangkat o on o.m_pangkat_id=h.m_pangkat_id
LEFT JOIN m_bank r on r.m_bank_id=f.m_bank_id
LEFT JOIN m_departemen i on i.m_departemen_id=f.m_departemen_id
LEFT JOIN m_divisi j on j.m_divisi_id=f.m_divisi_id
LEFT JOIN p_karyawan_kontrak k on k.p_karyawan_id=a.p_karyawan_id  and k.active=1
LEFT JOIN m_status_pekerjaan l on l.m_status_pekerjaan_id=k.m_status_pekerjaan_id
LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_karyawan_kartu n on n.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_office p on p.m_office_id=f.m_kantor_id
LEFT JOIN m_karyawan_grade q on q.m_karyawan_grade_id=f.m_karyawan_grade_id
WHERE a.p_karyawan_id=$id";

        $karyawan=DB::connection()->select($sqlkaryawan);
		$sql="SELECT * FROM m_bank WHERE active=1 ORDER BY nama_bank ASC ";
		$bank=DB::connection()->select($sql);
        $sqlagama="SELECT * FROM m_agama WHERE active=1 ORDER BY nama ASC ";
        $agama=DB::connection()->select($sqlagama);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkota="SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
        $kota=DB::connection()->select($sqlkota);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);

        // $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
        // $departemen=DB::connection()->select($sqldepartemen);
        
        $sqldepartemen="SELECT * FROM m_mesin_absen WHERE active=1 ORDER BY nama ASC ";
        $absen=DB::connection()->select($sqldepartemen);

        // $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi 
        //               FROM m_jabatan 
        //               LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
        //               LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id
        //               WHERE m_jabatan.active=1 ORDER BY m_jabatan.nama ASC";
        // $jabatan=DB::connection()->select($sqljabatan);
        $sqlgrade="SELECT * FROM m_directorat WHERE active=1 ORDER BY nama_directorat ASC ";
        $directorat=DB::connection()->select($sqlgrade);
        
        $sqlgrade="SELECT * FROM m_divisi_new WHERE active=1 ORDER BY nama_divisi ASC ";
        $divisi_new=DB::connection()->select($sqlgrade);

        $sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
        $pangkat=DB::connection()->select($sqlpangkat);

        $sqldivisi="SELECT * FROM m_divisi WHERE active=1 ORDER BY nama ASC ";
        $divisi=DB::connection()->select($sqldivisi);


        $where = "";
        if($karyawan[0]->m_lokasi_id)
        $where = "and m_lokasi_id = ".$karyawan[0]->m_lokasi_id;
        $sqlgrade="SELECT * FROM m_directorat WHERE active=1 $where ORDER BY nama_directorat ASC ";
        $directorat=DB::connection()->select($sqlgrade);
        
        $where = "";
        if($karyawan[0]->m_directorat_id)
        $where = "and m_directorat_id = ".$karyawan[0]->m_directorat_id;
        
        $sqlgrade="SELECT * FROM m_divisi_new WHERE active=1 $where ORDER BY nama_divisi ASC ";
        $divisi_new=DB::connection()->select($sqlgrade);

        $sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
        $pangkat=DB::connection()->select($sqlpangkat);

        $where = "";
        if($karyawan[0]->m_divisi_new_id)
        $where = "and m_divisi_new_id = ".$karyawan[0]->m_divisi_new_id;
        
        $sqldivisi="SELECT * FROM m_divisi WHERE active=1 $where ORDER BY nama ASC ";
        $divisi=DB::connection()->select($sqldivisi);
        
        $where = "";
        if($karyawan[0]->m_divisi_id)
        $where = "and m_divisi_id = ".$karyawan[0]->m_divisi_id;
        
        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 $where ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);

        $where = "";
        if($karyawan[0]->m_departemen_id)
        $where = "and m_departemen_id = ".$karyawan[0]->m_departemen_id. '';
        
        $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi 
                      FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id
                      WHERE m_jabatan.active=1 $where ORDER BY m_jabatan.nama ASC";
        $jabatan=DB::connection()->select($sqljabatan);              
        $sqlgrade="SELECT * FROM m_grade WHERE active=1 ORDER BY nama ASC ";
        $grade=DB::connection()->select($sqlgrade);
        
       
        $sqlstspekerjaan="SELECT * FROM m_status_pekerjaan WHERE active=1 ORDER BY nama ASC ";
        $stspekerjaan=DB::connection()->select($sqlstspekerjaan);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

 		$sqlgrade="SELECT * FROM m_karyawan_grade WHERE active=1 ORDER BY nama_grade ASC ";
        $karyawan_grade=DB::connection()->select($sqlgrade);
        
        $sqlkantor="SELECT * FROM m_office WHERE active=1 ORDER BY nama ASC ";
        $kantor=DB::connection()->select($sqlkantor);
		
		$sql = "SELECT * FROM p_karyawan_keluarga WHERE active=1 and p_karyawan_id=$id ORDER BY nama ASC ";
		$keluarga = DB::connection()->select($sql);
		$sql = "SELECT * FROM p_karyawan_pendidikan WHERE active=1 and p_karyawan_id=$id ORDER BY nama_sekolah ASC ";
		$pendidikan = DB::connection()->select($sql);
		$sql = "SELECT * FROM p_karyawan_kursus WHERE active=1 and p_karyawan_id=$id ORDER BY nama_kursus ASC ";
		$kursus = DB::connection()->select($sql);
		$sqlidkar = "SELECT * FROM p_karyawan_pakaian WHERE p_karyawan_id=$id and tipe=1";
		$datapakaian = DB::connection()->select($sqlidkar);
		$sqlidkar = "SELECT *
	    ,CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN d.nama
	   				ELSE c.nama END as nama
	   				,
	   				CASE
				    WHEN p_karyawan_pakaian.p_karyawan_keluarga_id =-1 THEN 'Diri Sendiri'
	   				ELSE c.hubungan END as hubungan
	   				
	     FROM p_karyawan_pakaian 
	     	left join p_karyawan D on p_karyawan_pakaian.p_karyawan_id = d.p_karyawan_id 
	     	left join p_karyawan_keluarga c on p_karyawan_pakaian.p_karyawan_keluarga_id = c.p_karyawan_keluarga_id 
	     
	     WHERE p_karyawan_pakaian.p_karyawan_id=$id and tipe=2 and p_karyawan_pakaian.active=1";
		$datapakaiankeluarga = DB::connection()->select($sqlidkar);

		$sql = "SELECT * FROM p_karyawan_riwayat_pekerjaan WHERE active=1 and p_karyawan_id = $id ORDER BY awal_periode ASC ";
		$datapekerjaan = DB::connection()->select($sql);
		
		$sql = "SELECT * FROM p_karyawan_award left join m_jenis_reward on m_jenis_reward.m_jenis_reward_id = p_karyawan_award.m_jenis_reward_id WHERE p_karyawan_award.active=1 and p_karyawan_id = $id ORDER BY tgl_award ASC ";
		$award = DB::connection()->select($sql);
		
        return view('backend.karyawan.edit_karyawan',compact('lokasi','absen','kota','jk','departemen','jabatan','grade','stspekerjaan','agama','karyawan','divisi','pangkat','user','bank','karyawan_grade','kantor','divisi_new','directorat','award','datapekerjaan','datapakaiankeluarga','datapakaian','kursus','pendidikan','keluarga','id'));
    }

    public function update_karyawan(Request $request, $id){
        DB::beginTransaction();
        try{
        	DB::enableQueryLog();
            $iduser=Auth::user()->id;
            $sqlidkar="SELECT * FROM p_karyawan left join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id WHERE p_karyawan_pekerjaan.p_karyawan_id=$id";
            $datakar=DB::connection()->select($sqlidkar);
            $idrec=$datakar[0]->p_recruitment_id;
            $idkaryawan=$datakar[0]->p_karyawan_id;
            $id_karyawan=$datakar[0]->p_karyawan_id;
            
            
            $karyawan1 = DB::connection()->select("select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where p_karyawan.p_karyawan_id  = $id_karyawan");
            		
            if($datakar[0]->m_jabatan_id != $request->get("jabatan")){
            	
            	DB::connection()->table("absen_libur_shift")
											->where("tanggal",'>=',date('Y-m-d'))
											->where("p_karyawan_id",$idkaryawan)
											->update(['active'=>0]);
							
							DB::connection()->table("absen_shift")
											->where("tanggal",'>=',date('Y-m-d'))
											->where("p_karyawan_id",$idkaryawan)
											->update(['active'=>0]);
            }
            
            if(!empty($idrec)){
                DB::connection()->table("p_recruitment")
                    ->where("p_recruitment_id",$idrec)
                    ->update([
                        "nama_lengkap" => $request->get("nama_lengkap"),
                        "nama_panggilan" => $request->get("nama_panggilan"),
                        "m_jenis_kelamin_id" => $request->get("jk"),
                        "email" => $request->get("email"),
                        "no_hp" => $request->get("no_hp"),
                        "tempat_lahir" => $request->get("tempat_lahir"),
                        "tgl_lahir" => date('Y-m-d',strtotime($request->get("tgl_lahir"))),
                        "m_status_id" => $request->get("status_pernikahan"),
                        "no_ktp" => $request->get("ktp"),
                        "alamat_ktp" => $request->get("alamat_ktp"),
                        "alamat_tinggal" => $request->get("alamat_ktp"),
                        "active" => 1,
                        "update_date" => date("Y-m-d"),
                        "update_by" => $iduser,
                    ]);
            }
            else{
                $sqlidrec="SELECT max(p_recruitment_id)+1 as p_recruitment_id FROM p_recruitment";
                $datarec=DB::connection()->select($sqlidrec);
                $idrec=$datarec[0]->p_recruitment_id;
                $bln=date('m');
                $thn=date('y');
                $koderec='1.REC.'.$bln.'.'.$thn.'.'.$idrec;

                DB::connection()->table("p_recruitment")
                    ->insert([
                        "p_recruitment_id" => $idrec,
                        "kode" => $koderec,
                        "nama_lengkap" => $request->get("nama_lengkap"),
                        "nama_panggilan" => $request->get("nama_panggilan"),
                        "m_kota_id" => $request->get("kota"),
                        "m_jenis_kelamin_id" => $request->get("jk"),
                        "m_agama_id" => $request->get("agama"),
                        "email" => $request->get("email"),
                        "no_hp" => $request->get("no_hp"),
                        "tempat_lahir" => $request->get("tempat_lahir"),
                        "tgl_lahir" => date('Y-m-d',strtotime($request->get("tgl_lahir"))),
                        "m_status_id" => $request->get("status_pernikahan"),
                        "no_ktp" => $request->get("ktp"),
                        "alamat_ktp" => $request->get("alamat_ktp"),
                        "alamat_tinggal" => $request->get("alamat_ktp"),
                        "active" => 1,
                        "create_date" => date("Y-m-d"),
                        "create_by" => $iduser,
                    ]);
            }
			
			
            $lokasi=$request->get("lokasi");
            $sql="SELECT tgl_bergabung,user_id,p_karyawan_pekerjaan.m_lokasi_id FROM p_karyawan
            left join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
					WHERE p_karyawan.p_karyawan_id = $id ";
            //echo $sql;die;
            $datasql=DB::connection()->select($sql);
            $user_id = $datasql[0]->user_id;
            $karyawan_lokasi_id = $datasql[0]->m_lokasi_id;
			if($karyawan_lokasi_id != $lokasi){
				
				$blnkontrak=date('m',strtotime($datasql[0]->tgl_bergabung));
				$thnkontrak=date('y',strtotime($datasql[0]->tgl_bergabung));
				$thnkontrak2=date('Y',strtotime($datasql[0]->tgl_bergabung));
				$blnkontrak2 = $blnkontrak+1;
				if($blnkontrak2==13){
				    $blnkontrak2=1;
				    $thnkontrak2+=1;
				}
				$sql="SELECT count(*)as count FROM p_karyawan
						
            left join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
						WHERE tgl_bergabung >='$thnkontrak2-$blnkontrak-01' and tgl_bergabung <'$thnkontrak2-$blnkontrak2-01' and m_lokasi_id = $lokasi ";
	            //echo $sql;die;
	            $datasql=DB::connection()->select($sql);
	            $jumlah=($datasql[0]->count)+1;
	            if(strlen($jumlah)==1){
	                $nik='0'.$jumlah;
	            }
	            else{
	                $nik=$jumlah;
	            }
				;
	            $sqlokasi="SELECT * FROM m_lokasi WHERE m_lokasi_id=$lokasi";
	            //echo $sql;
	            $datalokasi=DB::connection()->select($sqlokasi);
	            $kodenik=$datalokasi[0]->kode_nik;
	            $nik=$kodenik.$thnkontrak.$blnkontrak.$nik;
	            $loop = true;
	            while($loop){
	            	
	            $sqlokasi="SELECT * FROM p_karyawan WHERE nik='$nik'";
	            $datalokasi=DB::connection()->select($sqlokasi);
	            if(count($datalokasi)){
	            	$jumlah+=1;
	            	 if(strlen($jumlah)==1){
		                $nik='0'.$jumlah;
			            }
			            else{
			                $nik=$jumlah;
			            }
						;
					$nik=$kodenik.$thnkontrak.$blnkontrak.$nik;
	            }else{
	            	$loop=false;
	            }
	            }
	            DB::connection()->table("p_karyawan")
	                ->where("p_karyawan_id",$id)
	                ->update([
	                    "nik" => $nik,
	                    "nama" => $request->get("nama_lengkap"),
	                    "email_perusahaan" => $request->get("email"),
	                    "tgl_bergabung" => date('Y-m-d',strtotime($request->get("tgl_bergabung"))),
	                   // "tgl_bergabung" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
	                    "ukuran_baju"=>$request->get("ukuran_baju"),
	                    "pendidikan"=>$request->get("pendidikan"),
	                    "jurusan"=>$request->get("jurusan"),
	                    "nama_sekolah"=>$request->get("nama_sekolah"),
	                    "no_hp2"=>$request->get("no_hp"),
	                    "domisili"=>$request->get("domisili"),
	                    "jumlah_anak"=>$request->get("jumlah_anak"),
	                    "active" => $request->get("status"),
	                    "update_date" => date("Y-m-d"),
	                    "update_by" => $iduser,
	                ]);
				if($user_id){
					DB::connection()->table("users")
	                ->where("id",$user_id)
	                ->update([
	                    "username" => $nik,
	                ]);
				}
			}else{
				DB::connection()->table("p_karyawan")
	                ->where("p_karyawan_id",$id)
	                ->update([
	                    "nama" => $request->get("nama_lengkap"),
	                    "email_perusahaan" => $request->get("email"),
	                //    "tgl_bergabung" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
	                "tgl_bergabung" => date('Y-m-d',strtotime($request->get("tgl_bergabung"))),
	                    "ukuran_baju"=>$request->get("ukuran_baju"),
	                    "pendidikan"=>$request->get("pendidikan"),
	                    "jurusan"=>$request->get("jurusan"),
	                    "nama_sekolah"=>$request->get("nama_sekolah"),
	                    "no_hp2"=>$request->get("no_hp"),
	                    "domisili"=>$request->get("domisili"),
	                    "jumlah_anak"=>$request->get("jumlah_anak"),
	                    "active" => $request->get("status"),
	                    "update_date" => date("Y-m-d"),
	                    "update_by" => $iduser,
	                ]);
			}
            $sqlidkarpek="SELECT * FROM p_karyawan_pekerjaan WHERE p_karyawan_id=$id";
            $datakarpek=DB::connection()->select($sqlidkarpek);
            if(!empty($datakarpek)){
                DB::connection()->table("p_karyawan_pekerjaan")
                    ->where("p_karyawan_id",$id)
                    ->update([
                        "m_lokasi_id" => $request->get("lokasi"),
                        "m_departemen_id" => $request->get("departemen"),
                        "m_jabatan_id" => $request->get("jabatan"),
                        "m_divisi_id" => $request->get("divisi"),
                        "m_directorat_id" => $request->get("directorat"),
                        "m_divisi_new_id" => $request->get("divisi_new"),
                        "m_mesin_absen_id" => $request->get("lokasi_absen"),
                        
                        "kantor" => $request->get("kantor"),
                        "kota" => $request->get("kotakerja"),
						
						"is_shift" => $request->get("is_shift"),
                        "periode_gajian" => $request->get("periode_gajian"),
						"m_bank_id" => $request->get("bank"),
                    "norek" => $request->get("norek"),
                    "bpjs_kantor" => $request->get("bpjs_kantor"),
                        "tgl_bpjs_kantor" => $request->get("tgl_bpjs_kantor"),
                    "pajak_onoff" => $request->get("pajakonoff"),
                    "m_kantor_id" => $request->get("kantor"),
                    "m_karyawan_grade_id" => $request->get("grade"),
                        "active" => 1,
                        "update_date" => date("Y-m-d"),
                        "update_by" => $iduser,
                    ]);
            }
            else{
                DB::connection()->table("p_karyawan_pekerjaan")
                    ->insert([
                        "p_karyawan_id" => $id,
                        "m_lokasi_id" => $request->get("lokasi"),
                        "m_departemen_id" => $request->get("departemen"),
                        "m_jabatan_id" => $request->get("jabatan"),
                        "m_divisi_id" => $request->get("divisi"),
	                    "bank" => $request->get("bank"),
	                    "norek" => $request->get("norek"),
						"is_shift" => $request->get("is_shift"),
	                    "m_kantor_id" => $request->get("kantor"),
	                    "m_karyawan_grade_id" => $request->get("grade"),	
                        "kantor" => $request->get("kantor"),
                        "kota" => $request->get("kotakerja"),
                        "active" => 1,
                        "create_date" => date("Y-m-d"),
                        "create_by" => $iduser,
                    ]); 
            }


            if($request->get("status_pekerjaan")=='10'){
                $tgl_akhir=null;
            }
            else{
                $tgl_akhir=date('Y-m-d',strtotime($request->get("tgl_akhir")));
            }
            // $sqlidkarkon="SELECT * FROM p_karyawan_kontrak WHERE p_karyawan_id=$id";
            // $datakarkon=DB::connection()->select($sqlidkarkon);
            // if(!empty($datakarkon)){
            //     DB::connection()->table("p_karyawan_kontrak")
            //         ->where("p_karyawan_id",$id)
            //         ->update([
            //             "tgl_awal" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
            //             "tgl_akhir" => $tgl_akhir,
            //             "m_status_pekerjaan_id" => $request->get("status_pekerjaan"),
            //             "keterangan" => $request->get("keterangan"),
            //             "active" => 1,
            //             "update_date" => date("Y-m-d"),
            //             "update_by" => $iduser,
            //         ]);
            // }
            // else{
            //     DB::connection()->table("p_karyawan_kontrak")
            //         ->insert([
            //             "p_karyawan_id" => $id,
            //             "tgl_awal" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
            //             "tgl_akhir" => $tgl_akhir,
            //             "m_status_pekerjaan_id" => $request->get("status_pekerjaan"),
            //             "keterangan" => $request->get("keterangan"),
            //             "active" => 1,
            //             "create_date" => date("Y-m-d"),
            //             "create_by" => $iduser,
            //         ]);
            // }

            $sqlidkarkar="SELECT * FROM p_karyawan_kartu WHERE p_karyawan_id=$id";
            $datakarkar=DB::connection()->select($sqlidkarkar);
            if(!empty($datakarkar)){
                DB::connection()->table("p_karyawan_kartu")
                    ->where("p_karyawan_id",$id)
                    ->update([
                        "kartu_keluarga" => $request->get("kk"),
                        "ktp" => $request->get("ktp"),
                        "no_npwp" => $request->get("npwp"),
                        "no_bpjsks" => $request->get("bpjsks"),
                        "no_bpjstk" => $request->get("bpjstk"),
                        "no_sima" => $request->get("sima"),
                        "no_simc" => $request->get("simc"),
                        "no_pasport" => $request->get("pasport"),
                        "active" => 1,
                        "update_date" => date("Y-m-d"),
                        "update_by" => $iduser,
                    ]);
            }
            else{
                DB::connection()->table("p_karyawan_kartu")
                    ->insert([
                        "p_karyawan_id" => $id,
                        "kartu_keluarga" => $request->get("kk"),
                        "ktp" => $request->get("ktp"),
                        "no_npwp" => $request->get("npwp"),
                        "no_bpjsks" => $request->get("bpjsks"),
                        "no_bpjstk" => $request->get("bpjstk"),
                        "no_sima" => $request->get("sima"),
                        "no_simc" => $request->get("simc"),
                        "no_pasport" => $request->get("pasport"),
                        "active" => 1,
                        "create_date" => date("Y-m-d"),
                        "create_by" => $iduser,
                    ]);
            }

            $sqlidkarabsen="SELECT * FROM p_karyawan_absen WHERE p_karyawan_id=$id";
            $datakarabsen=DB::connection()->select($sqlidkarabsen);
            if(!empty($datakarabsen)){
                DB::connection()->table("p_karyawan_absen")
                    ->where("p_karyawan_id",$id)
                    ->update([
                        "no_absen" => $request->get("no_absen"),
                        "active" => 1,
                        "update_date" => date("Y-m-d"),
                        "update_by" => $iduser,
                    ]);
            }
            else{
                DB::connection()->table("p_karyawan_absen")
                    ->insert([
                        "p_karyawan_id" => $id,
                        "no_absen" => $request->get("no_absen"),
                        "active" => 1,
                        "create_date" => date("Y-m-d"),
                        "create_by" => $iduser,
                    ]);
            }
            
            
            
            $array = array("m_jabatan_id","m_departemen_id","m_lokasi_id","m_bank_id","m_kantor_id","m_divisi_id","m_directorat_id","m_divisi_new_id","bpjs_kantor","tgl_bpjs_kantor","norek","periode_gajian","nik","kota","is_shift","pajak_onoff");    
		          
		            $karyawan2 	= DB::connection()->select("select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where p_karyawan.p_karyawan_id  = $id_karyawan");
		            $data4['p_karyawan_id']	=$id_karyawan;
		            $data4['create_by']		=$iduser;
		            $data4['create_date']	=date('Y-m-d H:i:s');
		            foreach($karyawan1 as $karyawan){
		            	for($i=0;$i<count($array);$i++){
		            		$row = $array[$i];
			            	$data4['dari_'.$array[$i]] = $karyawan->$row;
		            	}
		            }
		            foreach($karyawan2 as $karyawan){
		            	for($i=0;$i<count($array);$i++){
		            		$row = $array[$i];
			            	$data4['ke_'.$array[$i]] = $karyawan->$row;
		            	}
		            }
		            DB::connection()->table("p_historis_pekerjaan")
		                ->insert($data4);
		                
		                
            $query = (DB::getQueryLog());$help = new Helper_function();
			$help->historis(($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),json_encode($query));
            DB::commit();
            return redirect()->route('be.karyawan')->with('success',' Karyawan Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_karyawan($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("p_karyawan")
                ->where("p_karyawan_id",$id)
                ->delete();

            $sqlidkarabs="SELECT * FROM p_karyawan_absen WHERE p_karyawan_id=$id";
            $datakarabs=DB::connection()->select($sqlidkarabs);
            if(!empty($datakarabs)){
                DB::connection()->table("p_karyawan_absen")
                    ->where("p_karyawan_id",$id)
                    ->delete();
            }

            $sqlidkarkar="SELECT * FROM p_karyawan_kartu WHERE p_karyawan_id=$id";
            $datakarkar=DB::connection()->select($sqlidkarkar);
            if(!empty($datakarkar)){
                DB::connection()->table("p_karyawan_kartu")
                    ->where("p_karyawan_id",$id)
                    ->delete();
            }

            $sqlidkarkon="SELECT * FROM p_karyawan_kontrak WHERE p_karyawan_id=$id";
            $datakarkon=DB::connection()->select($sqlidkarkon);
            if(!empty($datakarkon)){
                DB::connection()->table("p_karyawan_kontrak")
                    ->where("p_karyawan_id",$id)
                    ->delete();
            }

            $sqlidkarpek="SELECT * FROM p_karyawan_pekerjaan WHERE p_karyawan_id=$id";
            $datakarpek=DB::connection()->select($sqlidkarpek);
            if(!empty($datakarpek)){
                DB::connection()->table("p_karyawan_pekerjaan")
                    ->where("p_karyawan_id",$id)
                    ->delete();
            }
            DB::commit();
            return redirect()->back()->with('success','Karyawan Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function generate_nik(Request $request)
    {
        DB::beginTransaction();
        try{

            $sqlidkar="SELECT a.*,b.*,c.kode_nik FROM p_karyawan a
LEFT JOIN p_karyawan_pekerjaan b on a.p_karyawan_id=b.p_karyawan_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
WHERE a.active=1 and a.p_karyawan_id in(12) ORDER BY tgl_bergabung";
            $datakar=DB::connection()->select($sqlidkar);

            foreach ($datakar as $kar){
                $blnkontrak=date('m',strtotime($kar->tgl_bergabung));
                $thnkontrak=date('y',strtotime($kar->tgl_bergabung));
                $thnkontrak2=date('Y',strtotime($kar->tgl_bergabung));
                //echo $thnkontrak;die;
                $kodenik=$kar->kode_nik;
                $sql="SELECT count(*)+1 as count FROM p_karyawan
WHERE to_char(tgl_bergabung,'yy-mm')='".$thnkontrak.'-'.$blnkontrak."' and nik ilike '".$thnkontrak2.$blnkontrak."%' ";
                //echo $sql;
                $datasql=DB::connection()->select($sql);

                $jumlah=$datasql[0]->count;
                //echo $jumlah;die;
                if(strlen($jumlah)>1){
                    echo 'masuk';die;
                    $nik=$jumlah;
                }
                else{
                    //echo 'masuk2'.'-'.$jumlah;
                    $nol=0;
                    $nik=$nol.$jumlah;
                    //echo $nik;die;
                }
                $nik=$kodenik.$thnkontrak.$blnkontrak.$nik;
                $sqlcek="select * from p_karyawan where nik='".$nik."' ";
                $cek=DB::connection()->select($sqlcek);
                if(!empty($cek)){
                    $sql="SELECT count(*)+1 as count FROM p_karyawan
WHERE nik = '".$nik."' ";
                    //echo $sql;
                    $datasql=DB::connection()->select($sql);

                    $jumlah=$datasql[0]->count;
                    //$jumlah=strlen($jumlah);
                    //echo $jumlah;die;
                    if(strlen($jumlah)>1){
                        $nik=$jumlah;
                    }
                    else{
                        $nol=0;
                        $nik=$nol.$jumlah;
                    }
                    $urut=$nik+1;
                    //echo $nik;die;
                    $nik=$kodenik.$thnkontrak.$blnkontrak.$urut;
                }

                DB::connection()->table("p_karyawan")
                    ->where("p_karyawan_id",$kar->p_karyawan_id)
                    ->update([
                        "nik"=>$nik
                    ]);
                //echo '<pre>';
                //echo $nik;
                //echo '</pre>';
            }//die;

            DB::commit();
            return redirect()->back()->with('success','Generate Berhasil!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function karyawanresign()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND b.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
       
        $sqlkaryawan="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap,case when a.active=1 then 'Active' else 'Non Active' end as status,
c.nama as nmlokasi,b.kantor,b.kota,d.nama as nmdivisi,f.nama as nmdept,g.nama as nmjabatan,
h.tgl_awal,h.tgl_akhir,i.nama as nmstatus,a.update_date
FROM p_karyawan a
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
LEFT JOIN m_divisi d on d.m_divisi_id=b.m_divisi_id
LEFT JOIN m_departemen f on f.m_departemen_id=b.m_departemen_id
LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
LEFT JOIN p_karyawan_kontrak h on h.p_karyawan_id=a.p_karyawan_id  and h.active=1
LEFT JOIN m_status_pekerjaan i on i.m_status_pekerjaan_id=h.m_status_pekerjaan_id
                    WHERE 1=1 $whereLokasi and a.active=0 order by a.update_date desc, a.nama";
        $karyawan=DB::connection()->select($sqlkaryawan);
        return view('backend.karyawan.karyawanresign', compact('karyawan','user'));
    }

    public function historis_kerja_karyawan (Request $request)
    {
    	$iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access FROM users
left join m_role on m_role.m_role_id=users.role
left join p_karyawan on p_karyawan.user_id=users.id
left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND b.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
        $sqlkaryawan="SELECT a.p_karyawan_id,a.nik,a.nama as nama_lengkap,case when a.active=1 then 'Active' else 'Non Active' end as status,
c.nama as nmlokasi,g.nama as nmjabatan
FROM p_karyawan a
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_lokasi c on c.m_lokasi_id=b.m_lokasi_id
LEFT JOIN m_jabatan g on g.m_jabatan_id=b.m_jabatan_id
                    WHERE 1=1 $whereLokasi and a.active=1 order by a.nama";
        $karyawan=DB::connection()->select($sqlkaryawan);
        $historis = array();
        if($request->karyawan){
        	$array = array("m_jabatan"=>"m_jabatan_id"
        	,"m_departemen"=>"m_departemen_id"
        	,"m_lokasi"=>"m_lokasi_id"
        	
        	,"m_bank"=>"m_bank_id"
        	,"m_office"=>"m_kantor_id"
        	,"m_divisi"=>"m_divisi_id"
        	,"-1"=>"m_directorat_id"
        	,"-1"=>"bpjs_kantor"
        	,"-1"=>"tgl_bpjs_kantor"
        	,"-1"=>"norek"
        	,"-1"=>"periode_gajian"
        	,"-1"=>"nik"
        	,"-1"=>"kota"
        	,"-1"=>"is_shift"
        	,"-1"=>"pajak_onoff");    
		     	$select ="*";
		     	$join ="";
		     foreach($array as $key => $value){
		     	if($key=="m_office")
		     		$value = "m_office_id";
		     	$extend_nama = "";
		     	if($key=="m_bank")
		     		$extend_nama = "_bank";
		     	
		     	if($key!="-1")
		     	$select .=", dari_$key.nama$extend_nama as dari_$value
		     				,ke_$key.nama$extend_nama as ke_$value";
		     	else
		     	$select .=",dari_$value,ke_$value";
		     	
		     	if($key!="-1"){
		     	if($key=="m_office"){
		     		
		     		$dari= 'dari_m_kantor_id';
		     		$ke = 'ke_m_kantor_id';
		     	}else{
		     		$dari= 'dari_'.$value;
		     		$ke = 'ke_'.$value;
		     	}	
		     	$join .= "
		     	LEFT JOIN $key dari_$key on dari_$key.$value = p_historis_pekerjaan.$dari
		     	LEFT JOIN $key ke_$key on ke_$key.$value = p_historis_pekerjaan.$ke
		     	";
		     	}
		     }    
		    $sql="SELECT $select FROM p_historis_pekerjaan
				$join
				where p_historis_pekerjaan.p_karyawan_id=".$request->karyawan;
		        $historis=DB::connection()->select($sql);
		        
        }
        return view('backend.karyawan.historis_kerja_karyawan', compact('karyawan','historis','request'));
    }

    public function input_absen($pin)
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$date = date('Y-m-d');
        $sqlkaryawan="SELECT a.*,c.no_absen,d.mesin_id
FROM p_karyawan a
LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_karyawan_absen c on c.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_mesin_absen d on d.m_lokasi_id=b.m_lokasi_id

WHERE 1=1 and c.no_absen='$pin' and a.active=1 
order by a.nama";
        $karyawan=DB::connection()->select($sqlkaryawan);

        return view('backend.karyawan.input_absen', compact('user','pin','karyawan'));
    }
	public function ajax_input_absen()
    {
    	$id = $_POST['token'];
    	$tgl = date("Y-m-d",strtotime($_POST['tgl']));
		$sql = "
		select *, case when l.date_time is null then '$tgl 00:00:00' else l.date_time end from p_karyawan_absen a LEFT JOIN p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_karyawan_absen c on c.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_mesin_absen d on d.m_lokasi_id=b.m_lokasi_id
LEFT JOIN absen_log l on l.pin=c.no_absen and l.mesin_id = d.mesin_id and l.ver = 1 and l.date_time>= '$tgl' and l.date_time<= '$tgl 23:59'

where a.p_karyawan_id = $id
		";
		
		$data = DB::connection()->select($sql);
		echo json_encode($data[0]);	
    }
	public function export_absen()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,p_karyawan.p_karyawan_id FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        $sqlmesin="SELECT * FROM m_mesin_absen";
        $mesin=DB::connection()->select($sqlmesin);
		$date = date('Y-m-d');
       
        return view('backend.karyawan.export_absen', compact('user','mesin'));
    }public function download_excel_shift(Request $request)
    {
    		
			return  KaryawanController::excel_empty_data($request);
		
    }
    public function excel_absen($request)
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
		$date = $request->get('tgl_awal');		
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		for($i=0;$i<$help->hitungHari($request->get('tgl_awal'),$request->get('tgl_akhir'));$i++){
			$sheet->setCellValue($help->toAlpha($i).'1', $date);	$i++;
			$date= $help->tambah_tanggal($date,1);
		} 
		
		$rows = 4;

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
		$karyawan = $request->get('karyawan');
		$sql="SELECT * FROM p_karyawan
		left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
		where p_karyawan.active=1
			and p_karyawan_id in($karyawan)
		 order by nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		$sql = "select * from m_lokasi where m_lokasi_id=".$entitas;
		$hisentitas=DB::connection()->select($sql);
		
		$type = 'csv';
		$fileName = "Template Data Shift ".$hisentitas[0]->nama.".csv";
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}else if($type == 'csv'){
			$writer = new Csv($spreadsheet,';');
		}
		$writer->setDelimiter(';');
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
    }
	
    public function simpan_input_absen_hr2(Request $request)
    {
       
        try{
        	

           

           
           // return redirect()->route('admin')->with('success',' Absen Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function simpan_input_absen(Request $request)
    {
        DB::beginTransaction();
        try{
            $date_time_awal=date('Y-m-d',strtotime($request->get('tgl_absen'))).' '.$request->get('jam_masuk').'';
           
            DB::connection()->table("absen_log")
                ->insert([
                    "mesin_id" => $request->get('mesin'),
                    "pin" => $request->get('no_absen'),
                    "date_time" => $date_time_awal,
                    "ver" => 1,
                    "status_absen_id" => 1,
                    "created_at" => date('Y-m-d H:i:s'),
                ]);

           

            DB::commit();
            return redirect()->route('admin')->with('success',' Absen Karyawan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function aktif($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("p_karyawan")
                ->where("p_karyawan_id",$id)
                ->update([
                    "active"=>1
                ]);

            DB::commit();
            return redirect()->back()->with('success','Karyawan Berhasil di Aktifkan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function finddirectorat(Request $request)
    {
        $data = DB::connection()->select('select * from m_directorat where active=1 and  m_lokasi_id = '.$request->id);
        if(!count($data)){
            echo '<option value="">Tidak data directorat di bawah entitas terkait</option>';
        
        }else{
            echo'<option value="">- Directorat -</option>';
        foreach($data as $row){
            echo '<option value="'.$row->m_directorat_id.'">'.$row->nama_directorat.'</option>';
        }
        }
    }

    public function finddivisi(Request $request)
    {
        $data = DB::connection()->select('select * from m_divisi_new where active=1 and  m_directorat_id = '.$request->id);
        if(!count($data)){
            echo '<option value="">Tidak data divisi di bawah entitas terkait</option>';
        
        }else{
            echo'<option value="">- Divisi -</option>';
        foreach($data as $row){
            echo '<option value="'.$row->m_divisi_new_id.'">'.$row->nama_divisi.'</option>';
        }
        }
    }
    public function finddepartement(Request $request)
    {
        $data = DB::connection()->select('select * from m_divisi where active=1 and m_divisi_new_id = '.$request->id);
        if(!count($data)){
            echo '<option value="">Tidak data departement di bawah divisi terkait</option>';
        
        }else{
        echo'<option value="">- Departemen -</option>';
        foreach($data as $row){
            echo '<option value="'.$row->m_divisi_id.'">'.$row->nama.'</option>';
        }
        }
    }
    public function findseksi(Request $request)
    {
        $data = DB::connection()->select('select * from m_departemen where active=1 and  m_divisi_id = '.$request->id);
        echo'<option value="">- Seksi -</option>';
        foreach($data as $row){
            echo '<option value="'.$row->m_departemen_id.'">'.$row->nama.'</option>';
        }
    }
    public function findjabatan(Request $request)
    {
        $data = DB::connection()->select('select * from m_jabatan where active=1 and  m_departemen_id = '.$request->id);
         echo'<option value="">- Jabatan -</option>';
        foreach($data as $row){
            echo '<option value="'.$row->m_jabatan_id.'">'.$row->nama.'</option>';
        }
    }
}
