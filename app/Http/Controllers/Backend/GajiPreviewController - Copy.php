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
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;

use App\Eloquent\AbsenLog;
use App\Eloquent\Absen;
use DateTime;;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel;
use PHPExcel_IOFactory;

class GajiPreviewController extends Controller
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
    public function view(Request $request, $page,$subpage='gaji')
    {

        $help = new Helper_function();
		if ($request->get('submit_appr') == "AjukanHR") {
            GajiPreviewController::simpan_konfirm_gaji_hr($request);
           
        }else if ($request->get('submit_appr') == "Approve") {
            GajiPreviewController::simpan_appr_gaji($request);
            
        }else if ($request->get('submit_appr') == "Approve Voucher") {
            GajiPreviewController::simpan_appr_gaji_voucher($request);
            
        }else if ($request->get('submit_appr') == "Konfirmasi") {
            GajiPreviewController::simpan_konfirm_gaji($request);
           
        }
        	
        $pekanan = '';
        $iduser = Auth::user()->id;
        $sqluser = "SELECT p_recruitment.foto,role,p_karyawan_pekerjaan,p_karyawan_pekerjaan.m_lokasi_id,user_entitas_access,m_role_id FROM users
					left join m_role on m_role.m_role_id=users.role
					left join p_karyawan on p_karyawan.user_id=users.id
					left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
					left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
					where users.id=$iduser";
        $user = DB::connection()->select($sqluser);
        if ($user[0]->user_entitas_access) {
            $id_lokasi = $user[0]->user_entitas_access;
            $whereLokasirole = "and m_lokasi_id  = $id_lokasi";
        } else {
            $whereLokasirole = "";
        }
        
        
       if($subpage=='gaji')
        	$is_thr = "and is_thr=0";
        else 
        	$is_thr = "and is_thr=1";
        $sql = "select * from m_lokasi where active=1 and sub_entitas = 0 $whereLokasirole";
        $entitas = DB::connection()->select($sql);
		$sudah_appr_hr = array();
        if ($request->get('prl_generate')) {
            $id_prl = $request->get('prl_generate');

            $sql = "select * from m_lokasi where active=1 and sub_entitas = 0 and m_lokasi_id in (select m_lokasi_id from prl_gaji where prl_generate_id = $id_prl) $whereLokasirole";
            $entitas = DB::connection()->select($sql);

            $sql = "SELECT prl_generate.*,a.name as appr_nama FROM prl_generate left join users a on prl_generate.appr_by = a.id where prl_generate_id=$id_prl";
            $generate = DB::connection()->select($sql);
            if($subpage=='gaji'){
            if ($generate[0]->periode_gajian==1){
            	
                $pekanan = '';
 					//if($request->get('menu')=='Gaji2' or $request->get('menu')=='Gaji' ){
 						
 				    $data_row = array();
                    $data_head = array();
                    $data_head[] = array('Absensi',10,'col');
                    $data_head[] = array('Pendapatan',5,'col');
                    $data_head[] = array('Tunjangan',6,'col');
                    $data_head[] = array('Potongan',14,'col');
                    $data_head[] = array('Total',3,'col');
                    $data_row[] = array('Hari Absen', 'ha', 2, 'absensi');
                    $data_row[] = array('Izin Hak Karyawan', 'ihk', 2, 'absensi');
                    $data_row[] = array('Sakit', 'sakit', 2, 'absensi');
                    $data_row[] = array('Cuti', 'cuti', 2, 'absensi');
                    $data_row[] = array('Izin Potong Cuti', 'ipc', 2, 'absensi');
                    $data_row[] = array('Izin Potongan Gaji', 'ipg', 2, 'absensi');
                    $data_row[] = array('Tanpa Keterangan', 'ihk', 2, 'absensi');
                    $data_row[] = array('Jam Lembur', 'jam_lembur', 2, 'absensi');
                    $data_row[] = array('Terlambat', 'terlambat', 2, 'absensi');
                    $data_row[] = array('Fingerprint', 'fingerprint', 2, 'absensi');
                    $data_row[] = array('Pulang Mendahului Tanpa Izin', 'pm', 2, 'absensi');
                    
                    $data_row[] = array('Gaji Pokok', 'gapok', 1, 'pendapatan');
                    $data_row[] = array('Tunjangan Grade', 'tunjangan_grade', 1, 'pendapatan');
                    $data_row[] = array('Tunjangan Entitas', 'tunjangan_entitas', 1, 'pendapatan');
                    $data_row[] = array(
                        'Total Pendapatan', 'jumlah_pendapatan', 4, 'total_pendapatan_karyawan',
                        array(
                            array(
                                "Tambah", array(
                                    array("field", 'gapok'),
                                    array("field", 'tunjangan_grade'),
                                    array("field", 'tunjangan_entitas'),
                                )
                            )
                        )
                    );
                    
                    $data_row[] = array('Lembur', 'lembur', 2, 'tunjangan');
                    //$data_row[] = array('Bonus', 'bonus', 2, 'tunjangan');


                    
                    $data_row[] = array('Tunjangan BPJS Kesehatan', 'tunjangan_bpjskes', 1, 'tunjangan');
                    $data_row[] = array('Tunjangan BPJS Ketenaga Kerjaan', 'tunjangan_bpjsket', 1, 'tunjangan');
                    $data_row[] = array('Tunjangan Kost', 'tunjangan_kost', 1, 'tunjangan');
                    $data_row[] = array('Koreksi(+)', 'korekplus', 2, 'tunjangan');


                    $data_row[] = array(
                        'Total Tunjangan', 'jumlah_tunjangan', 4, 'total_tunjangan_karyawan',
                        array(
                            array(
                                "Tambah", array(
                                
                                    array("field", 'lembur'),
                                   // array("field", 'bonus'),
                                    array("field", 'tunjangan_kost'),
                                    array("field", 'korekplus'),
                                    array("field", 'tunjangan_bpjskes'),
                                    array("field", 'tunjangan_bpjsket'), 
                                )
                            )
                        )
                    );

                    $data_row[] = array('Potongan Absen', 'absen', 2, 'potongan');
                    $data_row[] = array('Potongan Izin', 'potizin', 2, 'potongan');
                    $data_row[] = array('Potongan Telat', 'telat', 2, 'potongan');
                    $data_row[] = array('Potongan Fingerprint', 'potfinger', 2, 'potongan');
                    $data_row[] = array('Potongan Pulang Mendahului', 'potpm', 2, 'potongan');
                    
                    $data_row[] = array('Sewa Kost', 'sewa_kost', 1, 'potongan');
                    $data_row[] = array('Iuran BPJS Kesehatan', 'iuran_bpjskes', 1, 'potongan');
                    $data_row[] = array('Iuran BPJS Ketenaga Kerjaan', 'iuran_bpjsket', 1, 'potongan');
                    $data_row[] = array('Zakat', 'zakat', 1, 'potongan');
                    $data_row[] = array('Infaq', 'infaq', 1, 'potongan');
                    
                    
                    $data_row[] = array('Koreksi(-)', 'korekmin', 2, 'potongan');
                    $data_row[] = array('Potongan KKB', 'KKB', 1, 'potongan');
                    $data_row[] = array('Potongan Koperasi Asa', 'ASA', 1, 'potongan');
                    $data_row[] = array('Pajak', 'pajak', 2, 'potongan');
                    
					
					 $data_row[] = array(
                        'Total Potongan', 'jumlah_potongan', 4, 'total_potongan_karyawan',
                        array(
                            array(
                                "Tambah", array(
                                    array("field", 'telat'),
                                    array("field", 'absen'),
                                    array("field", 'sewa_kost'),
                                    array("field", 'iuran_bpjskes'),
                                    array("field", 'iuran_bpjsket'),
                                    array("field", 'zakat'),
                                    array("field", 'infaq'),
                                    array("field", 'korekmin'),
                                    array("field", 'KKB'),
                                    array("field", 'ASA'),
                                    array("field", 'pajak'),
                                    array("field", 'potizin'),
                                    array("field", 'potfinger'),
                                    array("field", 'potpm'),
                                )
                            )
                        )
                    );
                    $data_row[] = array(
                        'Total Pendapatan Tunjangan', 'jumlah_pendapatan_tunjangan', 4, 'total_pendapatan_tunjangan_karyawan',
                        array(
                            array(
                                "Tambah", array(
                                    array("field", 'jumlah_pendapatan'),
                                    array("field", 'jumlah_tunjangan'),
                                )
                            )
                        )
                    );

                    $data_row[] = array(
                        'THP', 'thp_karyawan', 4, 'thp_karyawan',
                        array(
                            array(
                                "Kurang", array(
                                    array("field", 'jumlah_pendapatan_tunjangan'),
                                    array("field", 'jumlah_potongan'),
                                )
                            )
                        )
                    );
	
 					
                   
 
            }
            else if ($generate[0]->periode_gajian == 0){
            	
                $pekanan = '_pekanan';
                
                 //if($request->get('menu')=='Gaji2'){
                 $data_row = array();
                 $data_head = array();
                    $data_head[] = array('Absensi',2,'col');
                    $data_head[] = array('Pendapatan',3,'col');
                    $data_head[] = array('Tunjangan',5,'col');
                    $data_head[] = array('Potongan',9,'col');
                    $data_head[] = array('Total',3,'col');
                                $data_row[] = array('Hari Absen', 'ha',2,'absensi');
                                $data_row[] = array('Jam Lembur', 'jam_lembur',2,'absensi');
                                $data_row[] = array('Upah Harian', 'upah_harian',1,'master');
                                $data_row[] = array('Gaji Total Perbulan', 'gaji_total_per_bulan',3,'',
                                						array(
                                									array("Kali",array(
					                                							array("field",'upah_harian'),
					                                							array("num",22)
					                                							)
                                										)
                                							)
                                					);
                                $data_row[] = array('Gaji Total Upah Harian', 'gaji_total_upah_harian',3,'pendapatan',
                                						array(
                                									array("Kali",array(
					                                							array("field",'ha'),
					                                							array("field",'upah_harian'),
					                                							)
                                										)
                                							)
                                					);
                                
                               
                               
                              	
                               
                                 $data_row[] = array('Lembur', 'lembur',2,'pendapatan');
                    			$data_row[] = array('Bonus', 'bonus', 2, 'pendapatan');
                                $data_row[] = array('Tunjangan Kost', 'tunjangan_kost',1,'tunjangan');
                                $data_row[] = array('Koreksi(+)', 'korekplus',2,'tunjangan');
                                $data_row[] = array('Total Tunjangan', 'jumlah_tunjangan',4,'total_tunjangan_karyawan',
                                						array(
			                                						array("Tambah",array(
						                                							array("field",'tunjangan_kost'),
						                                							array("field",'korekplus'),
						                                							array("field",'lembur'),
						                                							array("field",'bonus'),
						                                							)
                                										)
                                							)
                                					);

                                
                                $data_row[] = array('Potongan Telat', 'telat',2,'potongan');
                                $data_row[] = array('Sewa Kost', 'sewakost',1,'potongan');
                                $data_row[] = array('Potongan Fingerprint', 'finger',2,'potongan');
                                $data_row[] = array('Potongan Pulang Mendahului', 'potpm',2,'potongan');
                                $data_row[] = array('Potongan KKB', 'KKB',1,'potongan');
                                $data_row[] = array('Potongan Koperasi Asa', 'ASA',1,'potongan');
                                $data_row[] = array('Zakat', 'zakat',1,'potongan');
                                $data_row[] = array('Infaq', 'infaq',1,'potongan');
                                $data_row[] = array('Koreksi(-)', 'korekmin',2,'potongan');
                                $data_row[] = array('Total Potongan', 'jumlah_potongan',4,'total_potongan_karyawan',
                                						array(
			                                						array("Tambah",array(
						                                							array("field",'telat'),
						                                							array("field",'finger'),
						                                							array("field",'sewakost'),
						                                							array("field",'zakat'),
						                                							array("field",'zakat'),
						                                							array("field",'korekmin'),
						                                							array("field",'KKB'),
						                                							array("field",'ASA'),
						                                							array("field",'potpm'),
						                                							)
                                										)
                                							)
                                					);
                                $data_row[] = array('Total Pendapatan Tunjangan', 'jumlah_pendapatan_tunjangan',4,'total_pendapatan_tunjangan_karyawan',
                                						array(
			                                						array("Tambah",array(
						                                							array("field",'gaji_total_upah_harian'),
						                                							array("field",'jumlah_tunjangan'),
						                                							
						                                							)
                                										)
                                							)
                                					);
								$data_row[] = array('THP', 'thp_karyawan',4,'thp_karyawan',
                                						array(
			                                						array("Kurang",array(
						                                							array("field",'jumlah_pendapatan_tunjangan'),
						                                							array("field",'jumlah_potongan'),
						                                							)
                                										)
                                							)
                                					);
					
            }
            }
            else{
            	 $data_row = array();
                    $data_head = array();
                    $data_head[] = array('Pendapatan',3,'col');
                    $data_head[] = array('Potongan',4,'col');
                    $data_head[] = array('Total',3,'col');
                    
                    $data_row[] = array('Gaji Pokok', 'gapok', 2, 'pendapatan');
                    $data_row[] = array('Tunjangan Grade', 'tunjangan_grade', 2, 'pendapatan');


                    $data_row[] = array(
                        'Total Pendapatan', 'jumlah_pendapatan', 4, 'total_pendapatan_karyawan',
                        array(
                            array(
                                "Tambah", array(
                                    array("field", 'gapok'),
                                    array("field", 'tunjangan_grade'),
                                )
                            )
                        )
                    ); 
                    $data_row[] = array('Pajak', 'pajak', 2, 'potongan');
                    $data_row[] = array('Infaq', 'infaq', 2, 'potongan');
                    $data_row[] = array('Zakat', 'zakat', 2, 'potongan');
                    
                    $data_row[] = array(
                        'Total Potongan', 'jumlah_potongan', 4, 'total_potongan_karyawan',
                        array(
                            array(
                                "Tambah", array(
                                    array("field", 'pajak'),
                                    array("field", 'infaq'),
                                    array("field", 'zakat'),
                                )
                            )
                        )
                    ); 
                    
                    $data_row[] = array('Total Bulan Gabung', 'total_bulan_gabung', 2, 'absensi');
                    $data_row[] = array('Persentasi Pendapatan', 'presentase_pendapatan', 2, 'absensi');
                    

                   

                    $data_row[] = array(
                        'THP', 'thp_karyawan', 4, 'thp_karyawan',
                        array(
                        		array("Kali", array(
                                    array("field", 'jumlah_pendapatan'),
                                    array("field", 'presentase_pendapatan')
                                    )),
                                 array("Bagi", array(
                                    array("num", '100'),
                                )),
                                 array("Kurang", array(
                                    array("field", 'jumlah_potongan'))
                                )
                            )
                       
                    );
            }
           

            $sqlperiode = "SELECT * FROM prl_gaji where prl_generate_id=$id_prl";
            $sudahappr = DB::connection()->select($sqlperiode);
            $sudah_appr = array();
            $sudah_appr_keuangan = array();
            $m_lokasi_hr_appr_on = '';
            $m_lokasi_hr_appr_off = '';
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
            }
            $m_lokasi_hr_appr_on .= '-1';
            $m_lokasi_hr_appr_off .= '-1';
            $where = '';
            $appendwhere = '';
			if($subpage=='gaji'){
				
			 $periode_absen = $generate[0]->periode_absen_id;
            $sqlperiode = "SELECT * FROM m_periode_absen where periode_absen_id=$periode_absen";
            $periodetgl = DB::connection()->select($sqlperiode);
            $type = $periodetgl[0]->type;
            $where = " k.periode_gajian = " . $type;
            $appendwhere = "and";

            $periode_gajian = $periodetgl[0]->type;
            
			
            $tgl_awal = date('Y-m-d', strtotime($periodetgl[0]->tgl_awal));
            $tgl_akhir = date('Y-m-d', strtotime($periodetgl[0]->tgl_akhir));
            
            
            }else{
            	$tgl_awal = $generate[0]->tgl_patokan;
            }
            $wherelokasi = '';
            $wherepajak = '';
            $wherebank = '';
            $where_directur = "";
            if ($request->get('pajakonoff'))
                $wherepajak = "and k.on_off='" . $request->get('pajakonoff') . "'";
            if ($request->get('bank'))
                $wherebank = "and r.m_bank_id='" . $request->get('bank') . "'";
            if ($request->get('entitas'))
                $wherelokasi = 'and c.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id where prl_generate_id = ' . $id_prl . ' and lokasi_id = ' . $request->get('entitas') . ') ';
            $iduser = Auth::user()->id;

            if ($user[0]->user_entitas_access) {
                $id_lokasi = $user[0]->user_entitas_access;
                $whereLokasirole = "and c.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji.prl_gaji_id = prl_gaji_detail.prl_gaji_id where prl_generate_id = $id_prl and m_lokasi_id  = $id_lokasi)";
            } else {
                $whereLokasirole = "";
                $generate[0]->periode_gajian = -1;
            }
            //echo print_r($user[0]);die;

            if ($user[0]->m_role_id == 12 or ($page=='direksi') or ($page=='ajuan')) {
			
                $where_directur = " and  (
				(d.m_lokasi_id  in ($m_lokasi_hr_appr_on) and pajak_onoff='ON') 
					or (d.m_lokasi_id  in ($m_lokasi_hr_appr_off) and pajak_onoff='OFF')
					)";
            } 

            $sql = "SELECT c.p_karyawan_id, ".$generate[0]->periode_gajian." as gajian_type,c.nama as nama_lengkap,c.nik,m_lokasi.kode as nmlokasi,m_lokasi.nama as nmentitas,
            f.m_pangkat_id,i.nama as nmpangkat ,
            f.nama as nmjabatan,g.tgl_awal,AGE(CURRENT_DATE, c.tgl_bergabung)::VARCHAR AS umur,f.job as jobweight , j.nama_grade as grade,k.no_rek as norek,k.lokasi_id as m_lokasi_id, k.on_off as pajak_onoff,r.nama_bank,keterangan_ajuan,prl_generate_karyawan_id,k.periode_gajian,case k.periode_gajian when 1 then 'Bulanan' else 'Pekanan' end as type_gaji, k.jabatan_id as m_jabatan_id,k.m_bank_id 
            
			FROM p_karyawan c 
			LEFT JOIN prl_generate_karyawan k on c.p_karyawan_id=k.p_karyawan_id and k.status=1 and k.prl_generate_id = $id_prl
			
			--LEFT JOIN p_karyawan_pekerjaan d on d.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN p_karyawan_kontrak g on g.p_karyawan_id=c.p_karyawan_id  
			LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=k.lokasi_id
			LEFT JOIN m_jabatan f on k.jabatan_id=f.m_jabatan_id 

			LEFT JOIN m_pangkat i on f.m_pangkat_id=i.m_pangkat_id 
			LEFT JOIN m_karyawan_grade j on f.job>=j.job_min and f.job<= j.job_max
			LEFT JOIN m_bank r on r.m_bank_id=k.m_bank_id
			WHERE $where $appendwhere  
			
			 c.p_karyawan_id in (select p_karyawan_id from prl_gaji_detail join prl_gaji on prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id where prl_generate_id = $id_prl and prl_gaji_detail.active=1)
			--AND d.m_departemen_id != 17
			  and f.m_pangkat_id != 6
			$wherelokasi
			$whereLokasirole
			$wherepajak
			$wherebank
			$where_directur
			order by c.nama";;
            //echo $sql;
            $list_karyawan = DB::connection()->select($sql);
            $where_prl = '';
            if ($request->get('entitas'))
                $where_prl = 'and a.m_lokasi_id = ' . $request->get('entitas');

            $sql = "select *, (select count(*) from p_karyawan_koperasi where nama_koperasi='ASA' and active=1 and b.p_karyawan_id = p_karyawan_koperasi.p_karyawan_id) as is_koperasi_asa, (select count(*) from p_karyawan_koperasi where nama_koperasi='KKB' and active=1 and b.p_karyawan_id = p_karyawan_koperasi.p_karyawan_id) as is_koperasi_kkb,b.keterangan as keterangan_gaji,
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
			where prl_generate_id = $id_prl
			$where_prl
			and b.active=1
			order by prl_gaji_detail_id 
			";
            $row = DB::connection()->select($sql);
            $data = array();
            foreach ($row as $row) {

                $data[$row->p_karyawan_id][$row->nama] = round($row->nominal);
                $data[$row->p_karyawan_id]['Entitas'][$row->nama] = ($row->nm_lokasi);
                $data[$row->p_karyawan_id]['Entitas_id'][$row->nama] = ($row->m_lokasi_id);
                $data[$row->p_karyawan_id]['Keterangan'][$row->nama] = ($row->keterangan);
                $data[$row->p_karyawan_id]['id'][$row->nama] = ($row->prl_gaji_detail_id);
            }

            $row = DB::connection()->select("select * from p_karyawan_gapok join p_karyawan on p_karyawan_gapok.p_karyawan_id = p_karyawan.p_karyawan_id where p_karyawan.active=1 and p_karyawan_gapok.active=1");
            $columns = DB::connection()->getSchemaBuilder()->getColumnListing("p_karyawan_gapok");
            foreach ($row as $row) {
                for ($i = 0; $i < count($columns); $i++) {
                	
                    $nama_row = $columns[$i];
                    if(!in_array($nama_row,array('tunjangan_kost','sewa_kost','koperasi_asa','pajak','koperasi_kkb','korekplus','korekmin')))
                    $data[$row->p_karyawan_id][$columns[$i]]['master'] = ($row->$nama_row);
                }
            }
            $row = DB::connection()->select("select * from p_karyawan_koperasi join p_karyawan on p_karyawan_koperasi.p_karyawan_id = p_karyawan.p_karyawan_id where p_karyawan.active=1 and p_karyawan_koperasi.active=1  and  tgl_akhir>='$tgl_awal'"); 

            foreach ($row as $row) {
            	if($row->nama_koperasi=='SEWA KOST')
            		$row->nama_koperasi = 'sewa_kost';
            	else if($row->nama_koperasi=='INFAQ')
            		$row->nama_koperasi = 'infaq';
            	else if($row->nama_koperasi=='ZAKAT')
            		$row->nama_koperasi = 'zakat';
            	else if($row->nama_koperasi=='TUNJANGAN KOST')
            		$row->nama_koperasi = 'tunjangan_kost';
                $data[$row->p_karyawan_id][$row->nama_koperasi]['master'] = ($row->nominal);
            }
			/*
            $sqlpajak = "SELECT * from prl_potongan
   				join p_karyawan on prl_potongan.p_karyawan_id = p_karyawan.p_karyawan_id
                WHERE 1=1 and prl_potongan.active=1
                	and m_potongan_id = 20
                order by nama";
            $pajak = DB::connection()->select($sqlpajak);
            foreach ($pajak as $row) {
                $data[$row->p_karyawan_id]['pajak']['master'] = ($row->nominal);
            }*/
        } else {
            $id = '';
            $data = array();
            $sudah_appr_keuangan = array();
            $sudah_appr_hr = array();
            $sudah_appr = array();
            $sudah_appr_voucher = array();
            $list_karyawan = '';
            $generate = '';
            $id_prl = '';
            $data_row = array();
                 $data_head = array();
        }
    
        
		if($subpage=='thr')
			$whereperiode = 'and is_thr=1';
		else
			$whereperiode = 'and is_thr=0';
        $sqlperiode = "SELECT m_periode_absen.*,
		
		case when a.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,
		a.tahun as tahun_gener,a.*,a.bulan as bulan_gener,b.tgl_awal as tgl_awal_lembur,b.tgl_akhir as tgl_akhir_lembur
		FROM prl_generate a 
		left join m_periode_absen on m_periode_absen.periode_absen_id = a.periode_absen_id
		left join m_periode_absen b on b.periode_absen_id = a.periode_lembur_id
		where a.active = 1 $whereperiode 
		ORDER BY a.create_date desc, prl_generate_id desc";
        $periode = DB::connection()->select($sqlperiode);
        $periode_absen = $request->get('periode_gajian');
        

        if ($request->get('entitas')) {

            $sql = "select * from m_lokasi where m_lokasi_id=" . $request->get('entitas');
            $hisentitas = DB::connection()->select($sql);
        } else {
            $hisentitas = "";
        }
        $perigen = '';
        foreach ($periode as $periode2) {
            if ($periode2->prl_generate_id == $id_prl) {
                $perigen = 'Periode: ' . $periode2->tahun_gener . ' Bulan: ' . $periode2->bulan_gener . ' | Absen:' . $periode2->tgl_awal . ' - ' . $periode2->tgl_akhir . ' | Lembur:' . $periode2->tgl_awal_lembur . ' - ' . $periode2->tgl_akhir_lembur . '';
            }
        }
        if ($user[0]->user_entitas_access) {
            $id_lokasi = $user[0]->user_entitas_access;
            $whereLokasigenerate = "and m_lokasi.m_lokasi_id  = $id_lokasi";
        } else {
        	$whereLokasigenerate = "";
        }
        if(empty($list_karyawan) or !($request->get('preview') or $request->get('view') or $request->get('menu'))){
        	
        $sqluser = "SELECT prl_generate.*,prl_gaji.*,case when prl_generate.periode_gajian=0 then 'Pekanan' else 'Bulanan' end as tipe,m_lokasi.nama as nmlokasi,
    	a.tgl_awal as tgl_awal_absen, 
    	a.tgl_akhir as tgl_akhir_absen, 
    	b.tgl_awal as tgl_awal_lembur, 
    	b.tgl_akhir as tgl_akhir_lembur,
    	prl_generate.tahun,
    	a.pekanan_ke,
    	(select count(*) from p_karyawan_pekerjaan join m_jabatan on m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id and m_pangkat_id !=6 join p_karyawan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan.active=1 where p_karyawan_pekerjaan.m_lokasi_id =prl_gaji.m_lokasi_id and pajak_onoff='ON' and p_karyawan_pekerjaan.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail where prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id )) is_on,
    	(select count(*) from p_karyawan_pekerjaan join m_jabatan on m_jabatan.m_jabatan_id = p_karyawan_pekerjaan.m_jabatan_id and m_pangkat_id !=6 join p_karyawan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id and p_karyawan.active=1 where p_karyawan_pekerjaan.m_lokasi_id =prl_gaji.m_lokasi_id and pajak_onoff='OFF' and p_karyawan_pekerjaan.p_karyawan_id in(select p_karyawan_id from prl_gaji_detail where prl_gaji_detail.prl_gaji_id = prl_gaji.prl_gaji_id )) is_off
    	FROM prl_gaji 
    		left join m_lokasi  on prl_gaji.m_lokasi_id = m_lokasi.m_lokasi_id
    		left join prl_generate  on prl_gaji.prl_generate_id = prl_generate.prl_generate_id
    		left join m_periode_absen a on prl_generate.periode_absen_id = a.periode_absen_id
    		left join m_periode_absen b on prl_generate.periode_lembur_id = b.periode_absen_id
    		where prl_generate.active = 1 $is_thr	$whereLokasigenerate
    		and m_lokasi.active=1
    		order by prl_generate.prl_generate_id desc
    	";
        $generate_gaji = DB::connection()->select($sqluser);
        }else{
        	$generate_gaji = '';
        }
        
        $data['page'] = $page;
        $data['subpage'] = $subpage;
		
        //echo ''.$request->get('menu');die;
         
        if ($request->get('Cari') == "RekapExcel") {

            $param['data'] = $data;
            $param['user'] = $user;
            $param['help'] = $help;
            $param['id_prl'] = $id_prl;
            $param['periode'] = $periode;
            $param['entitas'] = $entitas;
            $param['periode_absen'] = $periode_absen;
            $param['request'] = $request;
            $param['list_karyawan'] = $list_karyawan;
            $param['hisentitas'] = $hisentitas;
            $param['perigen'] = $perigen;
            $param['generate_gaji'] = $generate_gaji;
            $param['sudah_appr_voucher'] = $sudah_appr_voucher;
            $param['data_row'] = $data_row;
            $param['data_head'] = $data_head;
            if ($request->get('menu') == 'Presensi') {
                //die;
                if ($pekanan)
                    return GajiPreviewController::exportsPresensi_Pekanan($param);
                else
                    return GajiPreviewController::exportsPresensi($param);
            } elseif ($request->get('menu') == 'previewpajak') {
                return GajiPreviewController::exportsPajak($param);
            } elseif ($request->get('menu') == 'Gaji') {
                
                    return GajiPreviewController::exportsGaji($param);
            }else
                    return GajiPreviewController::exportsGaji($param);
        } else if ($request->get('Cari') == "EditKaryawan") {
        	$sqllokasi="SELECT * FROM m_lokasi WHERE active=1 and sub_entitas=0 ORDER BY nama ASC ";
        	$lokasi=DB::connection()->select($sqllokasi);

			$sql="SELECT * FROM m_bank WHERE active=1 ORDER BY nama_bank ASC ";
		 	$bank=DB::connection()->select($sql);
		 
		 	$sqljabatan="SELECT m_jabatan.*,m_pangkat.nama as nmpangkat,m_lokasi.nama as nmlokasi,m_lokasi.kode as kdlokasi 
                      FROM m_jabatan 
                      LEFT JOIN m_pangkat on m_pangkat.m_pangkat_id=m_jabatan.m_pangkat_id
                      LEFT JOIN m_lokasi on m_lokasi.m_lokasi_id=m_jabatan.m_lokasi_id
                      WHERE m_jabatan.active=1 ORDER BY m_jabatan.nama ASC ";
        	$jabatan=DB::connection()->select($sqljabatan);
		 	
		 	
		 	
        	return view('backend.gaji.preview.edit_karyawan', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'lokasi', 'bank', 'jabatan', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr', 'sudah_appr_voucher','generate_gaji'));
        } else if ($request->get('Cari') == "RekapExcelRp") {

            $param['data'] = $data;
            $param['user'] = $user;
            $param['help'] = $help;
            $param['id_prl'] = $id_prl;
            $param['periode'] = $periode;
            $param['entitas'] = $entitas;
            $param['periode_absen'] = $periode_absen;
            $param['request'] = $request;
            $param['list_karyawan'] = $list_karyawan;
            $param['hisentitas'] = $hisentitas;
            $param['perigen'] = $perigen;
            $param['data_row'] = $data_row;
            $param['data_head'] = $data_head;
            
            $param['generate_gaji'] = $generate_gaji;
            if ($request->get('menu') == 'Presensi') {
                //die;
                if ($pekanan)
                    return GajiPreviewController::exportsPresensiRp_Pekanan($param);
                else
                    return GajiPreviewController::exportsPresensiRp($param);
            } elseif ($request->get('menu') == 'previewpajak') {
                return GajiPreviewController::exportsPajakRp($param);
            } elseif ($request->get('menu') == 'Gaji') {
                if ($pekanan)
                    return GajiPreviewController::exportsGajiRp_pekanan($param);
                else
                    return GajiPreviewController::exportsGajiRp($param);
            }
        } else  if ($request->get('Cari') == "Ajuan HR") {
            
           $generate = $generate[0];
            $sql = "SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi 
			FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='hr'";
            $appr = DB::connection()->select($sql);
            return view('backend.gaji.preview.approval_hr', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
        }else if ($request->get('Cari') == "Approval") {
            $generate = $generate[0];
            $sql = "SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi 
			FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='direktur'";
            $appr = DB::connection()->select($sql);
            return view('backend.gaji.preview.approval', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
        }  else if ($request->get('Cari') == "Konfirmasi") {
            $generate = $generate[0];
            $sql = "
			SELECT prl_generate_appr.*,a.name as appr_nama,m_lokasi.nama as nmlokasi FROM prl_generate_appr 
				left join users a on prl_generate_appr.appr_by = a.id 
				left join m_lokasi  on prl_generate_appr.m_lokasi_id = m_lokasi.m_lokasi_id 
				
				where prl_generate_id=$id_prl and appr_type='keuangan'";
            $appr = DB::connection()->select($sql);

            return view('backend.gaji.preview.approval_keuangan', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'appr', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr','generate_gaji'));
        } else if ($request->get('Cari') == "Ajuan") {
            if ($request->get('entitas')) {

                $sqlperiode = "SELECT *,a.name as appr_nama_on,b.name as appr_nama_off FROM prl_gaji  
					left join users a on prl_gaji.appr_on_direktur_by = a.id 
					left join users b on prl_gaji.appr_off_direktur_by = b.id 
					where prl_generate_id=$id_prl and m_lokasi_id = " . $request->get('entitas') . "";
                $sudahappr = DB::connection()->select($sqlperiode);
            } else {
                $sudahappr = array();
            }

            return view('backend.gaji.preview.ajuan', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'generate', 'sudahappr', 'sudah_appr', 'sudah_appr_voucher','generate_gaji'));
        } else if ($request->get('Cari') == "ExcelAjuan") {
            $param['data'] = $data;
            $param['user'] = $user;
            $param['help'] = $help;
            $param['id_prl'] = $id_prl;
            $param['periode'] = $periode;
            $param['perigen'] = $perigen;
            $param['entitas'] = $entitas;
            $param['hisentitas'] = $hisentitas;
            $param['periode_absen'] = $periode_absen;
            $param['request'] = $request;
            $param['list_karyawan'] = $list_karyawan;
            $param['data_row'] = $data_row;
            $param['data_head'] = $data_head;
            $param['sudah_appr_voucher'] = $sudah_appr_voucher;
            
                return GajiPreviewController::exportsAjuan($param);
        } else {
            if ($request->get('menu') == 'Presensi') {

                return view('backend.gaji.preview.view_presensi' . $pekanan, compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr', 'sudah_appr_voucher','generate_gaji'));
            } else if ($request->get('menu') == 'previewpajak') {
                return view('backend.gaji.preview.view_previewpajak' . $pekanan, compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr', 'sudah_appr_voucher','generate_gaji'));
            } else {

                return view('backend.gaji.preview.viewgapreview', compact('data', 'data_row', 'data_head', 'entitas', 'user', 'id_prl', 'help', 'periode', 'periode_absen', 'request', 'list_karyawan', 'sudah_appr', 'sudah_appr_keuangan', 'sudah_appr_hr', 'sudah_appr_voucher','generate_gaji'));
            }
        }
    }
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function save_keterangan_ajuan(Request $request){
    	 DB::beginTransaction();
        try {

          
            DB::connection()->table("prl_generate_karyawan")
                ->where('prl_generate_karyawan_id', $request->get('id_generate_karyawan'))
                ->update([
                    "keterangan_ajuan" => $request->get('keterangan'),

                ]);


            DB::commit();
            
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function update_nominal($id_prl, $id, $nominal)
    {
        DB::beginTransaction();
        try {

            //print_r($appr);
            DB::connection()->table("prl_gaji_detail")
                ->where('prl_gaji_detail_id', $id)
                ->update([
                    "nominal" => $nominal,

                ]);


            DB::commit();
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Cari'])->with('success', 'Approval Gaji Berhasil di Update!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function hapus_approval($id, $id_prl)
    {
        DB::beginTransaction();
        try {
            $idUser = Auth::user()->id;
            $sql = "select * from prl_generate_appr where prl_generate_appr_id = '$id'";
            $appr = DB::connection()->select($sql);
            $pajak = $appr[0]->pajak;

            //print_r($appr);
            DB::connection()->table("prl_gaji")
                ->where('prl_generate_id', $id_prl)
                ->where('m_lokasi_id', $appr[0]->m_lokasi_id)
                ->update([
                    "appr_" . $pajak . "_direktur_status" => 0,
                    "appr_" . $pajak . "_direktur_keterangan" => 'Hapus Approval',
                    "appr_" . $pajak . "_direktur_by" => $idUser,
                    "appr_" . $pajak . "_direktur_date" => date("Y-m-d H:i:s"),

                ]);

            DB::connection()->table("prl_generate_appr")
                ->where('prl_generate_appr_id', $id)
                ->delete();
            DB::commit();
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Approval'])->with('success', 'Approval Gaji Berhasil di Update!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function simpan_appr_gaji($request)
    {
        DB::beginTransaction();
        try {
            $idUser = Auth::user()->id;
            $id = $request->get('prl_generate');
            $id_prl = $request->get('prl_generate');
            $help = new Helper_function();
            $entitas = ($request->get('entitas'));
            $pajakonoff= $request->get('pajakonoff');
            $list_pajak = array();
            if($pajakonoff==''){
            	$list_pajak[] = 'on';
            	$list_pajak[] = 'off';
            }else if($pajakonoff=='ON'){
            	$list_pajak[] = 'on';
            }else if($pajakonoff=='OFF'){
            	$list_pajak[] = 'off';
            }
            
           
            for($i=0;$i<count($list_pajak);$i++){
            	$pajak = $list_pajak[$i];
            DB::connection()->table("prl_gaji")
                ->where('prl_generate_id', $id)
                ->where('m_lokasi_id', $entitas)
                ->update([
                    "appr_" . $pajak . "_direktur_status" => (1),
                    "appr_" . $pajak . "_direktur_by" => $idUser,
                    "appr_" . $pajak . "_direktur_date" => date('Y-m-d H:i:s'),

                ]);
            $sql = "select (select count(*) from prl_gaji where prl_generate_id = $id_prl ) as count_lokasi,
					(select count(*) from prl_gaji where prl_generate_id = $id_prl and appr_on_direktur_status=1) as count_on,
					(select count(*) from prl_gaji where prl_generate_id = $id_prl and appr_off_direktur_status=1) as count_off
			 ";
            $count = DB::connection()->select($sql);

            $count_lokasi = ($count[0]->count_lokasi) * 2;
            $count_on = $count[0]->count_on;
            $count_off = $count[0]->count_off;

            if (($count_lokasi) == $count_off + $count_on) {


                DB::connection()->table("prl_generate")
                    ->where('prl_generate_id', $id)
                    ->update([
                        "appr_status" => (1),
                        "appr_by" => $idUser,
                        "appr_date" => date("Y-m-d H:i:s"),
                        "appr_date_lock" => $help->tambah_tanggal(date("Y-m-d"), 3)
                    ]);
            }
            DB::connection()->table("prl_generate_appr")
                ->insert([
                    "pajak" => ($pajak),
                    "m_lokasi_id" => ($entitas),
                    "prl_generate_id" => ($id),
                    "appr_status" => (1),
                    "appr_by" => $idUser,
                    "appr_type" => "direktur",
                    "appr_date" => date("Y-m-d H:i:s")
                ]);
			}
            DB::commit();
            //echo 'Hai';
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Cari'])->with('success', 'Data Gaji Di Ajukan!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function simpan_appr_gaji_voucher($request)
    {
        DB::beginTransaction();
        try {
            $idUser = Auth::user()->id;
            $id = $request->get('prl_generate');
            $id_prl = $request->get('prl_generate');
            $help = new Helper_function();
            $entitas = ($request->get('entitas'));
            $pajakonoff= $request->get('pajakonoff');
            $list_pajak = array();
            if($pajakonoff==''){
            	$list_pajak[] = 'on';
            	$list_pajak[] = 'off';
            }else if($pajakonoff=='ON'){
            	$list_pajak[] = 'on';
            }else if($pajakonoff=='OFF'){
            	$list_pajak[] = 'off';
            }
            
           
            for($i=0;$i<count($list_pajak);$i++){
            	$pajak = $list_pajak[$i];
            DB::connection()->table("prl_gaji")
                ->where('prl_generate_id', $id)
                ->where('m_lokasi_id', $entitas)
                ->update([
                    "appr_" . $pajak . "_voucher_status" => (1),
                    "appr_" . $pajak . "_voucher_by" => $idUser,
                    "appr_" . $pajak . "_voucher_date" => date('Y-m-d H:i:s'),

                ]);
            $sql = "select (select count(*) from prl_gaji where prl_generate_id = $id_prl ) as count_lokasi,
					(select count(*) from prl_gaji where prl_generate_id = $id_prl and appr_on_direktur_status=1) as count_on,
					(select count(*) from prl_gaji where prl_generate_id = $id_prl and appr_off_direktur_status=1) as count_off
			 ";
            $count = DB::connection()->select($sql);

            $count_lokasi = ($count[0]->count_lokasi) * 2;
            $count_on = $count[0]->count_on;
            $count_off = $count[0]->count_off;

           
            DB::connection()->table("prl_generate_appr")
                ->insert([
                    "pajak" => ($pajak),
                    "m_lokasi_id" => ($entitas),
                    "prl_generate_id" => ($id),
                    "appr_status" => (1),
                    "appr_by" => $idUser,
                    "appr_type" => "voucher",
                    "appr_date" => date("Y-m-d H:i:s")
                ]);
			}
            DB::commit();
            //echo 'Hai';
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Cari'])->with('success', 'Data Gaji Di Ajukan!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function simpan_konfirm_gaji($request)
    {
        DB::beginTransaction();
        try {
        	
            $idUser = Auth::user()->id;
             $id = $request->get('prl_generate');
            $id_prl = $request->get('prl_generate');
            $help = new Helper_function();
            $entitas = ($request->get('entitas'));
            $pajakonoff= $request->get('pajakonoff');
            $list_pajak = array();
            if($pajakonoff==''){
            	$list_pajak[] = 'on';
            	$list_pajak[] = 'off';
            }else if($pajakonoff=='ON'){
            	$list_pajak[] = 'on';
            }else if($pajakonoff=='OFF'){
            	$list_pajak[] = 'off';
            }
            
         
            for($i=0;$i<count($list_pajak);$i++){
            	$pajak = $list_pajak[$i];
	            DB::connection()->table("prl_gaji")
	                ->where('prl_generate_id', $id)
	                ->where('m_lokasi_id', $entitas)
	                ->update([
	                    "appr_" . $pajak . "_keuangan_status" => 1,
	                    "appr_" . $pajak . "_keuangan_by" => $idUser,
	                    "appr_" . $pajak . "_keuangan_date" => date('Y-m-d H:i:s'),

	                ]);

	            DB::connection()->table("prl_generate_appr")
	                ->insert([
	                    "pajak" => ($pajak),
	                    "m_lokasi_id" => ($entitas),
	                    "prl_generate_id" => ($id),
	                    "appr_status" => (1),
	                    "appr_by" => $idUser,
	                    "appr_date" => date("Y-m-d H:i:s"),
	                    "appr_type" => "keuangan",
	                ]);
	                
	           $karyawan = DB::connection()->select("select * from prl_gaji b join prl_gaji_detail a on a.prl_gaji_id = b.prl_gaji_id  where prl_generate_id = $id and b.m_lokasi_id = $entitas"); 
	           $p_karyawan_id =array();   
	           foreach($karyawan as $karyawan){
	          	 if(!in_array($karyawan->p_karyawan_id,$p_karyawan_id))  {
	           	
	           		DB::connection()->table("queue")
		                ->insert([
		                    "panel" => ('admin'),
		                    //"p_karyawan_id" => ($karyawan->p_karyawan_id),
		                    "function_call" => ('slip_simpan'),
		                    "parameter_1" => ($karyawan->p_karyawan_id),
		                    "parameter_2" => $id,
		                    "status_queue" => (0),
		                    "priority" => (9),
		                    "create_by" => $idUser,
		                    "create_date" => date("Y-m-d H:i:s"),
		                ]);
	           		$p_karyawan_id[] =$karyawan->p_karyawan_id;
	           	}
	           }    
	                
			}
            DB::commit();
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Konfirmasi'])->with('success', 'Konfirmasi Transfer Gaji Berhasil di Update!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function save_keterangan_edit_ajuan(Request $request)
    {
    	 DB::beginTransaction();
        try {
        	 DB::connection()->table("prl_generate_karyawan")
	                ->where('prl_generate_karyawan_id', $request->id_generate_karyawan)
	                ->update([
	                    "jabatan_id" => $request->jabatan,
	                  
	                    "m_bank_id" => $request->bank,
	                    "lokasi_id" => $request->lokasi,
	                    "on_off" => $request->pajakonoff,
	                    
	                    "no_rek" => $request->norek,
	                    "keterangan_ajuan" => "tst",
	                ]);
//print_r($request);
        	 DB::commit();
            //return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $request->id_prl . '&menu=Gaji&Cari=EditKaryawan'])->with('success', 'Data Gaji Di Ajukan!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function simpan_konfirm_gaji_hr($request)
    {
        DB::beginTransaction();
        try {
        	//prl_generate=59&menu=Gaji&entitas=3&pajakonoff=&bank=
            $idUser = Auth::user()->id;
            $id = $request->get('prl_generate');
            $id_prl = $request->get('prl_generate');
            $help = new Helper_function();
            $entitas = ($request->get('entitas'));
            $pajakonoff= $request->get('pajakonoff');
            $list_pajak = array();
            if($pajakonoff==''){
            	$list_pajak[] = 'on';
            	$list_pajak[] = 'off';
            }else if($pajakonoff=='ON'){
            	$list_pajak[] = 'on';
            }else if($pajakonoff=='OFF'){
            	$list_pajak[] = 'off';
            }
            
           
            for($i=0;$i<count($list_pajak);$i++){
            	$pajak = $list_pajak[$i];
	            DB::connection()->table("prl_gaji")
	                ->where('prl_generate_id', $id)
	                ->where('m_lokasi_id', $entitas)
	                ->update([
	                    "appr_" . $pajak . "_hr_status" => (1),
	                    "appr_" . $pajak . "_hr_by" => $idUser,
	                    "appr_" . $pajak . "_hr_date" => date('Y-m-d H:i:s'),
	                ]);

	            DB::connection()->table("prl_generate_appr")
	                ->insert([
	                    "pajak" => ($pajak),
	                    "m_lokasi_id" => ($entitas),
	                    "prl_generate_id" => ($id),
	                    "appr_status" => (1),
	                    "appr_by" => $idUser,
	                    "appr_date" => date("Y-m-d H:i:s"),
	                    "appr_type" => "hr",
	                ]);
            }
            DB::commit();
            return redirect()->route('be.previewgaji', ['non_ajuan', 'prl_generate=' . $id_prl . '&menu=Gaji&Cari=Cari'])->with('success', 'Data Gaji Di Ajukan!');
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
    public function preview_non_ajuan(Request $request, $subpage)
    { 
    	 return redirect()->route('be.transaksi', ['non_ajuan',$subpage]);
    }
    public function preview_ajuan(Request $request, $subpage)
    { 
    	 return redirect()->route('be.transaksi', ['ajuan',$subpage]);
    }
    public function preview_direksi(Request $request, $subpage)
    { 
    	 return redirect()->route('be.transaksi', ['direksi',$subpage]);
    }
    public function save_change_nominal(Request $request)
    {
        DB::beginTransaction();
        try {
            $idUser = Auth::user()->id;
            $help = new Helper_function();


            $id_nominal = $request->get('id_nominal');
            $keterangan = $request->get('keterangan');
            $nominal_now = $request->get('nominal_now');
            $id_karyawan = $request->get('id_karyawan');
            $id_prl = $request->get('id_prl');
            $field = $request->get('field');
            if ($id_nominal != -1) {

                DB::connection()->table("prl_gaji_detail")
                    ->where('prl_gaji_detail_id', $id_nominal)
                    ->update([
                        "nominal" => ($nominal_now),
                        "keterangan" => ($keterangan),
                        "update_date" => date('Y-m-d H:i:s'),

                    ]);
            } else {
                if ($field == 'KKB') {
                    $type = 5;
                    $id = 9;
                } else if ($field == 'ASA') {
                    $id = 21;
                    $type = 5;
                } else if ($field == 'sewa_kost') {
                    $id = 16;
                    $type = 5;
                } else if ($field == 'telat') {
                    $id = 12;
                    $type = 5;
                } else if ($field == 'potfinger') {
                    $id = 23;
                    $type = 5;
                } else if ($field == 'potpm') {
                    $id = 24;
                    $type = 5;
                } else if ($field == 'potizin') {
                    $id = 22;
                    $type = 5;
                } else if ($field == 'absen') {
                    $id = 13;
                    $type = 5;
                } else  if ($field == 'iuran_bpjskes') {
                    $id = 14;
                    $type = 5;
                } else  if ($field == 'iuran_bpjsket') {
                    $id = 15;
                    $type = 5;
                } else if ($field == 'zakat') {
                    $type = 5;

                    $id = 18;
                } else if ($field == 'infaq') {
                    $id = 18;
                    $type = 5;
                } else if ($field == 'korekmin') {
                    $id = 17;
                    $type = 5;
                } else if ($field == 'tunjangan_grade') {
                    $id = 11;
                    $type = 3;
                } else if ($field == 'gapok') {
                    $id = 17;
                    $type = 3;
                } else if ($field == 'lembur') {
                    $id = 15;
                    $type = 3;
                } else if ($field == 'tunjangan_bpjskes') {
                    $id = 12;
                    $type = 3;
                } else if ($field == 'tunjangan_bpjsket') {
                    $id = 13;
                    $type = 3;
                } else if ($field == 'tunjangan_kost') {

                    $type = 3;
                    $id = 14;
                } else if ($field == 'korekplus') {
                    $type = 3;
                    $id = 16;
                }else if ($field == 'pajak') {
                    $type = 5;
                    $id = 20;
                }else if ($field == 'bonus') {
                    $type = 3;
                    $id = 19;
                }




                if ($type == 1) {
                    $row = 'gaji_absen_id';
                } else if ($type == 2) {
                    $row = 'prl_tunjangan_id';
                } else if ($type == 3) {
                    $row = 'm_tunjangan_id';
                } else if ($type == 4) {
                    $row = 'prl_potongan_id';
                } else if ($type == 5) {
                    $row = 'm_potongan_id';
                }
                $id_generate = $id_prl;
                $sqluser = "SELECT a.m_lokasi_id, (select count(*) from prl_gaji b  where a.m_lokasi_id = b.m_lokasi_id and prl_generate_id = $id_generate ) as jumlah, (select max(prl_gaji_id)  from prl_gaji b) as max FROM p_karyawan_pekerjaan a where p_karyawan_id = $id_karyawan ";
                //////echo $sqluser;
                $karyawan = DB::connection()->select($sqluser);



                if (!$karyawan[0]->jumlah) {
                    DB::connection()->table("prl_gaji")

                        ->insert([
                            "m_lokasi_id" => $karyawan[0]->m_lokasi_id,
                            "prl_gaji_id" => $karyawan[0]->max + 1,
                            "prl_generate_id" => $id_generate,

                            "active" => 1,
                            "create_date" => date("Y-m-d")
                        ]);
                    $id_prl_Gaji = $karyawan[0]->max + 1;
                } else {
                    $id_lokasi = $karyawan[0]->m_lokasi_id;
                    $sqluser = "select (prl_gaji_id) from prl_gaji b  where m_lokasi_id = $id_lokasi and prl_generate_id = $id_generate  ";
                    $karyawan = DB::connection()->select($sqluser);
                    $id_prl_Gaji = $karyawan[0]->prl_gaji_id;
                }

                $prl_detail = DB::connection()->select("select * from prl_gaji_detail where prl_gaji_id = $id_prl_Gaji and type=$type and p_karyawan_id = $id_karyawan and $row=$id");
                if (count($prl_detail)) {
                    DB::connection()->table("prl_gaji_detail")
                        ->where('prl_gaji_detail_id', $prl_detail[0]->prl_gaji_detail_id)
                        ->update([
                            "nominal" => $nominal_now,
                            "update_date" => date("Y-m-d H:i:s")
                        ]);
                } else {


                    DB::connection()->table("prl_gaji_detail")

                        ->insert([

                            "prl_gaji_id" => $id_prl_Gaji,
                            "type" => $type,
                            "p_karyawan_id" => $id_karyawan,
                            $row => $id,

                            "nominal" => $nominal_now,
                            "create_date" => date("Y-m-d H:i:s")
                        ]);
                }
            }
         $tunjangan = "select *
			from m_tunjangan a 
			left join prl_tunjangan b on a.m_tunjangan_id = b.m_tunjangan_id
			where b.p_karyawan_id = $id_karyawan and b.active=1";
        //////echo $potongan;
        $tunjangan = DB::connection()->select($tunjangan);
        $gapok = 0;
        //print_r($tunjanga)
        foreach ($tunjangan as $tunjangan) {
            if ($tunjangan->is_gapok) $gapok += $tunjangan->nominal;
            ////echo '<strong>'.$tunjangan->nama.': </strong>'.$help->rupiah($tunjangan->nominal);
            ////echo '<br>';
        }
        $sql = "Select p_karyawan.nama, p_karyawan.p_karyawan_id ,c.nama as nm_pangkat,c.m_pangkat_id from p_karyawan 
				join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id 
				join m_jabatan b on a.m_jabatan_id = b.m_jabatan_id 
				join m_pangkat c on c.m_pangkat_id = b.m_pangkat_id 
    			where p_karyawan.p_karyawan_id = $id_karyawan
    			
    	";
        $karyawan = DB::connection()->select($sql);
        $help = new Helper_function();
        $id_pangkat = $karyawan[0]->m_pangkat_id;  
        $sql = "SELECT *,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='absen') as nominal_absen,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='izin') as nominal_izin,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='alpha') as nominal_alpha,
            	(select nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='fingerprint') as nominal_fingerprint,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='absen') as type_nominal_absen,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='izin') as type_nominal_izin,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='alpha') as type_nominal_alpha,
            	(select type_nominal from m_potongan_absen a where a.m_pangkat_id = m_pangkat.m_pangkat_id and type_absen='fingerprint') as type_nominal_fingerprint
            	
            	FROM m_pangkat where active=1 and m_pangkat_id = $id_pangkat";
        $potongan_absen = DB::connection()->select($sql);
        if ($potongan_absen[0]->type_nominal_absen == 1) {
            $potabsen = $potongan_absen[0]->nominal_absen;
        } else if ($potongan_absen[0]->type_nominal_absen == 2) {
            $potabsen = ($potongan_absen[0]->nominal_absen / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_absen == 3) {
            $potabsen = ($gapok / 22);
        }
        if ($potongan_absen[0]->type_nominal_absen == 1) {
            $potmendahului = $potongan_absen[0]->nominal_absen;
        } else if ($potongan_absen[0]->type_nominal_absen == 2) {
            $potmendahului = ($potongan_absen[0]->nominal_absen / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_absen == 3) {
            $potmendahului = ($gapok / 22);
        }

        if ($potongan_absen[0]->type_nominal_izin == 1) {
            $potizin = $potongan_absen[0]->nominal_izin;
        } else if ($potongan_absen[0]->type_nominal_izin == 2) {
            $potizin = ($potongan_absen[0]->nominal_izin / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_izin == 3) {
            $potizin = ($gapok / 22);
        }

        if ($potongan_absen[0]->type_nominal_alpha == 1) {
            $potalpha = $potongan_absen[0]->nominal_alpha;
        } else if ($potongan_absen[0]->type_nominal_alpha == 2) {
            $potalpha = ($potongan_absen[0]->nominal_alpha / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_alpha == 3) {
            $potalpha = ($gapok / 22);
        }
        if ($potongan_absen[0]->type_nominal_fingerprint == 1) {
            $potfingerprint = $potongan_absen[0]->nominal_fingerprint;
        } else if ($potongan_absen[0]->type_nominal_fingerprint == 2) {
            $potfingerprint = ($potongan_absen[0]->nominal_fingerprint / 100 * $gapok);
        } else if ($potongan_absen[0]->type_nominal_fingerprint == 3) {
            $potfingerprint = ($gapok / 22);
        }
        $datapot['absen'] = $potabsen;
        $datapot['izin'] = $potizin;
        $datapot['mendahului'] = $potmendahului;
        $datapot['alpha'] = $potalpha;
        $datapot['fingerprint'] = $potfingerprint;
        
        echo json_encode($datapot);
        
            DB::commit();
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }
    }
   
    public function exportsAjuan($param)
    {

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $entitas = $param['entitas'];
        $id_prl = $param['id_prl'];
        $hisentitas = $param['hisentitas'];

        $perigen = $param['perigen'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $data_row = $param['data_row'];
        $sudah_appr_voucher = $param['sudah_appr_voucher'];


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);


        $i = 0;


		
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NO');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NAMA');
       
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'BANK');
        $i++;
        
        
        $sheet->setCellValue($help->toAlpha($i) . '1', 'REKENING');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NOMINAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'KETERANGAN');
        $i++;


        $rows = 2;



        if (!empty($list_karyawan)) {

            $no = 0;
            $nominal = 0;
            $total_nominal=0;
			$return = $help->preview_gaji($data_row, 2);
            $total = $return['total'];
            foreach ($list_karyawan as $list_karyawan) {
                $total_all = 0;
                  $return = $help->preview_gaji($data_row, 1, $total, $data, $list_karyawan, $sudah_appr_voucher, $id_prl);
                   $total = $return['total'];
                    $nominal=0;
                    if (isset($return['field'][$list_karyawan->p_karyawan_id])) {


                            $field = $return['field'][$list_karyawan->p_karyawan_id];
                            if($list_karyawan->gajian_type==-1){
							$nominal = $field['thp_karyawan'];
							}else {
                            
                               $nominal = $field['thp_karyawan']+ $field['korekmin'] - $field['korekplus'];
                           
                            
                           
                            if (isset($data[$list_karyawan->p_karyawan_id]['Entitas_id']['Koreksi(+)'])) {
                                $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas_id']['Koreksi(+)'];
                                if ($nmlok==$request->get('entitas')) {
                                    $nominal += $field['korekplus'];
                                }
                            }
                            if (isset($data[$list_karyawan->p_karyawan_id]['Entitas_id']['Koreksi(-)'])) {
                                $nmlok = $data[$list_karyawan->p_karyawan_id]['Entitas_id']['Koreksi(-)'];
                              if ($nmlok==$request->get('entitas')) {
                                    $nominal -= $field['korekmin'];
                                } 
                            }
                            }
                            
                        }
				  $total_nominal +=$nominal;
				  	

                //$nominal +=(($grade +$gapok+$tunjangan)-$potongan); 
                $no++;
                $sheet->setCellValue('A' . $rows, $no);
                $sheet->setCellValue('B' . $rows, $list_karyawan->nama_lengkap);
                $sheet->setCellValue('C' . $rows, $list_karyawan->nama_bank);
                $sheet->setCellValue('D' . $rows, $list_karyawan->norek);
						$sheet->setCellValueExplicit('D' . $rows, $list_karyawan->norek, DataType::TYPE_STRING);
                
                $sheet->setCellValue('E' . $rows, $nominal);
                		$sheet->getStyle('E' . $rows)->getNumberFormat()->setFormatCode('Rp ###,###,##0');
						$sheet->setCellValueExplicit('E' . $rows, $nominal, DataType::TYPE_NUMERIC);
               
                $sheet->setCellValue('F' . $rows, $list_karyawan->keterangan_ajuan);
            

                //echo substr($absen->a,0,5);die;
                $rows++;
            }
            $sheet->setCellValue('A' . $rows, 'Jumlah');
            $sheet->mergeCells('A' . $rows.':D' . $rows.'');
            $sheet->getStyle('E' . $rows)->getNumberFormat()->setFormatCode('Rp ###,###,##0');
						$sheet->setCellValueExplicit('E' . $rows, $total_nominal, DataType::TYPE_NUMERIC);
						$style = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );
		$sheet->getStyle('A' . $rows.':D' . $rows)->applyFromArray($style);
            $sheet->setCellValue('F' . $rows, '');
            $sheet->setCellValue('G' . $rows, '');
            $sheet->setCellValue('H' . $rows, '');
        }

        if (isset($hisentitas->nama))
            $nama = $hisentitas->nama;
        else
            $nama = '';
		$iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);
        
        $type = 'xlsx';
        $fileName = "Ajuan Entitas ".$iduser[0]->name." " . $nama . ' ' . str_replace(':', '', str_replace('|', '', $perigen)) .date('YmdHis'). "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    
    public function exportsPresensi($param)
    {

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $id_prl = $param['id_prl'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $hisentitas = $param['hisentitas'];
        //$hisentitas = $hisentitas[0];
        $perigen = $param['perigen'];


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NAMA PEGAWAI');
        $sheet->setCellValue('B1', 'Masa Kerja');
        $sheet->setCellValue('C1', 'THP');
        $i = 3;



        $sheet->setCellValue($help->toAlpha($i) . '1', 'PANGKAT');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'DATA ABSENSI');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'POTONGAN ABSEN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'JAM LEMBUR HARI KERJA');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jumlah');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'JAM LEMBUR HARI LIBUR');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jumlah');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Total Lembur');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TERLAMBAT');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Potongan Fingerprint');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'cek');
        $i++;

        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '');
        $i = 3;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Sakit');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IHK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'CUTI');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IPG');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IPG');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '1 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '>=2 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'COUNT');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'SUM');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '8 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '9 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '>=10 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Jumlah');
        $i++;

        $sheet->setCellValue($help->toAlpha($i) . '2', 'JUMLAH');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'JUMLAH');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $rows = 3;

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);

        if (!empty($list_karyawan)) {

            foreach ($list_karyawan as $list_karyawan) {
                $total_all = 0;
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                $help->rupiah($tunjangan = $tunkost + $korekplus + $tunket + $tunkes + $lembur);

                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin'] : $telat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $zakat = $data[$list_karyawan->p_karyawan_id]['Zakat'] : $zakat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $infaq = $data[$list_karyawan->p_karyawan_id]['Infaq'] : $infaq = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : $korekmin = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan KKB']) ? $kkp = $data[$list_karyawan->p_karyawan_id]['Potongan KKB'] : $kkp = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : $asa = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $pajak = $data[$list_karyawan->p_karyawan_id]['Pajak'] : $pajak = 0);
                $help->rupiah($potongan = $telat + $absen + $sewakost + $ibpjs + $ibpjt + $zakat + $infaq + $korekmin + $kkp + $asa + $pajak);
                $grade = isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok']) ? $data[$list_karyawan->p_karyawan_id]['Gaji Pokok'] : 0;
                $gapok = isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']) ? $data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'] : 0;

                $sheet->setCellValue('A' . $rows, $list_karyawan->nama_lengkap);
                $sheet->setCellValue('B' . $rows, $list_karyawan->umur);
                $sheet->setCellValue('C' . $rows, ($grade + $gapok + $tunjangan) - $potongan);
                $sheet->setCellValue('D' . $rows, $list_karyawan->nmpangkat);
                $i = 4;


                $sheet->setCellValue($help->toAlpha($i) . $rows, ($absen = isset($data[$list_karyawan->p_karyawan_id]['Hari Absen']) ? $data[$list_karyawan->p_karyawan_id]['Hari Absen'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($sakit = isset($data[$list_karyawan->p_karyawan_id]['Sakit']) ? $data[$list_karyawan->p_karyawan_id]['Sakit'] : 0));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, ($ihk = isset($data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($cuti = isset($data[$list_karyawan->p_karyawan_id]['Cuti']) ? $data[$list_karyawan->p_karyawan_id]['Cuti'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($ipg = isset($data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas']) ? $data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($tk = isset($data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan']) ? $data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($sakit + $ihk + $cuti + $ipg + $tk));
                $i++;


                $sheet->setCellValue($help->toAlpha($i) . $rows, ($potizin = isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Izin'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($potabsen = isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($potizin + $potabsen));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur1 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur2 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['COUNT Kerja']) ? $data[$list_karyawan->p_karyawan_id]['COUNT Kerja'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['SUM Kerja']) ? $data[$list_karyawan->p_karyawan_id]['SUM Kerja'] : 0));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur8 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam'] : $lembur = 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur9 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam'] : $lembur = 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur10 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam'] : 0));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, $lembur8 + $lembur10 + $lembur9);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ((isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur']) ? $data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur'] : 0)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Jam Lembur']) ? $data[$list_karyawan->p_karyawan_id]['Jam Lembur'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (($lembur = isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $data[$list_karyawan->p_karyawan_id]['Lembur'] : 0)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Terlambat']) ? $data[$list_karyawan->p_karyawan_id]['Terlambat'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (($telat = isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Telat'] : 0)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Fingerprint']) ? $data[$list_karyawan->p_karyawan_id]['Fingerprint'] : 0));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (($finger = isset($data[$list_karyawan->p_karyawan_id]['Total Fingerprint']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : 0)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (($telat + $finger)));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($absen + $sakit + $ihk + $cuti + $ipg + $tk));
                $i++;

                //echo substr($absen->a,0,5);die;
                $rows++;
            }
        }$iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);


        $type = 'xlsx';
        $fileName = "Presensi Gaji ".$iduser[0]->name." " . str_replace(':', '', str_replace('|', '', $perigen)) .date('YmdHis'). "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function exportsPresensi_Pekanan($param)
    {

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $id_prl = $param['id_prl'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $hisentitas = $param['hisentitas'];
        //$hisentitas = $hisentitas[0];
        $perigen = $param['perigen'];


        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NAMA PEGAWAI');
        $sheet->setCellValue('B1', 'GAJI TOTAL PERBULAN');
        $sheet->setCellValue('C1', 'TOTAL KEHADIRAN');
        $i = 3;



        $sheet->setCellValue($help->toAlpha($i) . '1', 'Keterangan');
        $i++; //1
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //2
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //3
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //4
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //5
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //6
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //7
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++; //8	
        $sheet->setCellValue($help->toAlpha($i) . '1', 'JAM LEMBUR HARI KERJA');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'JAM LEMBUR HARI LIBUR');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Total Lembur');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'cek');
        $i++;

        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '');
        $i = 3;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Sakit');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IHK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'ALPHA');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Terlambat');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IDT');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'IPM');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'PM');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'Total');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '1 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '>=2 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'NOMINAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '8 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '9 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '>=10 jam');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'TOTAL');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', 'NOMINAL');
        $i++;
        $rows = 3;

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);

        if (!empty($list_karyawan)) {

            foreach ($list_karyawan as $list_karyawan) {
                $total_all = 0;
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Upah Harian']) ? $uh = $data[$list_karyawan->p_karyawan_id]['Upah Harian'] : $uh = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                $help->rupiah($tunjangan = $tunkost + $korekplus + $tunket + $tunkes + $lembur);

                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat/ Ijin'] : $telat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $zakat = $data[$list_karyawan->p_karyawan_id]['Zakat'] : $zakat = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $infaq = $data[$list_karyawan->p_karyawan_id]['Infaq'] : $infaq = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : $korekmin = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan KKB']) ? $kkp = $data[$list_karyawan->p_karyawan_id]['Potongan KKB'] : $kkp = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $asa = $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : $asa = 0);
                $help->rupiah(isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $pajak = $data[$list_karyawan->p_karyawan_id]['Pajak'] : $pajak = 0);
                $help->rupiah($potongan = $telat + $absen + $sewakost + $ibpjs + $ibpjt + $zakat + $infaq + $korekmin + $kkp + $asa + $pajak);
                $ihk = isset($data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Izin Hitung Kerja'] : 0;
                $ipg = isset($data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas']) ? $data[$list_karyawan->p_karyawan_id]['Izin Perjalanan Dinas'] : 0;
                $cuti = 0;


                $sheet->setCellValue('A' . $rows, $list_karyawan->nama_lengkap);
                $sheet->setCellValue('B' . $rows, $uh * 22);

                $absen = isset($data[$list_karyawan->p_karyawan_id]['Hari Absen']) ? $data[$list_karyawan->p_karyawan_id]['Hari Absen'] : 0;
                $sheet->setCellValue('C' . $rows, $absen);

                $sakit = isset($data[$list_karyawan->p_karyawan_id]['Sakit']) ? $data[$list_karyawan->p_karyawan_id]['Sakit'] : 0;
                $sheet->setCellValue('D' . $rows, $sakit);
                $i = 4;


                $sheet->setCellValue($help->toAlpha($i) . $rows, $ihk + $ipg);
                $i++;

                $tk = isset($data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan']) ? $data[$list_karyawan->p_karyawan_id]['Tanpa Keterangan'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $tk);
                $i++;

                $terlambat = isset($data[$list_karyawan->p_karyawan_id]['Terlambat']) ? $data[$list_karyawan->p_karyawan_id]['Terlambat'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $terlambat);
                $i++;

                $idt = isset($data[$list_karyawan->p_karyawan_id]['Izin Datang Terlambat']) ? $data[$list_karyawan->p_karyawan_id]['Izin Datang Terlambat'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $idt);
                $i++;

                $ipm = isset($data[$list_karyawan->p_karyawan_id]['Izin Pulang Mendahului ']) ? $data[$list_karyawan->p_karyawan_id]['Izin Pulang Mendahului '] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $ipm);
                $i++;

                $pm = isset($data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin']) ? $data[$list_karyawan->p_karyawan_id]['Pulang Mendahului Tanpa Izin'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $pm);
                $i++;


                $sheet->setCellValue($help->toAlpha($i) . $rows, ($tk + $ihk + $ipg + $sakit));
                $i++;

                $lembur1 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur 1 Jam'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur1));
                $i++;

                $lembur2 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur >=2 Jam'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur2));
                $i++;

                $total_lembur_kerja = isset($data[$list_karyawan->p_karyawan_id]['SUM Kerja']) ? $data[$list_karyawan->p_karyawan_id]['SUM Kerja'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($total_lembur_kerja));
                $i++;

                $nominal_lembur_kerja = isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja']) ? $data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Kerja'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($nominal_lembur_kerja));
                $i++;

                $lembur8 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam']) ? $lembur8 = $data[$list_karyawan->p_karyawan_id]['Lembur 8 Jam'] : $lembur8 = 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur8));
                $i++;

                $lembur9 = isset($data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam']) ? $lembur9 = $data[$list_karyawan->p_karyawan_id]['Lembur 9 Jam'] : $lembur9 = 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur9));
                $i++;

                $lembur10 = isset($data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam']) ? $data[$list_karyawan->p_karyawan_id]['Lembur >=10 Jam'] : 0;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur10));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur8 + $lembur10 + $lembur9));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, (isset($data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur']) ? $data[$list_karyawan->p_karyawan_id]['Jumlah Lembur Libur'] : 0));
                $i++;

                $sheet->setCellValue($help->toAlpha($i) . $rows, ($lembur));
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, ($absen + $sakit + $ihk + $cuti + $ipg + $tk));
                $i++;

                //echo substr($absen->a,0,5);die;
                $rows++;
            }
        }$iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);

	
        $type = 'xlsx';
        $fileName = "Presensi Gaji ".$iduser[0]->name." " . str_replace(':', '', str_replace('|', '', $perigen)) .date('YmdHis'). "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function exportsPajak($param)
    {
        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 0;

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $id_prl = $param['id_prl'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $hisentitas = $param['hisentitas'];
        //$hisentitas = $hisentitas[0];
        $perigen = $param['perigen'];





        $sheet->setCellValue($help->toAlpha($i) . '1', 'No');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Umur Kerja');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Masuk');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Perusahaan');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Pajak');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL PENDAPATAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL TUNJANGAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL POTONGAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL');
        $i++;
        $rows = 2;


        if (!empty($list_karyawan)) {
            $no = 0;
            foreach ($list_karyawan as $list_karyawan) {
                if ($list_karyawan->pajak_onoff == 'ON') {
                    $no++;
                    $total_all = 0;

                    $i = 0;

                    $sheet->setCellValue($help->toAlpha($i) . $rows, $no);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_lengkap);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->umur);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->tgl_awal);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmlokasi);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->pajak_onoff);
                    $i++;



                    isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok']) ? $grade = $data[$list_karyawan->p_karyawan_id]['Gaji Pokok'] : $grade = 0;
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']) ? $gapok = $data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'] : $gapok = 0);

                    (isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                    ($tunjangan = $tunkost + $korekplus + $tunket + $tunkes);

                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat'] : $telat = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']) ? $potfinger = $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : $potfinger = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']) ? $potpm = $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'] : $potpm = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin']) ? $potizin = $data[$list_karyawan->p_karyawan_id]['Potongan Izin'] : $potizin = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                    ($zakat = isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $data[$list_karyawan->p_karyawan_id]['Zakat'] : 0);
                    ($infaq = isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $data[$list_karyawan->p_karyawan_id]['Infaq'] : 0);
                    ($korekmin = isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : 0);
                    ($kkp = isset($data[$list_karyawan->p_karyawan_id]['Potongan KKB']) ? $data[$list_karyawan->p_karyawan_id]['Potongan KKB'] : 0);
                    ($asa = isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : 0);
                    ($pajak = isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $data[$list_karyawan->p_karyawan_id]['Pajak'] : 0);

                    $sheet->setCellValue($help->toAlpha($i) . $rows, (($grade + $gapok + $lembur)));
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, ($tunjangan));
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $potongan = $telat + $absen + $potizin + $potfinger + $potpm);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, ($grade + $gapok + $lembur + $tunjangan) - $potongan);
                    $i++;

                    //echo substr($absen->a,0,5);die;
                    $rows++;
                }
            }
        }

		$iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);
        
        $type = 'xlsx';
        $fileName = "Preview Pajak ".$iduser[0]->name." " . str_replace(':', '', str_replace('|', '', $perigen)) .date('YmdHis'). "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function exportsPajakRp($param)
    {
        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 0;

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        $sheet->getColumnDimension('T')->setAutoSize(true);
        $sheet->getColumnDimension('U')->setAutoSize(true);
        $sheet->getColumnDimension('V')->setAutoSize(true);
        $sheet->getColumnDimension('W')->setAutoSize(true);
        $sheet->getColumnDimension('X')->setAutoSize(true);
        $sheet->getColumnDimension('Y')->setAutoSize(true);
        $sheet->getColumnDimension('Z')->setAutoSize(true);
        $sheet->getColumnDimension('AA')->setAutoSize(true);
        $sheet->getColumnDimension('AB')->setAutoSize(true);
        $sheet->getColumnDimension('AC')->setAutoSize(true);
        $sheet->getColumnDimension('AD')->setAutoSize(true);
        $sheet->getColumnDimension('AE')->setAutoSize(true);
        $sheet->getColumnDimension('AF')->setAutoSize(true);
        $sheet->getColumnDimension('AG')->setAutoSize(true);
        $sheet->getColumnDimension('AH')->setAutoSize(true);
        $sheet->getColumnDimension('AI')->setAutoSize(true);
        $sheet->getColumnDimension('AJ')->setAutoSize(true);
        $sheet->getColumnDimension('AK')->setAutoSize(true);
        $sheet->getColumnDimension('AL')->setAutoSize(true);
        $sheet->getColumnDimension('AM')->setAutoSize(true);
        $sheet->getColumnDimension('AN')->setAutoSize(true);

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $id_prl = $param['id_prl'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $hisentitas = $param['hisentitas'];
        //$hisentitas = $hisentitas[0];
        $perigen = $param['perigen'];





        $sheet->setCellValue($help->toAlpha($i) . '1', 'No');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Umur Kerja');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Masuk');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Perusahaan');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Pajak');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL PENDAPATAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL TUNJANGAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL POTONGAN');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'TOTAL');
        $i++;
        $rows = 2;


        if (!empty($list_karyawan)) {
            $total['TOTAL PENDAPATAN']  = 0;
            $total['TOTAL TUNJANGAN']  = 0;
            $total['TOTAL POTONGAN']  = 0;
            $total['TOTAL ALL']  = 0;
            $no = 0;
            foreach ($list_karyawan as $list_karyawan) {
                if ($list_karyawan->pajak_onoff == 'ON') {
                    $no++;
                    $total_all = 0;

                    $i = 0;

                    $sheet->setCellValue($help->toAlpha($i) . $rows, $no);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_lengkap);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->umur);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->tgl_awal);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmlokasi);
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->pajak_onoff);
                    $i++;



                    isset($data[$list_karyawan->p_karyawan_id]['Gaji Pokok']) ? $grade = $data[$list_karyawan->p_karyawan_id]['Gaji Pokok'] : $grade = 0;
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Grade']) ? $gapok = $data[$list_karyawan->p_karyawan_id]['Tunjangan Grade'] : $gapok = 0);

                    (isset($data[$list_karyawan->p_karyawan_id]['Lembur']) ? $lembur = $data[$list_karyawan->p_karyawan_id]['Lembur'] : $lembur = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan']) ? $tunkes = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Kesehatan'] : $tunkes = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan']) ? $tunket = $data[$list_karyawan->p_karyawan_id]['Tunjangan BPJS Ketenaga Kerjaan'] : $tunket = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Koreksi(+)']) ? $korekplus = $data[$list_karyawan->p_karyawan_id]['Koreksi(+)'] : $korekplus = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']) ? $tunkost = $data[$list_karyawan->p_karyawan_id]['Tunjangan Kost']    : $tunkost = 0);
                    ($tunjangan = $tunkost + $korekplus + $tunket + $tunkes);

                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Telat']) ? $telat = $data[$list_karyawan->p_karyawan_id]['Potongan Telat'] : $telat = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint']) ? $potfinger = $data[$list_karyawan->p_karyawan_id]['Potongan Fingerprint'] : $potfinger = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului']) ? $potpm = $data[$list_karyawan->p_karyawan_id]['Potongan Pulang Mendahului'] : $potpm = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Izin']) ? $potizin = $data[$list_karyawan->p_karyawan_id]['Potongan Izin'] : $potizin = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Potongan Absen']) ? $absen = $data[$list_karyawan->p_karyawan_id]['Potongan Absen'] : $absen = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Sewa Kost']) ? $sewakost = $data[$list_karyawan->p_karyawan_id]['Sewa Kost'] : $sewakost = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan']) ? $ibpjs = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Kesehatan'] : $ibpjs = 0);
                    (isset($data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan']) ? $ibpjt = $data[$list_karyawan->p_karyawan_id]['Iuran BPJS Ketenaga Kerjaan'] : $ibpjt = 0);
                    ($zakat = isset($data[$list_karyawan->p_karyawan_id]['Zakat']) ? $data[$list_karyawan->p_karyawan_id]['Zakat'] : 0);
                    ($infaq = isset($data[$list_karyawan->p_karyawan_id]['Infaq']) ? $data[$list_karyawan->p_karyawan_id]['Infaq'] : 0);
                    ($korekmin = isset($data[$list_karyawan->p_karyawan_id]['Koreksi(-)']) ? $korekmin = $data[$list_karyawan->p_karyawan_id]['Koreksi(-)'] : 0);
                    ($kkp = isset($data[$list_karyawan->p_karyawan_id]['Potongan KKB']) ? $data[$list_karyawan->p_karyawan_id]['Potongan KKB'] : 0);
                    ($asa = isset($data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa']) ? $data[$list_karyawan->p_karyawan_id]['Potongan Koperasi Asa'] : 0);
                    ($pajak = isset($data[$list_karyawan->p_karyawan_id]['Pajak']) ? $data[$list_karyawan->p_karyawan_id]['Pajak'] : 0);

                    $sheet->setCellValue($help->toAlpha($i) . $rows, $help->rupiah(($grade + $gapok + $lembur)));
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $help->rupiah($tunjangan));
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $help->rupiah($potongan = $telat + $absen + $potizin + $potfinger + $potpm));
                    $i++;
                    $sheet->setCellValue($help->toAlpha($i) . $rows, $help->rupiah(($grade + $gapok + $lembur + $tunjangan) - $potongan));
                    $i++;
                    $total['TOTAL PENDAPATAN'] += ($grade + $gapok + $lembur);
                    $total['TOTAL TUNJANGAN'] += $tunjangan;
                    $total['TOTAL POTONGAN'] += $potongan;
                    $total['TOTAL ALL'] += ($grade + $gapok + $lembur + $tunjangan) - $potongan;
                    //echo substr($absen->a,0,5);die;
                    $rows++;
                }
            }
            $i = 0;

            $sheet->setCellValue($help->toAlpha($i) . $rows, $no);
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, 'TOTAL');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, $help->rupiah($total['TOTAL PENDAPATAN']));
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, $help->rupiah($total['TOTAL TUNJANGAN']));
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, $help->rupiah($total['TOTAL POTONGAN']));
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, $help->rupiah($total['TOTAL ALL']));
            $i++;
        }
        $iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);


        $type = 'xlsx';
        $fileName = "Preview Pajak ".$iduser[0]->name." " . str_replace(':', '', str_replace('|', '', $perigen)) .date('YmdHis'). "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    public function exportsGaji($param)
    {

        $data = $param['data'];
        $user = $param['user'];
        $help = $param['help'];
        $id_prl = $param['id_prl'];
        $periode = $param['periode'];
        $periode_absen = $param['periode_absen'];
        $request = $param['request'];
        $list_karyawan = $param['list_karyawan'];
        $hisentitas = $param['hisentitas'];
        //$hisentitas = $hisentitas[0];
        $perigen = $param['perigen'];
		$data_row = $param['data_row'];
		$data_head = $param['data_head'];
        $sudah_appr_voucher = $param['sudah_appr_voucher'];

        $help = new Helper_function();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 0;

		$return = $help->preview_gaji($data_row, 2);
        $total = $return['total'];

        $sheet->setCellValue($help->toAlpha($i) . '1', 'No');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'NIK');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Nama');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Jabatan');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Tanggal Masuk');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Perusahaan');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Pajak');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Job Weight');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '1', 'Grade');
       $i++;
        for($x=0;$x<count($data_head);$x++){
        	$awal = $i;
        	$akhir = $i+$data_head[$x][1]-1;
        	$i=$akhir;
        $sheet->setCellValue($help->toAlpha($awal) . '1', $data_head[$x][0]);
        	echo $help->toAlpha($awal).'1:'.$help->toAlpha($akhir).'1';
        //$sheet->setCellValueByColumnAndRow($awal, $akhir, $data_head[$x][0]);
    	$sheet->mergeCells($help->toAlpha($awal).'1:'.$help->toAlpha($akhir).'1');
    	$style = array(
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            )
        );
		$sheet->getStyle($help->toAlpha($awal).'1:'.$help->toAlpha($akhir).'1')->applyFromArray($style);
    	$i++;
		}
		//die;

        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C2', '');
        $i = 3;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        $sheet->setCellValue($help->toAlpha($i) . '2', '');
        $i++;
        
        for($x=0;$x<count($data_row);$x++){
        	
        $sheet->setCellValue($help->toAlpha($i) . '2', $data_row[$x][0]);
        $i++;
        }
        
        $rows = 3;
		for($x=0;$x<count($data_row)+15;$x++){
        $sheet->getColumnDimension($help->toAlpha($x))->setAutoSize(true);
		}

        if (!empty($list_karyawan)) {
            $no = 0;
            $return = $help->preview_gaji($data_row, 2);
            $total = $return['total'];
            foreach ($list_karyawan as $list_karyawan) {
                $no++;
                $total_all = 0;



                $i = 0;

                $sheet->setCellValue($help->toAlpha($i) . $rows, $no);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nik);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nama_lengkap);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmjabatan);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->tgl_awal);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->nmlokasi);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->pajak_onoff);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->jobweight);
                $i++;
                $sheet->setCellValue($help->toAlpha($i) . $rows, $list_karyawan->grade);
                $i++;
                
                       
                
                for ($x = 0; $x < count($data_row); $x++) {
		            $row1 = $data_row[$x][0];
		            $row2 = $data_row[$x][1];
		            $row3 = $data_row[$x][2];
					$row4 = $data_row[$x][3];
					$row5 = isset($data_row[$x][4])?$data_row[$x][4]:array();
					
                if($row3==1 or $row3==2){
                	if($row4=='absensi'){
 						$sheet->setCellValue($help->toAlpha($i) . $rows, ((isset($data[$list_karyawan->p_karyawan_id][$row1]) ? $$row2 = $data[$list_karyawan->p_karyawan_id][$row1] : $$row2 = 0)));
 						               		
                	}else{
                		$sheet->getStyle($help->toAlpha($i) . $rows)->getNumberFormat()->setFormatCode('Rp ###,###,##0');
				$sheet->setCellValueExplicit($help->toAlpha($i) . $rows, ((isset($data[$list_karyawan->p_karyawan_id][$row1]) ? $$row2 = $data[$list_karyawan->p_karyawan_id][$row1] : $$row2 = 0)), DataType::TYPE_NUMERIC);
 
                	}
                $i++;
                }else
                if($row3==3 or $row3==4){
					$$row2= 0;
						for($i1=0;$i1<count($row5);$i1++){
							$operator = $row5[$i1][0];
							for($i2=0;$i2<count($row5[$i1][1]);$i2++){
								$type_field = $row5[$i1][1][$i2][0];
								$row = $row5[$i1][1][$i2][1];
						//print_r($row5[$i1][1][$i2]);die;
								if($type_field=='field')
									$nom = $field[$list_karyawan->p_karyawan_id][$row];
								else
									$nom = $row;
								
								//if($i1==1);echo $operator;die;
								
								if($$row2==0){
									$$row2 = $nom;	
								}else{
									if($operator=='Kali')
									$$row2*= $nom;
									else if($operator=='Tambah')
									$$row2+= $nom;
									else if($operator=='Kurang')
									$$row2-= $nom;
									else if($operator=='Bagi')
									$$row2 = $$row2/$nom;

									
								}
							}
						}
						if($row2=='gaji_total_upah_harian'){
							if($ha==0){
								$$row2 = 0;
							}
						}
                		$sheet->setCellValue($help->toAlpha($i) . $rows, $$row2);
                		$sheet->getStyle($help->toAlpha($i) . $rows)->getNumberFormat()->setFormatCode('Rp ###,###,##0');
						$sheet->setCellValueExplicit($help->toAlpha($i) . $rows, $$row2, DataType::TYPE_NUMERIC);
					 $i++;
				}
                
				
	           $field[$list_karyawan->p_karyawan_id][$row2] =  $$row2;
				if(isset($$row2))
	           		$total[$row1] +=  $$row2;
				}
                
                

                //echo substr($absen->a,0,5);die;
                $rows++;
            }
            $i = 0;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
            $sheet->setCellValue($help->toAlpha($i) . $rows, '');
            $i++;
			for ($x = 0; $x < count($data_row); $x++) {
		            $row1 = $data_row[$x][0];
		            $row2 = $data_row[$x][1];
		            $row3 = $data_row[$x][2];
					$row4 = $data_row[$x][3];
					$row5 = isset($data_row[$x][4])?$data_row[$x][4]:array();
	          
					$nominal = $total[$row1];
				
					if($row4=='absensi'){
 						$sheet->setCellValue($help->toAlpha($i) . $rows,$nominal);
 						               		
                	}else{
                		$sheet->getStyle($help->toAlpha($i) . $rows)->getNumberFormat()->setFormatCode('Rp ###,###,##0');
				$sheet->setCellValueExplicit($help->toAlpha($i) . $rows, $nominal, DataType::TYPE_NUMERIC);
 
                	}
				//$sheet->setCellValue($help->toAlpha($i) . $rows, $nominal);
            $i++;
			}
            $i++;
        }

		$iduser=Auth::user()->id;
        $sqlidkar="select * from users where id=$iduser";
        $iduser=DB::connection()->select($sqlidkar);
        
        $type = 'xlsx';
        $fileName = "Preview Gaji ".$iduser[0]->name." " . str_replace(':', '', str_replace('|', '', $perigen)) .date('YmdHis'). "." . $type;
        if ($type == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } else if ($type == 'xls') {
            $writer = new Xls($spreadsheet);
        }
        $writer->save("export/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        return redirect(url('/') . "/export/" . $fileName);
    }
    
}
