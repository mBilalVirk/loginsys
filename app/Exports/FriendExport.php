<?php

namespace App\Exports;

use App\Models\Friend;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\View\View;

class FriendExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $data;

    // Accept filters in the constructor
    public function __construct($filters = [])
    {
        $query = Friend::with(['sender', 'receiver']);

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('sender', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('receiver', fn($q) => $q->where('name', 'like', "%{$search}%"));
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
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'az':
                    $query->orderBy('sender_id', 'asc'); // optionally by sender name
                    break;
                case 'za':
                    $query->orderBy('sender_id', 'desc');
                    break;
            }
        }

        $friends = $query->get();
        
        $this->data = [
            'title' => 'Friend List',
            'date' => date('Y/m/d'),
            'friends' => $friends,
        ];
    }

    public function view(): View
    {
        return view('friendexport', [
            'data' => $this->data,
        ]);
    }
}