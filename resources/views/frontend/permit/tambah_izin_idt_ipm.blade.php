@extends('layouts.app_fe')

@section('content')

 <?php
 $title = "Pengajuan IDT IPM";
 echo view('frontend.permit.tambah_all',compact('kar','jenisizin','appr','atasan','tolcut','karyawan','totalcuti','tgl_cut_off','idkar','type','title'));;?>
@endsection