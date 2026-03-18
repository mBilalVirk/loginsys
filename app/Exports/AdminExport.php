<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AdminExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $data;

    public function __construct()
    {
        $user = auth()->user();
        if ($user->role == 'super_admin') {
            $admins = user::where('role', 'admin')->where('id', '!=', $user->id)->whereNull('deleted_at')->get();
        } else {
            $admins = User::where('id', $user->id)->whereNull('deleted_at')->whereNull('deleted_at')->get();
        }
        $this->data = [
            'title' => 'Users',
            'date' => date('Y/m/d'),
            'admins' => $admins,
        ];
    }

    public function view(): View
    {
        return view('adminexport', [
            'data' => $this->data,
        ]);
    }
    public function title(): string
    {
        return 'Admins';
    }
}
