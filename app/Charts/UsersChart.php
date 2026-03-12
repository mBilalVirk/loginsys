<?php

namespace App\Charts;
use App\Models\User;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class UsersChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->labels(['Admins', 'User']);
        $admins = User::where('role', 'admin')->count();
        $users = User::where('role', 'user')->count();

        $this->dataset('User Roles', 'bar', [$admins, $users])
            ->backgroundColor(['#f87979', '#7acbf9']);

    }
}
