<?php

namespace App;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class absenpro_xls implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $tableHead = array(
            'No','Kantor' ,'NIK', 'Nama','Periode Gajian','Tgl Absen', 'Check In','Check Out','Keterangan'
        );
        $tableBody = array();
        $tgl_awal=$this->data['tgl_awal'];
        $tgl_akhir=$this->data['tgl_akhir'];
        $rekap=$this->data['rekap'];
        $help=$this->data['help'];
        $list_karyawan=$this->data['list_karyawan'];
        $mesin=$this->data['mesin'];
        $hari_libur=$this->data['hari_libur'];
        $tanggallibur=$this->data['tanggallibur'];
        
        
        $date = $tgl_awal;
        $no = 0;
        for($i = 0; $i <= $help->hitunghari($tgl_awal,$tgl_akhir); $i++){
								
                       $no++;
                       $mesin_absen = isset( $rekap[$list_karyawan->p_karyawan_id][$date]['a']['mesin_id'])? $mesin[$rekap[$list_karyawan->p_karyawan_id][$date]['a']['mesin_id']]:''; 
                       $masuk = isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk'])?$rekap[$list_karyawan->p_karyawan_id][$date]['a']['masuk']:'';
                       $keluar = isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar'])?$rekap[$list_karyawan->p_karyawan_id][$date]['a']['keluar']:'';
                       $keterangan = '';
                       if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['terlambat'])){
                                	if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['terlambat'] ) 
                                		$keterangan =  'TERLAMBAT   ';
                               		else 
                               			$keterangan = 'OK ';
								}if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'])){
									$keterangan .= ' - '.$rekap[$list_karyawan->p_karyawan_id][$date]['ci']['nama_ijin'];
								}
								
								
								
						$status = '';
                                	if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_masuk'])){
											if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_masuk']==3){
                                				$info= 'dirubah Manual';
                                				
											}else if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_masuk']==4){												
                                				$info= 'diinput Manual';
											}else{
                                				$info= 'sesuai Mesin';
												
											}
											$status .= 'Data Masuk '.$info;
											if($info!= 'sesuai Mesin'){
												
												$status .= '<br><b>Oleh</b> '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['updated_by_masuk'];
												$status .= '<br><b>tgl</b> '.(($rekap[$list_karyawan->p_karyawan_id][$date]['a']['updated_at_masuk']));
												$status .= '<br><b>data awal</b> :  '.(($rekap[$list_karyawan->p_karyawan_id][$date]['a']['time_before_update_masuk'])).'<br>';
											}
									}
									if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_keluar'])){
										if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_keluar']==3){
                                				$info= 'dirubah Manual';
                                				
											}else if($rekap[$list_karyawan->p_karyawan_id][$date]['a']['status_keluar']==4){												
                                				$info= 'diinput Manual';
											}else{
                                				$info= 'sesuai Mesin';
												
											}
											$status .= '<br>Data Keluar '.$info;
											if($info!= 'sesuai Mesin'){
												
												$status .= 'Oleh '.$rekap[$list_karyawan->p_karyawan_id][$date]['a']['updated_by_keluar'];
												$status .= 'tgl '.(($rekap[$list_karyawan->p_karyawan_id][$date]['a']['updated_at_keluar']));
												$status .= 'data awal :  '.(($rekap[$list_karyawan->p_karyawan_id][$date]['a']['time_before_update_keluar']));
									}
									
									}
									$status_libur='';;
									if(in_array($date,$hari_libur)){
											
										$status_libur='Hari Libur Nasional - '.$tanggallibur[$date];
									}else if(in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
											
										$status_libur='Hari Libur Sabtu Minggu';
									}
									//echo $status_libur.'<br>'
										
										;
                       $tableBody[] = array($no, $list_karyawan->pin,$mesin_absen, $date,$masuk, $keluar, $keterangan, $status_libur);
                       
                       $date = $help->tambah_tanggal($date,1);
                       }
        
        

        $table = array($tableHead, $tableBody);

        return collect($table);
    }
}
