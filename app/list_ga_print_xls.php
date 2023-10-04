<?php

namespace App;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class list_ga_print_xls implements FromCollection, ShouldAutoSize
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
            'Nama', 'Unit Kerja', 'Pengajuan', 'Tgl Pengajuan', 'Nama Barang', 'Qty','Deskripsi','Link','Tgl Digunakan'
        );
        $tableBody = array();
        foreach($this->data['ga'] as $k => $d){
            $tableBody[] = array($d->nama, $d->unit_kerja,$d->sifat,date('d-m-Y', strtotime($d->tgl_pengajuan)), $d->nama_barang,$d->qty,$d->deskripsi,$d->link, date('d-m-Y', strtotime($d->tgl_digunakan))
            );
        }

        $table = array($tableHead, $tableBody);

        return collect($table);
    }
}
