<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class KantorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function kantor()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlkantor="SELECT m_mesin_absen.nama as nama_mesin,m_office.*,
        (SELECT distinct string_agg(k.nama::character varying, ',')
            FROM m_office_entitas mk
            left join m_lokasi k on k.m_lokasi_id = mk.m_lokasi_id 
            WHERE mk.m_office_id = m_office.m_office_id and mk.active=1 ) as entitas_list 
            from m_office
                left join m_mesin_absen on m_mesin_absen_seharusnya_id = mesin_id
                WHERE 1=1 AND m_office.active=1 order by m_office.nama";
        $kantor=DB::connection()->select($sqlkantor);
        return view('backend.kantor.kantor',compact('kantor','user'));
    }

    public function tambah_kantor()
    {
     

        $sqlpangkat="SELECT * FROM m_mesin_absen WHERE active=1 ORDER BY nama ASC ";
        $absen=DB::connection()->select($sqlpangkat);
        
        $sqlpangkat="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        $entitas=DB::connection()->select($sqlpangkat);
        
        return view('backend.kantor.tambah_kantor',compact('absen','entitas')	);
    }

    public function simpan_kantor(Request $request){
    	
        $idUser=Auth::user()->id;
        DB::connection()->table("m_office")
            ->insert([
               
                "nama" => $request->get("nama"),
                "alamat" => $request->get("alamat"),
                "m_mesin_absen_seharusnya_id" => $request->get("lokasi_absen"),
                "active" => 1,
                "create_date" => date("Y-m-d"),
                "create_by" => $idUser,
            ]);

            $sql = DB::connection()->select("select * from seq_m_office");
            $entitas_office = $request->entitas;
            for($i=0;$i<count($request->entitas);$i++){
                $data['m_office_id'] =$sql[0]->last_value;
                $data['m_lokasi_id'] = $entitas_office[$i];
                DB::connection()->table('m_office_entitas')->insert($data);
            }
    
        return redirect()->route('be.kantor')->with('success',' kantor Berhasil di input!');
    }

    public function edit_kantor($id)
    {
        $sqldatakantor="SELECT m_office.*,
        (SELECT distinct string_agg(k.m_lokasi_id::character varying, ',')
                           FROM m_office_entitas mk
                           left join m_lokasi k on k.m_lokasi_id = mk.m_lokasi_id 
                           WHERE mk.m_office_id = m_office.m_office_id and mk.active=1 ) as entitas_list 
               FROM m_office
               
                WHERE 1=1 AND m_office.active=1 AND m_office.m_office_id=$id
                order by m_office.nama";
        $datakantor=DB::connection()->select($sqldatakantor);

       
        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqlkantor="SELECT * FROM m_office WHERE active=1 ORDER BY nama ASC ";
        $kantor=DB::connection()->select($sqlkantor);

        $sqlpangkat="SELECT * FROM m_pangkat WHERE active=1 ORDER BY nama ASC ";
        $pangkat=DB::connection()->select($sqlpangkat);
        $sqlpangkat="SELECT * FROM m_mesin_absen WHERE active=1 ORDER BY nama ASC ";
        $absen=DB::connection()->select($sqlpangkat);
        $sqlpangkat="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        $entitas=DB::connection()->select($sqlpangkat);
        
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.kantor.edit_kantor', compact('kantor','lokasi','id','datakantor','pangkat','user','absen','entitas'));
    }

    public function update_kantor(Request $request, $id){
        $idUser=Auth::user()->id;
        DB::connection()->table("m_office")
            ->where("m_office_id",$id)
            ->update([
                 "nama" => $request->get("nama"),
                "alamat" => $request->get("alamat"),
                "m_mesin_absen_seharusnya_id" => $request->get("lokasi_absen"),
                "active" => 1,
                "update_date" => date("Y-m-d"),
                "update_by" => $idUser,
            ]);

            DB::connection()->table('m_office_entitas')->where("m_office_id",$id)->update(["active"=>0]);
            $entitas_office = $request->entitas;
            for($i=0;$i<count($request->entitas);$i++){
                $data['m_office_id'] =$id;
                $data['m_lokasi_id'] = $entitas_office[$i];
                
                DB::connection()->table('m_office_entitas')->insert($data);
            }
    
        return redirect()->route('be.kantor')->with('success',' kantor Berhasil di Ubah!');
    }

    public function hapus_kantor($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_office")
            ->where("m_office_id",$id)
            ->update([
                "active"=>0,
                "update_date"=>date('Y-m-d'),
                "create_by" => $idUser,
            ]);

        return redirect()->back()->with('success',' kantor Berhasil di Hapus!');
    }
}
