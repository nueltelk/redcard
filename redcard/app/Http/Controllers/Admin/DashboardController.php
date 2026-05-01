<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminChat;
use App\Models\Loan;
use App\Models\Unit;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'total_units' => Unit::count(),
            'borrowed' => Loan::where('status', 'borrowed')->count(),
            'users' => User::where('role', 'user')->count(),
            'recentLoans' => Loan::query()
                ->with(['user'])
                ->latest()
                ->limit(8)
                ->get(),
            'returnChats' => AdminChat::query()
                ->with(['user', 'loan'])
                ->where('context', 'return_request')
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }
}