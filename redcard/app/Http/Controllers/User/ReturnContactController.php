<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminChat;
use App\Models\Loan;
use Illuminate\Http\Request;

class ReturnContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'loan_id' => ['nullable', 'integer'],
            'return_location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'condition' => ['nullable', 'string', 'max:255'],
            'review' => ['nullable', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:500'],
            'redirect_whatsapp' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $loan = null;

        if (!empty($data['loan_id'])) {
            $loan = Loan::query()
                ->where('id', $data['loan_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();
        }

        $message = trim((string) ($data['message'] ?? ''));
        if ($message === '') {
            $message = $loan
                ? "Halo admin, saya ingin mengembalikan barang untuk pinjaman #{$loan->id}."
                : 'Halo admin, saya ingin mengembalikan barang pinjaman.';
        }

        $condition = trim((string) ($data['condition'] ?? ''));
        $review = trim((string) ($data['review'] ?? ''));

        AdminChat::create([
            'user_id' => $user->id,
            'loan_id' => $loan?->id,
            'return_location_id' => $data['return_location_id'] ?? null,
            'channel' => 'whatsapp',
            'context' => 'return_request',
            'message' => $message,
            'condition' => $condition !== '' ? $condition : null,
            'review' => $review !== '' ? $review : null,
        ]);

        $adminNumber = preg_replace('/\D+/', '', (string) config('services.whatsapp.admin_number'));
        if (!$adminNumber) {
            return back()->withErrors([
                'contact_admin' => 'Nomor WhatsApp admin belum diatur. Hubungi administrator sistem.',
            ]);
        }

        $waText = "Halo Admin, saya {$user->name} (@{$user->username}) ingin mengembalikan barang.";
        if ($loan) {
            $waText .= " Pinjaman ID: #{$loan->id}.";
        }
        $waText .= " Pesan: {$message}";

        if (!empty($data['return_location_id'])) {
            $waText .= " | Lokasi titip (ID): {$data['return_location_id']}";
        }
        if ($condition !== '') {
            $waText .= " | Kondisi: {$condition}";
        }
        if ($review !== '') {
            $waText .= " | Ulasan: {$review}";
        }

        $shouldRedirect = (bool) ($data['redirect_whatsapp'] ?? false);
        if ($shouldRedirect) {
            $request->session()->put('show_return_form', true);
            return redirect()->away('https://wa.me/' . $adminNumber . '?text=' . urlencode($waText));
        }

        $request->session()->forget('show_return_form');
        return back()
            ->with('return_request_success', 'Permintaan pengembalian berhasil dikirim.')
            ->with('return_request_submitted', true);
    }
}
