<?php

namespace App;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class absen_excel implements FromView
{
    public function view(): View
    {
        return view('backend.absen.absen_excel1',[
            'data'=>User::all()
        ]);
    }
}
