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

    public function __construct($filters = [])
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
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // 📅 DATE FILTER
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // 🔽 SORTING
        if(!empty($filters['sort'])){
        switch ($filters['sort']) {
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
        }
        $admins = $query->get();
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
