<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ScopesOrganizationEvents;
use App\Http\Controllers\Controller;
use App\Models\EventRegistration;

class AdminRegistrationController extends Controller
{
    use ScopesOrganizationEvents;

    public function index()
    {
        $registrations = EventRegistration::with([
            'event.organization',
            'participant.user',
            'payment.paymentMethod',
        ])
            ->whereIn('event_id', $this->organizationEventIds())
            ->latest()
            ->get();

        $registrationsJson = $registrations->map(fn ($r) => [
            'id' => $r->id,
            'participantId' => $r->participant_id,
            'paymentId' => $r->payment?->id,
            'name' => $r->participant->name ?? '-',
            'eventTitle' => $r->event->title ?? '-',
            'amount' => $r->event->isFree()
                ? 'Gratis'
                : 'Rp'.number_format((float) ($r->payment?->amount ?? $r->event->price ?? 0), 0, ',', '.'),
            'method' => $r->payment?->paymentMethod?->name ?? ($r->event->isFree() ? '-' : '-'),
            'proofImg' => $r->payment?->proof_image_url,
            'status' => $this->statusLabel($r),
            'isFinal' => in_array($r->status, ['approved', 'rejected', 'cancelled'], true),
            'email' => $r->participant->email ?? '-',
            'phone' => $r->participant->phone ?? '-',
        ]);

        return view('admin.registrations.index', compact('registrations', 'registrationsJson'));
    }

    public function approve($id)
    {
        $registration = $this->findOrganizationRegistration($id);

        if (in_array($registration->status, ['approved', 'rejected', 'cancelled'], true)) {
            return back()->with('error', 'Status pendaftaran sudah final dan tidak dapat diubah.');
        }

        if ($registration->payment && $registration->payment->status !== 'approved') {
            $registration->payment->update([
                'status' => 'approved',
                'paid_at' => $registration->payment->paid_at ?? now(),
            ]);
        }

        $registration->update(['status' => 'approved']);

        return back()->with('success', 'Pendaftaran berhasil diterima.');
    }

    public function reject($id)
    {
        $registration = $this->findOrganizationRegistration($id);

        if (in_array($registration->status, ['approved', 'rejected', 'cancelled'], true)) {
            return back()->with('error', 'Status pendaftaran sudah final dan tidak dapat diubah.');
        }

        if ($registration->payment) {
            $registration->payment->update(['status' => 'rejected']);
        }

        $registration->update(['status' => 'rejected']);

        return back()->with('success', 'Pendaftaran berhasil ditolak.');
    }

    private function statusLabel(EventRegistration $r): string
    {
        if ($r->status === 'cancelled') {
            return 'Dibatalkan';
        }

        if ($r->status === 'approved') {
            return 'Diterima';
        }

        if ($r->status === 'rejected') {
            return 'Ditolak';
        }

        if ($r->payment && $r->payment->status === 'pending') {
            return 'Menunggu Verifikasi';
        }

        return 'Pending';
    }
}
