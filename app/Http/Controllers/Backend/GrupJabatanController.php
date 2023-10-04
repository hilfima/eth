<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class GrupJabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function grup_jabatan()
    {
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlgrupjabatan="SELECT m_grupjabatan.*,m_lokasi.nama as nmlokasi 
                FROM m_grupjabatan
                LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_grupjabatan.m_lokasi_id
                WHERE 1=1 AND m_grupjabatan.active=1 order by nama";
        $grupjabatan=DB::connection()->select($sqlgrupjabatan);
        return view('backend.grup_jabatan.grup_jabatan',compact('grupjabatan','user'));
    }

    public function tambah_grup_jabatan()
    {
        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqljabatan="SELECT * FROM m_jabatan WHERE active=1 ORDER BY nama ASC ";
        $jabatan=DB::connection()->select($sqljabatan);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.grup_jabatan.tambah_grup_jabatan',compact('lokasi','jabatan','user'));
    }

    public function simpan_grup_jabatan(Request $request){
        $idUser=Auth::user()->id;
        $this->validate($request, [
            'nama' => 'required|string|max:100',
            'jabatan' => 'required',
            'lokasi' => 'required',
        ]);
        DB::connection()->table("m_grupjabatan")
            ->insert([
                "nama" => $request->get("nama"),
                "m_jabatan_id" => $request->get("jabatan"),
                "m_lokasi_id" => $request->get("lokasi"),
                "active" => 1,
                "created_at" => date("Y-m-d"),
                "created_by" => $idUser,
            ]);

        return redirect()->route('be.grup_jabatan')->with('success','Grup Jabatan Berhasil di input!');
    }

    public function edit_grup_jabatan($id)
    {
        $sqlgrup_jabatan="SELECT m_grupjabatan.*,m_lokasi.nama as nmlokasi,m_jabatan.nama as nmjabatan 
                FROM m_grupjabatan
                LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=m_grupjabatan.m_jabatan_id
                LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_grupjabatan.m_lokasi_id
                WHERE 1=1 AND m_grupjabatan.active=1 AND m_grupjabatan.m_grupjabatan_id=$id 
                order by m_grupjabatan.nama";
        $grup_jabatan=DB::connection()->select($sqlgrup_jabatan);

        $sqllokasi="SELECT * FROM m_lokasi WHERE active=1 ORDER BY nama ASC ";
        $lokasi=DB::connection()->select($sqllokasi);

        $sqljabatan="SELECT * FROM m_jabatan WHERE active=1 ORDER BY nama ASC ";
        $jabatan=DB::connection()->select($sqljabatan);

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.grup_jabatan.edit_grup_jabatan', compact('grup_jabatan','lokasi','jabatan','user'));
    }

    public function update_grup_jabatan(Request $request, $id){
        $idUser=Auth::user()->id;
        $this->validate($request, [
            'nama' => 'required|string|max:100',
            'jabatan' => 'required',
            'lokasi' => 'required',
        ]);

        DB::connection()->table("m_grupjabatan")
            ->where("m_grupjabatan_id",$id)
            ->update([
                "nama" => $request->get("nama"),
                "m_jabatan_id" => $request->get("jabatan"),
                "m_lokasi_id" => $request->get("lokasi"),
                "active" => 1,
                "created_at" => date("Y-m-d"),
                "created_by" => $idUser,
            ]);

        return redirect()->route('be.grup_jabatan')->with('success','Grup Jabatan Berhasil di Ubah!');
    }

    public function hapus_grup_jabatan($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("m_grupjabatan")
            ->where("m_grupjabatan_id",$id)
            ->update([
                "active"=>0,
                "updated_at"=>date('Y-m-d'),
                "created_by" => $idUser,
            ]);

        return redirect()->back()->with('success','Grup Jabatan Berhasil di Hapus!');
    }
}
