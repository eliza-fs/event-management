<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ScopesOrganizationEvents;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class AdminPaymentController extends Controller
{
    use ScopesOrganizationEvents;

    public function index()
    {
        $payments = Payment::with([
            'eventRegistration.event',
            'eventRegistration.participant',
            'paymentMethod',
        ])
            ->whereHas('eventRegistration', fn ($q) => $q->whereIn('event_id', $this->organizationEventIds()))
            ->latest()
            ->get();

        $paymentsJson = $payments->map(fn ($p) => [
            'id' => $p->payment_code,
            'dbId' => $p->id,
            'name' => $p->eventRegistration->participant->name ?? '-',
            'eventTitle' => $p->eventRegistration->event->title ?? '-',
            'amount' => 'Rp'.number_format((float) $p->amount, 0, ',', '.'),
            'method' => $p->paymentMethod->name ?? '-',
            'status' => $this->paymentStatusLabel($p->status),
            'proofImg' => $p->proof_image_url,
        ]);

        return view('admin.payments.index', compact('payments', 'paymentsJson'));
    }

    public function approve($id)
    {
        $payment = $this->findOrganizationPayment($id);
        $payment->update([
            'status' => 'approved',
            'paid_at' => $payment->paid_at ?? now(),
        ]);

        $payment->eventRegistration->update(['status' => 'approved']);

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject($id)
    {
        $payment = $this->findOrganizationPayment($id);
        $payment->update(['status' => 'rejected']);

        return back()->with('success', 'Pembayaran berhasil ditolak.');
    }

    private function paymentStatusLabel(string $status): string
    {
        return match ($status) {
            'approved' => 'Berhasil',
            'rejected' => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }
}
