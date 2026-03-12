<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
class GeneratePDFcontroller extends Controller
{
    public function GeneratePDF(){

    $users = User::get();
    $data = [
        'title'=> 'Users',
        'date' => date('y/m/d'),
        'users'=> $users
    ];
    $pdf = Pdf::loadView('generatepdf', $data);
    return $pdf->stream('user.pdf');
    }
}
