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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class ShiftController extends Controller
{
    public function tambah_shift_excel()
    {
    //	 $i=4;
	//echo $i!=1 and $i!=2 and $i!=3 ;die;
       $sqllokasi="select * from m_lokasi where active=1";
        $lokasi=DB::connection()->select($sqllokasi);
                $id = '';
                $type = 'simpan_shift';
                $data['nama']='';
                $data['keterangan']='';
        $sql = "select * from m_lokasi where active=1";
		$entitas=DB::connection()->select($sql);
        return view('frontend.shift.tambah_shift_excel',compact('id','data','type','lokasi','entitas'));
    }
    public function hari_libur_shift (Request $request)
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $help = new Helper_function();
         $help=new Helper_function();
         
        $id_karyawan=$idkar[0]->p_karyawan_id;
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$where = "";
		if($request->tgl_awal){
		    $where .= "and tanggal >= '$request->tgl_awal'";
		}
		
		if($request->tgl_akhir){
		    $where .= "and tanggal <= '$request->tgl_akhir'";
		}
		if($where){
        $sqlLibur_shift="SELECT distinct tanggal,absen_libur_shift.p_karyawan_id,a.nama,keterangan,absen_libur_shift_id,m_jabatan_id
                       FROM absen_libur_shift
                    	left join p_karyawan a on absen_libur_shift.p_karyawan_id = a.p_karyawan_id
                    	left join p_karyawan_pekerjaan b on a.p_karyawan_id = b.p_karyawan_id
      	
                    	where absen_libur_shift.active = 1
                    	and b.m_jabatan_id in($bawahan)
                    	$where
                        order by tanggal desc
                        ";
		
        $Libur_shift=DB::connection()->select($sqlLibur_shift);
		}else{
		    $Libur_shift=array();
		}
	  return view('frontend.shift.hari_libur_shift',compact('Libur_shift','help','request'));
    } 
	public function tambah_libur_shift(Request $request)
    { 
    $type = 'simpan_libur_shift';
        $id = '';$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser ";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        //echo $id;die;
         $id_karyawan = $idkar[0]->p_karyawan_id;
		 $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		
		$sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1 and m_jabatan_id in($bawahan) order by p_karyawan.nama";
        $karyawan=DB::connection()->select($sqlusers); 
        $data['tanggal'] = '';
        $data['p_karyawan'] = '';
        $data['keterangan'] = '';
        return view('frontend.shift.tambah_libur_shit', compact('karyawan','id','data','type'));
    } 
    public function simpan_libur_shift(Request $request){
        DB::beginTransaction();
        try{
        	$help = new Helper_function();
            $idUser=Auth::user()->id;
            $karyawan_shift = $request->get("karyawan");
			for($i=0;$i<count($karyawan_shift);$i++){
				DB::connection()->table("absen_libur_shift")
               		 ->insert([
                   		"tanggal"=>($request->get('tanggal')),
                    	"p_karyawan_id"=>($karyawan_shift[$i]),
                    	"keterangan"=>($request->get('keterangan')),
                    ]);
				
				}
           
            DB::commit();
            return redirect()->route('fe.hari_libur_shift')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
	public function edit_libur_shift($id)
    {
         $sqlLibur_shift="SELECT * FROM absen_libur_shift WHERE absen_libur_shift_id=$id ORDER BY tanggal";
        $Libur_shift=DB::connection()->select($sqlLibur_shift);
		$type = 'update_libur_shift';
      
		$sqlusers="SELECT p_karyawan.*,m_departemen.nama as nmdept FROM p_karyawan
LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_pekerjaan.m_departemen_id
                WHERE 1=1 and p_karyawan.active=1  order by p_karyawan.nama";
        $karyawan=DB::connection()->select($sqlusers); 
        $data['tanggal'] = $Libur_shift[0]->tanggal;
        $data['p_karyawan'] = $Libur_shift[0]->p_karyawan_id;
        $data['keterangan'] = $Libur_shift[0]->keterangan;
        
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.shift.tambah_libur_shit', compact('Libur_shift','user','type','id','data','karyawan'));
    }

    public function update_libur_shift(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("absen_libur_shift")
                ->whereNotIn("absen_libur_shift_id",$id)
                ->where("tanggal",($request->get('tanggal')))
                ->where("p_karyawan_id",($karyawan_shift[0]) )
                ->delete();
        	$karyawan_shift = $request->get("karyawan");
            DB::connection()->table("absen_libur_shift")
                ->where("absen_libur_shift_id",$id)
                ->update([
                   "tanggal"=>($request->get('tanggal')),
                    	"p_karyawan_id"=>($karyawan_shift[0]),
                    	"keterangan"=>($request->get('keterangan')),
                  
                ]);
            DB::commit();
            return redirect()->route('fe.hari_libur_shift')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_libur_shift($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("absen_libur_shift")
                ->where("absen_libur_shift_id",$id)
                ->update([
                    "active" => 0
                ]);
            DB::commit();
            return redirect()->route('fe.hari_libur_shift')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
	public function jadwal_shift(Request $request)
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
        $shift = "select * from absen_shift
        left join absen on absen_shift.absen_id = absen.absen_id
        
         where p_karyawan_id = $id $where and absen_shift.active = 1
         order by tanggal desc
         ";
        $shift=DB::connection()->select($shift);
	  return view('frontend.shift.jadwal_shift',compact('shift','help','request'));
    }
	public function tambah_shift ()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        //echo $id;die;
       $id_karyawan = $idkar[0]->p_karyawan_id;
		 $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$sqllokasi="select * from p_karyawan where active=1 and p_karyawan_id in($bawahan)";
        $karyawan=DB::connection()->select($sqllokasi);
                $id = '';
                $type = 'simpan_shift';
                $data['nama']='';
                $data['keterangan']='';
                $help =new Helper_function();
        return view('frontend.shift.tambah_shift_2',compact('id','data','type','karyawan','help'));
    	
    }
 	public function penjadwalan_shift()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        //echo $id;die;
        $id_karyawan = $idkar[0]->p_karyawan_id;
		 $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];// echo ''.$bawahan;
        $help = new Helper_function();
        $sqlshift="
        select * from absen_shift
        left join absen on absen_shift.absen_id = absen.absen_id
        left join p_karyawan on absen_shift.p_karyawan_id = p_karyawan.p_karyawan_id
        left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        
         where p_karyawan_pekerjaan.m_jabatan_id in ($bawahan) and absen_shift.active=1
         order by tanggal desc
        ";
                        
        $shift=DB::connection()->select($sqlshift);

        $iduser=Auth::user()->id;
        
        

        //return view('frontend.shift.view_jamkerja',compact('shift'));
       
        
	  return view('frontend.shift.penjadwalan_shift',compact('shift','help'));
    }
	public function get_template_shift(Request $request)
    { 
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
        where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $id_karyawan=$idkar[0]->p_karyawan_id;
        $entitas=$idkar[0]->m_lokasi_id;
        //echo $id;die;
      $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar']; 
      	$sqllokasi="select * from p_karyawan 
      	left join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id
      	where p_karyawan.active=1 and m_jabatan_id in($bawahan)";
        $karyawan=DB::connection()->select($sqllokasi);
		$jam_shift=DB::connection()->select("select * from m_jam_shift where active=1 
		                    and (entitas is null or (entitas like '%$entitas,%' and entitas is not null) ) 
		                    
		                    and (tgl_awal is null  or (tgl_awal<='".date('Y-m-d')."' and tgl_akhir>='".date('Y-m-d')."' ))");
        $help = new Helper_function();
		return view('frontend.shift.get_template_shift',compact('karyawan','help','jam_shift','request'));
    }
	public function simpan_shift_multiple(Request $request)
    {
    	
    	DB::beginTransaction();
        try{
        	$iduser=Auth::user()->id;
	        $sqlidkar="select *,m_office.nama as nama_kantor, m_jabatan.nama as nama_jabatan from p_karyawan 
	        join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
	        left join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
	        left join m_office on p_karyawan_pekerjaan.m_kantor_id = m_office.m_office_id
	        where user_id=$iduser";
	        $idkar=DB::connection()->select($sqlidkar);
	        $id=$idkar[0]->p_karyawan_id;
			$jam_shift=DB::connection()->select("select * from m_jam_shift where active=1 and (tgl_awal is null or (tgl_awal<='".date('Y-m-d')."' and tgl_akhir>='".date('Y-m-d')."' ))");
        		
			foreach ($jam_shift as $jam) {
				DB::connection()->table("absen")
				->insert([

					"tgl_awal"=>($request->get("tgl_awal")),
					"tgl_akhir"=>($request->get("tgl_akhir")),

					"jam_masuk"=>($jam->jam_masuk),
					"jam_keluar"=>($jam->jam_keluar),
					"keterangan"=>$jam->nama_jam_shift." ".$idkar[0]->nama_kantor.' - Struktural '.$idkar[0]->nama_jabatan,
					"shifting"=>1,

					"active"=>1,
					"create_by" => $iduser,
					"create_date" => date("Y-m-d H:i:s")
				]);
				$SQL = " SELECT currval('hrm.seq_absen')";
				$shift=DB::connection()->select($SQL);
				$idshift[$jam->m_jam_shift_id] = $shift[0]->currval;
			}
        		$help = new Helper_function();
        		
        		$karyawan_shift = $request->get("karyawan");
        		$data_shift = $request->get("shift");
				for($i=0;$i<count($karyawan_shift);$i++){
        		$status = $request->get("status-".$karyawan_shift[$i]);
					
					
				if($status){
					
					$date = $request->get("tgl_awal");
					for($j=0;$j<=$help->hitungHari($request->get("tgl_awal"),$request->get("tgl_akhir"));$j++)
					{
						if(isset($data_shift[$karyawan_shift[$i]][$date])){
							DB::connection()->table("absen_libur_shift")
											->where("tanggal",$date)
											->where("p_karyawan_id",$karyawan_shift[$i])
											->update(['active'=>0]);
							
							DB::connection()->table("absen_shift")
											->where("tanggal",$date)
											->where("p_karyawan_id",$karyawan_shift[$i])
											->update(['active'=>0]);
							
							if($data_shift[$karyawan_shift[$i]][$date]==-1){
								 DB::connection()->table("absen_libur_shift")
				               		 ->insert([
				                   		"tanggal"=>($date),
				                    	"p_karyawan_id"=>($karyawan_shift[$i]),
				                    	"keterangan"=>"Libur ".$idkar[0]->nama_kantor.' - Struktural '.$idkar[0]->nama_jabatan,
			                    ]);
							}else {
								
									$id = $idshift[$data_shift[$karyawan_shift[$i]][$date]];
								
								 DB::connection()->table("absen_shift")
			               		 ->insert([
			                   		"absen_id"=>($id),
			                    	"p_karyawan_id"=>($karyawan_shift[$i]),
			                    	"tanggal"=>($date),
			                    ]);
							}
							
							
						}
						
						
	                    
	                   
					$date = $help->tambah_tanggal($date,1);
					}
				}
				
				}
        		
         	DB::commit();
            return redirect()->route('fe.penjadwalan_shift')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
	public function simpan_shift(Request $request)
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
                    "shifting"=>($request->get("shifting")?1:0),
                   
                    "active"=>1,
                    "create_by" => $idUser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                $SQL = " SELECT currval('hrm.seq_absen')";
                $shift=DB::connection()->select($SQL);
//                print_r($shift);
                $id = $shift[0]->currval;

           
				$karyawan_shift = $request->get("karyawan");
				for($i=0;$i<count($karyawan_shift);$i++){
					$help = new Helper_function();
				$date = $request->get("tgl_awal");
				for($j=0;$j<=$help->hitungHari($request->get("tgl_awal"),$request->get("tgl_akhir"));$j++){
					 DB::connection()->table("absen_shift")
               		 ->insert([
                   		"absen_id"=>($id),
                    	"p_karyawan_id"=>($karyawan_shift[$i]),
                    	"tanggal"=>($date),
                    ]);
				$date = $help->tambah_tanggal($date,1);
				}
				
				}
				
			
            DB::commit();
            return redirect()->route('fe.penjadwalan_shift')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }
    public function edit_shift($id)
    {
       

         $type = 'update_shift';
		$sqlshift="SELECT * FROM absen_shift WHERE  absen_shift_id = $id  ";
        $shift=DB::connection()->select($sqlshift);
        $karyawan=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		$absen=DB::connection()->select("select * from absen where active=1 order by tgl_awal,keterangan");
		$data['tanggal']=$shift[0]->tanggal;
		$data['p_karyawan_id']=$shift[0]->p_karyawan_id;
		$data['absen_id']=$shift[0]->absen_id;
		$data['id']=$id;
		$help = new Helper_function();
		
		return view('frontend.shift.tambah_shift_karyawan', compact('data','id','type','help','karyawan','absen'));
    }
    public function update_shift(Request $request, $id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("absen")
            ->where("absen_id",$id)
            ->update([
               "nama_shift" => $request->get("nama"),
                "keterangan" => $request->get("keterangan"),
            ]);

        return redirect()->route('fe.shift')->with('success',' shift Berhasil di Ubah!');
    }public function update_shift_karyawan(Request $request, $id)
    {
        $idUser=Auth::user()->id;
		DB::connection()->table("absen_shift")
		->where("absen_shift_id",$id)
            ->update([
               "absen_id" => $request->get("absen_id"),
			   "tanggal" => $request->get("tanggal"),
            ]);

			return redirect()->route('fe.penjadwalan_shift')->with('success',' shift Berhasil di Ubah!');
    }
    public function hapus_shift($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("absen_shift")
            ->where("absen_shift_id",$id)
            ->delete();

        return redirect()->back()->with('success',' shift Berhasil di Hapus!');
    }
	public function excel_shift(Request $request)
    {
    	$entitas = $request->get('entitas');
			return  ShiftController::excel_empty_data($entitas);
		
    }public function excel_empty_data($entitas)
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'KARYAWAN SHIFT');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'SHIFT KE');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL AWAL');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'TANGGAL AKHIR');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM MASUK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'JAM KELUAR');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'KETERANGAN');	$i++;
		$i=0;	
		
		$sheet->setCellValue($help->toAlpha($i).'2', 'XXXXX');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'Karyawan Contoh');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '1');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '1');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '2022-08-01');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '2022-08-14');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '07:01');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', '10:30');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'2', 'SHIFT KARYAWAN PEKANAN 1');	$i++;
		$i=0;	
		
		$sheet->setCellValue($help->toAlpha($i).'3', 'XXXXX');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', 'Karyawan Contoh');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '1');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '1');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '2022-08-15');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '2022-08-21');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '07:01');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', '10:30');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'3', 'SHIFT KARYAWAN PEKANAN 2');	$i++;
		$i=0;
		$sheet->setCellValue($help->toAlpha($i).'4', 'XXXXX');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'4', 'Contoh Pengisian Non SHift');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'4', '0');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'4', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'4', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'4', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'4', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'4', '');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'4', '');	$i++;
		
		$rows = 5;

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
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id 
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		 $help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		
		$sql="SELECT * FROM p_karyawan
		left join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
		where p_karyawan.active=1
			and p_karyawan.p_karyawan_id in($bawahan)
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
		
		
		
		$type = 'csv';
		$fileName = "Template Data Shift .csv";
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
    }public function simpan_excel_shift (Request $request){

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
	$name = 'shift-'.date('YmdHis').'-'.$file->getClientOriginalName();
	$file->move($tujuan_upload,$name);
    $uploadFilePath = 'export/'.$name;

    //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
   // $uploadFilePath = 'Gaji.xlsx';

		
//require('plugins\spreadsheet-reader-master\php-excel-reader/excel_reader2.php');

//require('plugins\spreadsheet-reader-master\SpreadsheetReader.php');
   
        return view('frontend.shift.export',compact('uploadFilePath','help'));
	}public function excel_exist_data()
    {
    	$help = new Helper_function();
    	$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		
		$i=0;						
									
				
		
		$sheet->setCellValue($help->toAlpha($i).'1', 'NIK');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'Nama Karyawan');	$i++;
		$sheet->setCellValue($help->toAlpha($i).'1', 'SHIFT KE');	$i++;
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
		left join absen_shift on absen_shift.p_karyawan_id=p_karyawan.p_karyawan_id
		left join absen on absen_shift.absen_id=absen.absen_id
		where p_karyawan.active=1 order by nama asc";
        $list_karyawan=DB::connection()->select($sql);

		
		if(!empty($list_karyawan)){
	
			foreach($list_karyawan as $list_karyawan){
				
					$i=0;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nik);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->nama);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->shift_ke);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->jam_masuk);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->jam_keluar);$i++;
                	$sheet->setCellValue($help->toAlpha($i). $rows, $list_karyawan->keterangan);$i++;
                               
                                 
                               
				//echo substr($absen->a,0,5);die;
				$rows++;
			}
		}
		
		
		
		$type = 'csv';
		$fileName = "Template Exist Data Shift  .csv";
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