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
        $this->labels(['Admins', 'User', 'Super Admin']);
        $admins = User::where('role', 'admin')->count();
        $users = User::where('role', 'user')->count();
        $superAdmin = User::where('role', 'super_admin')->count();

        $this->dataset('User Roles', 'bar', [$admins, $users, $superAdmin])->backgroundColor(['#f87979', '#7acbf9', '#3acb22']);
    }
}
