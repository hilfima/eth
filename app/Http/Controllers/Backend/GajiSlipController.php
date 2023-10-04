<?php

namespace App\Http\Controllers\Backend;

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
use PDF;

class GajiSlipController extends Controller
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

    public function view(Request $request)
    {
    	
		$help= new Helper_function();
			$iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access,m_role_id FROM users
					left join m_role on m_role.m_role_id=users.role
					left join p_karyawan on p_karyawan.user_id=users.id
					left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
					left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
					where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        $id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasikaryawan = "AND g.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasikaryawan = "";	
       
		if($request->get('prl_generate')   )
		{
			$id_kary = $id = $request->get('p_karyawan');
			$id_prl = $request->get('prl_generate');
			$sql="SELECT *,
					case when prl_generate_karyawan.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
					(prl_generate_karyawan_id) as id_slip,prl_generate_karyawan.periode_gajian as periode,prl_generate.tahun
					FROM prl_generate 
						join prl_generate_karyawan on prl_generate_karyawan.prl_generate_id=prl_generate.prl_generate_id and p_karyawan_id = $id
						where prl_generate.prl_generate_id=$id_prl and prl_generate_karyawan.active = 1";
			$generate=DB::connection()->select($sql);
			
			$periode_absen=$generate[0]->periode_absen_id;
			if($periode_absen){
				
			$sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
			$periodetgl=DB::connection()->select($sqlperiode);
			$type = $periodetgl[0]->type;
			$where = " d.periode_gajian = ".$type; 
			$appendwhere = "and";
			
			$periode_gajian=$periodetgl[0]->type;
			$tgl_awal=date('Y-m-d',strtotime($periodetgl[0]->tgl_awal));
			$tgl_akhir=date('Y-m-d',strtotime($periodetgl[0]->tgl_akhir));
			}
			
		
			
			$sql="select *, (select count(*) from p_karyawan_koperasi where nama_koperasi='ASA' and active=1 and b.p_karyawan_id = p_karyawan_koperasi.p_karyawan_id) as is_koperasi_asa, (select count(*) from p_karyawan_koperasi where nama_koperasi='KKB' and active=1 and b.p_karyawan_id = p_karyawan_koperasi.p_karyawan_id) as is_koperasi_kkb,
			case 
				when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
				when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
				when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
				when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
				when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
				end as nama,m_lokasi.kode as nm_lokasi
			from prl_gaji a 
			join m_lokasi on a.m_lokasi_id = m_lokasi.m_lokasi_id
			join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id 
			where prl_generate_id = $id_prl and p_karyawan_id = $id
		
			 and b.active=1 and a.active=1
			group by nama,p_karyawan_id,nm_lokasi,m_lokasi.m_lokasi_id,prl_gaji_detail_id,a.prl_gaji_id
			order by prl_gaji_detail_id 
			
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 ";
		
		$row = DB::connection()->select($sql);
		
			$data= array();
			foreach ($row as $row) {
				
				$data[$row->p_karyawan_id][$row->nama] = round($row->nominal);
				$data[$row->p_karyawan_id]['Entitas'][$row->nama] = ($row->nm_lokasi);
				$data[$row->p_karyawan_id]['id'][$row->nama] = ($row->prl_gaji_detail_id);
				if($row->nama=='Iuran BPJS Ketenaga Kerjaan'){
				//	echo '<br>'.$row->nominal.'-->>>>>>'.($row->prl_gaji_detail_id);
				}	
			}	
		 
		
		
		 $sqlkaryawan="SELECT a.p_karyawan_id,a.nik,b.nama_lengkap,b.nama_panggilan,c.nama as nmjk,d.nama as nmkota,e.nama as nmagama,g.nama as nmlokasi,g.alamat as alamatlokasi,g.m_lokasi_id,h.nama as nmjabatan,i.nama as nmdept,j.nama as nmdivisi,l.nama as nmstatus,m.no_absen,n.kartu_keluarga,
case when f.is_shift=0 then 'Shift' else 'Non Shift' end as shift,k.tgl_awal,k.tgl_akhir,a.tgl_bergabung,a.email_perusahaan,k.keterangan,case when a.active=1 then 'Active' else 'Non Active' end as status,o.nama as nmpangkat,b.alamat_ktp,a.pendidikan,a.jurusan,a.nama_sekolah,a.domisili,a.jumlah_anak,b.tempat_lahir,case when m_status_id=0 then 'Belum Menikah' else 'Sudah Menikah' end as status_pernikahan,b.no_hp,c.nama as jenis_kelamin,e.nama as agama,b.email,b.tgl_lahir,f.kantor,n.ktp,n.no_npwp,n.no_bpjstk,n.no_bpjsks,b.m_status_id,b.m_kota_id,b.m_jenis_kelamin_id,b.m_agama_id,f.m_lokasi_id,f.m_jabatan_id,k.m_status_pekerjaan_id,f.m_departemen_id,f.m_divisi_id,a.active,f.kota,n.no_sima,n.no_simc,n.no_pasport, case when f.periode_gajian=1 then 'Bulanan' else 'Pekanan' end as periode_absen,f.periode_gajian,p.nama as nama_kantor,q.nama_grade,f.bank,f.norek,f.m_kantor_id,f.m_karyawan_grade_id
FROM p_karyawan a
LEFT JOIN p_recruitment b on b.p_recruitment_id=a.p_recruitment_id
LEFT JOIN m_jenis_kelamin c on c.m_jenis_kelamin_id=b.m_jenis_kelamin_id
LEFT JOIN m_kota d on d.m_kota_id=b.m_kota_id
LEFT JOIN m_agama e on e.m_agama_id=b.m_agama_id
LEFT JOIN p_karyawan_pekerjaan f on f.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_lokasi g on g.m_lokasi_id=f.m_lokasi_id
LEFT JOIN m_jabatan h on h.m_jabatan_id=f.m_jabatan_id
LEFT JOIN m_pangkat o on o.m_pangkat_id=h.m_pangkat_id
LEFT JOIN m_departemen i on i.m_departemen_id=f.m_departemen_id
LEFT JOIN m_divisi j on j.m_divisi_id=f.m_divisi_id
LEFT JOIN p_karyawan_kontrak k on k.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_status_pekerjaan l on l.m_status_pekerjaan_id=k.m_status_pekerjaan_id
LEFT JOIN p_karyawan_absen m on m.p_karyawan_id=a.p_karyawan_id
LEFT JOIN p_karyawan_kartu n on n.p_karyawan_id=a.p_karyawan_id
LEFT JOIN m_office p on p.m_office_id=f.m_kantor_id
LEFT JOIN m_karyawan_grade q on q.m_karyawan_grade_id=f.m_karyawan_grade_id
WHERE a.p_karyawan_id=$id $whereLokasikaryawan";
		$karyawan=DB::connection()->select($sqlkaryawan);
		}
		else{
			$id = '';
			$data= array();
		$list_karyawan='';	
		$karyawan='';	
		$row='';	
		$generate='';	
		$id_prl='';	
		}
        
		
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
		$sqlperiode="SELECT m_periode_absen.*,
		
		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur
		FROM prl_generate a 
		left join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		left join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		where  a.active = 1
		ORDER BY a.create_date desc, prl_generate_id   desc";
		$periode=DB::connection()->select($sqlperiode);
		$periode_absen=$request->get('periode_gajian');
        $user=DB::connection()->select($sqluser);
		$list_karyawan = "SELECT a.nama,a.p_karyawan_id
					FROM p_karyawan a
					join p_karyawan_pekerjaan g on a.p_karyawan_id = g.p_karyawan_id

					WHERE a.active =1 $whereLokasikaryawan order by nama";
		$list_karyawan=DB::connection()->select($list_karyawan);
		if($request->get('Cari')=='PDF'){
			$data = ['data' => $data,
           'help' => $help,
           'user' => $user, 
           'periode' => $periode, 
           'periode_absen' => $periode_absen, 
           'request' => $request, 
           'id_prl' => $id_prl, 
           'id_kary' => $id_kary, 
           
           'list_karyawan' => $list_karyawan, 
           'karyawan' => $karyawan, 
           'generate' => $generate, 
           'row'=> $row,
           'view'=>'pdf'
           ];
          
			  $pdf = PDF::loadView('frontend.slip.pdf_slip', $data)->setPaper('a5', 'portait');
			 
			    $dompdf = $pdf->getDomPDF();
				$canvas = $dompdf->get_canvas();
				
				$dompdf->render();
				if($generate[0]->periode==1)
				$nama_periode = "".$help->bulan($generate[0]->bulan)."  ".($generate[0]->tahun);
				else
					$nama_periode ="Pekan ".$generate[0]->generate_pekanan_ke." ".$help->bulan($generate[0]->bulan)." ".($generate[0]->tahun);;
			return	$dompdf->stream('Slip Gaji - '.$karyawan[0]->nama_lengkap.' - '.$nama_periode.' .pdf', array("Attachment" => true));
       
		}else{
        return view('backend.gaji.slip.slip',compact('data','user','help','periode','periode_absen','request','id_prl','help','periode','periode_absen','request','list_karyawan','karyawan','generate','row'));
		}
    } 
} 