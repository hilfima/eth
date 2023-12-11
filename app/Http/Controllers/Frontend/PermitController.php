<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use App\Helper_function;
use DB;
use Mail;

class PermitController extends Controller
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
	
	public function email_appr()
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		WHERE 1=1 and a.kode='PIZIN.07.21.100001' and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc ";
		$data=DB::connection()->select($sqldata);
		return view('email_appr_izin',compact('data'));
	}

	public function get_izin_detail(Request $request)
	{
		$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan 
						join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
						where user_id=$iduser";
			
			$idkar=DB::connection()->select($sqlidkar);
			$idkaryawan=$idkar[0]->p_karyawan_id;
		$val = $request->val;
		$jenis_izin = DB::connection()->select("select * from m_batas_pengajuan 
		left join m_jenis_ijin on m_jenis_ijin.m_batas_pengajuan_id = m_batas_pengajuan.m_batas_pengajuan_id 
		where m_jenis_ijin_id=$val");
		$batas_hari = (int)$jenis_izin[0]->batas_hari;
		$batas_tipe = $jenis_izin[0]->batas_tipe;

		//print_r($jenis_izin[0])
		$help = new Helper_function();
		$keterangan = "";
		$hari_sebelum = 0;
		$min = 0;
		$hari_setelah = 0;
		if($batas_tipe=='-'){
			$min = $help->tambah_tanggal(date('Y-m-d'),$batas_hari);
			$absen = true;
		}else if($batas_tipe=='+-'){
			$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."'  and tgl_akhir >= '".date('Y-m-d')."' ";
				$gapen=DB::connection()->select($sql);
				$min = $gapen[0]->tgl_awal;
				if(!count($gapen)){
					$absen = false;
					$keterangan ="Belum terdapat periode aktif, silahkan hubungi HC ";
					
				}else{
					$hari_sebelum = $jenis_izin[0]->batas_hari_sebelum;
					$hari_setelah = $jenis_izin[0]->batas_hari_setelah;
					$absen = true;
					$min = $gapen[0]->tgl_awal;
				}
		}else{
			
			
			$help = new Helper_function();
			$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."'  and tgl_akhir >= '".date('Y-m-d')."' ";
				$gapen=DB::connection()->select($sql);
				$min = $gapen[0]->tgl_awal;
				if(!count($gapen)){
					$absen = false;
					$keterangan ="Belum terdapat periode aktif, silahkan hubungi HC ";
					
				}else{
					$rekap = $help->rekap_absen($gapen[0]->tgl_awal,$gapen[0]->tgl_akhir,$gapen[0]->tgl_awal,$gapen[0]->tgl_akhir,$idkar[0]->periode_gajian,null,$idkaryawan);
					if(isset($rekap['absen']['a'][date('Y-m-d')][$idkaryawan]['masuk'])){
						$absen = true;
						if(isset($rekap['absen']['a'][$help->tambah_tanggal(date('Y-m-d'),-$batas_hari)][$idkaryawan]['masuk'])){
							$absen = false;
							$keterangan ="Tidak dapat melakukan pengajuan karena pengajuan diharuskan $batas_hari Hari setelah masuk ";
							
						}else{
							$absen = true;	
							$true = false;
							$i=0;
							$imax = $help->hitungHari($gapen[0]->tgl_awal,date('Y-m-d'));
							$date=$help->tambah_tanggal(date('Y-m-d'),-$batas_hari);
							while($true==false){
								
								if($i==$imax){
									$true=true;
									$min = $gapen[0]->tgl_awal;
								}
								if(isset($rekap['absen']['a'][$date][$idkaryawan]['masuk'])){
									$true=true;
									$min = $date;
								}
								

								$i++;
								$date = $help->tambah_tanggal($date,-1);
							}
							
						}
					}else{
						$absen = false;
						$keterangan ="Absen belum terekap system";
					}
				}
		}
		$kontent_alasan = ' <div class="form-group row" >
		<label class="col-sm-2 control-label">Alasan*</label>
		<div class="col-sm-10">
		 <select required class="form-control select2" name="alasan_id" id="alasan_id" style="width: 100%;" placeholder="Alasan">
		<option value="">Pilih Alasan</option>';
		//$val
		$alasan = DB::connection()->select("select * from m_jenis_alasan where jenis = $val");
		foreach($alasan as $alasan){
			$kontent_alasan .="<option value='$alasan->m_jenis_alasan_id'>$alasan->alasan</option>";

		}
		$kontent_alasan .="</select></div></div></div>";
		$kontent_parameter_input = ' <div class="form-group row">
		<label class="col-sm-2 control-label">'.$jenis_izin[0]->nama_parameter_input.'*</label>
		<div class="col-sm-10"> 
			<input type="date" class="form-control " id="tgl_parameter" onchange="parameter_tgl(this,'.$hari_sebelum.','.$hari_setelah.','.$min.')" min="'.$min.'" name="tgl_parameter" data-target="#tgl_akhir" value="" required  data-date-format="DD-MMMM-YYYY" >
					   
			</div>
		</div>';
		$data['min'] = $min;
		$data['require_file'] = $jenis_izin[0]->wajib_file==1?1:0;
		$data['alasan'] = $jenis_izin[0]->alasan==1?1:0;
		$data['absen'] = $absen;
		$data['batas_tipe'] = $batas_tipe;
		$data['nama_parameter_input'] = $kontent_parameter_input;
		$data['keterangan'] = $keterangan;
		$data['hari_sebelum'] = $hari_sebelum;
		$data['hari_setelah'] = $hari_setelah;
		$data['kontent_alasan'] = $kontent_alasan;
		$data['tgl_akhir_awal'] = $jenis_izin[0]->tanggal_akhir_sama_tanggal_awal;
		$data['batas_tipe'] = $batas_tipe;
		echo json_encode($data);
	}

	public function status_persetujuan(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";

		if ($request->get('ajuan'))
			$where .= ' and c.tipe = '.$request->get('ajuan');
		if (($request->get('status')))
			$where .= ' and a.status_appr_1 = '.$request->get('status');



		$sqlcuti="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 FROM t_permit a
				left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
				left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
				left join p_karyawan d on d.p_karyawan_id=a.appr_1
				WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 ORDER BY a.tgl_awal desc";
		$permit=DB::connection()->select($sqlcuti);
		$help = new Helper_function();
		return view('frontend.permit.status_persetujuan',compact('permit','help','request'));
	}

	public function get_jam_permit(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
					join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
					where user_id=$iduser";
		
		$idkar=DB::connection()->select($sqlidkar);
		$idkaryawan=$idkar[0]->p_karyawan_id;
		
		$help = new Helper_function();
		$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."'  and tgl_akhir >= '".date('Y-m-d')."' ";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
				echo json_encode(
				array(
					
					"simpan_type"=>0,
					"simpan_keterangan"=>"Periode Aktif belum ditentukan, silahkan hubungi HC"
				)
			);	
			}else{
				
		//$rekap = $rekap = $help->rekap_absen($gapen[0]->tgl_awal,$gapen[0]->tgl_akhir,$gapen[0]->tgl_awal,$gapen[0]->tgl_akhir,$idkar[0]->periode_gajian,null,$idkaryawan);
		$rekap = $help->rekap_absen($request->tgl_awal,$request->tgl_akhir,$request->tgl_awal,$request->tgl_akhir,$idkar[0]->periode_gajian,null,$idkaryawan);
		
		$tgl_awal = $request->tgl_awal;
		
		if(isset($rekap['absen']['a'][$tgl_awal][$idkaryawan]['masuk']) and $request->pengajuan==21){
			
			/// IDT 2 5
			/// IPM 2
			// sebulan 3 kali maksimal(IDT+IPM)
			$date = date_create($tgl_awal.' '.$rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_masuk']);
			//echo $tgl_awal.' '.$rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_masuk'];
			date_add($date, date_interval_create_from_date_string('150 minutes '));
			$jam_idt_paling_telat =  date_format($date, 'H:i:s');
			$idt=0;
			if(isset($rekap['pengajuan']['total_id'][$idkaryawan][21])){
				
				$idt=$rekap['pengajuan']['total_id'][$idkaryawan][21];
			}
			$ipm=0;
			if(isset($rekap['pengajuan']['total_id'][$idkaryawan][26])){
				
				$ipm=$rekap['pengajuan']['total_id'][$idkaryawan][26];
			}
			//print_r($rekap['absen']['a'][$tgl_awal][$idkaryawan]); 
		if(!isset($rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_masuk'])){
			echo json_encode(
					array(
					"simpan_type"=>0,
				    "simpan_keterangan"=>"Jam Masuk Kantor tidak ada, periksa pada Cek Presensi dan pastikan bahwa anda sudah absen pada tanggal tersebut serta untuk karyawan shift, coba diperiksa pada jadwal shift"
				)
				);
		}else
		if($ipm+$idt>3)	{
				echo json_encode(
					array(
					"simpan_type"=>0,
				"simpan_keterangan"=>"Anda tidak bisa mengajukan kembali pengajuan IDT dan IPM dikarenakan meleibihi akumulasi total pengajuan IDT dan IPM dalam rentang periode berjalan"
				)
				);
		}else if($rekap['absen']['a'][$tgl_awal][$idkaryawan]['masuk']<$jam_idt_paling_telat and $request->pengajuan == 21){
				
				echo json_encode(
					array(
						"jam_masuk_kerja"=>$rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_masuk'],
						"jam_masuk_finger"=>$rekap['absen']['a'][$tgl_awal][$idkaryawan]['masuk'],
						"simpan_type"=>1,
						"simpan_keterangan"=>""
					)
				);
		}else if(($rekap['absen']['a'][$tgl_awal][$idkaryawan]['masuk']>=$jam_idt_paling_telat) and $request->pengajuan == 21 ){
				echo json_encode(
				array("simpan_type"=>0,
				"simpan_keterangan"=>"Jam masuk absen anda melebihi batas ketentuan yang berlaku(Jam Kerja : ".$rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_masuk']. " | Batas : $jam_idt_paling_telat |  Jam Masuk : ".$rekap['absen']['a'][$tgl_awal][$idkaryawan]['masuk'].")"
					)
				);
		}else if(isset($rekap['pengajuan']['ci'][$tgl_awal][$idkaryawan])){
			echo json_encode(
				array(
					
					"simpan_type"=>0,
					"simpan_keterangan"=>"Terdapat pada tanggal yang diajukan"
				)
			);
		}else{
			echo json_encode(
				array(
					
					"simpan_type"=>0,
					"simpan_keterangan"=>"Absen anda belum terekap system,anda tidak bisa melakukan pengajuan"
				)
			);
		}
		}
        else if(isset($rekap['absen']['a'][$tgl_awal][$idkaryawan]['keluar']) and $request->pengajuan==26){
                $date = date_create($tgl_awal.' '.$rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_keluar']);
			//echo $tgl_awal.' '.$rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_masuk'];
			date_add($date, date_interval_create_from_date_string('-120 minutes '));
			$jam_ipm_paling_telat =  date_format($date, 'H:i:s');
			$idt=0;
			if(isset($rekap['pengajuan']['total_id'][$idkaryawan][21])){
				
				$idt=$rekap['pengajuan']['total_id'][$idkaryawan][21];
			}
			$ipm=0;
			if(isset($rekap['pengajuan']['total_id'][$idkaryawan][26])){
				
				$ipm=$rekap['pengajuan']['total_id'][$idkaryawan][26];
			}
			//print_r($rekap['absen']['a'][$tgl_awal][$idkaryawan]); 
			if(!isset($rekap['absen']['a'][$tgl_awal][$idkaryawan]['keluar'])){
				echo json_encode(
				array(
					
					"simpan_type"=>0,
					"simpan_keterangan"=>"Absen anda belum terekap system,anda tidak bisa melakukan pengajuan"
				)
			);
		}   
    		if(!isset($rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_keluar'])){
    			echo json_encode(
    					array(
    					"simpan_type"=>0,
    				    "simpan_keterangan"=>"Jam Masuk Kantor tidak ada(untuk karyawan shift, coba diperiksa pada jadwal shift)"
    				)
    				);
    		}else
    		if($ipm+$idt>3)	{
    				echo json_encode(
    					array(
    					"simpan_type"=>0,
    				"simpan_keterangan"=>"Anda tidak bisa mengajukan kembali pengajuan IDT dan IPM dikarenakan meleibihi akumulasi total pengajuan IDT dan IPM dalam rentang periode berjalan"
    				)
    				);
    		}else if($rekap['absen']['a'][$tgl_awal][$idkaryawan]['keluar']>$jam_ipm_paling_telat and $request->pengajuan == 26){
    				
    				echo json_encode(
    					array(
    						"jam_keluar_kerja"=>$rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_keluar'],
    						"jam_keluar_finger"=>$rekap['absen']['a'][$tgl_awal][$idkaryawan]['keluar'],
    						"batas_keluar"=>$jam_ipm_paling_telat,
    						"simpan_type"=>1,
    						"simpan_keterangan"=>""
    					)
    				);
    		}else if(($rekap['absen']['a'][$tgl_awal][$idkaryawan]['keluar']<=$jam_ipm_paling_telat) and $request->pengajuan == 26 ){
    				echo json_encode(
    				array("simpan_type"=>0,
    				"simpan_keterangan"=>"Jam Keluar absen anda melebihi batas ketentuan yang berlaku(Jam Keluar Kerja : ".$rekap['absen']['a'][$tgl_awal][$idkaryawan]['jam_keluar']. " | Batas : $jam_ipm_paling_telat 
    				    |  Jam Keluar : ".$rekap['absen']['a'][$tgl_awal][$idkaryawan]['keluar'].")"
					    )
    				);
    		}else if(isset($rekap['pengajuan']['ci'][$tgl_awal][$idkaryawan])){
    			echo json_encode(
    				array(
    					
    					"simpan_type"=>0,
    					"simpan_keterangan"=>"Terdapat pada tanggal yang diajukan"
    				)
    			);
    		}else{
    			echo json_encode(
    				array(
    					
    					"simpan_type"=>0,
    					"simpan_keterangan"=>"Absen anda belum terekap system,anda tidak bisa melakukan pengajuan"
    				)
    			);
    		}
        }
        else if(!($request->pengajuan == 26 or $request->pengajuan == 21)){
             echo json_encode(
				array(
					
					"simpan_type"=>1,
					"simpan_keterangan"=>""
				)
			);
		}
        else{
            echo json_encode(
				array(
					
					"simpan_type"=>0,
					"simpan_keterangan"=>"Absen anda belum terekap system,anda tidak bisa melakukan pengajuan"
				)
			);
        }

	}
	}

	public function permit()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$sqldatakar="select * from get_data_karyawan() where p_karyawan_id=$id and m_pangkat_id IN(3,4,5,6)";
		$datakar=DB::connection()->select($sqldatakar);

		$sqldw="select * from get_data_karyawan() where p_karyawan_id=$id";
		$dw=DB::connection()->select($sqldw);

		$tahun=date('Y');
		$sqlcuti="SELECT * FROM get_list_cuti_tahunan_new($tahun,$id) ";
		$datacuti=DB::connection()->select($sqlcuti);
		if (!empty($datacuti)) {
			$cuti=$datacuti[0]->sisa_cuti;
		} else {
			$cuti=0;
		}


		return view('frontend.permit.permit', compact('datakar','cuti','dw'));
	}
	public function listed(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan 
					join p_karyawan_pekerjaan a on p_karyawan.p_karyawan_id = a.p_karyawan_id 
					where user_id=$iduser";
		
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1   ";
		$gapen=DB::connection()->select($sql);
		
		$tgl_akhir_gaji = $gapen[0]->tgl_akhir;
		$tgl_awal_gaji = $gapen[0]->tgl_awal;
		$where = " and tgl_awal<='".$tgl_akhir_gaji."'";
		$where = " and tgl_awal>='".$tgl_awal_gaji."'";
		$where = " ";
		
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";
        else{
            $where .= " and tgl_awal>='".$tgl_awal_gaji."'";
	    	$where .= " and tgl_awal<='".$tgl_akhir_gaji."'";
        }
		if ($request->get('ajuan'))
			$where .= ' and c.tipe = '.$request->get('ajuan');

		if (($request->get('status')))
			$where .= " and (
			    (a.status_appr_1 = ".$request->get('status')." and a.m_jenis_ijin_id !=22 and a.appr_1=$id ) or

    			( 
    			    (a.status_appr_1 = ".$request->get('status')." and a.appr_2 is null  and a.m_jenis_ijin_id =22 and a.appr_1=$id) or
    			    (a.status_appr_2 = ".$request->get('status')."   and a.m_jenis_ijin_id =22 and a.appr_1=$id)  
    			)

			)";
		else{
		  //  $where .= "and ((a.appr_1=$id and a.status_appr_1=3) or (a.appr_2=$id and a.status_appr_2=3 and status_appr_1!=2) ) ";
		    $where .= "and ((a.appr_1=$id ) or (a.appr_2=$id and status_appr_1!=2) ) ";
		}

		$date = date('Y-m-d');
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,appr_2,d.nama as nama_appr,f.nama as nama_appr2,tgl_appr_1,status_appr_1,1,status_appr_2,b.pangkat,b.departemen,appr_1, case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,

		case when status_appr_2=1 then 'Disetujui' when status_appr_2=2 then 'Ditolak' end as sts_pengajuan2,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan f on f.p_karyawan_id=a.appr_2
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 
		    $where
			--and ((tgl_awal<='$date' and c.tipe=3) or c.tipe!=3)
			and a.active=1  ORDER BY (case when a.appr_1=$id then a.status_appr_1 else a.status_appr_2  end ) desc,a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		//echo $sqldata;
		$where = '';
		if ($request->get('tgl_awal2') and !$request->get('tgl_akhir2'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal2')."'";
		else if ($request->get('tgl_akhir2') and !$request->get('tgl_awal2'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir2')."'";
		else if ($request->get('tgl_akhir2') and $request->get('tgl_awal2'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal2')."' and tgl_awal <= '".$request->get('tgl_akhir2')."'";

		if ($request->get('ajuan2'))
			$where .= ' and c.tipe = '.$request->get('ajuan2');

		if ($request->get('status2')==-1)
			$where .= ' and a.status_appr_1 = 0';
		else if (($request->get('status2')))
			$where .= ' and a.status_appr_1 = '.$request->get('status2');
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen, case when status_appr_2=1 then 'Disetujui' when status_appr_2=2 then 'Ditolak' end as sts_pengajuan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_2
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.appr_2=$id $where and a.active=1  ORDER BY a.tgl_awal desc";
		$data2=DB::connection()->select($sqldata);
		$help = new Helper_function();
		return view('frontend.permit.list',compact('data','data2','request','help','id','tgl_awal_gaji','tgl_akhir_gaji'));
		//return view('undermaintenance',compact('data','data2','request','help','id'));
	}

	public function list_cuti(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		 where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";
		$sqlcuti="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where  and a.active=1 and c.tipe=3 ORDER BY a.tgl_awal desc";
		$cuti=DB::connection()->select($sqlcuti);
		$help =new Helper_function();
		$type = 'karyawan';
		return view('frontend.permit.list_cuti',compact('cuti','request','help','type','idkar'));
	}


	public function list_izin(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		 where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;


		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";
		$sqlizin="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc";
		$izin=DB::connection()->select($sqlizin);
		$help =new Helper_function();

		return view('frontend.permit.list_izin',compact('izin','request','help','idkar'));
	}

	public function list_lembur(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		 where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";

		$sqllembur="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_lembur,d.nama as nama_appr,tgl_appr_1,status_appr_1,
		f.nama as nama_appr2,tgl_appr_2,status_appr_2 ,e.nama as pjs

		FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan f on f.p_karyawan_id=a.appr_2
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 and c.tipe=2 ORDER BY a.tgl_awal desc";
		$lembur=DB::connection()->select($sqllembur);
		$help =new Helper_function();

		return view('frontend.permit.list_lembur',compact('lembur','request','help'));
	}
	public function list_perdin(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		 where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";

		$sqlizin="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 and c.tipe=4 ORDER BY a.tgl_awal desc";
		$izin=DB::connection()->select($sqlizin);
		$help =new Helper_function();

		return view('frontend.permit.list_perdin',compact('izin','request','help','idkar'));
	}
	public function setujui_lintas (Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("absen_log")
			->where("absen_log_id",$kode)
			->update([
				"appr_status"=>1,
				"appr"=>$iduser,
				"appr_date" => date("Y-m-d"),
				
			]);
			$notifdata=DB::connection()->select("select * from absen_log 
			left join p_karyawan_absen on p_karyawan_absen.no_absen = absen_log.pin
				
			where absen_log_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_permit",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Penyesuaian Absensi Lintas Mesin Absen pada tanggal".date('Y-m-d',strtotime($notifdata[0]->date_time))."  sudah di setujui",
                        
                        ]);
			DB::commit();

			return redirect()->route('fe.approval_lintas_mesin_absen')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function tolak_lintas (Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("absen_log")
			->where("absen_log_id",$kode)
			->update([
				"appr_status"=>2,
				"appr"=>$id,
				"appr_date" => date("Y-m-d"),
				
			]);
			$notifdata=DB::connection()->select("select * from absen_log 
			left join p_karyawan_absen on p_karyawan_absen.no_absen = absen_log.pin
				
			where absen_log_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"absen_log",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Penyesuaian Absensi Lintas Mesin Absen pada tanggal".date('Y-m-d',strtotime($notifdata[0]->date_time))."  ditolak",
                        
                        ]);
			DB::commit();

			return redirect()->route('fe.approval_lintas_mesin_absen')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function setujui_ajuan (Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"status_appr_1"=>1,
				"appr_1"=>$id,
				"tgl_appr_1" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			$notifdata=DB::connection()->select("select * from t_permit left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id where t_form_exit_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_permit",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan ".$notifdata[0]->nama." pada tanggal ".($notifdata[0]->tgl_awal!=$notifdata[0]->tgl_akhir?$notifdata[0]->tgl_awal.' s/d '.$notifdata[0]->tgl_akhir:$notifdata[0]->tgl_awal)." sudah di setujui ".$idkar[0]->nama." (layer 1)",
                        
                        ]);
			DB::commit();

			return redirect()->route('fe.listed')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function setujui_ajuan_2(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"status_appr_2"=>1,
				"appr_2"=>$id,
				"tgl_appr_2" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			$notifdata=DB::connection()->select("select * from t_permit left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id where t_form_exit_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_permit",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan ".$notifdata[0]->nama." pada tanggal ".($notifdata[0]->tgl_awal!=$notifdata[0]->tgl_akhir?$notifdata[0]->tgl_awal.' s/d '.$notifdata[0]->tgl_akhir:$notifdata[0]->tgl_awal)." sudah di setujui ".$idkar[0]->nama."(layer 2)",
                        
                        ]);
			DB::commit();

			return redirect()->route('fe.listed')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function tolak_ajuan(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"status_appr_1"=>2,
				"appr_1"=>$id,
				"tgl_appr_1" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			$notifdata=DB::connection()->select("select * from t_permit left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id where t_form_exit_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_permit",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan ".$notifdata[0]->nama." pada tanggal ".($notifdata[0]->tgl_awal!=$notifdata[0]->tgl_akhir?$notifdata[0]->tgl_awal.' s/d '.$notifdata[0]->tgl_akhir:$notifdata[0]->tgl_awal)." sudah di tolak ".$idkar[0]->nama."",
                        
                        ]);
			DB::commit();

			return redirect()->route('fe.listed')->with('success','Approval  Berhasil di rubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function tolak_ajuan_2(Request $request, $kode)
	{

		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"status_appr_2"=>2,
				"appr_2"=>$id,
				"tgl_appr_2" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);

			$notifdata=DB::connection()->select("select * from t_permit left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id where t_form_exit_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_permit",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan ".$notifdata[0]->nama." pada tanggal ".($notifdata[0]->tgl_awal!=$notifdata[0]->tgl_akhir?$notifdata[0]->tgl_awal.' s/d '.$notifdata[0]->tgl_akhir:$notifdata[0]->tgl_awal)." sudah di tolak ".$idkar[0]->nama."(layer 2)",
             ]);
			DB::commit();
			return redirect()->route('fe.listed')->with('success','Approval  Berhasil di rubah!');
			//return redirect()->route('be.list_ajuan',$request->get('type'))->with('success','Pengajuan Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	
	public function hitung_hari(Request $request)
	{

		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$cuti = $request->get('cuti');
		$tgl_awal = $request->get('tgl_awal');
		$tgl_akhir = $request->get('tgl_akhir');
		$date = $tgl_awal;
		$help = new Helper_function();
		$sql="select * from m_hari_libur where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir'";
		$harilibur = DB::connection()->select($sql);
		$hari_libur = array();
		foreach ($harilibur as $libur) {
			$hari_libur[] = $libur->tanggal;
		}
		$hari_libur_shift = array();
		$sql="select * from absen_libur_shift where tanggal >= '$tgl_awal' and tanggal <='$tgl_akhir'";
		$harilibur = DB::connection()->select($sql);
		foreach ($harilibur as $libur) {
			$hari_libur_shift[$libur->tanggal][$libur->p_karyawan_id] = 1;
		}
		$info_karyawan 		= DB::connection()->select("select * ,(select count(*) from m_karyawan_shift where p_karyawan.p_karyawan_id = m_karyawan_shift.p_karyawan_id and m_karyawan_shift.active=1) as is_karyawan_shift
								from p_karyawan
								left join p_karyawan_absen on p_karyawan.p_karyawan_id = p_karyawan_absen.p_karyawan_id
								left join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
								left join p_karyawan_kontrak on p_karyawan.p_karyawan_id = p_karyawan_kontrak.p_karyawan_id and p_karyawan_kontrak.active = 1
								left join m_jabatan on m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id
								where p_karyawan.p_karyawan_id = $id");




		$count = 0;
		for ($i=0;$i<=$help->hitungHari($tgl_awal,$tgl_akhir);$i++) {

			if (!$info_karyawan[0]->is_karyawan_shift)
				$bool_hari_libur = !(in_array(Helper_function::nama_hari($date),array('Minggu','Sabtu')) or in_array($date,$hari_libur) or isset($hari_libur_shift[$date][$id]) );
			else
				$bool_hari_libur = !(isset($hari_libur_shift[$date][$id])) ;
			if ($bool_hari_libur)
				$count +=1;
			$date = 	$help->tambah_tanggal($date,1);
		}
		echo json_encode(array('count'=>$count));
	}
	public function hitung_hari_tanpa_libur(Request $request)
	{

		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$tgl_awal = $request->get('tgl_awal');
		$tgl_akhir = $request->get('tgl_akhir');
		$date = $tgl_awal;
		$help = new Helper_function();

		$count = 0;
		for ($i=0;$i<=$help->hitungHari($tgl_awal,$tgl_akhir);$i++) {

			$count +=1;
			$date = 	$help->tambah_tanggal($date,1);
		}
		echo json_encode(array('count'=>$count));
	}
	public function tambah_cuti()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=1 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		//$atasan= substr($this->hirarki($idkar[0]->m_jabatan_id,''),0,-1);
		//$bawahan = substr($this->hirarkiBawahan($idkar[0]->m_jabatan_id,''),0,-1);
		$appr=DB::connection()->select("Select * from p_karyawan_pekerjaan where m_jabatan_id in ($atasan)");
		$help = new Helper_function();
		if($idkar[0]->m_pangkat_id==6 and !count($appr)){
		    $atasan =  -1;
    	}else{
    		$jabstruk = $help->jabatan_struktural($id_karyawan);
    		$atasan = $jabstruk['atasan'];
    		$bawahan = $jabstruk['bawahan'];
    		$sejajar = $jabstruk['sejajar'];
    		
    		$atasan_layer = $jabstruk['atasan_layer'];
    		
    		$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
    		//echo $atasan;die;
    
    		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan)  and m_pangkat_id not in (1,2,3)";
    		$appr=DB::connection()->select($sqlappr);
    	
		}
			$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id ";
    		$kar=DB::connection()->select($sqlkar);
		$where = '';

		$sqlkar="SELECT * from get_data_karyawan() where (m_jabatan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar))  and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan))  order by nama_lengkap";
		$karyawan=DB::connection()->select($sqlkar);



		$tahun=date('Y');
		
		$cuti = $help->query_cuti2($idkar);
		$date2 = $cuti['date'];
		$all = $cuti['all'];
		$tanggal_loop = $cuti['tanggal_loop'];
		$no=0;$nominal=0;
		$tahun = array();
		$tahunbesar = array();
		$datasisa = array();
		$ipg = array();
		$potong_gaji = array();
		$hutang = 0;
		$jumlah = 0;
		foreach ($tanggal_loop as $i=> $loop) {
			if ($all[$i]['tanggal']<=date('Y-m-d')) {
				$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date2,$i,$nominal,$jumlah,$ipg,$potong_gaji);
				$datasisa =$return['datasisa'];
				$hutang =$return['hutang'];
				$nominal =$return['nominal'];
				$jumlah =$return['jumlah'];
				$ipg =$return['ipg'];
				$potong_gaji =$return['potong_gaji'];
			}
		}


		if (isset($datasisa)) {
			asort($datasisa);
			$totalcuti = 0;
			foreach ($datasisa as $value=>$key) {
				$tahun = $value;
				if ($value>2000)
					$value = 'Sisa Cuti Tahun '.$value;
				else
					$value = 'Sisa Cuti Besar ke '.$value;
				//echo $value.' : 	'.$key.'<br>';
				if ($key  or $tahun>=date('Y')-1) {
					$totalcuti +=$key;
				}
			}
		}
		$totalcuti -=$hutang;
		$cuti = $totalcuti;
		if ($cuti>0) {
			$sqljeniscuti="SELECT * from m_jenis_ijin WHERE tipe=3 order by nama";
			$jeniscuti=DB::connection()->select($sqljeniscuti);
		} else {
			$sqljeniscuti="SELECT * from m_jenis_ijin WHERE tipe=3 and m_jenis_ijin_id IN(7,8,9,10) order by nama";
			$jeniscuti=DB::connection()->select($sqljeniscuti);
		}
		$help = new Helper_function();
		if ($idkar[0]->periode_gajian==1) {
			$sql="select * from m_gaji_bulanan order by tanggal desc limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_bulan($gapen[0]->tanggal,1);
			if ($next_gajian<date('Y-m-d')) {

				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_bulanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_bulan($next_gajian,1);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,3);
			$tgl_awal_cut_off  = $help->tambah_tanggal($tgl_akhir_gaji2,-3);
		} else if ($idkar[0]->periode_gajian==0) {


			$sql="select * from m_gaji_pekanan order by tanggal limit 1";
			$gapen=DB::connection()->select($sql);
			$help = new Helper_function();
			$tgl_awal_gaji = $gapen[0]->tanggal;
			$next_gajian = $help->tambah_tanggal($gapen[0]->tanggal,14);
			if ($next_gajian<date('Y-m-d')) {
				$tgl_awal_gaji = $next_gajian;
				DB::connection()->table("m_gaji_pekanan")
				->insert([
					"tanggal"=>$next_gajian,
				]);

				$next_gajian = $help->tambah_tanggal($next_gajian,14);
			}
			$tgl_akhir_gaji = $next_gajian;
			$tgl_akhir_gaji = date('Y-m-d');
			$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,1);
			$tgl_akhir_gaji2 = $help->tambah_tanggal($next_gajian,1);
			$tgl_awal_cut_off  = $help->tambah_tanggal($next_gajian,-1);
		}



		$tgl_akhir_cut_off  = $next_gajian;
		$tgl_cut_off ='';
		if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
			$tgl_cut_off = $tgl_awal_cut_off;
		else
			$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,-1);
			


        $sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."' ";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
					return view('frontend.permit.hub_hc');
			}else{
				
			
			
			
			
					$tgl_awal_gaji = $gapen[0]->tgl_awal;
					
					$tgl_akhir_gaji = $gapen[0]->tgl_akhir;;
					$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
					$tgl_akhir_gaji2 = $help->tambah_tanggal($tgl_akhir_gaji,3);
					$tgl_awal_cut_off  = $tgl_awal_gaji;
				



				$tgl_akhir_cut_off  = $tgl_akhir_gaji;
				$tgl_cut_off ='';
				if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
					$tgl_cut_off = $tgl_awal_cut_off;
				else
					$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);
			}    
		return view('frontend.permit.tambah_cuti',compact('kar','jeniscuti','appr','help','karyawan','totalcuti','tgl_cut_off','idkar','atasan'));
	}
	public function tambah_izin()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=1 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$help = new Helper_function();
		$appr=DB::connection()->select("Select * from p_karyawan_pekerjaan where m_jabatan_id in ($atasan)");
		$help = new Helper_function();
		if($idkar[0]->m_pangkat_id==6 and !count($appr)){
		    $atasan =  -1;
    	}else{
    	    $jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$atasan_layer = $jabstruk['atasan_layer'];
		
		 		$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan)   and m_pangkat_id not in (1,2,3)";
		$appr=DB::connection()->select($sqlappr);
		}
		//echo $id;die;
		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id";
		$kar=DB::connection()->select($sqlkar);

		$sqlkar="SELECT * from get_data_karyawan() where (m_jabatan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar)) and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan))  order by nama_lengkap";
		$karyawan=DB::connection()->select($sqlkar);
		$where = '';
		if ($idkar[0]->periode_gajian==0) {
		
			$where = ' and m_jenis_ijin_id in(21,26,20,3,1)';
		}

		$sqljenisizin="SELECT * from m_jenis_ijin WHERE tipe=1 and active=1  $where order by urutan asc";
		$jenisizin=DB::connection()->select($sqljenisizin);
		$sqljenisalasan="SELECT * from m_jenis_idt_ipm where jenis=1";
		$jenisalasan=DB::connection()->select($sqljenisalasan);
		$sqljenisalasan="SELECT * from m_jenis_idt_ipm where jenis=2";
		$jenisalasan_ipc=DB::connection()->select($sqljenisalasan);
		$tahun=date('Y');
		$cuti = $help->query_cuti2($idkar);
		$date2 = $cuti['date'];
		$all = $cuti['all'];
		$tanggal_loop = $cuti['tanggal_loop'];
		$no=0;$nominal=0;
		$tahun = array();
		$tahunbesar = array();
		$datasisa = array();
		$ipg = array();
		$potong_gaji = array();
		$hutang = 0;
		$jumlah = 0;
		foreach ($tanggal_loop as $i=> $loop) {
			if ($all[$i]['tanggal']<=date('Y-m-d')) {
				$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date2,$i,$nominal,$jumlah,$ipg,$potong_gaji);
				$datasisa =$return['datasisa'];
				$hutang =$return['hutang'];
				$nominal =$return['nominal'];
				$jumlah =$return['jumlah'];
				$ipg =$return['ipg'];
				$potong_gaji =$return['potong_gaji'];
			}
		}


		if (isset($datasisa)) {
			asort($datasisa);
			$totalcuti = 0;
			foreach ($datasisa as $value=>$key) {
				$tahun = $value;
				if ($value>2000)
					$value = 'Sisa Cuti Tahun '.$value;
				else
					$value = 'Sisa Cuti Besar ke '.$value;
				//echo $value.' : 	'.$key.'<br>';
				if ($key  or $tahun>=date('Y')-1) {
					$totalcuti +=$key;
				}
			}
		}
		$totalcuti -=$hutang;
		$tolcut = $totalcuti;
