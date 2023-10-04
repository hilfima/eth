<?php

namespace App;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ajuan_xls implements FromCollection, ShouldAutoSize
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
            'No','Kode' ,'Nama','Jenis','Tgl Ajuan', 'Tgl Awal','Tgl Akhir','Jam Awal','Jam Akhir','Lama','Approval','Status Pengajuan','Status','Tipe Lembur','Gajian'
        );
        $tableBody = array();
        foreach($this->data['data'] as $k => $d){
            $jaw=$d->jam_awal;
            if($d->jam_awal=='' || $d->jam_awal==null){
                $jaw=null;
            }
            $jak=$d->jam_akhir;
            if($d->jam_akhir=='' || $d->jam_akhir==null){
                $jak=null;
            }
            $tableBody[] = array($k+1, $d->kode,$d->nama_lengkap, $d->nmtipe,date('d-m-Y',strtotime($d->create_date)), date('d-m-Y',strtotime($d->tgl_awal)), date('d-m-Y',strtotime($d->tgl_akhir)), $jaw, $jak,$d->lama, $d->nama_appr,$d->sts_pengajuan,$d->keterangan,$d->tipe_lembur,$d->gajian
            );
        }

        $table = array($tableHead, $tableBody);

        return collect($table);
    }
}
