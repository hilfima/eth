<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;use App\Setting;
use Maatwebsite\Excel\Excel;
use Mail;
use Response;
use PDF;

class Fe_SlipController extends Controller
{
    /*
     *
     * Create a new controller instance.
     *
     * @return void
     */
	 public function __construct()
	 {
		 $this->middleware('auth');
	 }
	 public function lihat_slip(Request $request)
    { 
    	
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $periode_gaji = $idkar[0]->periode_gajian;
        $pajak_onoff = $idkar[0]->pajak_onoff;
        $id_lokasi = $idkar[0]->m_lokasi_id;
        $gaji = array();
        if(!$pajak_onoff)
        	$pajak_onoff = 'off';
        
        $pajak_onoff = strtolower($pajak_onoff);
        	
        $id=$idkar[0]->p_karyawan_id;
        $id_kary = $id;
        $where_pekanan ='';
        $r = "appr_".$pajak_onoff."_keuangan_status";
        $gaji = array();
        if($request->pekanan_ke)
        $where_pekanan =' and pekanan_ke='.$request->pekanan_ke;
        if( $request->get('bulan') and $request->get('tahun')){
        	
         $gaji=DB::connection()->select("select *,m_lokasi.nama as namalokasi,m_jabatan.nama as nmjabatan,m_pangkat.nama as nmpangkat
         from prl_generate 
        join m_periode_absen on prl_generate.periode_absen_id = m_periode_absen.periode_absen_id
        join prl_generate_karyawan on prl_generate_karyawan.prl_generate_id = prl_generate.prl_generate_id  and p_karyawan_id = $id_kary
        join prl_gaji on prl_gaji.m_lokasi_id = prl_generate_karyawan.lokasi_id and prl_generate.prl_generate_id = prl_gaji.prl_generate_id
        left join m_lokasi on prl_gaji.m_lokasi_id = m_lokasi.m_lokasi_id
        left join m_jabatan on prl_generate_karyawan.jabatan_id = m_jabatan.m_jabatan_id
		LEFT JOIN m_pangkat on m_jabatan.m_pangkat_id=m_pangkat.m_pangkat_id
        where prl_generate.tahun = ".$request->get('tahun')." and prl_generate.bulan = ".$request->get('bulan')." and $r=1  and prl_generate_karyawan.periode_gajian = ".$request->get('periode_gajian')." and prl_generate.active=1  $where_pekanan ");
        	if(count($gaji))
			$id_prl = $gaji[0]->prl_generate_id;
        	else
        		$id_prl=-99;
        }else{
        	$id_prl='';
        }
			$data= array();
			if($id_prl and $id_prl!=-99){
				
			$sql="SELECT *,case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,(select prl_generate_karyawan_id from prl_generate_karyawan where prl_generate_id=$id_prl and p_karyawan_id = $id) as id_slip FROM prl_generate where prl_generate_id=$id_prl and active = 1";
			$generate=DB::connection()->select($sql);
			$periode_absen=$generate[0]->periode_absen_id;
			$sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
			$periodetgl=DB::connection()->select($sqlperiode);
			$type = $periodetgl[0]->type;
			$where = " d.periode_gajian = ".$type; 
			$appendwhere = "and";
			$periode_gajian=$periodetgl[0]->type;
			$tgl_awal=date('Y-m-d',strtotime($periodetgl[0]->tgl_awal));
			$tgl_akhir=date('Y-m-d',strtotime($periodetgl[0]->tgl_akhir));
			$sql="select *,
			case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
				when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
				when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
				when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
				when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
				 end as nama from prl_gaji a join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id where prl_generate_id = $id_prl and p_karyawan_id = $id";
		
				$row = DB::connection()->select($sql);
				
				foreach($row as $row){
					$data[$row->p_karyawan_id][$row->nama] = round($row->nominal);
					
				}	
			}else{
				$generate='';
				$periode_absen ='';
				$row ='';
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
WHERE a.p_karyawan_id=$id";
		$karyawan=DB::connection()->select($sqlkaryawan);
		$help = new Helper_function();
		
		$sqlperiode="SELECT prl_generate.*, 
		(select tgl_awal from  m_periode_absen a where  prl_generate.periode_absen_id = a.periode_absen_id) as tgl_awal,(select tgl_akhir from  m_periode_absen b where  prl_generate.periode_absen_id = b.periode_absen_id) as tgl_akhir,(select pekanan_ke from m_periode_absen c where  prl_generate.periode_absen_id = c.periode_absen_id) as pekanan_ke,
		case when prl_generate.bulan=1 then 'Januari'
		when prl_generate.bulan=2 then 'Februari'
		when prl_generate.bulan=3 then 'Maret'
		when prl_generate.bulan=4 then 'April'
		when prl_generate.bulan=5 then 'Mei'
		when prl_generate.bulan=6 then 'Juni'
		when prl_generate.bulan=7 then 'Juli'
		when prl_generate.bulan=8 then 'Agustus'
		when prl_generate.bulan=9 then 'September'
		when prl_generate.bulan=10 then 'Oktober'
		when prl_generate.bulan=11 then 'November'
		when prl_generate.bulan=12 then 'Desember' end ,
		case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe
		FROM prl_generate 
		join prl_gaji on prl_generate.prl_generate_id = prl_gaji.prl_generate_id and m_lokasi_id = $id_lokasi
		where  prl_generate.periode_gajian = $periode_gaji and appr_".$pajak_onoff."_keuangan_status=1 and prl_generate.active = 1
		ORDER BY prl_generate.tahun desc,bulan desc
		
		
		";
		//echo $sqlperiode;die;
		$periode=DB::connection()->select($sqlperiode);
		
		if($request->get('Cari')=='PDF'){
			$data = ['data' => $data,
           'help' => $help,
          
           'periode_absen' => $periode_absen, 
           'request' => $request, 
           'id_prl' => $id_prl, 
           'id_kary' => $id_kary, 
           
           'karyawan' => $karyawan, 
           'gaji' => $gaji, 
           'generate' => $generate, 
           'row'=> $row];
          
			 $pdf = PDF::loadView('frontend.slip.pdf_slip', $data)->setPaper('a5', 'portait');
			 
			    $dompdf = $pdf->getDomPDF();
				$canvas = $dompdf->get_canvas();
				
				$dompdf->render();
			return	$dompdf->stream('Slip Gaji.pdf', array("Attachment" => true));
			// return $pdf->download('Slip Gaji.pdf');
       
		}else{
		  
      return view('frontend.slip.list_slip_2',compact('data','help','periode','periode_absen','id_prl','id_kary','help','periode_absen','karyawan','generate','row','idkar','request','gaji')); 
       }
    	
    } 
    public function slip_thr(Request $request)
    { 
    	
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan left  join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $periode_gaji = $idkar[0]->periode_gajian;
        $pajak_onoff = $idkar[0]->pajak_onoff;
        $id_lokasi = $idkar[0]->m_lokasi_id;
        $gaji = array();
        if(!$pajak_onoff)
        	$pajak_onoff = 'off';
        
        $pajak_onoff = strtolower($pajak_onoff);
        	
        $id=$idkar[0]->p_karyawan_id;
        $id_kary = $id;
        $where_pekanan ='';
        $r = "appr_".$pajak_onoff."_keuangan_status";
        $gaji = array();
        if($request->pekanan_ke)
        $where_pekanan =' and pekanan_ke='.$request->pekanan_ke;
        if( $request->get('tahun')){
        	
         $gaji=DB::connection()->select("select *,m_lokasi.nama as namalokasi,m_jabatan.nama as nmjabatan,m_pangkat.nama as nmpangkat
         from prl_generate 
        join prl_generate_karyawan on prl_generate_karyawan.prl_generate_id = prl_generate.prl_generate_id  and p_karyawan_id = $id_kary
        join prl_gaji on prl_gaji.m_lokasi_id = prl_generate_karyawan.lokasi_id and prl_generate.prl_generate_id = prl_gaji.prl_generate_id
        left join m_lokasi on prl_gaji.m_lokasi_id = m_lokasi.m_lokasi_id
        left join m_jabatan on prl_generate_karyawan.jabatan_id = m_jabatan.m_jabatan_id
		LEFT JOIN m_pangkat on m_jabatan.m_pangkat_id=m_pangkat.m_pangkat_id
        where prl_generate.tahun = ".$request->get('tahun')."  and $r=1  and prl_generate_karyawan.periode_gajian = ".$request->get('periode_gajian')." and prl_generate.active=1  $where_pekanan and is_thr=1");
        	if(count($gaji))
			$id_prl = $gaji[0]->prl_generate_id;
        	else
        		$id_prl=-99;
        }else{
        	$id_prl='';
        }
			$data= array();
			if($id_prl and $id_prl!=-99){
				
			$sql="SELECT *,case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,(select prl_generate_karyawan_id from prl_generate_karyawan where prl_generate_id=$id_prl and p_karyawan_id = $id) as id_slip FROM prl_generate where prl_generate_id=$id_prl and active = 1";
			$generate=DB::connection()->select($sql);
			$periode_absen=$generate[0]->periode_absen_id;
			
			$sql="select *,
			case when type=1 then (select nama_gaji from m_gaji_absen where b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id )
				when type=2 then (select nama from prl_tunjangan join m_tunjangan on prl_tunjangan.m_tunjangan_id = m_tunjangan.m_tunjangan_id where b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id )
				when type=3 then (select nama from m_tunjangan where b.m_tunjangan_id = m_tunjangan.m_tunjangan_id)
				when type=4 then (select nama from prl_potongan join m_potongan on prl_potongan.m_potongan_id = m_potongan.m_potongan_id where b.prl_potongan_id = prl_potongan.prl_potongan_id )
				when type=5 then (select nama from m_potongan where b.m_potongan_id = m_potongan.m_potongan_id)
				 end as nama from prl_gaji a join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id where prl_generate_id = $id_prl and p_karyawan_id = $id";
		
				$row = DB::connection()->select($sql);
				
				foreach($row as $row){
					$data[$row->p_karyawan_id][$row->nama] = round($row->nominal);
					
				}	
			}else{
				$generate='';
				$periode_absen ='';
				$row ='';
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
WHERE a.p_karyawan_id=$id";
		$karyawan=DB::connection()->select($sqlkaryawan);
		$help = new Helper_function();
		
		$sqlperiode="SELECT prl_generate.*, 
		(select tgl_awal from  m_periode_absen a where  prl_generate.periode_absen_id = a.periode_absen_id) as tgl_awal,(select tgl_akhir from  m_periode_absen b where  prl_generate.periode_absen_id = b.periode_absen_id) as tgl_akhir,(select pekanan_ke from m_periode_absen c where  prl_generate.periode_absen_id = c.periode_absen_id) as pekanan_ke,
		case when prl_generate.bulan=1 then 'Januari'
		when prl_generate.bulan=2 then 'Februari'
		when prl_generate.bulan=3 then 'Maret'
		when prl_generate.bulan=4 then 'April'
		when prl_generate.bulan=5 then 'Mei'
		when prl_generate.bulan=6 then 'Juni'
		when prl_generate.bulan=7 then 'Juli'
		when prl_generate.bulan=8 then 'Agustus'
		when prl_generate.bulan=9 then 'September'
		when prl_generate.bulan=10 then 'Oktober'
		when prl_generate.bulan=11 then 'November'
		when prl_generate.bulan=12 then 'Desember' end ,
		case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe
		FROM prl_generate 
		join prl_gaji on prl_generate.prl_generate_id = prl_gaji.prl_generate_id and m_lokasi_id = $id_lokasi
		where  prl_generate.periode_gajian = $periode_gaji and appr_".$pajak_onoff."_keuangan_status=1 and prl_generate.active = 1
		ORDER BY prl_generate.tahun desc,bulan desc
		
		
		";
		//echo $sqlperiode;die;
		$periode=DB::connection()->select($sqlperiode);
		
		if($request->get('Cari')=='PDF'){
			$data = ['data' => $data,
           'help' => $help,
          
           'periode_absen' => $periode_absen, 
           'request' => $request, 
           'id_prl' => $id_prl, 
           'id_kary' => $id_kary, 
           
           'karyawan' => $karyawan, 
           'gaji' => $gaji, 
           'generate' => $generate, 
           
           'row'=> $row];
          
			 $pdf = PDF::loadView('frontend.slip.pdf_slip', $data)->setPaper('a5', 'portait');
			 
			    $dompdf = $pdf->getDomPDF();
				$canvas = $dompdf->get_canvas();
				
				$dompdf->render();
			return	$dompdf->stream('Slip Gaji.pdf', array("Attachment" => true));
			// return $pdf->download('Slip Gaji.pdf');
       
		}else{
       return view('frontend.slip.list_slip_thr',compact('data','help','periode','periode_absen','id_prl','id_kary','help','periode_absen','karyawan','generate','row','idkar','request','gaji')); 
       }
    	
    }public function slip()
    {
    	$iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
    	$sqlperiode="SELECT m_periode_absen.*,
		
		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur,
		case when bulan=1 then 'Januari'
		when bulan=2 then 'Februari'
		when bulan=3 then 'Maret'
		when bulan=4 then 'April'
		when bulan=5 then 'Mei'
		when bulan=6 then 'Juni'
		when bulan=7 then 'Juli'
		when bulan=8 then 'Agustus'
		when bulan=9 then 'September'
		when bulan=10 then 'Oktober'
		when bulan=11 then 'November'
		when bulan=12 then 'Desember' end as bulan
		FROM prl_generate a 
		join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		join prl_generate_karyawan c on a.prl_generate_id = c.prl_generate_id and p_karyawan_id =  $id
		where  a.active = 1 
		ORDER BY a.create_date desc, prl_generate_id   desc";
		$slip=DB::connection()->select($sqlperiode);
    	return view('frontend.slip.slip',compact('slip'));
	}
}
