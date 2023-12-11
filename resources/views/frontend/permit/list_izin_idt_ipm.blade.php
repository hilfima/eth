@extends('layouts.app_fe')

@section('content')

 <?php
 $title = "Pengajuan IDT IPM";
 echo view('frontend.permit.list_all',compact($type,'request','help','idkar','title','type'));;?>
@endsection