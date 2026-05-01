<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminChat;
use App\Models\Loan;
use App\Models\Location;
use App\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::query()
            ->with(['user', 'pickupLocation', 'returnLocation', 'items.unit.categories'])
            ->latest()
            ->get();

        $returnRequests = AdminChat::query()
            ->where('context', 'return_request')
            ->whereNotNull('loan_id')
            ->whereIn('loan_id', $loans->pluck('id'))
            ->latest()
            ->get()
            ->groupBy('loan_id')
            ->map(fn ($items) => $items->first());

        return view('admin.loans.index', [
            'loans' => $loans,
            'locations' => Location::query()->orderBy('name')->get(),
            'returnRequests' => $returnRequests,
        ]);
    }

    public function return($id, Request $request)
    {
        $loan = Loan::findOrFail($id);

        app(LoanService::class)
            ->returnLoan($loan, $request->all(), $request->user());

        return back()->with('success', 'Berhasil dikembalikan');
    }
}