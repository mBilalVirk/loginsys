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

    public function __construct()
    {
        $friends = Friend::with(['sender', 'receiver'])->get();
        $this->data = [
            'title' => 'Users',
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
