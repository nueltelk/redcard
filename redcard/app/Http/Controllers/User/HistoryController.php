<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;

class HistoryController extends Controller
{
    public function index()
    {
        $loans = Loan::query()
            ->where('user_id', auth()->id())
            ->with(['pickupLocation', 'items.unit.categories'])
            ->latest()
            ->get();
        return view('user.history.index', compact('loans'));
    }
}