<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Support\StorageImage;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function history()
    {
        $payments = Payment::with([
            'eventRegistration.event.organization',
            'eventRegistration.participant',
            'paymentMethod',
        ])
            ->whereHas('eventRegistration.participant', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->get()
            ->map(fn ($payment) => (object) [
                'transaction_name' => $payment->eventRegistration->event->title ?? '-',
                'date_formatted' => ($payment->paid_at ?? $payment->created_at)?->format('d M Y') ?? '-',
                'amount' => (float) $payment->amount,
                'method' => $payment->paymentMethod->name ?? '-',
                'registration_status' => $payment->eventRegistration->status ?? '-',
                'status' => match ($payment->status) {
                    'approved' => 'success',
                    'pending' => 'pending',
                    default => 'failed',
                },
            ]);

        return view('user.payment-history', compact('payments'));
    }

    public function uploadProof(Request $request, $paymentId)
    {
        $request->validate([
            'proof_image' => 'required|image|max:5120',
        ]);

        $payment = Payment::whereHas('eventRegistration.participant', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($paymentId);

        $path = StorageImage::storeUploadedFile($request->file('proof_image'), 'payment_proofs');

        $payment->update([
            'proof_image' => $path,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah.');
    }
}
