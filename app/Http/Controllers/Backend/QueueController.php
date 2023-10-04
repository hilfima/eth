<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Maatwebsite\Excel\Excel;
use Mail;
use App\Helper_function;
use Response;
use PDF;

class QueueController extends Controller
{
    
    public function queue($panel)
    {
    	
    	$queue = DB::connection()->select("select  * from queue where panel ='$panel' and status_queue in(0,2) order by status_queue,priority desc, create_date limit 1");
    	//print_r($queue);
    	if(count($queue)){
    		
    	DB::connection()->table("queue")
                ->where('queue_id', $queue[0]->queue_id)
                ->update([
                    "status_queue"=>2
                ]);
    	$function = $queue[0]->function_call;
    	if(QueueController::$function($queue[0]->parameter_1,$queue[0]->parameter_2,$queue[0]->parameter_3)){
    		DB::connection()->table("queue")
                ->where('queue_id', $queue[0]->queue_id)
                ->update([
                    "status_queue"=>1
                ]);
    	}
    		echo json_encode(array('status'=>1));
    	}else{
    		echo json_encode(array('status'=>0));
    	}
    	
    	
    }
    public function slip_simpan($parameter1_id_karyawan,$parameter2_id_generate)
    {
    		$help = new Helper_function();
			$id = $parameter1_id_karyawan;
			$id_prl = $parameter2_id_generate;
			$sql="SELECT *,case when periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,(select prl_generate_karyawan_id from prl_generate_karyawan where prl_generate_id=$id_prl and p_karyawan_id = $id) as id_slip FROM prl_generate where prl_generate_id=$id_prl and active = 1";
			$generate=DB::connection()->select($sql);
			
			$periode_absen=$generate[0]->periode_absen_id;
			$sqlperiode="SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
			$periodetgl=DB::connection()->select($sqlperiode);
			$type = $periodetgl[0]->type;
			if($type) $p = 'bulanan';
			else if(!$type) $p = 'pekanan';
			$where = " d.periode_gajian = ".$type; 
			$appendwhere = "and";
			
			$periode_gajian=$periodetgl[0]->type;
			$tgl_awal_name_file=date('Y m d',strtotime($periodetgl[0]->tgl_awal));
			$tgl_akhir_name_file=date('Y m d',strtotime($periodetgl[0]->tgl_akhir));
			$tgl_awal=date('Y-m-d',strtotime($periodetgl[0]->tgl_awal));
			$tgl_akhir=date('Y-m-d',strtotime($periodetgl[0]->tgl_akhir));
			
		
			
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
		
			 and b.active=1
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
		 
		
		
		 $sqlkaryawan="SELECT a.nama as nmlengkap,a.p_karyawan_id,a.nik,b.nama_lengkap,b.nama_panggilan,c.nama as nmjk,d.nama as nmkota,e.nama as nmagama,g.nama as nmlokasi,g.alamat as alamatlokasi,g.m_lokasi_id,h.nama as nmjabatan,i.nama as nmdept,j.nama as nmdivisi,l.nama as nmstatus,m.no_absen,n.kartu_keluarga,
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
WHERE a.p_karyawan_id=$id ";
		$karyawan=DB::connection()->select($sqlkaryawan);
		
		
        
		
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
		$sqlperiode="SELECT m_periode_absen.*,
		
		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur
		FROM prl_generate a 
		join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		where  a.active = 1
		ORDER BY a.create_date desc, prl_generate_id   desc";
		$periode=DB::connection()->select($sqlperiode);
		//$periode_absen=$request->get('periode_gajian');
        $user=DB::connection()->select($sqluser);
		$list_karyawan = "SELECT a.nama,a.p_karyawan_id
					FROM p_karyawan a
					join p_karyawan_pekerjaan g on a.p_karyawan_id = g.p_karyawan_id

					WHERE a.active =1  order by nama";
		$list_karyawan=DB::connection()->select($list_karyawan);
		
			$data = ['data' => $data,
			           'help' => $help,
			           'user' => $user, 
			           'periode' => $periode, 
			           'periode_absen' => $periode_absen, 
			           'id_prl' => $id_prl, 
			           
			           'list_karyawan' => $list_karyawan, 
			           'karyawan' => $karyawan, 
			           'generate' => $generate, 
			           'parameter1_id_karyawan' => $parameter1_id_karyawan, 
			           'row'=> $row];
          
			 $pdf = PDF::loadView('backend.gaji.slip.pdf', $data);
			 //return $pdf->download('Slip Gaji.pdf');
			$output = $pdf->output();
    		file_put_contents('dist\img\slip\Slif Gaji '.$karyawan[0]->nmlengkap.' - '.$p.' '.$tgl_awal_name_file.' sd'.$tgl_akhir_name_file.'.pdf', $output);
    		return true;
    }
    
    
}