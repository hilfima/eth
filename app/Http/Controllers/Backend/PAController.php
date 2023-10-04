<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use PDF;
class PAController extends Controller
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
    public function pa()
    {
        $sqlpa="SELECT a.m_pa_jawaban_id,a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.bulan,a.tahun,a.active,
 case when status=0 then 'Belum Approve' else 'Approve' end as sts_pa,sum(e.jawaban) as total,sum(e.jawaban)/count(e.m_pa_jawaban_id) as rata2,
 c.nama as penilai,d.nama as approve,a.status
FROM m_pa_jawaban a
left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
left join p_karyawan c on c.p_karyawan_id=a.penilai
left join p_karyawan d on d.p_karyawan_id=a.approve
left join m_pa_jawaban1 e on e.m_pa_jawaban_id=a.m_pa_jawaban_id
where a.active=1
GROUP BY a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.active,a.bulan,a.tahun,a.status,
c.nama,d.nama,a.m_pa_jawaban_id
ORDER BY a.created_at desc";
        $pa=DB::connection()->select($sqlpa);

        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
       // $id=$idkar[0]->p_karyawan_id;
		
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

        return view('backend.pa.pa',compact('pa','user'));
    }

    public function tambah_pa()
    {
        $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        //$id=$idkar[0]->p_karyawan_id;
$id=null;
        $sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);

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

        $sqlkaryawan="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and a.active=1  order by nama";
        $karyawan=DB::connection()->select($sqlkaryawan);
		$sqlidkar="select * from p_karyawan 
			join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id 
			where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=1 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		
        $sqlmanager="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and c.m_pangkat_id IN(3,4,5,6)  and a.active=1 order by nama";
        $manager=DB::connection()->select($sqlmanager);

        $sqldirektur="SELECT a.* FROM p_karyawan a
left join p_karyawan_pekerjaan b on b.p_karyawan_id=a.p_karyawan_id
left join m_jabatan c on c.m_jabatan_id=b.m_jabatan_id
left join m_pangkat d on d.m_pangkat_id=c.m_pangkat_id
WHERE 1=1 and c.m_pangkat_id=6   and a.active=1 order by nama";
        $direktur=DB::connection()->select($sqldirektur);

        $sqlpa="SELECT m_pa_grup.nama,m_pa.* FROM m_pa
LEFT JOIN m_pa_grup on m_pa_grup.m_pa_grup_id=m_pa.m_grup_pa_id
WHERE m_pa.active=1 ORDER BY 2";
        $pa=DB::connection()->select($sqlpa);
        $sqlpajumlah="SELECT count(*) as jumlah FROM m_pa WHERE m_pa.active=1";
        $pajumlah=DB::connection()->select($sqlpajumlah);
        $jumlah=$pajumlah[0]->jumlah;
        return view('backend.pa.tambah_pa',compact('pa','jumlah','karyawan','manager','direktur','pangkat','user'));
    }

    public function simpan_pa(Request $request)
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
            $sqlidpa="SELECT coalesce(max(m_pa_jawaban_id),0)+1 as id FROM m_pa_jawaban WHERE 1=1";
            $idpadata=DB::connection()->select($sqlidpa);
            $idpa=$idpadata[0]->id;
            DB::connection()->table("m_pa_jawaban")
                ->insert([
                    "m_pa_jawaban_id"=>$idpa,
                    "p_karyawan_id"=>$request->get('nama'),
                    "penilai"=>$request->get('manager'),
                    "approve"=>$request->get('direktur'),
                    "rekomendasi"=>$request->get('rekomendasi'),
                    "keterangan"=>$request->get('deskripsi'),
                    "tanggal"=>date('Y-m-d H:i:s',strtotime($request->get('tanggal'))),
                    "bulan"=>date('m',strtotime($request->get('tanggal'))),
                    "tahun"=>date('Y',strtotime($request->get('tanggal'))),
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
                DB::connection()->table("m_pa_jawaban1")
                    ->insert([
                        "m_pa_id"=>$nomor,
                        "m_pa_jawaban_id"=>$idpa,
                        "jawaban"=>$jawaban,
                        "created_at"=>date('Y-m-d  H:i:s'),
                        "created_by"=>$id,
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
            return redirect()->route('be.pa')->with('success','Penilaian Kinerja Berhasil di simpan!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }

    public function view_pa($id, Request $request)
    {
        $sqlpa="SELECT a.m_pa_jawaban_id,a.p_karyawan_id,b.nik,b.nama,a.tanggal,a.bulan,a.tahun,a.active,
 case when status=0 then 'Belum Approve' else 'Approve' end as sts_pa,sum(e.jawaban) as total,sum(e.jawaban)/14 as rata2,
 c.nama as nmpenilai,d.nama as nmapprove,a.rekomendasi,a.keterangan,a.keterangan_direktur,e.m_pa_id,a.penilai,a.approve
 
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
        $type = $request->get('Cari');
        //echo $type;die;
		if($request->get('Cari')=='print'){
			
        $send = ['pa'=>$pa,'jumlah'=>$jumlah,'karyawan'=>$karyawan,'manager'=>$manager,'direktur'=>$direktur,'padetil'=>$padetil,'pagroup'=>$pagroup,'pangkat'=>$pangkat,'user'=>$user,'type'=>$type
        
        ];
        $pdf = PDF::loadView('backend.pa.print_pa', $send);

        $pdf->setPaper('letter', 'portait');
        return $pdf->download('Penilaian Kerja.pdf');
		}else{
			
        	return view('backend.pa.view_pa',compact('pa','jumlah','karyawan','manager','direktur','padetil','pagroup','pangkat','user','type'));
		}
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
     $id=null; //  $id=$idkar[0]->p_karyawan_id;
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

        return view('backend.pa.edit_pa',compact('pa','jumlah','karyawan','manager','direktur','padetil','pagroup','pangkat','user'));
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
            return redirect()->route('be.pa')->with('success','Penilaian Kinerja Berhasil di simpan!');
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
    $id=null; //  $id=$idkar[0]->p_karyawan_id;
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

        return view('backend.pa.approve_pa',compact('pa','jumlah','karyawan','manager','direktur','padetil','pagroup','pangkat','user'));
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
            return redirect()->route('be.pa')->with('success','Approval Penilaian Kinerja Berhasil di simpan!');
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
