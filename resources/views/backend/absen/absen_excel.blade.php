<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="content">
    <table>
        <thead>
            @php
            $days=$tanggal_awal;
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
            @endphp
            <tr>
                <th>No.</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Periode Gajian</th>
                <th>Total Hari</th>
                <th>Jumlah Hari Masuk</th>
                <th>Jumlah Libur</th>
                <th>Absen Masuk</th>
                <th>Terlambat</th>
                <th>{!! $days1 !!}</th>
                <th>{!! $days2 !!}</th>
                <th>{!! $days3 !!}</th>
                <th>{!! $days4 !!}</th>
                <th>{!! $days5 !!}</th>
                <th>{!! $days6 !!}</th>
                <th>{!! $days7 !!}</th>
                <th>{!! $days8 !!}</th>
                <th>{!! $days9 !!}</th>
                <th>{!! $days10 !!}</th>
                <th>{!! $days11 !!}</th>
                <th>{!! $days12 !!}</th>
                <th>{!! $days13 !!}</th>
                <th>{!! $days14 !!}</th>
                <th>{!! $days15 !!}</th>
                <th>{!! $days16 !!}</th>
                <th>{!! $days17 !!}</th>
                <th>{!! $days18 !!}</th>
                <th>{!! $days19 !!}</th>
                <th>{!! $days20 !!}</th>
                <th>{!! $days21 !!}</th>
                <th>{!! $days22 !!}</th>
                <th>{!! $days23 !!}</th>
                <th>{!! $days24 !!}</th>
                <th>{!! $days25 !!}</th>
                <th>{!! $days26 !!}</th>
                <th>{!! $days27 !!}</th>
                <th>{!! $days28 !!}</th>
                <th>{!! $days29 !!}</th>
                <th>{!! $days30 !!}</th>
                <th>{!! $days31 !!}</th>
            </tr>
        </thead>
        @php
            $no=1;
        @endphp
        <tbody>
            @foreach($rekapabsen as $rekap)
                <tr>{!! $no++ !!}</tr>
                <tr>{!! $rekap->nik !!}</tr>
                <tr>{!! $rekap->nama !!}</tr>
                <tr>{!! $rekap->periode_gaji !!}</tr>
                <tr>{!! $rekap->total_hari !!}</tr>
                <tr>{!! $rekap->jml_hari_kerja !!}</tr>
                <tr>{!! $rekap->jml_hari_libur !!}</tr>
                <tr>{!! $rekap->masuk !!}</tr>
                <tr>{!! $rekap->terlambat !!}</tr>
                @if($rekap->a>'07:30')
                    <tr style="background-color:red">{!! $rekap->a !!}</tr>
                @else
                    <tr>{!! $rekap->a !!}</tr>
                @endif
                <tr>{!! $rekap->b !!}</tr>
                <tr>{!! $rekap->c !!}</tr>
                <tr>{!! $rekap->d !!}</tr>
                <tr>{!! $rekap->e !!}</tr>
                <tr>{!! $rekap->f !!}</tr>
                <tr>{!! $rekap->g !!}</tr>
                <tr>{!! $rekap->h !!}</tr>
                <tr>{!! $rekap->i !!}</tr>
                <tr>{!! $rekap->j !!}</tr>
                <tr>{!! $rekap->k !!}</tr>
                <tr>{!! $rekap->l !!}</tr>
                <tr>{!! $rekap->m !!}</tr>
                <tr>{!! $rekap->n !!}</tr>
                <tr>{!! $rekap->o !!}</tr>
                <tr>{!! $rekap->p !!}</tr>
                <tr>{!! $rekap->q !!}</tr>
                <tr>{!! $rekap->r !!}</tr>
                <tr>{!! $rekap->s !!}</tr>
                <tr>{!! $rekap->t !!}</tr>
                <tr>{!! $rekap->u !!}</tr>
                <tr>{!! $rekap->v !!}</tr>
                <tr>{!! $rekap->w !!}</tr>
                <tr>{!! $rekap->x !!}</tr>
                <tr>{!! $rekap->y !!}</tr>
                <tr>{!! $rekap->z !!}</tr>
                <tr>{!! $rekap->aa !!}</tr>
                <tr>{!! $rekap->ab !!}</tr>
                <tr>{!! $rekap->ac !!}</tr>
                <tr>{!! $rekap->ad !!}</tr>
                <tr>{!! $rekap->ae !!}</tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
