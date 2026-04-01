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

    public function __construct($filters = [])
    {
        $authUser = auth()->user();
        $query = User::query();
         if ($authUser->role == 'super_admin') {
            $query->where('id', '!=', $authUser->id);
        } else {
            $query->whereNotIn('role', ['admin', 'super_admin']);
        }
        // Apply search filter
         if (!empty($filters['search'])) {
        $kw = $filters['search'];
        $query->where(function($q) use ($kw) {
            $q->where('name', 'like', "%{$kw}%")
              ->orWhere('email', 'like', "%{$kw}%");
        });
    }

        // Apply date filters
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Apply sort
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('name', 'asc');
                    break;
                case 'az':
                    $query->orderBy('name', 'asc'); // or by sender name
                    break;
                case 'za':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $users = $query->get();
        $this->data = [
            'title' => 'Users',
            'date' => date('Y/m/d'),
            'users' => $users,
        ];
    }

    public function view(): View
    {
        return view('exportexcel', [
            'data' => $this->data, // pass to view
        ]);
    }
    public function title(): string
    {
        return 'Users';
    }
}
