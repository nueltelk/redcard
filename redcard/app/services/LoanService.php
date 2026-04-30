<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\RedCard;
use Carbon\Carbon;

class LoanService
{
    public function createLoan($user, $data)
    {
       
        $redCard = RedCard::where('user_id', $user->id)->first();
        if ($redCard && $redCard->blocked_until && now()->lt($redCard->blocked_until)) {
            throw new \Exception('User sedang diblokir (RedCard)');
        }

        if (count($data['units']) > 2) {
            throw new \Exception('Maksimal meminjam 2 unit');
        }

       
        $loanDate = Carbon::parse($data['loan_date']);
        $dueDate  = $loanDate->copy()->addDays(5); // max 5 hari

        foreach ($data['units'] as $unitId) {

            $conflict = LoanItem::where('unit_id', $unitId)
                ->whereHas('loan', function ($q) use ($data) {
                    $q->where('loan_date', $data['loan_date'])
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

      
        foreach ($data['units'] as $unitId) {
            LoanItem::create([
                'loan_id' => $loan->id,
                'unit_id' => $unitId
            ]);
        }

        return $loan;
    }

   
    public function returnLoan($loan, $data)
    {
        $returnDate = now();
        $fine = 0;

        if ($returnDate->gt(Carbon::parse($loan->due_date))) {
            $daysLate = $returnDate->diffInDays($loan->due_date);
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