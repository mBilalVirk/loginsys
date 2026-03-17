<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $data;

    public function __construct()
    {
        $users = User::all(); // fetch all users
        $this->data = [
            'title' => 'Users',
            'date' => date('Y/m/d'),
            'users' => $users
        ];
    }

    public function view(): View
    {
        return view('exportexcel', [
            'data' => $this->data // pass to view
        ]);
    }
}