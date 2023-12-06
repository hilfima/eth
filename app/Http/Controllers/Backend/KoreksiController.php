<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class koreksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function koreksi(Request $request)
    {
         $sudah_appr = array();
            $sudah_appr_keuangan = array();
            $sudah_appr_hr = array();
            $sudah_appr_voucher = array();
            $m_lokasi_hr_appr_on = '';
            $m_lokasi_hr_appr_off = '';
            $m_lokasi_direktur_appr_on = '';
            $m_lokasi_direktur_appr_off = '';
       	$iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access,m_role_id FROM users
					left join m_role on m_role.m_role_id=users.role
					left join p_karyawan on p_karyawan.user_id=users.id
					left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
					left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
					where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
		$sqlperiode="SELECT m_periode_absen.*,
		
		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur
		FROM prl_generate a 
		join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		where a.active = 1
		ORDER BY a.create_date desc, prl_generate_id desc";
		$periode=DB::connection()->select($sqlperiode);
		$id_prl = $request->get('prl_generate');
		if($request->get('prl_generate')){
			$id_lokasi = Auth::user()->user_entitas;
        if($id_lokasi and $id_lokasi!=-1) 
			$whereLokasi = "AND e.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
       
        	$sqlperiode = "SELECT * FROM prl_gaji where prl_generate_id=$id_prl ";
            $sudahappr = DB::connection()->select($sqlperiode);
             if($sudahappr){
            foreach ($sudahappr as $apprs) {
                if ($apprs->appr_on_direktur_status == 1)
                    $sudah_appr[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_direktur_status == 1)
                    $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 0;




                if ($apprs->appr_on_keuangan_status == 1)
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_keuangan_status == 1)
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['OFF'] = 0;

                if ($apprs->appr_on_hr_status == 1)
                    $sudah_appr_hr[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_hr[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_hr_status == 1)
                    $sudah_appr_hr[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_hr[$apprs->m_lokasi_id]['OFF'] = 0;




                if ($apprs->appr_on_voucher_status == 1)
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['ON'] = 0;


                if ($apprs->appr_off_voucher_status == 1)
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['OFF'] = 0;


                if ($apprs->appr_on_hr_status == 1) {
                    $m_lokasi_hr_appr_on .= $apprs->m_lokasi_id . ',';
                }
                if ($apprs->appr_off_hr_status == 1) {
                    $m_lokasi_hr_appr_off .= $apprs->m_lokasi_id . ',';
                }
                if ($apprs->appr_off_direktur_status == 1) {
                    $m_lokasi_direktur_appr_off .= $apprs->m_lokasi_id . ',';
                }
                if ($apprs->appr_on_direktur_status == 1) {
                    $m_lokasi_direktur_appr_on .= $apprs->m_lokasi_id . ',';
                }
            }
             $m_lokasi_direktur_appr_on .= '-1';
            $m_lokasi_direktur_appr_off .= '-1';
            $m_lokasi_hr_appr_on .= '-1';
            $m_lokasi_hr_appr_off .= '-1';
                 
             }
             $sqlkoreksi="SELECT *,c.nama,e.nama as nmlokasi ,
        
                case 
				when b.type=1 then nama_gaji
				when b.type=2 then tunjangan2.nama
				when b.type=3 then tunjangan3.nama 
				when b.type=4 then m_potongan4.nama 
				when b.type=5 then m_potongan5.nama
				
				end as nama_type,m_lokasi.kode as nm_lokasi,b.nominal,prl_gaji_detail_id,b.p_karyawan_id,a.m_lokasi_id,ml.nama as sumber_dana
			from prl_gaji a 
			join m_lokasi on a.m_lokasi_id = m_lokasi.m_lokasi_id
			join prl_gaji_detail b on b.prl_gaji_id = a.prl_gaji_id and a.prl_generate_id = $id_prl
			join prl_generate_karyawan k on k.p_karyawan_id = b.p_karyawan_id and k.prl_generate_id = a.prl_generate_id and k.prl_generate_id = $id_prl 
			join m_lokasi ml on b.m_lokasi_sumber_data_id = ml.m_lokasi_id
			left join m_gaji_absen on b.gaji_absen_id = m_gaji_absen.m_gaji_absen_id
			left join m_tunjangan as tunjangan3 on b.m_tunjangan_id = tunjangan3.m_tunjangan_id
			left join prl_tunjangan on b.prl_tunjangan_id = prl_tunjangan.prl_tunjangan_id
			left join m_tunjangan as tunjangan2 on prl_tunjangan.m_tunjangan_id = tunjangan2.m_tunjangan_id
			
			left join m_potongan as m_potongan5 on b.m_potongan_id = m_potongan5.m_potongan_id
			left join prl_potongan  on b.prl_potongan_id = prl_potongan.prl_potongan_id
			left join m_potongan as m_potongan4 on prl_potongan.m_potongan_id = m_potongan4.m_potongan_id
			
			
        		join p_karyawan c on b.p_karyawan_id = c.p_karyawan_id
        		join p_karyawan_pekerjaan d on b.p_karyawan_id = d.p_karyawan_id
        		join m_lokasi e on e.m_lokasi_id = k.lokasi_id
                WHERE 1=1 and a.prl_generate_id = $id_prl and ((b.m_tunjangan_id = 16 or b.m_potongan_id = 17) or (b.keterangan is not null and b.string_jenis_ijin is null )) $whereLokasi
                 
                and a.active=1 
                and b.active=1
                order by b.nominal desc,keterangan desc,lokasi_id
                ";
                //echo $sqlkoreksi;die;
        $Koreksi=DB::connection()->select($sqlkoreksi);
		}else{
			$Koreksi = '';
			$sqlperiode = '';
            $sudahappr = '';
		}
	
           
           
           
        return view('backend.gaji.koreksi.koreksi',compact('Koreksi','periode','request','id_prl','sudah_appr'));
    }

    public function tambah_koreksi_pajak($id_prl)
    {
    	$sql="SELECT * FROM prl_generate where prl_generate_id=$id_prl";
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
			
			$sql = "SELECT c.p_karyawan_id,c.nama as nama,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id,i.nama as nmpangkat ,m_jabatan.nama as nmjabatan,g.tgl_awal,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur,m_lokasi.m_lokasi_id
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN p_karyawan_kontrak g on g.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
			LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
			LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			WHERE $where $appendwhere  c.active = 1
			
			
			order by c.nama,m_departemen.nama";;
			//echo $sql;and c.p_karyawan_id not in(select p_karyawan_id from prl_gaji_detail a join prl_gaji b on a.prl_gaji_id = b.prl_gaji_id where prl_generate_id = $id_prl and a.nominal!=0)
			$list_karyawan = DB::connection()->select($sql);
			$entitas = DB::connection()->select("Select * from m_lokasi where active=1 and sub_entitas=0 ORDER BY nama ");
                $id = $id_prl;
                $type = 'simpan_Koreksi';
                $data['karyawan']='';
                $data['type']='';
                $data['keterangan']='';
                $data['m_lokasi_sumber_data_id']='';
                $data['nominal']='Rp ';
                $data['prl_generate_id']=$id_prl;
        return view('backend.gaji.koreksi.tambah_koreksi_pajak',compact('id','id_prl','data','type','entitas','list_karyawan'));
    }

    public function tambah_koreksi($id_prl)
    {
       		$sql="SELECT * FROM prl_generate where prl_generate_id=$id_prl";
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
			$whereLokasi = "AND m_lokasi.m_lokasi_id in($id_lokasi)";					
		else
			$whereLokasi = "";	
			$sqlperiode = "SELECT * FROM prl_gaji where prl_generate_id=$id_prl ";
            $sudahappr = DB::connection()->select($sqlperiode);
             if($sudahappr){
                 
            $m_lokasi_hr_appr_on = '';
            $m_lokasi_hr_appr_off = '';
            $m_lokasi_direktur_appr_on = '';
            $m_lokasi_direktur_appr_off = '';
            foreach ($sudahappr as $apprs) {
                if ($apprs->appr_on_direktur_status == 1)
                    $sudah_appr[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_direktur_status == 1)
                    $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr[$apprs->m_lokasi_id]['OFF'] = 0;




                if ($apprs->appr_on_keuangan_status == 1)
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_keuangan_status == 1)
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_keuangan[$apprs->m_lokasi_id]['OFF'] = 0;

                if ($apprs->appr_on_hr_status == 1)
                    $sudah_appr_hr[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_hr[$apprs->m_lokasi_id]['ON'] = 0;

                if ($apprs->appr_off_hr_status == 1)
                    $sudah_appr_hr[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_hr[$apprs->m_lokasi_id]['OFF'] = 0;




                if ($apprs->appr_on_voucher_status == 1)
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['ON'] = 1;
                else
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['ON'] = 0;


                if ($apprs->appr_off_voucher_status == 1)
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['OFF'] = 1;
                else
                    $sudah_appr_voucher[$apprs->m_lokasi_id]['OFF'] = 0;


                if ($apprs->appr_on_hr_status == 1) {
                    $m_lokasi_hr_appr_on .= $apprs->m_lokasi_id . ',';
                }
                if ($apprs->appr_off_hr_status == 1) {
                    $m_lokasi_hr_appr_off .= $apprs->m_lokasi_id . ',';
                }
                if ($apprs->appr_off_direktur_status == 1) {
                    $m_lokasi_direktur_appr_off .= $apprs->m_lokasi_id . ',';
                }
                if ($apprs->appr_on_direktur_status == 1) {
                    $m_lokasi_direktur_appr_on .= $apprs->m_lokasi_id . ',';
                }
            }
             $m_lokasi_direktur_appr_on .= '-1';
            $m_lokasi_direktur_appr_off .= '-1';
            $m_lokasi_hr_appr_on .= '-1';
            $m_lokasi_hr_appr_off .= '-1';
                 
             }
            $whereLokasi .= "and (case when d.pajak_onoff='ON' then d.m_lokasi_id not in($m_lokasi_direktur_appr_on) else d.m_lokasi_id not in($m_lokasi_direktur_appr_off) end)";
			$sql = "SELECT c.p_karyawan_id,c.nama as nama,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id,i.nama as nmpangkat ,m_jabatan.nama as nmjabatan,g.tgl_awal,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur,m_lokasi.m_lokasi_id
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN p_karyawan_kontrak g on g.p_karyawan_id=c.p_karyawan_id  and g.active=1
			LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
			LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
			LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			WHERE $where $appendwhere  c.active = 1 $whereLokasi
		
			
			AND f.m_pangkat_id != 6
			
			order by c.nama,m_departemen.nama";;
			//echo $sql;and c.p_karyawan_id not in(select p_karyawan_id from prl_gaji_detail a join prl_gaji b on a.prl_gaji_id = b.prl_gaji_id where prl_generate_id = $id_prl and a.nominal!=0)
			$list_karyawan = DB::connection()->select($sql);
			$entitas = DB::connection()->select("Select * from m_lokasi where active=1 and sub_entitas=0 ORDER BY nama ");
                $id = $id_prl;
                $type = 'simpan_Koreksi';
                $data['karyawan']='';
                $data['type']='';
                $data['keterangan']='';
                $data['m_lokasi_sumber_data_id']='';
                $data['nominal']='Rp ';
                $data['prl_generate_id']=$id_prl;
        return view('backend.gaji.koreksi.tambah_koreksi',compact('id','id_prl','data','type','entitas','list_karyawan'));
    }

    public function simpan_koreksi(Request $request,$id_prl){
    	$help = new Helper_function();
	  // echo $kode;die;
	  $karyawan_list = $request->get('karyawan');
	  for($i=0;$i<count($karyawan_list);$i++){
	  	
	  	$ex = explode('-',$karyawan_list[$i]);
	  		$id_kary = $ex[0];
	  		$id_lokasi = $ex[1];
	  		$id_lokasi = $request->get("sumber_data");
	  	$sql = "select prl_gaji_id from prl_gaji where prl_generate_id = $id_prl and m_lokasi_id = $id_lokasi";
	  	$id_gaji = DB::connection()->select($sql);
		  
		  if (!count($id_gaji)) {
			  $sql = "select max(prl_gaji_id) from prl_gaji";
			  $karyawan = DB::connection()->select($sql);
			DB::connection()->table("prl_gaji")
			  ->insert([
			  	  "m_lokasi_id" => $id_lokasi,
				  "prl_gaji_id" => $karyawan[0]->max + 1,
				  "prl_generate_id" => $id_prl,

				  "active" => 1,
				  "create_date" => date("Y-m-d H:i:s")
			  ]);
			  $id_prl_Gaji = $karyawan[0]->max + 1;
	  	}else{
			  $id_prl_Gaji = $id_gaji[0]->prl_gaji_id;
	  	}
        if($request->get("type")==3){
			$row = 'm_tunjangan_id';
			$value= 16;
		}else  if($request->get("type")==5){
			$row = 'm_potongan_id';
			$value= 17;
		} 
        	
        
         DB::connection()->table("prl_gaji_detail")
            ->insert([
				"prl_gaji_id" => $id_prl_Gaji,
                "p_karyawan_id" => $id_kary,
                "type" => $request->get("type"),
                "keterangan" => $request->get("keterangan"),
                "m_lokasi_sumber_data_id" => $request->get("sumber_data"),
                $row => $value,
                "nominal" => $help->hapusRupiah($request->get("nominal")),
                
            ]);
          
		}
        return redirect()->route('be.Koreksi')->with('success',' koreksi Berhasil di input!');
    }

    public function edit_koreksi($id)
    {
      
			$entitas = DB::connection()->select("Select * from m_lokasi where active=1 and sub_entitas=0 ORDER BY nama ");
			$sql = "SELECT c.p_karyawan_id,c.nama as nama,c.nik,m_lokasi.kode as nmlokasi,m_departemen.nama as departemen , f.m_pangkat_id,i.nama as nmpangkat ,m_jabatan.nama as nmjabatan,g.tgl_awal,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur,m_lokasi.m_lokasi_id
			FROM p_karyawan c 
			LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN p_karyawan_kontrak g on g.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_departemen on m_departemen.m_departemen_id=d.m_departemen_id
			LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=d.m_lokasi_id
			LEFT JOIN m_jabatan on m_jabatan.m_jabatan_id=d.m_jabatan_id 
			LEFT JOIN m_jabatan f on d.m_jabatan_id=f.m_jabatan_id 
			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			WHERE c.active = 1   
			
			order by c.nama,m_departemen.nama";;
			//echo $sql;and c.p_karyawan_id not in(select p_karyawan_id from prl_gaji_detail a join prl_gaji b on a.prl_gaji_id = b.prl_gaji_id where prl_generate_id = $id_prl and a.nominal!=0)
			$list_karyawan = DB::connection()->select($sql);

                $type = 'update_Koreksi';
        $sqlkoreksi="SELECT *,(select prl_generate_id from prl_gaji where prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id) as prl_generate_id FROM prl_gaji_detail  WHERE active=1 and prl_gaji_detail_id = $id  ";
        $koreksi=DB::connection()->select($sqlkoreksi);
        
		$data['karyawan']=$koreksi[0]->p_karyawan_id;
        $data['type']=$koreksi[0]->type;
        $data['nominal']=$koreksi[0]->nominal;
        $data['keterangan']=$koreksi[0]->keterangan;
        $data['prl_generate_id'] = $koreksi[0]->prl_generate_id;
        $data['m_lokasi_sumber_data_id']=$koreksi[0]->m_lokasi_sumber_data_id;
        return view('backend.gaji.koreksi.tambah_koreksi', compact('data','id','type','list_karyawan','entitas'));
    }

    public function update_koreksi(Request $request, $id){
        $idUser=Auth::user()->id;
		$help = new Helper_function();
       
	  		$id_lokasi = $request->get("sumber_data");
	  		$id_prl = $request->get('id_prl');
	  	$sql = "select prl_gaji_id from prl_gaji where prl_generate_id = $id_prl and m_lokasi_id = $id_lokasi";
	  	$id_gaji = DB::connection()->select($sql);
		  echo $request->get("type");
        if($request->get("type")==3){
			$row = 'm_tunjangan_id';
			$value= 16;
		}else  if($request->get("type")==5){
			$row = 'm_potongan_id';
			$value= 17;
		} 
		DB::connection()->table("prl_gaji_detail")
            ->where("prl_gaji_detail_id",$id)
            ->update([
               "prl_gaji_id" => $id_gaji[0]->prl_gaji_id,
                
                "type" => $request->get("type"),
                "keterangan" => $request->get("keterangan"),
                $row => $value,
                "nominal" => $help->hapusRupiah($request->get("nominal")),
                "update_date"=>date("Y-m-d H:i:s")
            ]);

        return redirect()->route('be.Koreksi')->with('success',' koreksi Berhasil di Ubah!');
    }

    public function hapus_koreksi($id)
    {
        $idUser=Auth::user()->id;
        DB::connection()->table("prl_gaji_detail")
            ->where("prl_gaji_detail_id",$id)
            ->update([
                "active"=>0,
               
            ]);

        return redirect()->back()->with('success',' koreksi Berhasil di Hapus!');
    }
}
