<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Location;
use App\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        return view('admin.loans.index', [
            'loans' => Loan::query()
                ->with(['user', 'pickupLocation', 'returnLocation', 'items.unit'])
                ->latest()
                ->get(),
            'locations' => Location::query()->orderBy('name')->get(),
        ]);
    }

    public function return($id, Request $request)
    {
        $loan = Loan::findOrFail($id);

        app(LoanService::class)
            ->returnLoan($loan, $request->all());

        return back()->with('success', 'Berhasil dikembalikan');
    }
}