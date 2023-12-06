<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;

class lemburController extends Controller
{
    public function tambah_lembur_excel()
    {
    //	 $i=4;
	//echo $i!=1 and $i!=2 and $i!=3 ;die;
       $sqllokasi="select * from m_lokasi where active=1";
        $lokasi=DB::connection()->select($sqllokasi);
                $id = '';
                $type = 'simpan_lembur';
                $data['nama']='';
                $data['keterangan']='';
        $sql = "select * from m_lokasi where active=1";
		$entitas=DB::connection()->select($sql);
        return view('frontend.lembur.tambah_lembur_excel',compact('id','data','type','lokasi','entitas'));
    }
    public function perintah_lembur (Request $request)
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $help = new Helper_function();
        
        $sqllembur="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_lembur,d.nama as nama_appr,tgl_appr_1,status_appr_1,
			f.nama as nama_appr2,tgl_appr_2,status_appr_2 ,e.nama as pjs
		
		FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan f on f.p_karyawan_id=a.appr_2
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_atasan_input=$id  and a.active=1 and c.tipe=2 ORDER BY a.tgl_awal desc";
		$lembur=DB::connection()->select($sqllembur);

	  return view('frontend.lembur.perintah_lembur',compact('lembur','help','request'));
    } 
	public function tambah_perintah_lembur(Request $request)
    { 
    	$type = 'simpan_libur_lembur';
        $id = '';$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser ";
        $kar=DB::connection()->select($sqlidkar);
        $id=$kar[0]->p_karyawan_id;
        //echo $id;die;
        $id_karyawan = $kar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 and p_karyawan.p_karyawan_id in($bawahan) order by p_karyawan.nama";
        $karyawan=DB::connection()->select($sqlusers); 
        $data['tanggal'] = '';
        $data['p_karyawan'] = '';
        $data['keterangan'] = '';
        
        
        
		if(isset($atasan_layer[1])){
		 	$atasan =-1;
		    $atasan2 = $atasan_layer[1];
		    
			$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan2)   and m_pangkat_id not in (1,2,3)";
			$appr2=DB::connection()->select($sqlappr);
		}else{
		$atasan = -2;
		
		$atasan2 = -1;
		$appr2=null;
		}
		
        return view('frontend.lembur.tambah_perintah_lembur', compact('karyawan','id','data','type','kar','atasan','atasan2','appr2'));
    } 
    public function simpan_libur_lembur(Request $request){
        DB::beginTransaction();
        try{
        	$help = new Helper_function();
            $idUser=Auth::user()->id;
            $karyawan_lembur = $request->get("karyawan");
			for($i=0;$i<count($karyawan_lembur);$i++){
				DB::connection()->table("absen_libur_lembur")
               		 ->insert([
                   		"tanggal"=>($request->get('tanggal')),
                    	"p_karyawan_id"=>($karyawan_lembur[$i]),
                    	"keterangan"=>($request->get('keterangan')),
                    	
                    ]);
				
				}
           
            DB::commit();
            return redirect()->route('fe.hari_libur_lembur')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function lembur_duplicate_check_multi(Request $request)
	{
		$nama = $request->nama;
		$count = 0;
		$list_nama = "";
		for($i=0;$i<count($nama);$i++){
			$check =DB::connection()->select("select * from t_permit 
				left join p_karyawan on t_permit.p_karyawan_id = p_karyawan.p_karyawan_id
				where tgl_awal ='$request->tgl' and (
					(jam_awal>='$request->jam_awal' and '$request->jam_awal'<= jam_akhir) or 
					(jam_awal<='$request->jam_awal' and '$request->jam_awal'<= jam_akhir) ) and t_permit.p_karyawan_id = ".$nama[$i]."
			");
			$count += count($check);
			if($list_nama)
				$list_nama.=", ";
			if(count($check))
			$list_nama.=$check[0]->nama;
		}
		$return['count']= $count;
		$return['nama']= $list_nama;
		echo json_encode($return);
		
	}
    public function lembur_duplicate_check(Request $request)
	{
		$nama = $request->nama;
		$count = 0;
		$list_nama = "";
			$check =DB::connection()->select("select * from t_permit 
				left join p_karyawan on t_permit.p_karyawan_id = p_karyawan.p_karyawan_id
				where tgl_awal ='".date('Y-m-d',strtotime($request->tgl_awal))."' and (
					(jam_awal>='$request->jam_awal' and '$request->jam_awal'<= jam_akhir) or 
					(jam_awal<='$request->jam_awal' and '$request->jam_awal'<= jam_akhir) ) and t_permit.p_karyawan_id = $request->p_karyawan_id and t_permit.active=1
			");
			$count += count($check);
			if($list_nama)
				$list_nama.=", ";
			if(count($check))
			$list_nama.=$check[0]->nama;

		$return['count']= $count;
		$return['nama']= $list_nama;
		echo json_encode($return);
		
	}
    public function simpan_perintah_lembur(Request $request){
        DB::beginTransaction();
        try{
        	$help = new Helper_function();
            $idUser=Auth::user()->id;$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;
$help = new Helper_function();
          	$keluar = strtotime($request->get('jam_awal'));
		    $masuk = strtotime($request->get('jam_akhir'));
			 $total_jam =  $masuk-$keluar;
	        $lama_php =  gmdate('G', $total_jam	); ;
			$lama_input = $request->get('lama');
			if($lama_input!=$lama_php)
				$lama_input = $lama_php;
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
	        where user_id=$iduser ";
	        $kar=DB::connection()->select($sqlidkar);
	        $id=$kar[0]->p_karyawan_id;
            $karyawan_lembur = $request->get("nama");
			for($i=0;$i<count($karyawan_lembur);$i++){
				$array = [
						"m_jenis_ijin_id"=>22,
                    	"p_karyawan_id"=>($karyawan_lembur[$i]),
                    	"keterangan"=>($request->get('keterangan')),
                    	"kode"=>$nocuti,
						"tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl'))),
						"tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl'))),
						"keterangan"=>$request->get('alasan'),
						"appr_1"=>$request->get('atasan'),
						"appr_2"=>$request->get('atasan2'),
						"lama"=>$lama_input,
						"intruksi_atasan"=>1,
						"create_date"=>date('Y-m-d H:i:s'),
						"create_by"=>$id,
						"p_karyawan_atasan_input"=>$id,
						"active"=>1,
						"tipe_lembur"=>$request->get('tipe_lembur'),
						"jam_awal"=>$request->get('jam_awal'),
						"jam_akhir"=>$request->get('jam_akhir'),
                    ];
                if($request->get('atasan')==$id){
                	$array['status_appr_1'] = 1;
                	$array['appr_1'] = $id;
                	$array['tgl_appr_1'] = date('Y-m-d');
                }
				if($request->get('atasan2')==$id){
                	//$array['status_appr_2'] = 1;
                	//$array['appr_2'] = $id;
                	//$array['tgl_appr_2'] = date('Y-m-d');
                }
				DB::connection()->table("t_permit")
               		 ->insert($array);
				
			}
           
            DB::commit();
            return redirect()->route('fe.perintah_lembur')->with('success','Perintah Lembur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }public function edit_libur_lembur($id)
    {
         $sqlLibur_lembur="SELECT * FROM absen_libur_lembur WHERE absen_libur_lembur_id=$id ORDER BY tanggal";
        $Libur_lembur=DB::connection()->select($sqlLibur_lembur);
		$type = 'update_libur_lembur';
      
		$sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1  order by p_karyawan.nama";
        $karyawan=DB::connection()->select($sqlusers); 
        $data['tanggal'] = $Libur_lembur[0]->tanggal;
        $data['p_karyawan'] = $Libur_lembur[0]->p_karyawan_id;
        $data['keterangan'] = $Libur_lembur[0]->keterangan;
        
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.lembur.tambah_libur_shit', compact('Libur_lembur','user','type','id','data','karyawan'));
    }

    public function acc_intruksi_lembur(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("t_permit")
                ->where("t_form_exit_id",$id)
                ->update([
                   "appr_intruksi_date"=>(date('Y-m-d')),
                    	"appr_intruksi"=>($idUser),
                    	"status_appr_intruksi"=>(1),
                  
                ]);
            DB::commit();
            return redirect()->route('fe.list_lembur')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
    public function dec_intruksi_lembur(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("t_permit")
                ->where("t_form_exit_id",$id)
                ->update([
                   "appr_intruksi_date"=>(date('Y-m-d')),
                    	"appr_intruksi"=>($idUser),
                    	"status_appr_intruksi"=>(2),
                  
                ]);
            DB::commit();
            return redirect()->route('fe.list_lembur')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
	public function update_libur_lembur(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
        	$karyawan_lembur = $request->get("karyawan");
            DB::connection()->table("absen_libur_lembur")
                ->where("absen_libur_lembur_id",$id)
                ->update([
                   "tanggal"=>($request->get('tanggal')),
                    	"p_karyawan_id"=>($karyawan_lembur[0]),
                    	"keterangan"=>($request->get('keterangan')),
                  
                ]);
            DB::commit();
            return redirect()->route('fe.hari_libur_lembur')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_libur_lembur($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("absen_libur_lembur")
                ->where("absen_libur_lembur_id",$id)
                ->update([
                    "active" => 0
                ]);
            DB::commit();
            return redirect()->route('fe.hari_libur_lembur')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
	public function jadwal_lembur(Request $request)
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $help = new Helper_function();
        $where = '';
		if($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tanggal >= '".$request->get('tgl_awal')."'";
		else if($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tanggal <= '".$request->get('tgl_akhir')."'";
		else if($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tanggal >= '".$request->get('tgl_awal')."' and tanggal <= '".$request->get('tgl_akhir')."'";
        $lembur = "select * from absen_lembur
        left join absen on absen_lembur.absen_id = absen.absen_id
        
         where p_karyawan_id = $id $where and active = 1
         order by tanggal desc
         ";
        $lembur=DB::connection()->select($lembur);
	  return view('frontend.lembur.jadwal_lembur',compact('lembur','help','request'));
    }public function tambah_lembur ()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        //echo $id;die;
       $id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
    	$sqllokasi="select * from p_karyawan where active=1 and p_karyawan_id in($bawahan)";
        $karyawan=DB::connection()->select($sqllokasi);
                $id = '';
                $type = 'simpan_lembur';
                $data['nama']='';
                $data['keterangan']='';
                $help =new Helper_function();
        return view('frontend.lembur.tambah_lembur',compact('id','data','type','karyawan','help'));
    	
    }
 	public function penjadwalan_lembur()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        //echo $id;die;
        $id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
       // echo ''.$bawahan;
        $help = new Helper_function();
        $sqllembur="
        select * from absen_lembur
        left join absen on absen_lembur.absen_id = absen.absen_id
        left join p_karyawan on absen_lembur.p_karyawan_id = p_karyawan.p_karyawan_id
         where absen_lembur.p_karyawan_id in ($bawahan)
         order by tanggal desc
        ";
                        
        $lembur=DB::connection()->select($sqllembur);

        $iduser=Auth::user()->id;
        
        

        //return view('frontend.lembur.view_jamkerja',compact('lembur'));
       
        
	  return view('frontend.lembur.penjadwalan_lembur',compact('lembur','help'));
    }public function simpan_lembur(Request $request)
    {
    	
	  DB::beginTransaction();
        try{
        	/*$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id_lokasi=$idkar[0]->m_lokasi_id;*/
            $idUser=Auth::user()->id;
           $id =  DB::connection()->table("absen")
                ->insert([
                   
                    "tgl_awal"=>($request->get("tgl_awal")),
                    "tgl_akhir"=>($request->get("tgl_akhir")),
                   
                    "jam_masuk"=>($request->get("jam_masuk")),
                    "jam_keluar"=>($request->get("jam_keluar")),
                    "keterangan"=>($request->get("keterangan")),
                    "lemburing"=>($request->get("lemburing")?1:0),
                   
                    "active"=>1,
                    "create_by" => $idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                $SQL = " SELECT currval('hrm.seq_absen')";
                $lembur=DB::connection()->select($SQL);
//                print_r($lembur);
                $id = $lembur[0]->currval;

           
				$karyawan_lembur = $request->get("karyawan");
				for($i=0;$i<count($karyawan_lembur);$i++){
					$help = new Helper_function();
				$date = $request->get("tgl_awal");
				for($j=0;$j<=$help->hitungHari($request->get("tgl_awal"),$request->get("tgl_akhir"));$j++){
					 DB::connection()->table("absen_lembur")
               		 ->insert([
                   		"absen_id"=>($id),
                    	"p_karyawan_id"=>($karyawan_lembur[$i]),
                    	"tanggal"=>($date),
                    ]);
				$date = $help->tambah_tanggal($date,1);
				}
				
				}
				
			
            DB::commit();
            return redirect()->route('fe.penjadwalan_lembur')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function edit_lembur($id)
    {
       

                $type = 'update_lembur';
        $sqllembur="SELECT * FROM absen WHERE active=1 and absen_id = $id  ";
        $lembur=DB::connection()->select($sqllembur);
		$data['nama']=$lembur[0]->nama_lembur;
        $data['keterangan']=$lembur[0]->keterangan;
        
        return view('frontend.lembur.tambah_lembur', compact('data','id','type'));
    }
    public function update_lembur(Request $request, $id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("absen")
            ->where("absen_id",$id)
            ->update([
               "nama_lembur" => $request->get("nama"),
                "keterangan" => $request->get("keterangan"),
            ]);

        return redirect()->route('fe.lembur')->with('success',' lembur Berhasil di Ubah!');
    }
    public function hapus_lembur($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("absen_lembur")
            ->where("absen_lembur_id",$id)
            ->delete();

        return redirect()->back()->with('success',' lembur Berhasil di Hapus!');
    }
	public function excel_lembur(Request $request)
    {
    	$entitas = $request->get('entitas');
			return  lemburController::excel_empty_data($entitas);
		
    }public function excel_empty_data($entitas)
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'KARYAWAN lembur');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'lembur KE');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL AWAL');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL AKHIR');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM MASUK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM KELUAR');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'KETERANGAN');	$i++;
		$i=0;	
		
		$sheet->setCellValue($help->toAlpha($i).'2', 'XXXXX');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'Contoh Pengisian');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '1');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '1');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '2022-07-25');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '2022-08-14');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '07:01');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '10:30');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'lembur KARYAWAN EMM BUAH BATU PEKANAN 2');	$i++;
		$i=0;
		$sheet->setCellValue($help->toAlpha($i).'3', 'XXXXX');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', 'Contoh Pengisian Non lembur');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '0');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '');	$i++;
		
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
		$sql="SELECT * FROM p_karyawan
		left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
		where p_karyawan.active=1
			and a.m_lokasi_id = $entitas
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
		$fileName = "Template Data lembur ".$hisentitas[0]->nama.".csv";
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
    }public function simpan_excel_lembur (Request $request){

	$help = new Helper_function();

  	$mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet'];

	$file = $request->file('excel');
 
      	        // nama file
	//	echo 'File Name: '.$file->getClientOriginalName();
		
 
      	        // isi dengan nama folder tempat kemana file diupload
	$tujuan_upload = 'export';
 
                // upload file
          //       $imageName = time().'.'.$request->file->extension();  
	    	//$request->file->move(public_path('uploads/profil'), $imageName);
	$name = 'lembur-'.date('YmdHis').'-'.$file->getClientOriginalName();
	$file->move($tujuan_upload,$name);
    $uploadFilePath = 'export/'.$name;

    //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
   // $uploadFilePath = 'Gaji.xlsx';

		
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');

//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
   
        return view('frontend.lembur.export',compact('uploadFilePath','help'));
	}public function excel_exist_data()
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'lembur KE');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM MASUK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM KELUAR');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'KETERANGAN');	$i++;
		
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
		$sql="SELECT * FROM p_karyawan
		left join absen_lembur on absen_lembur.p_karyawan_id=p_karyawan.p_karyawan_id
		left join absen on absen_lembur.absen_id=absen.absen_id
		where p_karyawan.active=1 order by nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->lembur_ke);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->jam_masuk);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->jam_keluar);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->keterangan);$i++;
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		
		$type = 'csv';
		$fileName = "Template Exist Data lembur  .csv";
		if($type == 'xlsx'){
			$writer = new Xlsx($spreadsheet);
		} else if($type == 'xls'){
			$writer = new Xls($spreadsheet);
		}else if($type == 'csv'){
			$writer = new Csv($spreadsheet);
		}
		$writer->setDelimiter(';');
		$writer->save("export/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		return redirect(url('/')."/export/".$fileName);
    }

}