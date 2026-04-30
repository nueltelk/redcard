<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'loan_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'pickup_location_id' => ['required', 'exists:locations,id'],
            'units' => ['required', 'array', 'min:1', 'max:2'],
            'units.*' => ['required', 'exists:units,id'],
        ]);

        try {
            app(LoanService::class)
                ->createLoan(auth()->user(), $data);
        } catch (\Throwable $e) {
            return back()->withErrors(['borrow' => $e->getMessage()])->withInput();
        }

        return back()->with('success', 'Berhasil pinjam');
    }
}