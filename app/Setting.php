<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Setting extends Model
{
    protected $table='m_seting';
    protected $primaryKey='m_seting_id';
    const CREATED_AT='create_date';
    const UPDATED_AT='update_date';
    protected $fillable = ['nama', 'val1',"val2",'create_by','update_by'];

    public static function getVal2($nama, $val1){
        $data=DB::connection()->select("SELECT * FROM m_seting WHERE nama='".$nama."' AND val1='".$val1."'");
        $data=$data[0];
        return $data->val2;
    }
    public static function getval($nama){
        $data=DB::connection()->select("SELECT * FROM m_seting WHERE nama='".$nama."' ");
        $data=$data[0];
        return $data;
    }
}
