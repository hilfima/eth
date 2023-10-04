<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GADetilPinjaman extends Model
{
    protected $table = 'requisition_detil_ga_pinjaman';
    protected $fillable = [
        'req_ga_id','nama_barang','peruntukan','lama_pinjam','tgl_digunakan','tgl_dikembalikan'
    ];
}
