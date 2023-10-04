<?php

namespace App\Http\Controllers\Backend\Gaji;

use App\ajuan_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use DB;
use Maatwebsite\Excel\Excel;
use Mail;
use App\Helper_function;
use Response;

class Master_potongan_absenController extends Controller
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

    public function master_potongan_absen (Request $request)
    {
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
			where users.id=$iduser"; $user=DB::connection()->select($sqluser);
		$title='master potongan';

		$sql="SELECT a.*,b.nama, 
case when a.type_nominal=1 then 'Nominal'
when a.type_nominal=3 then 'Prorata'
when a.type_nominal=2 then 'Persentase' end as type_nom,
case when a.periode_gajian=0 then 'Pekanan'
when a.type_nominal=1 then 'Bulanan' end as nama_periode
FROM m_potongan_absen a
LEFT JOIN m_pangkat b on b.m_pangkat_id=a.m_pangkat_id
WHERE 1=1
ORDER BY b.nama";
		$row=DB::connection()->select($sql);
        return view('backend.gaji.master.potongan_absen.list_potongan',compact('user','row','title'));
    }
 

    public function master_potongan_absen_tambah(){
        //echo 'masuk';die;
		$data['nama']='';
		$data['nominal']='';
		
		$data['active']='';
		$id='';
		$title='master potongan';
		$type='simpan_'.str_replace(' ','_',$title);
		$page='Tambah';

        $sql = "SELECT * FROM m_pangkat where active=1 ORDER by nama";
        $pangkat=DB::connection()->select($sql);

		return view('backend.gaji.master.potongan_absen.potongan_absen',compact('data','type','id','page','title','title','pangkat'));
	}
	public function edit($id){
		$page='Edit';
		$title='master potongan';
		$type='update_'.str_replace(' ','_',$title);
		$sql = "SELECT * FROM m_potongan where m_potongan_id=$id ";
    	$cos=DB::connection()->select($sql);
		$data['nama']=$cos[0]->nama;
		$data['nominal']=$cos[0]->nominal;
		
		$data['active']=$cos[0]->active;
		
		return view('backend.gaji.master.potongan.tambah_edit_potongan',compact('data','type','id','page','title'));
	}

	public function master_potongan_absen_simpan(Request $request){
		$title='master potongan absen';
        DB::beginTransaction();
        try{ 
            $idUser=Auth::user()->id;
             DB::connection()->table("m_potongan_absen")
                ->insert([
                    "m_pangkat_id"=>($request->get("pangkat")),
                    "nominal"=>($request->get("nominal")),
                    "type_absen"=>($request->get("type_absen")),
                    "type_nominal"=>($request->get("type_potongan")),
                    "periode_gajian"=>($request->get("periode_gajian")),
                ]);

            DB::commit();
            return redirect()->route('be.master_potongan_absen')->with('success',' Pot0ngan Absen Bulanan/Pekanan Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }

    public function master_potongan_absen_edit($id){
		$title='master potongan';
        $sql="SELECT a.*,b.nama, 
case when a.type_nominal=1 then 'Nominal'
when a.type_nominal=2 then 'Prorata'
when a.type_nominal=3 then 'Persentase' end as type_nom,
case when a.periode_gajian=0 then 'Pekanan'
when a.type_nominal=1 then 'Bulanan' end as nama_periode
FROM m_potongan_absen a
LEFT JOIN m_pangkat b on b.m_pangkat_id=a.m_pangkat_id
WHERE 1=1 AND a.m_potongan_absen_id=$id
ORDER BY b.nama";
        $row=DB::connection()->select($sql);

        $sql = "SELECT * FROM m_pangkat where active=1 ORDER by nama";
        $pangkat=DB::connection()->select($sql);

        return view('backend.gaji.master.potongan_absen.potongan_absen_edit',compact('row','title','pangkat'));
	}

	public function master_potongan_absen_update(Request $request, $id){
		$title='master potongan';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_potongan_absen")
                ->where("m_potongan_absen_id",$id)
                ->update([
                    "m_pangkat_id"=>($request->get("pangkat")),
                    "nominal"=>($request->get("nominal")),
                    "type_absen"=>($request->get("type_absen")),
                    "type_nominal"=>($request->get("type_potongan")),
                    "periode_gajian"=>($request->get("periode_gajian")),
                ]);
            DB::commit();
            return redirect()->route('be.master_potongan_absen')->with('success','Master Potongan Absen Berhasil di ubah!');

        } catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
	}

    public function master_potongan_absen_hapus($id)
    {
		$title='master potongan';
        DB::beginTransaction();
        try{
            DB::connection()->table("m_potongan_absen")
                ->where("m_potongan_absen_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.master_potongan_absen')->with('success','Master Potongan Absen Berhasil dihapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
} 