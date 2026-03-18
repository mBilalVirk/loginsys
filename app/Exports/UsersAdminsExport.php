<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersAdminsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Admins' => new AdminExport(),
            'Users' => new UserExport(),
        ];
    }
}
