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
    public function GeneratePDF(Request $request)
    {
        $authUser = auth()->user();
        $query = User::query();
         if ($authUser->role == 'super_admin') {
            $query->where('id', '!=', $authUser->id);
        } else {
            $query->whereNotIn('role', ['admin', 'super_admin']);
        }
        if ($request->search) {
        $kw = $request->search;
        $query->where(function($q) use ($kw) {
            $q->where('name', 'like', "%{$kw}%")
              ->orWhere('email', 'like', "%{$kw}%");
        });
    }

        // Apply date filters
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply sort
        if ($request->sort) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'az':
                    $query->orderBy('created_at', 'asc'); // or by sender name
                    break;
                case 'za':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }
        $users = $query->get();

        $data = [
            'title' => 'Users',
            'date' => date('y/m/d'),
            'users' => $users,
        ];
        
        $pdf = Pdf::loadView('generatepdf', $data);
        return $pdf->stream('user.pdf');
    }

    public function FriendPDF(Request $request)
    {
        $query = Friend::with(['sender', 'receiver']);

        // Apply search filter
        if ($request->search) {
            $query->whereHas('sender', fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orWhereHas('receiver', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        // Apply date filters
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Apply sort
        if ($request->sort) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'az':
                    $query->orderBy('sender_id', 'asc'); // or by sender name
                    break;
                case 'za':
                    $query->orderBy('sender_id', 'desc');
                    break;
            }
        }

        $friends = $query->get();

        $data = [
            'title' => 'Friend List',
            'date' => date('y/m/d'),
            'friends' => $friends,
        ];

        $pdf = Pdf::loadView('friendpdf', $data);
        return $pdf->stream('Friends.pdf');
    }
    public function FriendExport(Request $request)
    {
        return Excel::download(new FriendExport($request->all()), 'Friend.csv');
    }

    public function ExportExcel(Request $request)
    {
        return Excel::download(new UserExport($request->all()), 'UserData.csv');
    }
    public function AdminPDF(Request $request)
    {
        $user = auth()->user();
        $query = User::query();
        if ($user->role == 'super_admin') {
            $query->where('role', 'admin')->where('id', '!=', $user->id)->whereNull('deleted_at');
          
        } else {
            $query->where('id', $user->id)->whereNull('deleted_at')->whereNull('deleted_at');
           
           
        }
        
        // ❌ Exclude soft deleted
        $query->whereNull('deleted_at');

        // 🔍 SEARCH (name + email)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }

        // 📅 DATE FILTER
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 🔽 SORTING
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'az':
                $query->orderBy('name', 'asc');
                break;

            case 'za':
                $query->orderBy('name', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc'); // newest
        }
        $admins = $query->get();
        $data = [
            'title' => 'Admin',
            'date' => date('y/m/d'),
            'users' => $admins,
        ];
        $pdf = Pdf::loadView('generatepdf', $data);
        return $pdf->stream('admin.pdf');
    }
    public function AdminExport(Request $request)
    {
        return Excel::download(new AdminExport($request->all()), 'AdminData.csv');
    }
    public function UserAdminExport()
    {
        return Excel::download(new UsersAdminsExport(), 'UserAdmin.xlsx');
    }
}
