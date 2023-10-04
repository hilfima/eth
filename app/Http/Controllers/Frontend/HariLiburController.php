<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class HariLiburController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function approval_pergantian_hari_libur(Request $request)
    {
        $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			
			$sqlharilibur="SELECT *
                       FROM t_pergantian_hari_libur a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id where a.atasan = $id  and a.active = 1 order by  a.atasan desc,a.create_date";
        $harilibur=DB::connection()->select($sqlharilibur);

       

        return view('frontend.pergantian_hari_libur.approval',compact('harilibur','request'));
    }
    public function acc_pergantian_hari_libur(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_pergantian_hari_libur")
			->where("t_pergantian_hari_libur_id",$kode)
			->update([
				"status_appr"=>1,
				"appr_date" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.approval_pergantian_hari_libur')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function dec_pergantian_hari_libur(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_pergantian_hari_libur")
			->where("t_pergantian_hari_libur_id",$kode)
			->update([
				"status_appr"=>2,
				"appr_date" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			DB::commit();

			return redirect()->route('fe.approval_pergantian_hari_libur')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
    public function pergantian_hari_libur(Request $request)
    {
      $iduser=Auth::user()->id;
        $sqluser="SELECT * FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        
          $sqlharilibur="SELECT a.*,b.*,c.nama as appr
                       FROM t_pergantian_hari_libur a
                       join p_karyawan b on a.p_karyawan_id = b.p_karyawan_id
                       join p_karyawan c on a.atasan = c.p_karyawan_id
                       where a.active = 1 
                       and a.p_karyawan_id = ".$user[0]->p_karyawan_id." 
                       order by atasan desc,a.create_date
                        ";
        $harilibur=DB::connection()->select($sqlharilibur);

        

        return view('frontend.pergantian_hari_libur.pergantian_hari_libur',compact('harilibur','request'));
    }

    public function tambah_pergantian_hari_libur()
    {
        $iduser=Auth::user()->id;
       
        $sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		$help = new Helper_function();
        $jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
      
		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id ";
		$kar=DB::connection()->select($sqlkar);
		
		$help = new Helper_function();
		$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
					return view('frontend.permit.hub_hc');
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

      return view('frontend.pergantian_hari_libur.tambah_pergantian_hari_libur', compact('appr','kar','tgl_cut_off'));
			}
    }

    public function simpan_pergantian_hari_libur(Request $request){
        DB::beginTransaction();
        try{
            $iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
            DB::connection()->table("t_pergantian_hari_libur")
                ->insert([
                   
                    "p_karyawan_id"=>$id,
                    "tgl_pengajuan"=>($request->get("tgl_ajuan")),
                    "tgl_pengganti_hari"=>($request->get("tgl_libur")),//
                    "keterangan"=>($request->get("keterangan")),
                    "atasan"=>($request->get("atasan")),
                    "status_appr"=>3,
                    "active"=>1,
                    "create_by" => $iduser,
                    "create_date" => date("Y-m-d H:i:s")
                ]);
                $sql = "SELECT last_value FROM seq_t_pergantian_hari_libur;";
                $seq=DB::connection()->select($sql);
                if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path='pergantian_hari_libur-'.date('YmdHis').$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()->table("t_pergantian_hari_libur")->where("t_pergantian_hari_libur_id",$seq[0]->last_value)
				->update([
					"file"=>$path
				]);
			}
            DB::commit();
            return redirect()->route('fe.pergantian_hari_libur')->with('success','Hari Libur Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function edit_pergantian_hari_libur($id)
    {
        $sqlharilibur="SELECT * FROM t_pergantian_hari_libur WHERE t_pergantian_hari_libur_id=$id ORDER BY tanggal";
        $harilibur=DB::connection()->select($sqlharilibur);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.pergantian_hari_libur.edit_pergantian_hari_libur', compact('harilibur','user'));
    }

    public function update_pergantian_hari_libur(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::beginTransaction();
        try{
            DB::connection()->table("t_pergantian_hari_libur")
                ->where("t_pergantian_hari_libur_id",$id)
                ->update([
                    "tanggal"=>date('Y-m-d',strtotime($request->get("tanggal"))),
                    "nama"=>($request->get("nama")),
                    "jumlah"=>1,
                    "is_berulang"=>($request->get("berulang")),
                    "is_cuti_bersama"=>($request->get("cuti_bersama")),
                    "active"=>1,
                    "update_by" => $idUser,
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.pergantian_hari_libur')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_pergantian_hari_libur($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("t_pergantian_hari_libur")
                ->where("t_pergantian_hari_libur_id",$id)
                ->update([
                    "active" => 0
                ]);
            DB::commit();
            return redirect()->route('fe.pergantian_hari_libur')->with('success','Hari Libur Berhasil di Ubah!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}
