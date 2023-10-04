<?php

namespace App;
use app\Helper_function;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class rekaplembur_xls implements FromCollection, ShouldAutoSize
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
            'No.',
                        'NIK',
                        'Nama',
                        'Departemen',
                     
        );
        $help = new Helper_function;
        $date = $this->data['tgl_awal'];
        for($i = 0; $i < $help->hitunghari($this->data['tgl_awal'],$this->data['tgl_akhir']); $i++){
        	$tableHead[]=$date;
         	$date = $help->tambah_tanggal($date,1);
		}
        $tableHead[] ='Total';
        $tableBody = array();
        $no=0;
        $rekap = $this->data['rekap'];
        
        foreach($this->data['list_karyawan'] as $list_karyawan){
       
                           $no++;
                           $content = array(
                                $no,
                                $list_karyawan->nik,
                                $list_karyawan->nama,
                                $list_karyawan->departemen,
                           );
                           $date = $this->data['tgl_awal'];
			                        $total = 0;
			                        for($i = 0; $i < $help->hitunghari($this->data['tgl_awal'],$this->data['tgl_akhir']); $i++){
										if(isset($rekap[$list_karyawan->p_karyawan_id][$date]['lama'])){
			                        			$total += $rekap[$list_karyawan->p_karyawan_id][$date]['lama'];
												$content[]= ' '.$rekap[$list_karyawan->p_karyawan_id][$date]['lama'].' Jam
													- '.$rekap[$list_karyawan->p_karyawan_id][$date]['jam_awal'].' 
														s/d  '.$rekap[$list_karyawan->p_karyawan_id][$date]['jam_akhir'].'
												';
											
										}else{
											$content[]= '-';
										}
										$date = $help->tambah_tanggal($date,1);
									}
										$content[] = $total;
                             $tableBody[] = $content;
		}

        $table = array($tableHead, $tableBody);

        return collect($table);
    }
}
