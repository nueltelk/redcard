<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BorrowController extends Controller
{
    private function parseCalendarDate(string $value): Carbon
    {
        $value = trim($value);

        // Preferred format from <input type="date">
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        // Fallback: DD/MM/YYYY or MM/DD/YYYY (ambiguous -> assume DD/MM for Indonesia)
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) {
            [$a, $b, $y] = array_map('intval', explode('/', $value));

            $day = $a;
            $month = $b;
            if ($a <= 12 && $b > 12) {
                // MM/DD
                $month = $a;
                $day = $b;
            }

            return Carbon::createFromDate($y, $month, $day)->startOfDay();
        }

        // Last resort
        return Carbon::parse($value)->startOfDay();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'loan_date' => ['required', 'date'],
            'return_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'pickup_location_id' => ['required', 'exists:locations,id'],
            'units' => ['required', 'array', 'min:1', 'max:2'],
            'units.*' => ['required', 'integer', 'distinct', 'exists:units,id'],
        ]);

        $loanDate = $this->parseCalendarDate((string) $data['loan_date']);
        $returnDate = $this->parseCalendarDate((string) $data['return_date']);
        $maxReturnDate = $loanDate->copy()->addDays(5);
        if ($returnDate->gt($maxReturnDate)) {
            throw ValidationException::withMessages([
                'return_date' => 'Maksimal peminjaman 5 hari dari tanggal pinjam.',
            ]);
        }

        try {
            app(LoanService::class)
                ->createLoan(auth()->user(), $data);
        } catch (\Throwable $e) {
            return back()->withErrors(['borrow' => $e->getMessage()])->withInput();
        }

        return back()->with('success', 'Berhasil pinjam');
    }
}