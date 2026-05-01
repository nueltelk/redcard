<?php

namespace App\Services;

use App\Models\User;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\RedCard;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;

class LoanService
{
    private function parseCalendarDate($value): Carbon
    {
        $value = trim((string) $value);

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) {
            [$a, $b, $y] = array_map('intval', explode('/', $value));

            $day = $a;
            $month = $b;
            if ($a <= 12 && $b > 12) {
                $month = $a;
                $day = $b;
            }

            return Carbon::createFromDate($y, $month, $day)->startOfDay();
        }

        return Carbon::parse($value)->startOfDay();
    }

    public function createLoan($user, $data)
    {
       
        $redCard = RedCard::where('user_id', $user->id)->first();
        if ($redCard && $redCard->blocked_until && now()->lt($redCard->blocked_until)) {
            throw new \Exception('User sedang diblokir (RedCard)');
        }

        $requestedUnits = array_values(array_unique($data['units'] ?? []));
        if (count($requestedUnits) > 2) {
            throw new \Exception('Maksimal meminjam 2 unit');
        }

        // Batasi total unit yang sedang dipinjam user maksimal 2 unit.
        $activeBorrowedUnits = LoanItem::query()
            ->whereHas('loan', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('status', 'borrowed');
            })
            ->count();

        if (($activeBorrowedUnits + count($requestedUnits)) > 2) {
            throw new \Exception('Maksimal 2 unit aktif per user. Kembalikan sebagian barang terlebih dahulu.');
        }

       
        $loanDate = $this->parseCalendarDate($data['loan_date']);
        $dueDate = $this->parseCalendarDate($data['return_date'] ?? $loanDate->copy()->addDays(5)->format('Y-m-d'));
        $maxDueDate = $loanDate->copy()->addDays(5);
        if ($dueDate->lt($loanDate) || $dueDate->gt($maxDueDate)) {
            throw new \Exception('Maksimal peminjaman 5 hari dari tanggal pinjam.');
        }

        foreach ($requestedUnits as $unitId) {

            $conflict = LoanItem::where('unit_id', $unitId)
                ->whereHas('loan', function ($q) use ($data) {
                    $q->where('loan_date', $data['loan_date'])
                      ->where('status', 'borrowed')
                      ->where(function ($q2) use ($data) {
                          $q2->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                             ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']]);
                      });
                })
                ->exists();

            if ($conflict) {
                throw new \Exception('Unit sedang dipakai di waktu tersebut');
            }
        }

       
        $loan = Loan::create([
            'user_id' => $user->id,
            'loan_date' => $data['loan_date'],
            'due_date' => $dueDate,
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'pickup_location_id' => $data['pickup_location_id'],
        ]);

      
        foreach ($requestedUnits as $unitId) {
            LoanItem::create([
                'loan_id' => $loan->id,
                'unit_id' => $unitId
            ]);
        }

        return $loan;
    }

   
    public function returnLoan($loan, $data, ?User $actor = null)
    {
        if (!$actor || $actor->role !== 'admin') {
            throw new AuthorizationException('Hanya admin yang dapat memproses pengembalian unit.');
        }

        $returnDate = now();
        $fine = 0;

        $dueDate = Carbon::parse($loan->due_date)->startOfDay();
        $dueEnd = $dueDate->copy()->endOfDay();

        if ($returnDate->gt($dueEnd)) {
            $daysLate = $dueDate->diffInDays($returnDate->startOfDay());
            $fine = $daysLate * 10000; // 10rb per hari
        }

      
        $loan->update([
            'return_date' => $returnDate,
            'return_location_id' => $data['return_location_id'] ?? null,
            'condition' => $data['condition'] ?? null, // kondisi barang
            'review' => $data['review'] ?? null,       // ulasan user
            'fine' => $fine,
            'status' => 'returned'
        ]);

      
        if ($fine > 0) {
            $this->addRedCard($loan->user_id);
        }

        return $loan;
    }

   
    private function addRedCard($userId)
    {
        $redCard = RedCard::firstOrCreate(
            ['user_id' => $userId],
            ['points' => 0]
        );

        $redCard->increment('points');

        // jika sudah 3x telat → blokir
        if ($redCard->points >= 3) {
            $redCard->update([
                'blocked_until' => now()->addDays(3)
            ]);
        }
    }
}