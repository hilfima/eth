<?php

namespace App;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class absen_xls implements FromCollection, ShouldAutoSize
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
        foreach($this->data['absen'] as $k => $d){
            $jk=$d->jam_keluar;
            if($d->jam_keluar=='' || $d->jam_keluar==null){
                $jk=null;
            }
            $tableBody[] = array($k+1, $d->nmlokasi,$d->pin, $d->nama,$d->periode_gaji, $d->tgl_absen, $d->jam_masuk, $jk, $d->keterangan
            );
        }

        $table = array($tableHead, $tableBody);

        return collect($table);
    }
}
