<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GADetil extends Model
{
    protected $table = 'requisition_detil_ga';
    protected $fillable = [
        'req_ga_id','nama_barang','deskripsi','tgl_digunakan','qty'
    ];
}
