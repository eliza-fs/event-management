<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ScopesOrganizationEvents;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\RefundLog;
use App\Support\StorageImage;
use Illuminate\Http\Request;

class AdminRefundController extends Controller
{
    use ScopesOrganizationEvents;

    public function index()
    {
        $refunds = Refund::with([
            'user',
            'payment.paymentMethod',
            'eventRegistration.event',
            'eventRegistration.participant',
        ])
            ->whereHas('eventRegistration', fn ($q) => $q->whereIn('event_id', $this->organizationEventIds()))
            ->latest()
            ->get();

        $refundsJson = $refunds->map(fn ($r) => [
            'id' => $r->id,
            'name' => $r->eventRegistration->participant->name ?? $r->user->name ?? '-',
            'eventTitle' => $r->eventRegistration->event->title ?? '-',
            'amount' => 'Rp'.number_format((float) $r->amount, 0, ',', '.'),
            'bank' => $r->bank_name.' • '.$r->account_number,
            'status' => $this->statusLabel($r->status),
            'transferProof' => StorageImage::url($r->transfer_proof),
        ]);

        return view('admin.refunds.index', compact('refunds', 'refundsJson'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'transfer_proof' => 'required|image|mimes:png,jpeg,jpg|max:5120',
            'note' => 'nullable|string|max:500',
        ]);

        $refund = $this->findOrganizationRefund($id);

        $proofPath = StorageImage::storeUploadedFile($request->file('transfer_proof'), 'refund_proofs');

        $refund->update([
            'status' => 'approved',
            'processed_at' => now(),
            'admin_note' => $request->note,
            'transfer_proof' => $proofPath,
        ]);

        $refund->eventRegistration->update(['status' => 'cancelled']);

        RefundLog::create([
            'refund_id' => $refund->id,
            'admin_id' => auth()->id(),
            'action' => 'approved',
            'note' => $request->note,
        ]);

        return back()->with('success', 'Refund disetujui dan pendaftaran dibatalkan.');
    }

    public function reject(Request $request, $id)
    {
        $refund = $this->findOrganizationRefund($id);
        $refund->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'admin_note' => $request->note,
        ]);

        RefundLog::create([
            'refund_id' => $refund->id,
            'admin_id' => auth()->id(),
            'action' => 'rejected',
            'note' => $request->note,
        ]);

        return back()->with('success', 'Refund ditolak.');
    }

    private function findOrganizationRefund(int $id): Refund
    {
        return Refund::whereHas('eventRegistration', fn ($q) => $q->whereIn('event_id', $this->organizationEventIds()))
            ->findOrFail($id);
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Menunggu',
        };
    }
}