/*
$sqlcuti="SELECT * FROM get_list_cuti_tahunan_new($tahun,$id) ";
$cuti=DB::connection()->select($sqlcuti);
$sqldw="SELECT * FROM get_data_karyawan() WHERE p_karyawan_id=$id";
$dw=DB::connection()->select($sqldw);
// print_r($cuti);die;

if($dw[0]->m_status_pekerjaan_id==5)
$tolcut = 0;
else{
if($cuti[0]->total_cuti_tahunan<1)
$tolcut = $cuti[0]->total_cuti_tahunan;
else
$tolcut = $cuti[0]->sisa_cuti;
}*/
		
			$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."' ";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
					return view('frontend.permit.hub_hc');
			}else{
				
			
			
			
			
					$tgl_awal_gaji = $gapen[0]->tgl_awal;
					
					$tgl_akhir_gaji = $gapen[0]->tgl_akhir;;
					$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
					$tgl_akhir_gaji2 = $help->tambah_tanggal($tgl_akhir_gaji,3);
					$tgl_awal_cut_off  = $tgl_awal_gaji;
				



				$tgl_akhir_cut_off  = $tgl_akhir_gaji;
				$tgl_cut_off ='';
				if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
					$tgl_cut_off = $tgl_awal_cut_off;
				else
					$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);
           
				return view('frontend.permit.tambah_izin',compact('kar','jenisizin','jenisalasan_ipc','appr','atasan','jenisalasan','tolcut','karyawan','totalcuti','tgl_cut_off','idkar'));
			}
			
	}
	public function tambah_perdin()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=1 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		
		
		
		$help = new Helper_function();
		$appr=DB::connection()->select("Select * from p_karyawan_pekerjaan where m_jabatan_id in ($atasan)");
		$help = new Helper_function();
		if($idkar[0]->m_pangkat_id==6 and !count($appr)){
		    $atasan =  -1;
    	}else{
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$atasan_layer = $jabstruk['atasan_layer'];
		
		 		$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_pangkat_id not in(1) and m_jabatan_id in($atasan)  and m_pangkat_id not in (1,2,3) ";
		$appr=DB::connection()->select($sqlappr);
    	}

		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id";
		$kar=DB::connection()->select($sqlkar);

		$sqlkar="SELECT * from get_data_karyawan()  where (m_jabatan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar)) and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan)) order by nama_lengkap";
		$karyawan=DB::connection()->select($sqlkar);


		$sqljenisizin="SELECT * from m_jenis_ijin WHERE tipe=1 and active=1 order by urutan asc";
		$jenisizin=DB::connection()->select($sqljenisizin);
		
		$sqljenisizin="SELECT * from m_alat_transportasi WHERE  active=1 order by m_alat_transportasi asc";
		$alat_transportasi=DB::connection()->select($sqljenisizin);
		
		$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."'  and tgl_akhir >= '".date('Y-m-d')."' ";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
					return view('frontend.permit.hub_hc');
			}else{
				
			
			
			
			
					$tgl_awal_gaji = $gapen[0]->tgl_awal;
					
					$tgl_akhir_gaji = $gapen[0]->tgl_akhir;;
					$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
					$tgl_akhir_gaji2 = $help->tambah_tanggal($tgl_akhir_gaji,3);
					$tgl_awal_cut_off  = $tgl_awal_gaji;
				



				$tgl_akhir_cut_off  = $tgl_akhir_gaji;
				$tgl_cut_off ='';
				if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
					$tgl_cut_off = $tgl_awal_cut_off;
				else
					$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);

				return view('frontend.permit.tambah_perdin',compact('kar','jenisizin','atasan','alat_transportasi','appr','karyawan','tgl_cut_off','idkar'));
		}
	}

	public function tambah_lembur()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		left join m_jabatan on m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural left join m_jabatan on m_jabatan_terkait=m_jabatan.m_jabatan_id where m_pangkat_id not in(5,6) and tipe_struktural=1 and m_jabatan_struktural.m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan2 = "(((select m_jabatan_terkait
		from m_jabatan_struktural
		left join m_jabatan on m_jabatan_terkait=m_jabatan.m_jabatan_id
		where m_pangkat_id in(5,6)
		and tipe_struktural=1
		and m_jabatan_struktural.m_jabatan_id = (select pkp.m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$appr=DB::connection()->select("Select * from p_karyawan_pekerjaan where m_jabatan_id in ($atasan)");
		$appr1=DB::connection()->select("Select * from p_karyawan_pekerjaan where m_jabatan_id in ($atasan)");
		$appr2=DB::connection()->select("Select * from p_karyawan_pekerjaan where m_jabatan_id in ($atasan2)");
		$help = new Helper_function();
		if($idkar[0]->m_pangkat_id==6 and !count($appr)){
		    $atasan =  -1;
    	}else{
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$atasan_layer = $jabstruk['atasan_layer'];
		
		if(isset($atasan_layer[2])){
		 	$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		    $atasan2 = $atasan_layer[2];
		}else{
		$atasan = '-1';
		
		$atasan2 = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		}
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan)   and m_pangkat_id not in (1,2,3)";
		$appr1=DB::connection()->select($sqlappr);
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan2) and m_pangkat_id in(5,6) ";
		$appr2=DB::connection()->select($sqlappr);
    	}
		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id";
		$kar=DB::connection()->select($sqlkar);

		$sqlkar="SELECT * from get_data_karyawan() where (m_jabatan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar)) and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan))  order by nama_lengkap";
		$karyawan=DB::connection()->select($sqlkar);


		$sqljenisizin="SELECT * from m_jenis_ijin WHERE tipe=2  and active=1 order by urutan asc";
		$jenisizin=DB::connection()->select($sqljenisizin);
		$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."' ";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
					return view('frontend.permit.hub_hc');
			}else{
				
			
			
			
			
					$tgl_awal_gaji = $gapen[0]->tgl_awal;
					
					$tgl_akhir_gaji = $gapen[0]->tgl_akhir;;
					$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
					$tgl_akhir_gaji2 = $help->tambah_tanggal($tgl_akhir_gaji,3);
					$tgl_awal_cut_off  = $tgl_awal_gaji;
				



				$tgl_akhir_cut_off  = $tgl_akhir_gaji;
				$tgl_cut_off ='';
				if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
					$tgl_cut_off = $tgl_awal_cut_off;
				else
					$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);

		$help = new Helper_function();		
		return view('frontend.permit.tambah_lembur',compact('kar','jenisizin','atasan','help','appr1','appr2','karyawan','tgl_cut_off','idkar'));
		}
	}

	public function simpan_cuti(Request $request)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan
			join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
			join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
			 where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			
			

			$data = $request->all();
			if (empty($request->input('cuti'))) {
				$data['cuti'] = $request->input('cuti');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}
			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;
			if($idkar[0]->m_pangkat_id==6 and !$request->get('atasan'))
				$tipe = -1;
			else
				$tipe = $request->get('atasan');
			DB::connection()->table("t_permit")
			->insert([
				"m_jenis_ijin_id"=>$request->get('cuti'),
				"p_karyawan_id"=>$id,
				"tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl_awal'))),
				"tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl_akhir'))),
				"appr_1"=>$tipe,
				"lama"=>$request->get('lama'),
				"pjs"=>$request->get('pjs'),
				"keterangan"=>$request->get('alasan'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"kode"=>$nocuti,
				"create_by"=>$id,
				"active"=>1,
			]);

			if ($request->file('file')) {
				
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path='permit-'.date('YmdHis').$file->getClientOriginalName();
				$file->move($destination,$path);
				
				DB::connection()->table("t_permit")->where("kode",$nocuti)
				->update([
					"foto"=>$path
				]);
			}

			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=3 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Cuti');
			//	});-->
			
			return redirect()->route('fe.list_cuti')->with('success','Pengajuan Cuti Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function simpan_izin(Request $request)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
			join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
			join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
			where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			
			

			$data = $request->all();
			if (empty($request->input('cuti'))) {
				$data['cuti'] = $request->input('cuti');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}

			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;
			if($idkar[0]->m_pangkat_id==6 and  !$request->get('atasan'))
				$tipe = -1;
			else
				$tipe = $request->get('atasan');
        $tanggal_awal = date('Y-m-d',strtotime($request->get('tgl_awal')));
        $tanggal_akhir = date('Y-m-d',strtotime($request->get('tgl_akhir')));
        if(!$tanggal_akhir or $tanggal_akhir=='1970-01-01'){
            $tanggal_akhir = $tanggal_awal;
        }
			DB::connection()->table("t_permit")
			->insert([
				"m_jenis_ijin_id"=>$request->get('cuti'),
				"p_karyawan_id"=>$id,
				"kode"=>$nocuti,
				"tgl_awal"=>$tanggal_awal,
				"tgl_akhir"=>$tanggal_akhir,
				"keterangan"=>$request->get('alasan'),
				"appr_1"=>$tipe,
				"simpan_keterangan"=>$request->simpan_keterangan,
				"simpan_type"=>$request->simpan_type,
				"jam_keluar_finger"=>$request->jam_keluar_finger,
				"jam_masuk_finger"=>$request->jam_masuk_finger,
				"m_jenis_alasan_id"=>$request->alasan_id,
				"jam_keluar_kerja"=>$request->jam_keluar_kerja,
				"jam_masuk_kerja"=>$request->jam_masuk_kerja,
				"lama"=>$request->get('lama'),
				"pjs"=>$request->get('pjs'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"create_by"=>$id,
				"active"=>1,
			]);

			if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path = 'permit-'.date('YmdHis').$file->getClientOriginalName();$file->move($destination,$path);
				//$path=$file->getClientOriginalName();
				//echo $path;die;
				DB::connection()->table("t_permit")->where("kode",$nocuti)
				->update([
					"foto"=>$path
				]);
			}

			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Izin');
			//		if($data[0]->foto!='' || $data[0]->foto!=null){
			//			$mail->attach('dist/img/file/'.$data[0]->foto);
			//		}

			//	});

			return redirect()->route('fe.list_izin')->with('success','Pengajuan Izin Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function hitung_jam_lembur(Request $request)
	{
	    echo PermitController::lama_jam_lembur($request);
	}

	public function lama_jam_lembur(Request $request)
	{
	    $istirahat_masuk = strtotime($request->get('tgl')." ".$request->get('jam_istirahat_awal'));
          	$istirahat_keluar = strtotime($request->get('tgl')." ".$request->get('jam_istirahat_akhir'));
          	
			$total_jam_istirahat =  $istirahat_keluar-$istirahat_masuk;
	        $lama_istirahat =  gmdate('G', $total_jam_istirahat	); ;
	        //$lama_istirahat =  gmdate('G', $total_jam_istirahat	); ;
	        
	        $minutes_istirahat =  gmdate('i', $total_jam_istirahat	); ;
	        $minutes_100_istirahat = $minutes_istirahat/60*100;
			$minutes_100_istirahat = $minutes_100_istirahat/100; 
	        
          	
          	$keluar = strtotime($request->get('jam_awal'));
		    $masuk = strtotime($request->get('jam_akhir'));
			$total_jam =  $masuk-$keluar;
	        $lama_php =  gmdate('G', $total_jam	); ;
	       
	        $minutes =  gmdate('i', $total_jam	); ;
	        $minutes_100 = $minutes/60*100;
			$minutes_100 = $minutes_100/100; 
	       // die;
	        
			$lama_input = $request->get('lama');
			if($lama_input!=$lama_php)
				$lama_input = $lama_php;
		
			if($request->get('jam_istirahat_awal')<$request->get('jam_awal') and $request->get('jam_istirahat_akhir') <$request->get('jam_awal')){
			    
 			}else
 			if($request->get('jam_istirahat_awal')<$request->get('jam_awal') and $request->get('jam_istirahat_akhir') >$request->get('jam_awal')){
 			    $jam_istirahat_awal = $request->get('jam_awal');
 			    $istirahat_masuk = strtotime($jam_istirahat_awal);
               	$istirahat_keluar = strtotime($request->get('jam_istirahat_akhir'));
              	
     			$total_jam_istirahat =  $istirahat_keluar-$istirahat_masuk;
     	        $lama_istirahat =  gmdate('G', $total_jam_istirahat	); ;
                $minutes_istirahat =  gmdate('i', $total_jam_istirahat	); ;
    	        $minutes_100_istirahat = $minutes_istirahat/60*100;
    			$minutes_100_istirahat = $minutes_100_istirahat/100; 
    	        $lama_input += $minutes_100;
    	        $lama_input -=($lama_istirahat+$minutes_100_istirahat);
    	        $lama_input = floor($lama_input);
 			}else
 			if($request->get('jam_istirahat_awal')>$request->get('jam_awal') and $request->get('jam_istirahat_akhir') >$request->get('jam_akhir')){
               $jam_istirahat_akhir = $request->get('jam_akhir');
 			    $istirahat_masuk = strtotime($request->get('jam_istirahat_awal'));
               	 $istirahat_keluar = strtotime($jam_istirahat_akhir);
              	
     			$total_jam_istirahat =  $istirahat_keluar-$istirahat_masuk;
    	        $lama_istirahat =  gmdate('G', $total_jam_istirahat	); ;
    	        $minutes_istirahat =  gmdate('i', $total_jam_istirahat	); ;
    	        $minutes_100_istirahat = $minutes_istirahat/60*100;
    			$minutes_100_istirahat = $minutes_100_istirahat/100; 
     	        $lama_input -=$lama_istirahat;
     	        $lama_input += $minutes_100;
    	        $lama_input -=($lama_istirahat+$minutes_100_istirahat);
    	        $lama_input = floor($lama_input);
		    
			}else
			if($request->get('jam_istirahat_awal')>$request->get('jam_awal') and $request->get('jam_istirahat_akhir') <$request->get('jam_akhir')){
    	        $lama_input += $minutes_100;
    	        $lama_input -=($lama_istirahat+$minutes_100_istirahat);
    	        $lama_input = floor($lama_input);
			    
			}
			//$return['lama_input'] = $lama_input;
			return $lama_input;
	}

	public function simpan_lembur(Request $request)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
			join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
			join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
			where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			
			$this->validate($request, [
				'jam_awal' => 'required',
				'jam_akhir' => 'required',
				'tgl' => 'required',

				'lama' => 'required',

			]);

			$data = $request->all();
			if (empty($request->input('jam_awal'))) {
				$data['jam_awal'] = $request->input('jam_awal');
			}
			if (empty($request->input('jam_akhir'))) {
				$data['jam_akhir'] = $request->input('jam_akhir');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}

			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;
            $help = new Helper_function();
          	$istirahat_masuk = strtotime($request->get('tgl')." ".$request->get('jam_istirahat_awal'));
          	$istirahat_keluar = strtotime($request->get('tgl')." ".$request->get('jam_istirahat_akhir'));
          	
			$total_jam_istirahat =  $istirahat_keluar-$istirahat_masuk;
	        $lama_istirahat =  gmdate('G', $total_jam_istirahat	); ;
	        //$lama_istirahat =  gmdate('G', $total_jam_istirahat	); ;
	        
	        $minutes_istirahat =  gmdate('i', $total_jam_istirahat	); ;
	        $minutes_100_istirahat = $minutes_istirahat/60*100;
			$minutes_100_istirahat = $minutes_100_istirahat/100; 
	        
          	
          	$keluar = strtotime($request->get('jam_awal'));
		    $masuk = strtotime($request->get('jam_akhir'));
			$total_jam =  $masuk-$keluar;
	        $lama_php =  gmdate('G', $total_jam	); ;
	       
	        $minutes =  gmdate('i', $total_jam	); ;
	        $minutes_100 = $minutes/60*100;
			$minutes_100 = $minutes_100/100; 
	       // die;
	        
			$lama_input = PermitController::lama_jam_lembur($request);
		
		//	echo $lama_istirahat; die;
			if($idkar[0]->m_pangkat_id==6 and !$request->get('atasan'))
				$tipe = -1;
			else
				$tipe = $request->get('atasan');

			DB::connection()->table("t_permit")
			->insert([
				"m_jenis_ijin_id"=>22,
				"p_karyawan_id"=>$id,
				"kode"=>$nocuti,
				"tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl'))),
				"tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl'))),
				"keterangan"=>$request->get('alasan'),
				"appr_1"=>$request->get('atasan'),
				"appr_2"=>$request->get('atasan2'),
				"lama"=>$lama_input,
				"pjs"=>$request->get('pjs'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"create_by"=>$id,
				"active"=>1,
				"tipe_lembur"=>$request->get('tipe_lembur'),
				"jam_awal"=>$request->get('jam_awal'),
				"jam_akhir"=>$request->get('jam_akhir'),
				"jam_istirahat_awal"=>$request->get('jam_istirahat_awal'),
				"jam_istirahat_akhir"=>$request->get('jam_istirahat_akhir'),
			]);
			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=2	 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr_lembur', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Lembur');
			//		//$mail->attach('dist/img/file/'.$data[0]->foto);

			//	});

		    return redirect()->route('fe.list_lembur')->with('success','Pengajuan Lembur Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function lihat_cuti($id)
	{
		$sqlcuti="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_hr=1 then 'Disetujui' when status_appr_hr=2 then 'Ditolak' when status_appr_hr=3 then 'Pending' end as approve_hr,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan,
		f.alasan as alasan_idt_ipm
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=3 ORDER BY a.tgl_awal desc";
		$cuti=DB::connection()->select($sqlcuti);

		return view('frontend.permit.lihat_cuti',compact('cuti'));
	}

	public function lihat_lembur($id)
	{
		$sqlizin="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_hr=1 then 'Disetujui' when status_appr_hr=2 then 'Ditolak' when status_appr_hr=3 then 'Pending' end as approve_hr,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan
		
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=2 ORDER BY a.tgl_awal desc";
		$Lembur=DB::connection()->select($sqlizin);

		return view('frontend.permit.lihat_lembur',compact('Lembur'));
	}
	public function lihat_izin($id)
	{
		$sqlizin="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan,
		case when status_appr_hr=1 then 'Disetujui' when status_appr_hr=2 then 'Ditolak' when status_appr_hr=3 then 'Pending' end as approve_hr,
		f.alasan as alasan_idt_ipm
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join m_jenis_alasan f on f.m_jenis_alasan_id=a.m_jenis_alasan_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc";
		$izin=DB::connection()->select($sqlizin);

		return view('frontend.permit.lihat_izin',compact('izin'));
	}
    
	public function lihat_izin_idt_ipm($id)
	{
	    return PermitController::lihat_all($id,'izin_idt_ipm');
	}
    public function list_izin_idt_ipm(Request $request)
	{
	    return PermitController::list_all($request,'izin_idt_ipm');
	}
	public function tambah_izin_idt_ipm()
	{
	    return PermitController::tambah_all('izin_idt_ipm');
	}
	public function simpan_izin_idt_ipm(Request $request)
	{
	    return PermitController::simpan_all($request,'izin_idt_ipm');
	}
	public function simpan_all($request,$type)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
			join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
			join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
			where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			
			

			$data = $request->all();
			if (empty($request->input('cuti'))) {
				$data['cuti'] = $request->input('cuti');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}

			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;
			if($idkar[0]->m_pangkat_id==6 and  !$request->get('atasan'))
				$appr = -1;
			else
				$appr = $request->get('atasan');
                $tanggal_awal = date('Y-m-d',strtotime($request->get('tgl_awal')));
                $tanggal_akhir = date('Y-m-d',strtotime($request->get('tgl_akhir')));
            if(!$tanggal_akhir or $tanggal_akhir=='1970-01-01'){
                 $tanggal_akhir = $tanggal_awal;
            }
			DB::connection()->table("t_permit")
			->insert([
				"m_jenis_ijin_id"=>$request->get('cuti'),
				"p_karyawan_id"=>$id,
				"kode"=>$nocuti,
				"tgl_awal"=>$tanggal_awal,
				"tgl_akhir"=>$tanggal_akhir,
				"keterangan"=>$request->get('alasan'),
				"appr_1"=>$appr,
				"simpan_keterangan"=>$request->simpan_keterangan,
				"simpan_type"=>$request->simpan_type,
				"jam_keluar_finger"=>$request->jam_keluar_finger,
				"jam_masuk_finger"=>$request->jam_masuk_finger,
				"m_jenis_alasan_id"=>$request->alasan_id,
				"jam_keluar_kerja"=>$request->jam_keluar_kerja,
				"jam_masuk_kerja"=>$request->jam_masuk_kerja,
				"lama"=>$request->get('lama'),
				"pjs"=>$request->get('pjs'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"create_by"=>$id,
				"active"=>1,
			]);

			if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path = 'permit-'.date('YmdHis').$file->getClientOriginalName();$file->move($destination,$path);
				//$path=$file->getClientOriginalName();
				//echo $path;die;
				DB::connection()->table("t_permit")->where("kode",$nocuti)
				->update([
					"foto"=>$path
				]);
			}

			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=1 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Izin');
			//		if($data[0]->foto!='' || $data[0]->foto!=null){
			//			$mail->attach('dist/img/file/'.$data[0]->foto);
			//		}

			//	});

			return redirect()->route('fe.list_'.$type)->with('success','Pengajuan Izin Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function tambah_all($type)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		
		if($type=='izin_idt_ipm')
            $tipe = 5;
        else if($type=='izin')
            $tipe = 1;
        else  if($type=='lembur')
            $tipe = 2;
        else  if($type=='cuti')
            $tipe = 3;
        else  if($type=='perdin')
            $tipe = 4;
		
		$id_karyawan = $idkar[0]->p_karyawan_id;
// 	
		$help = new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$atasan_layer = $jabstruk['atasan_layer'];
		
		$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		if($idkar[0]->m_pangkat_id==6 and $atasan!=-1){
		    $atasan =  -1;
		    $appr=array();
    	}else{
    	
		
		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan)   and m_pangkat_id not in (1,2,3)";
		$appr=DB::connection()->select($sqlappr);
		}
		//echo $id;die;
		$sqlkar="SELECT * from get_data_karyawan() WHERE p_karyawan_id=$id";
		$kar=DB::connection()->select($sqlkar);

		$sqlkar="SELECT * from get_data_karyawan() where (m_jabatan_id in($bawahan) or m_jabatan_id in($atasan) or m_jabatan_id in($sejajar)) and (m_departemen_id = (select m_departemen_id from p_karyawan_pekerjaan where p_karyawan_id=$id_karyawan)  or m_jabatan_id in($atasan))  order by nama_lengkap";
		$karyawan=DB::connection()->select($sqlkar);
		$where = '';
		if ($idkar[0]->periode_gajian==0) {
		
			$where = ' and m_jenis_ijin_id in(21,26,20,3,1)';
		}

		$sqljenisizin="SELECT * from m_jenis_ijin WHERE tipe=$tipe and active=1  $where order by urutan asc";
		$jenisizin=DB::connection()->select($sqljenisizin);
		$tahun=date('Y');
		$tolcut =0;
		$totalcuti =0;
		if(in_array($type,array('izin','cuti'))){
		$cuti = $help->query_cuti2($idkar);
		$date2 = $cuti['date'];
		$all = $cuti['all'];
		$tanggal_loop = $cuti['tanggal_loop'];
		$no=0;$nominal=0;
		$tahun = array();
		$tahunbesar = array();
		$datasisa = array();
		$ipg = array();
		$potong_gaji = array();
		$hutang = 0;
		$jumlah = 0;
		foreach ($tanggal_loop as $i=> $loop) {
			if ($all[$i]['tanggal']<=date('Y-m-d')) {
				$return = $help->perhitungan_cuti2($all,$datasisa,$hutang,$date2,$i,$nominal,$jumlah,$ipg,$potong_gaji);
				$datasisa =$return['datasisa'];
				$hutang =$return['hutang'];
				$nominal =$return['nominal'];
				$jumlah =$return['jumlah'];
				$ipg =$return['ipg'];
				$potong_gaji =$return['potong_gaji'];
			}
		}


		if (isset($datasisa)) {
			asort($datasisa);
			$totalcuti = 0;
			foreach ($datasisa as $value=>$key) {
				$tahun = $value;
				if ($value>2000)
					$value = 'Sisa Cuti Tahun '.$value;
				else
					$value = 'Sisa Cuti Besar ke '.$value;
				//echo $value.' : 	'.$key.'<br>';
				if ($key  or $tahun>=date('Y')-1) {
					$totalcuti +=$key;
				}
			}
		}
		$totalcuti -=$hutang;
		$tolcut = $totalcuti;
		}
			$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and periode_aktif=1  and tgl_akhir >= '".date('Y-m-d')."' ";
			$gapen=DB::connection()->select($sql);
			if(!count($gapen)){
					return view('frontend.permit.hub_hc');
			}else{
				
			
			
			
			
					$tgl_awal_gaji = $gapen[0]->tgl_awal;
					
					$tgl_akhir_gaji = $gapen[0]->tgl_akhir;;
					$tgl_awal_gaji2 = $help->tambah_tanggal($tgl_awal_gaji,3);
					$tgl_akhir_gaji2 = $help->tambah_tanggal($tgl_akhir_gaji,3);
					$tgl_awal_cut_off  = $tgl_awal_gaji;
				



				$tgl_akhir_cut_off  = $tgl_akhir_gaji;
				$tgl_cut_off ='';
				if ($tgl_awal_cut_off<=date('Y-m-d') and $tgl_akhir_cut_off>=date('Y-m-d'))
					$tgl_cut_off = $tgl_awal_cut_off;
				else
					$tgl_cut_off = $help->tambah_tanggal($tgl_awal_gaji,1);
           
				return view('frontend.permit.tambah_'.$type,compact('kar','jenisizin','appr','atasan','tolcut','karyawan','totalcuti','tgl_cut_off','idkar','type'));
			}
			
	}
    public function list_all($request,$type)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		 where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

        if($type=='izin_idt_ipm')
            $tipe = 5;
        else if($type=='izin')
            $tipe = 1;
        else  if($type=='lembur')
            $tipe = 2;
        else  if($type=='cuti')
            $tipe = 3;
        else  if($type=='perdin')
            $tipe = 4;
		$where = '';
		if ($request->get('tgl_awal') and !$request->get('tgl_akhir'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."'";
		else if ($request->get('tgl_akhir') and !$request->get('tgl_awal'))
			$where = "and tgl_awal <= '".$request->get('tgl_akhir')."'";
		else if ($request->get('tgl_akhir') and $request->get('tgl_awal'))
			$where = "and tgl_awal >= '".$request->get('tgl_awal')."' and tgl_awal <= '".$request->get('tgl_akhir')."'";
		$sqlizin="SELECT a.*,b.nik,b.nama,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs FROM t_permit a
		left join p_karyawan b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.p_karyawan_id=$id $where and a.active=1 and c.tipe=$tipe ORDER BY a.tgl_awal desc";
		$$type=DB::connection()->select($sqlizin);
		$help =new Helper_function();

		return view('frontend.permit.list_'.$type,compact($type,'request','help','idkar','type'));
	}
	public static function lihat_all($id,$type)
	{
	     if($type=='izin_idt_ipm')
            $tipe = 5;
        else if($type=='izin')
            $tipe = 1;
        else  if($type=='lembur')
            $tipe = 2;
        else  if($type=='cuti')
            $tipe = 3;
        else  if($type=='perdin')
            $tipe = 4;
		$sqlizin="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan,
		case when status_appr_hr=1 then 'Disetujui' when status_appr_hr=2 then 'Ditolak' when status_appr_hr=3 then 'Pending' end as approve_hr,
		f.alasan as alasan_idt_ipm
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join m_jenis_alasan f on f.m_jenis_alasan_id=a.m_jenis_alasan_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=$tipe ORDER BY a.tgl_awal desc";
		$$type=DB::connection()->select($sqlizin);

		return view('frontend.permit.lihat_'.$type,compact($type));
	}

	public function hapus_cuti($id)
	{
		DB::beginTransaction();
		try {
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$id)
			->update(["active"=>0]);
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Cuti Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function hapus_izin($id)
	{
		DB::beginTransaction();
		try {
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$id)
			->update(["active"=>0]);
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Izin Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function hapus_lembur($id)
	{
		DB::beginTransaction();
		try {
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$id)
			->update(["active"=>0]);
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Lembur Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}


	public function lihat_ajuan($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,f.alasan as alasan_idt_ipm,c.kode,c.tipe,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		left join m_jenis_alasan f on f.m_jenis_alasan_id=a.m_jenis_alasan_id
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		$help = new Helper_function();
		return view('frontend.permit.lihat',compact('data'));
	}
	public function lihat_ajuan_2($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,f.alasan as alasan_idt_ipm,c.kode,c.tipe,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		left join m_jenis_alasan f on f.m_jenis_alasan_id=a.m_jenis_alasan_id
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		$help = new Helper_function();
		return view('frontend.permit.lihat_2',compact('data'));
	}
	public function lihat($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.tipe,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		$help = new Helper_function();
		return view('frontend.permit.lihat_permit',compact('data','help'));
	}

	public function edit_ajuan($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,c.tipe,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);
		$help = new Helper_function();
		if ($data[0]->m_jenis_ijin_id ==21) {
			$rekap = $help->rekap_absen($data[0]->tgl_awal,$data[0]->tgl_akhir,$data[0]->tgl_awal,$data[0]->tgl_akhir,-1);
		} else {
			$rekap= array();
		}
		if(Auth::user()->role==-1){
		//print_r($rekap);
		}
		//$rekap = $help->rekap_absen($data[0]->tgl_awal,$data[0]->tgl_akhir,$data[0]->tgl_awal,$data[0]->tgl_akhir,-1);
		return view('frontend.permit.edit',compact('data','rekap','help'));
	}
	public function edit_ajuan_2($kode)
	{
		$sqldata="SELECT a.*,b.nik,b.nama_lengkap,c.kode,c.nama as nama_ijin,c.tipe,d.nama as nama_appr,tgl_appr_1,status_appr_1,b.pangkat,b.departemen,case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan,b.jabatan,e.nama as pjs
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1  ORDER BY a.tgl_awal desc";
		$data=DB::connection()->select($sqldata);

		return view('frontend.permit.edit_2',compact('data'));
	}

	public function approve_ajuan(Request $request, $kode)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"keterangan_atasan"=>$request->get('keterangan_atasan'),
				"status_appr_1"=>$request->get('sts_pengajuan'),
				"jam_awal"=>$request->get('jam_awal'),
				"jam_akhir"=>$request->get('jam_akhir'),
				"tgl_appr_1" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			
			$notifdata=DB::connection()->select("select * from t_permit left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id where t_form_exit_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_permit",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan ".$notifdata[0]->nama." pada tanggal ".($notifdata[0]->tgl_awal!=$notifdata[0]->tgl_akhir?$notifdata[0]->tgl_awal.' s/d '.$notifdata[0]->tgl_akhir:$notifdata[0]->tgl_awal)." sudah di ".($request->get('sts_pengajuan')==1?'Disetujui':'Ditolak')."  ".$idkar[0]->nama."(layer 2)",
             ]);
             
			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			$appr=Setting::where(['nama'=>'email_hrd'])->first();
			$emailhrd=$appr->val1;

			//Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
			//		$mail->cc($emailhrd);
			//	});
			
			return redirect()->route('fe.listed')->with('success','Pengajuan Berhasil di !'.$data[0]->sts_pengajuan);
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function approve_ajuan_2(Request $request, $kode)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$kode)
			->update([
				"keterangan_atasan_2"=>$request->get('keterangan_atasan2'),
				"status_appr_2"=>$request->get('sts_pengajuan2'),
				"jam_awal"=>$request->get('jam_awal'),
				"jam_akhir"=>$request->get('jam_akhir'),
				"tgl_appr_2" => date("Y-m-d"),
				"update_by" => $id,
				"update_date" => date("Y-m-d H:i:s"),
			]);
			$notifdata=DB::connection()->select("select * from t_permit left join m_jenis_ijin on m_jenis_ijin.m_jenis_ijin_id = t_permit.m_jenis_ijin_id where t_form_exit_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"t_permit",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Pengajuan ".$notifdata[0]->nama." pada tanggal ".($notifdata[0]->tgl_awal!=$notifdata[0]->tgl_akhir?$notifdata[0]->tgl_awal.' s/d '.$notifdata[0]->tgl_akhir:$notifdata[0]->tgl_awal)." sudah di ".($request->get('sts_pengajuan')==1?'Disetujui':'Ditolak')."  ".$idkar[0]->nama."(layer 2)",
             ]);
			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1 , case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' end as sts_pengajuan ,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			WHERE 1=1 and a.t_form_exit_id=$kode and a.active=1 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			$appr=Setting::where(['nama'=>'email_hrd'])->first();
			$emailhrd=$appr->val1;

			//Mail::send('email_bls_cuti', ['data' => $data,'emailhrd'=>$emailhrd], function ($mail) use ($data,$emailhrd){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_perusahaan)->subject('Status Pengajuan');
			//		$mail->cc($emailhrd);
			//	});
			return redirect()->route('fe.listed')->with('success','Pengajuan Berhasil di !'.$data[0]->sts_pengajuan);
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}

	public function simpan_perdin(Request $request)
	{
		DB::beginTransaction();
		try {
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;


			$data = $request->all();
			if (empty($request->input('cuti'))) {
				$data['cuti'] = $request->input('cuti');
			}
			if (empty($request->input('tgl_awal'))) {
				$data['tgl_awal'] = $request->input('tgl_awal');
			}
			if (empty($request->input('tgl_akhir'))) {
				$data['tgl_akhir'] = $request->input('tgl_akhir');
			}
			if (empty($request->input('atasan'))) {
				$data['atasan'] = $request->input('atasan');
			}
			if (empty($request->input('lama'))) {
				$data['lama'] = $request->input('lama');
			}

			date_default_timezone_set('Asia/Jakarta');
			$time=date('Y-m-d H:i:s');

			$Bulan=date('m');
			$Tahun2=date('y');

			$sqlnocuti="SELECT COALESCE(MAX(substr(kode, 14 , 5)::NUMERIC),10000)+1 AS counter
			FROM t_permit
			WHERE substr(kode, 8 , 2) ILIKE '$Bulan'
			AND substr(kode, 11 , 2) ILIKE '$Tahun2' ";
			//echo $sqlnopengajuan;die;
			$datanocuti=DB::connection()->select($sqlnocuti);
			$Counter=$datanocuti[0]->counter;
			$nocuti='PERMIT.'.$Bulan.'.'.$Tahun2.'.'.$Counter;
            $sqldata="SELECT a.*,b.nik,b.nama_lengkap,f.uang_makan,f.uang_saku,f.uang_saku2
				FROM p_karyawan_pekerjaan a
				left join get_data_karyawan() b on a.p_karyawan_id = a.p_karyawan_id 
			
				left join m_jabatan d on d.m_jabatan_id=a.m_jabatan_id
				left join m_pangkat f on d.m_pangkat_id=f.m_pangkat_id
				WHERE 1=1 and a.p_karyawan_id=$id and a.active=1  ";
        	$uangmakan=$data[0]->uang_makan;
			$uangsaku1=$data[0]->uang_saku;
			$uangsaku2=$data[0]->uang_saku2;
	        if($request->get('lama')>1)
	                $uangsaku = $uangsaku2;
            else 
            	$uangsaku = $uangsaku1;
				$uangmakan=$data[0]->uang_makan;
				$uangmakan2=$data[0]->uang_makan2;
				if($request->get('lama')>1)
						$uangmakan = $uangmakan2; 

			$data = [
				"m_jenis_ijin_id"=>$request->get('cuti'),
				"p_karyawan_id"=>$id,
				"kode"=>$nocuti,
				"jam_awal"=>date('H:i:s'),
				"tgl_awal"=>date('Y-m-d',strtotime($request->get('tgl_awal'))),
				"tgl_akhir"=>date('Y-m-d',strtotime($request->get('tgl_akhir'))),
				"keterangan"=>$request->get('alasan'),
				"appr_1"=>$request->get('atasan'),
				"km_awal"=>$request->get('km_awal'),
				"m_alat_transportasi_id"=>$request->get('alat_transportasi'),
				"tempat_tujuan"=>$request->get('tempat_tujuan'),
				"tipe_perdin"=>$request->get('tipe_perdin'),
				"lama"=>$request->get('lama'),
				"pjs"=>$request->get('pjs'),
				"create_date"=>date('Y-m-d  H:i:s'),
				"create_by"=>$id,
				"biaya_uang_saku"=>$uangsaku,
				"biaya_uang_makan"=>$uangmakan,
				"active"=>1,
			];
			DB::connection()->table("t_permit")
			->insert($data);

			if ($request->file('file')) {
				//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path = 'permit-'.date('YmdHis').$file->getClientOriginalName();$file->move($destination,$path);
				//$path=$file->getClientOriginalName();
				//echo $path;die;
				DB::connection()->table("t_permit")->where("kode",$nocuti)
				->update([
					"foto"=>$path
				]);
			}

			DB::commit();

			$sqldata="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode as kdpermit,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,coalesce(b.email_perusahaan,'mankewink@gmail.com') as email_perusahaan,e.email_perusahaan as email_appr
			FROM t_permit a
			left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
			left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
			left join p_karyawan d on d.p_karyawan_id=a.appr_1
			LEFT JOIN get_data_karyawan () e ON e.p_karyawan_id = A.appr_1
			WHERE 1=1 and a.kode='$nocuti' and a.active=1 and c.tipe=4 ORDER BY a.tgl_awal desc ";
			$data=DB::connection()->select($sqldata);

			//Mail::send('email_appr', ['data' => $data], function ($mail) use ($data){
			//		$mail->from('ethicagroup1@gmail.com', 'Ethica');
			//		$mail->to($data[0]->email_appr)->subject('Pengajuan Perjalanan Dinas');
			//		if($data[0]->foto!='' || $data[0]->foto!=null){
			//			$mail->attach('dist/img/file/'.$data[0]->foto);
			//		}
			//
			//				});

			return redirect()->route('fe.list_perdin')->with('success','Pengajuan Perjalanan Dinas Berhasil di simpan!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function lihat_perdin($id)
	{
		$sqlizin="SELECT a.*,b.nik,b.nama_lengkap,b.jabatan,b.departemen,c.kode,c.nama as nama_ijin,d.nama as nama_appr,tgl_appr_1,status_appr_1,e.nama as pjs,
		case when status_appr_hr=1 then 'Disetujui' when status_appr_hr=2 then 'Ditolak' when status_appr_hr=3 then 'Pending' end as approve_hr,
		case when status_appr_1=1 then 'Disetujui' when status_appr_1=2 then 'Ditolak' when status_appr_1=3 then 'Pending' end as sts_pengajuan
		FROM t_permit a
		left join get_data_karyawan() b on b.p_karyawan_id=a.p_karyawan_id
		left join m_jenis_ijin c on c.m_jenis_ijin_id=a.m_jenis_ijin_id
		left join p_karyawan d on d.p_karyawan_id=a.appr_1
		left join p_karyawan e on e.p_karyawan_id=a.pjs
		WHERE 1=1 and a.t_form_exit_id=$id and a.active=1 and c.tipe=4

		ORDER BY a.tgl_awal desc";
		$cuti=DB::connection()->select($sqlizin);

		return view('frontend.permit.lihat_perdin',compact('cuti'));
	}


	public function hapus_perdin($id)
	{
		DB::beginTransaction();
		try {
			DB::connection()->table("t_permit")
			->where("t_form_exit_id",$id)
			->update(["active"=>0]);
			DB::commit();

			return redirect()->back()->with('success','Pengajuan Izin Berhasil di Hapus!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function lembur_duplicate_check(Request $request)
	{
		$check =DB::connection()->select("select * from t_permit where tgl_awal ='$request->tgl' and (
				(jam_awal>='$request->jam_awal' and '$request->jam_awal'<= jam_akhir) or 
				(jam_awal<='$request->jam_awal' and '$request->jam_awal'<= jam_akhir) )
		");
		$return['count']= count($check);
		$return['check']= $check;
		echo json_encode($return);
		
	}

	public function generate_jam_finger ()
	{
	    
	    $help = new Helper_function();
	   
	   $month  = date('m');
	   $year  = date('Y');
	   $month = $month-1;
	   
	   $permit = DB::connection()->select("select * from t_permit 
	                                left join p_karyawan_pekerjaan on t_permit.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id 
	                                where generate_jam_finger!=4 and tgl_awal >= '$year-$month-24'and m_jenis_ijin_id = 22 ");
	   //print_r($permit);die;
	   //1 : belum keduanya
	   //2 : belum keluar
	   //3 : belum masuk
	   //4  : udah semuanya
	   foreach($permit as $permit)
	   {
	        $data= array();
	        $status_masuk=0;
	        $status_keluar=0;
	        $rekap = $rekap = $help->rekap_absen($permit->tgl_awal,$permit->tgl_akhir,$permit->tgl_awal,$permit->tgl_akhir,$permit->periode_gajian,null,$permit->p_karyawan_id);
	        echo $permit->generate_jam_finger;
	        if(isset($rekap[$permit->p_karyawan_id][$permit->tgl_awal]['a']['masuk']) and ($permit->generate_jam_finger==1 or $permit->generate_jam_finger==3 )){
	            $data['jam_masuk_finger']  = $rekap[$permit->p_karyawan_id][$permit->tgl_awal]['a']['masuk'];
	            $status_masuk = 1;
	        }
	        if(isset($rekap[$permit->p_karyawan_id][$permit->tgl_awal]['a']['keluar'])  and ($permit->generate_jam_finger==1 or $permit->generate_jam_finger==2 )){
	           
	            $data['jam_keluar_finger']  = $rekap[$permit->p_karyawan_id][$permit->tgl_awal]['a']['keluar'];
	            $status_keluar = 1; 
	        }
	        if(count($data)){
	            if($status_masuk and $status_keluar){
	                $data['generate_jam_finger'] = 4;
	            }else if($status_masuk and !$status_keluar){
	                $data['generate_jam_finger'] = 2; 
	            }else if(!$status_masuk and $status_keluar){
	                $data['generate_jam_finger'] = 3; 
	            }
	            print_r($data);
	            echo '<br>';
	            echo '<br>';
    	        DB::connection()->table("t_permit")
        			->where("t_form_exit_id",$permit->t_form_exit_id)
        			->update($data);
	        }
		   
	   }
	}
	
	public function approval_lintas_mesin_absen(Request $request)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select *,p_karyawan.p_karyawan_id as karyawan_id from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		join m_jabatan on p_karyawan_pekerjaan.m_jabatan_id = m_jabatan.m_jabatan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		
		$id_karyawan = $idkar[0]->p_karyawan_id;
		$bawahan = "((select p_karyawan_pekerjaan.p_karyawan_id from p_karyawan_pekerjaan where m_jabatan_id in (select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=2 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$atasan = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=1 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$sejajar = "(((select m_jabatan_terkait from m_jabatan_struktural where tipe_struktural=3 and m_jabatan_id = (select m_jabatan_id from p_karyawan_pekerjaan pkp where p_karyawan_id = $id_karyawan))))";
		$help = new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];
		$atasan_layer = $jabstruk['atasan_layer'];
		$atasan = isset($atasan_layer[1])?$atasan_layer[1]:'-1';
		$sql="select * from m_periode_absen where type= ".$idkar[0]->periode_gajian." and  ((periode_aktif=1 and tgl_akhir >= '".date('Y-m-d')."')  or (periode_aktif=0 and tgl_akhir <= '".date('Y-m-d')."' )) order by tgl_akhir desc limit 1";
				$gapen=DB::connection()->select($sql);
				$min = $gapen[0]->tgl_awal;
				$where = "";
				
					$where = "and date_time >= '$min'";
				
		$sql = "select *,p_karyawan.nama,ma.nama as m_a,ms.nama as m_s from absen_log
				left join p_karyawan_absen on p_karyawan_absen.no_absen = absen_log.pin
				left join p_karyawan on p_karyawan_absen.p_karyawan_id = p_karyawan.p_karyawan_id
				left join p_karyawan_pekerjaan on p_karyawan_absen.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
				left join m_office on m_office.m_office_id = p_karyawan_pekerjaan.m_kantor_id
				left join m_mesin_absen ma on  absen_log.mesin_id = ma.mesin_id
				left join m_mesin_absen ms on  m_office.m_mesin_absen_seharusnya_id = ms.mesin_id
				where m_office.m_mesin_absen_seharusnya_id != absen_log.mesin_id
				and absen_log.appr_status =3 and m_jabatan_id in($bawahan) and ver=1
				$where
				
				
				
			";
		
		$list = DB::connection()->select($sql);;

		return view('frontend.permit.approval_lintas_mesin_absen',compact('list','request'));
	}
	
	public function generate_jam_finger_klarifikasi()
	{
	    
	    $help = new Helper_function();
	  
	   $month  = date('m');
	   $year  = date('Y');
	   $month = $month-3;
	   
	   $permit = DB::connection()->select("select * from chat_room 
	                               where tanggal >= '$year-$month-24' and selesai!=3 and tujuan in(1,2,3,4,5) ");
	   //print_r($permit);die;
	   //1 : belum keduanya
	   //2 : belum keluar
	   //3 : belum masuk
	   //4  : udah semuanya
	   foreach($permit as $permit)
	   {
	        $data= array();
	        $status_masuk=0;
	        $status_keluar=0;
	        $rekap = $rekap = $help->rekap_absen($permit->tanggal,$permit->tanggal,$permit->tanggal,$permit->tanggal,-1,null,$permit->p_karyawan_create_id);
	       
	        if(isset($rekap[$permit->p_karyawan_create_id][$permit->tanggal]['a']['masuk'])){
	            $data['jam_masuk_finger']  = $rekap[$permit->p_karyawan_create_id][$permit->tanggal]['a']['masuk'];
	            $data['selesai']=3;
				print_r($permit);
				echo '<br>';
				DB::connection()->table("chat_room")
        			->where("chat_room_id",$permit->chat_room_id)
        			->update($data);
	            $status_masuk = 1;
	        }
	        
	        
		   
	   }
	}
}
