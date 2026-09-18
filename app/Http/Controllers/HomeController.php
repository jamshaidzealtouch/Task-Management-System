<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class HomeController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    public function home()
    {
        $userId = session('user_id');
        $userRole = session('user_role');

        $data = $this->dashboardService->getDashboardData(
            $userId,
            $userRole
        );

        return view('home', $data);
    }

    public function about()
    {
        return view('about');
    }
}