<?php

namespace App;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class rekap_absen_xls implements FromCollection, ShouldAutoSize
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
        $days=$this->data['tanggal_awal'];
        //echo $days;die;
        if($days>31){
            $days1=1;
        }
        else{
            $days1=$days;
        }

        $days2=$days1+1;
        if($days2>31){
            $days2=1;
        }
        else{
            $days2=$days2;
        }

        $days3=$days2+1;
        if($days3>31){
            $days3=1;
        }
        else{
            $days3=$days3;
        }

        $days4=$days3+1;
        if($days4>31){
            $days4=1;
        }
        else{
            $days4=$days4;
        }

        $days5=$days4+1;
        if($days5>31){
            $days5=1;
        }
        else{
            $days5=$days5;
        }

        $days6=$days5+1;
        if($days6>31){
            $days6=1;
        }
        else{
            $days6=$days6;
        }

        $days7=$days6+1;
        if($days7>31){
            $days7=1;
        }
        else{
            $days7=$days7;
        }

        $days8=$days7+1;
        if($days8>31){
            $days8=1;
        }
        else{
            $days8=$days8;
        }

        $days9=$days8+1;
        if($days9>31){
            $days9=1;
        }
        else{
            $days9=$days9;
        }

        $days10=$days9+1;
        if($days10>31){
            $days10=1;
        }
        else{
            $days10=$days10;
        }

        $days11=$days10+1;
        if($days11>31){
            $days11=1;
        }
        else{
            $days11=$days11;
        }

        $days12=$days11+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days13=$days12+1;
        if($days12>31){
            $days12=1;
        }
        else{
            $days12=$days12;
        }

        $days14=$days13+1;
        if($days14>31){
            $days14=1;
        }
        else{
            $days14=$days14;
        }

        $days15=$days14+1;
        if($days15>31){
            $days15=1;
        }
        else{
            $days15=$days15;
        }

        $days16=$days15+1;
        if($days16>31){
            $days16=1;
        }
        else{
            $days16=$days16;
        }

        $days17=$days16+1;
        if($days17>31){
            $days17=1;
        }
        else{
            $days17=$days17;
        }

        $days18=$days17+1;
        if($days18>31){
            $days18=1;
        }
        else{
            $days18=$days18;
        }

        $days19=$days18+1;
        if($days19>31){
            $days19=1;
        }
        else{
            $days19=$days19;
        }

        $days20=$days19+1;
        if($days20>31){
            $days20=1;
        }
        else{
            $days20=$days20;
        }

        $days21=$days20+1;
        if($days21>31){
            $days21=1;
        }
        else{
            $days21=$days21;
        }

        $days22=$days21+1;
        if($days22>31){
            $days22=1;
        }
        else{
            $days22=$days22;
        }

        $days23=$days22+1;
        if($days23>31){
            $days23=1;
        }
        else{
            $days23=$days23;
        }

        $days24=$days23+1;
        if($days24>31){
            $days24=1;
        }
        else{
            $days24=$days24;
        }

        $days25=$days24+1;
        if($days25>31){
            $days25=1;
        }
        else{
            $days25=$days25;
        }

        $days26=$days25+1;
        if($days26>31){
            $days25=1;
        }
        else{
            $days26=$days26;
        }

        $days27=$days26+1;
        if($days27>31){
            $days27=1;
        }
        else{
            $days27=$days27;
        }

        $days28=$days27+1;
        if($days28>31){
            $days28=1;
        }
        else{
            $days28=$days28;
        }

        $days29=$days28+1;
        if($days29>31){
            $days29=1;
        }
        else{
            $days29=$days29;
        }

        $days30=$days29+1;
        if($days30>31){
            $days30=1;
        }
        else{
            $days30=$days30;
        }

        $days31=$days30+1;
        if($days31>31){
            $days31=1;
        }
        else{
            $days31=$days31;
        }

        $tgl_awal=date('Y-m-d',strtotime($this->data['tgl_awal']));
        $tgl_akhir=date('Y-m-d',strtotime($this->data['tgl_akhir']));
        $sqlharilibur="SELECT sum(jumlah) as jumlah FROM m_hari_libur WHERE tanggal>='".$tgl_awal."' and tanggal<='".$tgl_akhir."'";
        $harilibur=DB::connection()->select($sqlharilibur);
        $jml_harilibur=$harilibur[0]->jumlah;

        $awal_cuti = date('d-m-Y',strtotime($tgl_awal));//'30-04-2021';
        $akhir_cuti = date('d-m-Y',strtotime($tgl_akhir));//'12-05-2021';

        // tanggalnya diubah formatnya ke Y-m-d
        $awal_cuti = date_create_from_format('d-m-Y', $awal_cuti);
        $awal_cuti = date_format($awal_cuti, 'Y-m-d');
        $awal_cuti = strtotime($awal_cuti);

        $akhir_cuti = date_create_from_format('d-m-Y', $akhir_cuti);
        $akhir_cuti = date_format($akhir_cuti, 'Y-m-d');
        $akhir_cuti = strtotime($akhir_cuti);

        $haricuti = array();
        $sabtuminggu = array();

        for ($i=$awal_cuti; $i <= $akhir_cuti; $i += (60 * 60 * 24)) {
            if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                $haricuti[] = $i;
            } else {
                $sabtuminggu[] = $i;
            }

        }
        $jumlah_cuti = count($haricuti)-$jml_harilibur+1;
        $jumlah_sabtuminggu = count($sabtuminggu);
        $abtotal = $jumlah_cuti + $jumlah_sabtuminggu+$jml_harilibur;
        /*echo "<pre>";
        echo "<h1>Sistem Cuti Online</h1>";
        echo "<hr>";
        echo "Mulai Masuk : " . date('d-m-Y', $awal_cuti) . "<br>";
        echo "Terakhir Masuk : " . date('d-m-Y', $akhir_cuti) . "<br>";
        echo "Jumlah Hari Masuk : " . $jumlah_cuti ."<br>";
        echo "Jumlah Hari Libur Nasional : " . $jml_harilibur ."<br>";
        echo "Jumlah Sabtu/Minggu : " . $jumlah_sabtuminggu ."<br>";
        echo "Total Hari : " . $abtotal ."<br>";
        echo "<h1>Hari Kerja</h1>";
        echo "<hr>";
        foreach ($haricuti as $value) {
            echo date('d-m-Y', $value)  . " -> " . strftime("%A, %d %B %Y", date($value)) . "\n" . "<br>";
        }
        die;*/

        $tableHead = array(
            'No','NIK', 'Nama','Periode Gajian','Jumlah Hari Masuk','Jumlah Libur','Absen Masuk', 'Terlambat','Cuti','IPG','IHK',
            $days1,$days2,$days3,$days4,$days5,$days6,$days7,$days8,$days9,$days10,$days11,$days12,$days13,$days14,$days15,
            $days16,$days17,$days18,$days19,$days20,$days21,$days22,$days23,$days24,$days25,$days26,$days27,$days28,
            $days29,$days30,$days31
        );
        $tableBody = array();
        foreach($this->data['rekapabsen'] as $k => $d){
            /*$a=substr($d->a,0,5);
            $b=substr($d->b,0,5);
            if($a>='07:31'){
                $a= "<font color='#ff0000'>$d->a</font>";
            }
            else{
                $a= $d->a;

            }
            if($b>='07:31'){
                $b=  "<p style='background-color:#FF0000'> $d->b </p>";
            }
            else{
                $b= $d->b;
            }*/

            $tableBody[] = array($k+1, $d->nik,$d->nama,$d->periode_gaji,$d->jml_hari_kerja,$d->jml_hari_libur,$d->masuk, $d->terlambat,$d->cuti,$d->ipg,$d->izin, $d->a, $d->b,$d->c,$d->d,$d->e,$d->f,$d->g,$d->h,$d->i,$d->j,$d->k,$d->l,$d->m,$d->n,$d->o,$d->p,$d->q,$d->r,$d->s,$d->t,$d->u,$d->v,$d->w,$d->x,$d->y,$d->z,$d->aa,$d->ab,$d->ac,$d->ad,$d->ae
            );
        }

        $table = array($tableHead, $tableBody);

        return collect($table);
    }
}
