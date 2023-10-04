<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class PTestController extends Controller
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
    public function ptest()
    {
        return view('frontend.pa.ptest');
    }

    public function rmib()
    {
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;

        $sqldata="SELECT * FROM t_rmib
left join get_data_karyawan() b on b.p_karyawan_id=t_rmib.p_karyawan_id
where t_rmib.p_karyawan_id=$iduser";
        $data=DB::connection()->select($sqldata);

        return view('frontend.pa.rmib', compact('data'));
    }

    public function tambah_rmib()
    {
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;

        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        $sqlkar="select * from get_data_karyawan() where p_karyawan_id=$id";
        //echo $sqlpa;die;
        $datakar=DB::connection()->select($sqlkar);
        $jk=$datakar[0]->m_jenis_kelamin_id;

        $sqlrmib="select * from m_rmib where active=1 and m_jk_id=$jk and grup='A' ORDER BY urutan";
        $datarmib=DB::connection()->select($sqlrmib);

        $sqlrmibjumlah="select count(*) as jumlah from m_rmib where active=1 and m_jk_id=$jk and grup='A' ";
        $datarmibjumlah=DB::connection()->select($sqlrmibjumlah);
        $jumlah=$datarmibjumlah[0]->jumlah;
        $grup='A';
        $idrmib=0;

        return view('frontend.pa.tambah_rmib',compact('user','datakar','datarmib','jumlah','grup','idrmib'));
    }

    public function simpan_rmib(Request $request)
    {
        DB::beginTransaction();
        try{
            $iduser = Auth::user()->id;
            $sqlidkar="select * from p_karyawan where user_id=$iduser";
            $idkar=DB::connection()->select($sqlidkar);
            $id=$idkar[0]->p_karyawan_id;

            $this->validate($request, [
                'id_soal' => 'required',
                'pilihan' => 'required',
                'jumlah' => 'required',
                //'alt1' => 'required',
                //'alt2' => 'required',
                //'alt3' => 'required',
            ]);

            $data = $request->all();
            if(empty($request->input('id_soal'))){
                $data['id_soal'] = $request->input('id_soal');
            }
            if(empty($request->input('pilihan'))){
                $data['pilihan'] = $request->input('pilihan');
            }
            if(empty($request->input('jumlah'))){
                $data['jumlah'] = $request->input('jumlah');
            }
            /*if(empty($request->input('alt1'))){
                $data['alt1'] = $request->input('alt1');
            }
            if(empty($request->input('alt2'))){
                $data['alt2'] = $request->input('alt2');
            }
            if(empty($request->input('alt3'))){
                $data['alt3'] = $request->input('alt3');
            }*/
            if(empty($request->input('idkar'))){
                $data['idkar'] = $request->input('idkar');
            }
            if(empty($request->input('tanggal'))){
                $data['tanggal'] = $request->input('tanggal');
            }
            if(empty($request->input('grup'))){
                $data['grup'] = $request->input('grup');
            }
            if(empty($request->input('idrmib'))){
                $data['idrmib'] = $request->input('idrmib');
            }
            $grup=$data['grup'];
            //echo $grup;die;
            $idrmib=$data['idrmib'];
            $sqlrmib="SELECT * FROM t_rmib WHERE t_rmib_id=$idrmib";
            $rmibdata=DB::connection()->select($sqlrmib);
            if(empty($rmibdata)){
                $data_input=$data['id_soal'];
                $data_input=$data_input[0];
                $jumlah=$data['jumlah'];
                date_default_timezone_set('Asia/Jakarta');
                $time=date('Y-m-d H:i:s');
                $sqlrmib="SELECT coalesce(max(t_rmib_id),0)+1 as id FROM t_rmib WHERE 1=1";
                $idrmibdata=DB::connection()->select($sqlrmib);
                $idrmib=$idrmibdata[0]->id;

                DB::connection()->table("t_rmib")
                    ->insert([
                        "t_rmib_id"=>$idrmib,
                        "p_karyawan_id"=>$request->get('idkar'),
                        /*"alt1"=>$request->get('alt1'),
                        "alt2"=>$request->get('alt2'),
                        "alt3"=>$request->get('alt3'),*/
                        "tanggal"=>date('Y-m-d',strtotime($request->get('tanggal'))),
                        "created_at"=>date('Y-m-d  H:i:s'),
                        "created_by"=>$id,
                        "active"=>1,
                    ]);

                for ($i=0;$i<$jumlah;$i++){
                    //id nomor soal
                    //echo $data['jumlah'];die;
                    $nomor=$data['id_soal'][$i];
                    //$id_siswa=$data['id_siswa'][$i];
                    //jika user tidak memilih jawaban
                    if (empty($data['pilihan'][$nomor])){
                        //$kosong++;
                        echo "<script>alert('Soal Nomor ke!'.$nomor.' Belum terisi');</script>";
                    }
                    //jawaban dari user
                    $jawaban = $data['pilihan'][$nomor];
                    //echo $nomor.'-'.$jawaban;die;
                    DB::connection()->table("t_rmib_detil")
                        ->insert([
                            "m_rmib_id"=>$nomor,
                            "t_rmib_id"=>$idrmib,
                            "t_rmib_jawaban"=>$data['pilihan'][$nomor],
                            "created_at"=>date('Y-m-d  H:i:s'),
                            "created_by"=>$id,
                            "active"=>1,
                        ]);
                }
                DB::commit();
            }
            else {
                $data_input = $data['id_soal'];
                $data_input = $data_input[0];
                $jumlah = $data['jumlah'];
                date_default_timezone_set('Asia/Jakarta');
                $time = date('Y-m-d H:i:s');
                $idrmib = $rmibdata[0]->t_rmib_id;

                DB::connection()->table("t_rmib")
                    ->where("t_rmib_id", $idrmib)
                    ->update([
                        "t_rmib_id" => $idrmib,
                        "p_karyawan_id" => $request->get('idkar'),
                        /*"alt1"=>$request->get('alt1'),
                        "alt2"=>$request->get('alt2'),
                        "alt3"=>$request->get('alt3'),*/
                        "tanggal" => date('Y-m-d', strtotime($request->get('tanggal'))),
                        "created_at" => date('Y-m-d  H:i:s'),
                        "created_by" => $id,
                        "active" => 1,
                    ]);

                for ($i = 0; $i < $jumlah; $i++) {
                    //id nomor soal
                    //echo $data['jumlah'];die;
                    $nomor = $data['id_soal'][$i];
                    //$id_siswa=$data['id_siswa'][$i];
                    //jika user tidak memilih jawaban
                    if (empty($data['pilihan'][$nomor])) {
                        //$kosong++;
                        echo "<script>alert('Soal Nomor ke!'.$nomor.' Belum terisi');</script>";
                    }
                    //jawaban dari user
                    $jawaban = $data['pilihan'][$nomor];
                    //echo $nomor.'-'.$jawaban;die;
                    DB::connection()->table("t_rmib_detil")
                        ->insert([
                            "m_rmib_id" => $nomor,
                            "t_rmib_id" => $idrmib,
                            "t_rmib_jawaban" => $data['pilihan'][$nomor],
                            "created_at" => date('Y-m-d  H:i:s'),
                            "created_by" => $id,
                            "active" => 1,
                        ]);
                    DB::commit();
                }
            }

            $iduser=Auth::user()->id;
            $sqlidkar="select * from p_karyawan where user_id=$iduser";
            $idkar=DB::connection()->select($sqlidkar);
            $id=$idkar[0]->p_karyawan_id;
            $grup=$data['grup'];

            if($grup=='A'){
                $grup='B';
            }
            else if($grup=='B'){
                $grup='C';
            }
            else if($grup=='C'){
                $grup='D';
            }
            else if($grup=='D'){
                $grup='E';
            }
            else if($grup=='E'){
                $grup='F';
            }
            else if($grup=='F'){
                $grup='G';
            }
            else if($grup=='G'){
                $grup='H';
            }
            else if($grup=='H'){
                $grup='I';
            }
            else if($grup=='I'){
                $grup='J';
            }

            $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
            $user=DB::connection()->select($sqluser);

            $sqlkar="select * from get_data_karyawan() where p_karyawan_id=$id";
            //echo $sqlpa;die;
            $datakar=DB::connection()->select($sqlkar);
            $jk=$datakar[0]->m_jenis_kelamin_id;

            $sqlrmib="select * from m_rmib where active=1 and m_jk_id=$jk and grup='$grup' ORDER BY urutan";
            $datarmib=DB::connection()->select($sqlrmib);

            $sqlrmibjumlah="select count(*) as jumlah from m_rmib where active=1 and m_jk_id=$jk and grup='$grup' ";
            $datarmibjumlah=DB::connection()->select($sqlrmibjumlah);
            $jumlah=$datarmibjumlah[0]->jumlah;

            if($grup=='J'){
                return view('frontend.pa.tambah_rmib_last',compact('user','datakar','datarmib','jumlah','grup','idrmib'));
            }
            else{
                return view('frontend.pa.tambah_rmib',compact('user','datakar','datarmib','jumlah','grup','idrmib'));
            }
            //return redirect()->route('fe.rmib')->with('success','Penilaian Minat Bakat Berhasil di Simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function simpan_rmib_last(Request $request)
    {
        DB::beginTransaction();
        try{
            $iduser = Auth::user()->id;
            $sqlidkar="select * from p_karyawan where user_id=$iduser";
            $idkar=DB::connection()->select($sqlidkar);
            $id=$idkar[0]->p_karyawan_id;

            $this->validate($request, [
                'alt1' => 'required',
                'alt2' => 'required',
                'alt3' => 'required',
            ]);

            $data = $request->all();
            if(empty($request->input('alt1'))){
                $data['alt1'] = $request->input('alt1');
            }
            if(empty($request->input('alt2'))){
                $data['alt2'] = $request->input('alt2');
            }
            if(empty($request->input('alt3'))){
                $data['alt3'] = $request->input('alt3');
            }

            if(empty($request->input('idkar'))){
                $data['idkar'] = $request->input('idkar');
            }
            if(empty($request->input('tanggal'))){
                $data['tanggal'] = $request->input('tanggal');
            }
            if(empty($request->input('grup'))){
                $data['grup'] = $request->input('grup');
            }
            if(empty($request->input('idrmib'))){
                $data['idrmib'] = $request->input('idrmib');
            }
            $grup=$data['grup'];
            //echo $grup;die;
            $idrmib=$data['idrmib'];
            //$sqlrmib="SELECT * FROM t_rmib WHERE t_rmib_id=$idrmib";
            //$rmibdata=DB::connection()->select($sqlrmib);

            DB::connection()->table("t_rmib")
                ->where("t_rmib_id", $idrmib)
                ->update([
                    "t_rmib_id" => $idrmib,
                    "p_karyawan_id" => $request->get('idkar'),
                    "alt1"=>$request->get('alt1'),
                    "alt2"=>$request->get('alt2'),
                    "alt3"=>$request->get('alt3'),
                    "tanggal" => date('Y-m-d', strtotime($request->get('tanggal'))),
                    "created_at" => date('Y-m-d  H:i:s'),
                    "created_by" => $id,
                    "active" => 1,
                ]);
            DB::commit();

            return redirect()->route('fe.rmib')->with('success','Penilaian Minat Bakat Berhasil di Simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function view_pa($id)
    {
        $sqlpa="SELECT a.m_pa_jawaban_id,a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.bulan,a.tahun,a.active,
 case when status=0 then 'Belum Approve' else 'Approve' end as sts_pa,sum(e.jawaban) as total,sum(e.jawaban)/14 as rata2,
 c.nama as penilai,d.nama as approve,a.rekomendasi,a.keterangan,a.keterangan_direktur,e.m_pa_id,a.penilai,a.approve
FROM m_pa_jawaban a
left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
left join p_karyawan c on c.p_karyawan_id=a.penilai
left join p_karyawan d on d.p_karyawan_id=a.approve
left join m_pa_jawaban1 e on e.m_pa_jawaban_id=a.m_pa_jawaban_id
left join m_pa f on f.m_pa_id=e.m_pa_id
where a.m_pa_jawaban_id=$id
GROUP BY a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.active,a.bulan,a.tahun,a.status,
c.nama,d.nama,a.m_pa_jawaban_id,e.m_pa_id,a.penilai,a.approve
ORDER BY 2,3";
        $pa=DB::connection()->select($sqlpa);

        $sqlpadetil="SELECT a.*,b.pertanyaan,b.sub1,b.sub2,c.nama 
FROM m_pa_jawaban1 a
LEFT JOIN m_pa b on a.m_pa_id=b.m_pa_id
LEFT JOIN m_pa_grup c on c.m_pa_grup_id=b.m_grup_pa_id
WHERE a.m_pa_jawaban_id=$id
ORDER BY a.m_pa_id";
        $padetil=DB::connection()->select($sqlpadetil);

        $sqlkaryawan="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 order by nama";
        $karyawan=DB::connection()->select($sqlkaryawan);

        $sqlmanager="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and c.m_pangkat_id IN(3,4,5,6) order by nama";
        $manager=DB::connection()->select($sqlmanager);

        $sqldirektur="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and c.m_pangkat_id=6 order by nama";
        $direktur=DB::connection()->select($sqldirektur);

        $sqlpagroup="SELECT m_pa_grup.nama,m_pa.* FROM m_pa
LEFT JOIN m_pa_grup on m_pa_grup.m_pa_grup_id=m_pa.m_grup_pa_id
WHERE m_pa.active=1 ORDER BY 2";
        $pagroup=DB::connection()->select($sqlpagroup);

        $sqlpajumlah="SELECT count(*) as jumlah FROM m_pa WHERE m_pa.active=1";
        $pajumlah=DB::connection()->select($sqlpajumlah);
        $jumlah=$pajumlah[0]->jumlah;

        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $sqlpangkat="select m_jabatan.m_pangkat_id from p_karyawan_pekerjaan 
LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
where p_karyawan_id=$iduser";
        //echo $sqlpa;die;
        $datapangkat=DB::connection()->select($sqlpangkat);
        $pangkat=0;
        if(!empty($datapangkat['m_pangkat_id'][0])){
            $pangkat=$datapangkat['m_pangkat_id'][0];
        }

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.pa.view_pa',compact('pa','jumlah','karyawan','manager','direktur','padetil','pagroup','pangkat','user'));
    }

    public function edit_pa($id)
    {
        $sqlpa="SELECT a.m_pa_jawaban_id,a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.bulan,a.tahun,a.active,
 case when status=0 then 'Belum Approve' else 'Approve' end as sts_pa,sum(e.jawaban) as total,sum(e.jawaban)/14 as rata2,
 c.nama as penilai,d.nama as approve,a.rekomendasi,a.keterangan,a.keterangan_direktur,e.m_pa_id,a.penilai,a.approve,a.status
FROM m_pa_jawaban a
left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
left join p_karyawan c on c.p_karyawan_id=a.penilai
left join p_karyawan d on d.p_karyawan_id=a.approve
left join m_pa_jawaban1 e on e.m_pa_jawaban_id=a.m_pa_jawaban_id
left join m_pa f on f.m_pa_id=e.m_pa_id
where a.m_pa_jawaban_id=$id
GROUP BY a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.active,a.bulan,a.tahun,a.status,
c.nama,d.nama,a.m_pa_jawaban_id,e.m_pa_id,a.penilai,a.approve
ORDER BY 2,3";
        $pa=DB::connection()->select($sqlpa);

        $sqlpadetil="SELECT a.*,b.pertanyaan,b.sub1,b.sub2,c.nama 
FROM m_pa_jawaban1 a
LEFT JOIN m_pa b on a.m_pa_id=b.m_pa_id
LEFT JOIN m_pa_grup c on c.m_pa_grup_id=b.m_grup_pa_id
WHERE a.m_pa_jawaban_id=$id
ORDER BY a.m_pa_id";
        $padetil=DB::connection()->select($sqlpadetil);

        $sqlkaryawan="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 order by nama";
        $karyawan=DB::connection()->select($sqlkaryawan);

        $sqlmanager="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and c.m_pangkat_id IN(3,4,5,6) order by nama";
        $manager=DB::connection()->select($sqlmanager);

        $sqldirektur="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and c.m_pangkat_id=6 order by nama";
        $direktur=DB::connection()->select($sqldirektur);

        $sqlpagroup="SELECT m_pa_grup.nama,m_pa.* FROM m_pa
LEFT JOIN m_pa_grup on m_pa_grup.m_pa_grup_id=m_pa.m_grup_pa_id
WHERE m_pa.active=1 ORDER BY 2";
        $pagroup=DB::connection()->select($sqlpagroup);
        $sqlpajumlah="SELECT count(*) as jumlah FROM m_pa WHERE m_pa.active=1";
        $pajumlah=DB::connection()->select($sqlpajumlah);
        $jumlah=$pajumlah[0]->jumlah;

        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $sqlpangkat="select m_jabatan.m_pangkat_id from p_karyawan_pekerjaan 
LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
where p_karyawan_id=$iduser";
        //echo $sqlpa;die;
        $datapangkat=DB::connection()->select($sqlpangkat);
        $pangkat=0;
        if(!empty($datapangkat['m_pangkat_id'][0])){
            $pangkat=$datapangkat['m_pangkat_id'][0];
        }

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.pa.edit_pa',compact('pa','jumlah','karyawan','manager','direktur','padetil','pagroup','pangkat','user'));
    }

    public function update_pa(Request $request,$idpa)
    {
        DB::beginTransaction();
        try{
            $id = Auth::user()->id;

            $this->validate($request, [
                'id_soal' => 'required',
                'pilihan' => 'required',
                'jumlah' => 'required',
                'rekomendasi' => 'required',
            ]);

            $data = $request->all();
            if(empty($request->input('id_soal'))){
                $data['id_soal'] = $request->input('id_soal');
            }
            if(empty($request->input('pilihan'))){
                $data['pilihan'] = $request->input('pilihan');
            }
            if(empty($request->input('jumlah'))){
                $data['jumlah'] = $request->input('jumlah');
            }
            //echo $data['jumlah'];die;
            $score=0;
            $benar=0;
            $salah=0;
            $kosong=0;
            $data_input=$data['id_soal'];
            $data_input=$data_input[0];
            $jumlah=$data['jumlah'];
            //return $data_input;die;
            //foreach ($data_input as $jumlah){
            //  $jumlah['pilihan'];die;
            //}
            date_default_timezone_set('Asia/Jakarta');
            $time=date('Y-m-d H:i:s');
            DB::connection()->table("m_pa_jawaban")
                ->where("m_pa_jawaban_id",$idpa)
                ->update([
                    "p_karyawan_id"=>$request->get('nama'),
                    "penilai"=>$request->get('manager'),
                    "approve"=>$request->get('direktur'),
                    "rekomendasi"=>$request->get('rekomendasi'),
                    "keterangan"=>$request->get('deskripsi'),
                    "tanggal"=>date('Y-m-d H:i:s',strtotime($request->get('tanggal'))),
                    "bulan"=>date('m',strtotime($request->get('tanggal'))),
                    "tahun"=>date('Y',strtotime($request->get('tanggal'))),
                    "updated_at"=>date('Y-m-d  H:i:s'),
                    "updated_by"=>$id,
                    "active"=>1,
                ]);

            DB::connection()->table("m_pa_jawaban1")
                ->where("m_pa_jawaban_id",$id)
                ->delete();

            for ($i=0;$i<$jumlah;$i++){
                //id nomor soal
                //echo $data['jumlah'];die;
                $nomor=$data['id_soal'][$i];
                //$id_siswa=$data['id_siswa'][$i];
                //jika user tidak memilih jawaban
                if (empty($data['pilihan'][$nomor])){
                    //$kosong++;
                    echo "<script>alert('Soal Nomor ke!'.$nomor.' Belum terisi');</script>";
                }
                //jawaban dari user
                $jawaban = $data['pilihan'][$nomor];
                //echo $nomor.'-'.$jawaban;die;
                DB::connection()->table("m_pa_jawaban1")
                    ->insert([
                        "m_pa_id"=>$nomor,
                        "m_pa_jawaban_id"=>$idpa,
                        "jawaban"=>$jawaban,
                        "updated_at"=>date('Y-m-d  H:i:s'),
                        "updated_by"=>$id,
                    ]);

                //cocokan jawaban user dengan jawaban di database
                ///$query=mysql_query("select * from tbl_soal where id_soal='$nomor' and knc_jawaban='$jawaban'");
                //$cek=mysql_num_rows($query);
                //if($cek){
                //jika jawaban cocok (benar)
                //$benar++;
                //}else{
                //jika salah
                //$salah++;
                //}

                //}
                /*RUMUS
                Jika anda ingin mendapatkan Nilai 100, berapapun jumlah soal yang ditampilkan
                hasil= 100 / jumlah soal * jawaban yang benar
                */

                /*$result=mysql_query("select * from tbl_soal WHERE aktif='Y'");
                $jumlah_soal=mysql_num_rows($result);
                $score = 100/$jumlah_soal*$benar;
                $hasil = number_format($score,1);*/
            }

            //Lakukan Penyimpanan Kedalam Database
            /*echo "
             <tr><td>Jumlah Jawaban Benar</td><td> : $benar </td></tr>
             <tr><td>Jumlah Jawaban Salah</td><td> : $salah</td></tr>
             <tr><td>Jumlah Jawaban Kosong</td><td>: $kosong</td></tr>
            </table></div>";*/

            DB::commit();
            return redirect()->route('fe.pa')->with('success','Penilaian Kinerja Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function approve_pa($id)
    {
        $sqlpa="SELECT a.m_pa_jawaban_id,a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.bulan,a.tahun,a.active,
 case when status=0 then 'Belum Approve' else 'Approve' end as sts_pa,sum(e.jawaban) as total,sum(e.jawaban)/14 as rata2,
 c.nama as penilai,d.nama as approve,a.rekomendasi,a.keterangan,a.keterangan_direktur,e.m_pa_id,a.penilai,a.approve,a.status
FROM m_pa_jawaban a
left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
left join p_karyawan c on c.p_karyawan_id=a.penilai
left join p_karyawan d on d.p_karyawan_id=a.approve
left join m_pa_jawaban1 e on e.m_pa_jawaban_id=a.m_pa_jawaban_id
left join m_pa f on f.m_pa_id=e.m_pa_id
where a.m_pa_jawaban_id=$id
GROUP BY a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.active,a.bulan,a.tahun,a.status,
c.nama,d.nama,a.m_pa_jawaban_id,e.m_pa_id,a.penilai,a.approve
ORDER BY 2,3";
        $pa=DB::connection()->select($sqlpa);

        $sqlpadetil="SELECT a.*,b.pertanyaan,b.sub1,b.sub2,c.nama 
FROM m_pa_jawaban1 a
LEFT JOIN m_pa b on a.m_pa_id=b.m_pa_id
LEFT JOIN m_pa_grup c on c.m_pa_grup_id=b.m_grup_pa_id
WHERE a.m_pa_jawaban_id=$id
ORDER BY a.m_pa_id";
        $padetil=DB::connection()->select($sqlpadetil);

        $sqlkaryawan="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 order by nama";
        $karyawan=DB::connection()->select($sqlkaryawan);

        $sqlmanager="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and c.m_pangkat_id IN(3,4,5,6) order by nama";
        $manager=DB::connection()->select($sqlmanager);

        $sqldirektur="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and c.m_pangkat_id=6 order by nama";
        $direktur=DB::connection()->select($sqldirektur);

        $sqlpagroup="SELECT m_pa_grup.nama,m_pa.* FROM m_pa
LEFT JOIN m_pa_grup on m_pa_grup.m_pa_grup_id=m_pa.m_grup_pa_id
WHERE m_pa.active=1 ORDER BY 2";
        $pagroup=DB::connection()->select($sqlpagroup);
        $sqlpajumlah="SELECT count(*) as jumlah FROM m_pa WHERE m_pa.active=1";
        $pajumlah=DB::connection()->select($sqlpajumlah);
        $jumlah=$pajumlah[0]->jumlah;

        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
        $sqlpangkat="select m_jabatan.m_pangkat_id from p_karyawan_pekerjaan 
LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
where p_karyawan_id=$iduser";
        //echo $sqlpa;die;
        $datapangkat=DB::connection()->select($sqlpangkat);
        $pangkat=0;
        if(!empty($datapangkat['m_pangkat_id'][0])){
            $pangkat=$datapangkat['m_pangkat_id'][0];
        }

        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('frontend.pa.approve_pa',compact('pa','jumlah','karyawan','manager','direktur','padetil','pagroup','pangkat','user'));
    }

    public function save_approve_pa(Request $request,$idpa)
    {
        DB::beginTransaction();
        try{
            $id = Auth::user()->id;

            date_default_timezone_set('Asia/Jakarta');
            $time=date('Y-m-d H:i:s');
            DB::connection()->table("m_pa_jawaban")
                ->where("m_pa_jawaban_id",$idpa)
                ->update([
                    "keterangan_direktur"=>$request->get('deskripsi'),
                    "status"=>$request->get('status'),
                    "updated_at"=>date('Y-m-d  H:i:s'),
                    "updated_by"=>$id,
                    "active"=>1,
                ]);

            DB::commit();
            return redirect()->route('fe.pa')->with('success','Approval Penilaian Kinerja Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function hapus_pa($id)
    {
        DB::beginTransaction();
        try{
            DB::connection()->table("m_pa_jawaban")
                ->where("m_pa_jawaban_id",$id)
                ->delete();

            DB::connection()->table("m_pa_jawaban1")
                ->where("m_pa_jawaban_id",$id)
                ->delete();

            return redirect()->back()->with('success','Penilaian Kinerja Berhasil di Hapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
}
