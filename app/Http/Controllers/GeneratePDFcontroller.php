<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
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

    public function FriendPDF(){
          $friends = Friend::with(['sender', 'receiver'])->get();
          $data =[
            'title'=>"Friend List",
            'date'=> date('y/m/d'),
            'friends'=>$friends
          ];
           $pdf = Pdf::loadView('friendpdf', $data);
    return $pdf->stream('friends.pdf');
    }

    public function ExportExcel(){
        return Excel::download(new UserExport, 'user.xlsx');
    }
}
