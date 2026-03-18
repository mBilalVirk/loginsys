<?php

namespace App\Http\Controllers;

use App\Exports\AdminExport;
use App\Exports\FriendExport;
use App\Exports\UserExport;
use App\Exports\UsersAdminsExport;
use App\Models\Friend;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GeneratePDFcontroller extends Controller
{
    public function GeneratePDF()
    {
        $users = User::get();
        $data = [
            'title' => 'Users',
            'date' => date('y/m/d'),
            'users' => $users,
        ];
        $pdf = Pdf::loadView('generatepdf', $data);
        return $pdf->stream('user.pdf');
    }

    public function FriendPDF()
    {
        $friends = Friend::with(['sender', 'receiver'])->get();
        $data = [
            'title' => 'Friend List',
            'date' => date('y/m/d'),
            'friends' => $friends,
        ];
        $pdf = Pdf::loadView('friendpdf', $data);
        return $pdf->stream('Friends.pdf');
    }
    public function FriendExport()
    {
        return Excel::download(new FriendExport(), 'Friend.csv');
    }

    public function ExportExcel()
    {
        return Excel::download(new UserExport(), 'UserData.csv');
    }
    public function AdminPDF()
    {
        $user = auth()->user();
        if ($user->role == 'super_admin') {
            $admins = user::where('role', 'admin')->where('id', '!=', $user->id)->whereNull('deleted_at')->get();
        } else {
            $admins = User::where('id', $user->id)->whereNull('deleted_at')->whereNull('deleted_at')->get();
        }
        $data = [
            'title' => 'Admin',
            'date' => date('y/m/d'),
            'users' => $admins,
        ];
        $pdf = Pdf::loadView('generatepdf', $data);
        return $pdf->stream('admin.pdf');
    }
    public function AdminExport()
    {
        return Excel::download(new AdminExport(), 'AdminData.csv');
    }
    public function UserAdminExport()
    {
        return Excel::download(new UsersAdminsExport(), 'UserAdmin.xlsx');
    }
}
