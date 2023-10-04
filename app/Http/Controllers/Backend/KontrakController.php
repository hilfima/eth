<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class KontrakController extends Controller
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
    public function kontrak()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$help = new Helper_function();
        $tgl=$help->tambah_bulan(date('Y-m-').'1',1);
        $sqlkontrak="SELECT p_karyawan_kontrak.*,p_karyawan.nik,p_karyawan.nama,m_divisi.nama as nmdivisi,m_departemen.nama as nmdept,m_lokasi.nama as nmlokasi 
                    FROM p_karyawan_kontrak
                    LEFT JOIN p_karyawan on p_karyawan.p_karyawan_id=p_karyawan_kontrak.p_karyawan_id
                    LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
                    LEFT JOIN m_divisi on m_divisi.m_divisi_id=p_karyawan_kontrak.m_divisi_kontrak__id
                    LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_kontrak.m_departemen_kontrak__id
                    LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=p_karyawan_kontrak.m_lokasi_kontrak_id
                    WHERE 1=1 and p_karyawan_kontrak.active=1 and p_karyawan_kontrak.m_status_pekerjaan_id NOT IN(10) 
                    and p_karyawan_kontrak.tgl_akhir <'".$tgl."' and p_karyawan.active=1 ";
        $kontrak=DB::connection()->select($sqlkontrak);
        return view('backend.kontrak.kontrak', compact('kontrak','user'));
    }

    public function view_kontrak($id)
    {
        $sqlkontrak="SELECT p_karyawan_kontrak.*,p_karyawan.nik,p_karyawan.nama,m_divisi.nama as nmdivisi,m_departemen.nama as nmdept,m_lokasi.nama as nmlokasi , p_karyawan.nama as nama_lengkap
                    FROM p_karyawan_kontrak 
                    LEFT JOIN p_karyawan on p_karyawan.p_karyawan_id=p_karyawan_kontrak.p_karyawan_id
                    LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
                    LEFT JOIN m_divisi on m_divisi.m_divisi_id=p_karyawan_kontrak.m_divisi_kontrak__id
                    LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_kontrak.m_departemen_kontrak__id
                    LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=p_karyawan_kontrak.m_lokasi_kontrak_id
WHERE 1=1 and p_karyawan_kontrak.active=1 and p_karyawan_kontrak.m_status_pekerjaan_id IN(1,9) and p_karyawan_kontrak.active=1 and p_karyawan_kontrak.p_karyawan_kontrak_id=$id";
        $kontrak=DB::connection()->select($sqlkontrak);

        $sqlagama="SELECT * FROM m_agama WHERE active=1 ORDER BY nama ASC ";
        $agama=DB::connection()->select($sqlagama);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkota="SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
        $kota=DB::connection()->select($sqlkota);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);
		$sqljk="SELECT * FROM m_office WHERE active=1 ORDER BY nama ASC ";
        $kantor=DB::connection()->select($sqljk);

        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
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

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		
		$sqljk="SELECT * FROM m_office WHERE active=1 ORDER BY nama ASC ";
        $kantor=DB::connection()->select($sqljk);
        
        
        $sqlgrade="SELECT * FROM m_directorat WHERE active=1 ORDER BY nama_directorat ASC ";
        $directorat=DB::connection()->select($sqlgrade);
        
        $sqlgrade="SELECT * FROM m_divisi_new WHERE active=1 ORDER BY nama_divisi ASC ";
        $divisi_new=DB::connection()->select($sqlgrade);

        
        return view('backend.kontrak.view_kontrak', compact('lokasi','kota','directorat','divisi_new','jk','departemen','jabatan','grade','stspekerjaan','agama','kontrak','divisi','pangkat','user','kantor'));
    }

    public function tambah_kontrak()
    {
    	$sqlagama="SELECT * FROM p_karyawan WHERE active=1 ORDER BY nama ASC ";
        $karyawan=DB::connection()->select($sqlagama);
        
        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkota="SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
        $kota=DB::connection()->select($sqlkota);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);

        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
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
        $sqljk="SELECT * FROM m_office WHERE active=1 ORDER BY nama ASC ";
        $kantor=DB::connection()->select($sqljk);
        
        $sqlgrade="SELECT * FROM m_directorat WHERE active=1 ORDER BY nama_directorat ASC ";
        $directorat=DB::connection()->select($sqlgrade);
        
        $sqlgrade="SELECT * FROM m_divisi_new WHERE active=1 ORDER BY nama_divisi ASC ";
        $divisi_new=DB::connection()->select($sqlgrade);
    	return view('backend.kontrak.tambah_kontrak', compact('karyawan','directorat','divisi_new','lokasi','kota','jk','departemen','jabatan','grade','stspekerjaan','divisi','pangkat','kantor'));
    }

    public function edit_kontrak($id)
    {
        $sqlkontrak="SELECT p_karyawan_kontrak.*,p_karyawan.nik,p_karyawan.nama,m_divisi.nama as nmdivisi,m_departemen.nama as nmdept,m_lokasi.nama as nmlokasi , p_karyawan.nama as nama_lengkap
                    FROM p_karyawan_kontrak 
                    LEFT JOIN p_karyawan on p_karyawan.p_karyawan_id=p_karyawan_kontrak.p_karyawan_id
                    LEFT JOIN p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
                    LEFT JOIN m_divisi on m_divisi.m_divisi_id=p_karyawan_kontrak.m_divisi_kontrak__id
                    LEFT JOIN m_departemen on m_departemen.m_departemen_id=p_karyawan_kontrak.m_departemen_kontrak__id
                    LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=p_karyawan_kontrak.m_lokasi_kontrak_id
WHERE 1=1 and p_karyawan_kontrak.active=1 and p_karyawan_kontrak.m_status_pekerjaan_id IN(1,9) and p_karyawan_kontrak.active=1 and p_karyawan_kontrak.p_karyawan_kontrak_id=$id";
        $kontrak=DB::connection()->select($sqlkontrak);

        $sqlagama="SELECT * FROM m_agama WHERE active=1 ORDER BY nama ASC ";
        $agama=DB::connection()->select($sqlagama);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkota="SELECT * FROM m_kota WHERE active=1 ORDER BY nama ASC ";
        $kota=DB::connection()->select($sqlkota);

        $sqljk="SELECT * FROM m_jenis_kelamin WHERE active=1 ORDER BY nama ASC ";
        $jk=DB::connection()->select($sqljk);

        $sqldepartemen="SELECT * FROM m_departemen WHERE active=1 ORDER BY nama ASC ";
        $departemen=DB::connection()->select($sqldepartemen);

        $sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
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

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		$sqljk="SELECT * FROM m_office WHERE active=1 ORDER BY nama ASC ";
        $kantor=DB::connection()->select($sqljk);
        return view('backend.kontrak.edit_kontrak',compact('lokasi','kota','jk','departemen','jabatan','grade','stspekerjaan','agama','kontrak','divisi','pangkat','user','id','kantor'));
    }

    public function simpan_kontrak(Request $request){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;

            
			DB::connection()->table("p_karyawan_kontrak")
                ->where("p_karyawan_id",$request->karyawan)
                
                ->update(['active'=>0]);
            $path='';
            if ($request->file('file')) { //echo 'masuk';die;
					$file = $request->file('file');
					$destination = "dist/img/file/";
					$path = 'kontrak_kerja-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					
				}
            DB::connection()->table("p_karyawan_kontrak")
               
                ->insert([
                    "tgl_awal" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
                    "tgl_akhir" => date('Y-m-d',strtotime($request->get("tgl_akhir"))),
                    "p_karyawan_id" => $request->get("karyawan"),
                    "m_status_pekerjaan_id" => $request->get("status_pekerjaan"),
                    "keterangan" => $request->get("keterangan"),
                    "active" => 1,
                    "m_lokasi_kontrak_id" => $request->get("lokasi"),
                    "m_departemen_kontrak__id" => $request->get("departemen"),
                    "m_jabatan_kontrak__id" => $request->get("jabatan"),
                    "m_divisi_kontrak__id" => $request->get("divisi"),
                    "m_pangkat_kontrak__id" => $request->get("pangkat"),
                    "m_kantor_kontrak__id" => $request->get("kantor"),
                    "update_date" => date("Y-m-d"),
                    "update_by" => $idUser,
                    "file_kontrak_kerja" => $path
                ]);

            DB::commit();
            return redirect()->route('be.kontrak')->with('success',' Kontrak Berhasil diperbaharui!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
	public function update_kontrak(Request $request, $id){
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;

            /*DB::connection()->table("p_karyawan_pekerjaan")
               
                ->insert([
                    "p_karyawan_id" => $request->get("lokasi"),
                    "m_lokasi_id" => $request->get("lokasi"),
                    "m_departemen_id" => $request->get("departemen"),
                    "m_jabatan_id" => $request->get("jabatan"),
                    "m_divisi_id" => $request->get("divisi"),
                    "kantor" => $request->get("kantor"),
                    "kota" => $request->get("kotakerja"),
                    "active" => 1,
                    "update_date" => date("Y-m-d"),
                    "update_by" => $idUser,
                ]);
*/
            $path='';
            if ($request->file('file')) { //echo 'masuk';die;
					$file = $request->file('file');
					$destination = "dist/img/file/";
					$path = 'kontrak_kerja-' . date('ymdhis') . '-' . $file->getClientOriginalName();
					$file->move($destination, $path);
					
				}
            if($request->get("status_pekerjaan")=='10'){
                $tgl_akhir=null;
            }
            else{
                $tgl_akhir=date('Y-m-d',strtotime($request->get("tgl_akhir")));
            }

            DB::connection()->table("p_karyawan_kontrak")
                ->where("p_karyawan_kontrak_id",$id)
                ->update([
                    "tgl_awal" => date('Y-m-d',strtotime($request->get("tgl_awal"))),
                    "tgl_akhir" => $tgl_akhir,
                    "m_status_pekerjaan_id" => $request->get("status_pekerjaan"),
                    "keterangan" => $request->get("keterangan"),
                    "active" => 1,
                    "m_lokasi_kontrak_id" => $request->get("lokasi"),
                    "m_departemen_kontrak__id" => $request->get("departemen"),
                    "m_jabatan_kontrak__id" => $request->get("jabatan"),
                    "m_divisi_kontrak__id" => $request->get("divisi"),
                    "m_pangkat_kontrak__id" => $request->get("pangkat"),
                    "m_kantor_kontrak__id" => $request->get("kantor"),
                    "update_date" => date("Y-m-d"),
                    "update_by" => $idUser,
                    "file_kontrak_kerja" => $path
                ]);

            DB::commit();
           // return redirect()->route('be.kontrak')->with('success',' Kontrak Berhasil diperbaharui!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_kontrak($id)
    {
        DB::beginTransaction();
        try{
        	 $idUser=Auth::user()->id;
        	DB::connection()->table("p_karyawan_kontrak")
                ->where("p_karyawan_kontrak_id",$id)
                ->update(['active'=>0,"update_by"=>$idUser,"update_date"=>date('Y-m-d')]);
           /* DB::connection()->table("p_karyawan")
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
            */
            DB::commit();
            return redirect()->back()->with('success','kontrak Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}
