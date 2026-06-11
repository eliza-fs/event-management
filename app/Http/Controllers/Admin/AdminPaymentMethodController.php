<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ScopesOrganizationEvents;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class AdminPaymentMethodController extends Controller
{
    use ScopesOrganizationEvents;

    public function index()
    {
        $org = $this->myOrganization();
        $eventIds = $this->organizationEventIds();

        $methods = PaymentMethod::with('event')
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->get();

        $events = Event::where('organization_id', $org->id)
            ->where('type', 'paid')
            ->orderBy('title')
            ->get(['id', 'title']);

        $methodsJson = $methods->map(fn ($m) => [
            'id' => $m->id,
            'eventId' => $m->event_id,
            'eventTitle' => $m->event->title ?? '-',
            'method' => $m->name,
            'accountNumber' => $m->account_number ?? '-',
            'accountOwner' => $m->account_name ?? '-',
        ]);

        return view('admin.payment-methods.index', compact('methods', 'events', 'methodsJson'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);
        $this->assertEventBelongsToOrg($validated['event_id']);

        PaymentMethod::create([
            'event_id' => $validated['event_id'],
            'organization_id' => $this->myOrganization()->id,
            'name' => $validated['method'],
            'type' => 'bank',
            'account_name' => $validated['account_owner'],
            'account_number' => $validated['account_number'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $method = $this->findOrganizationPaymentMethod($id);
        $validated = $this->validatePayload($request);
        $this->assertEventBelongsToOrg($validated['event_id']);

        $method->update([
            'event_id' => $validated['event_id'],
            'name' => $validated['method'],
            'account_name' => $validated['account_owner'],
            'account_number' => $validated['account_number'],
        ]);

        return back()->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->findOrganizationPaymentMethod($id)->delete();

        return back()->with('success', 'Metode pembayaran berhasil dihapus.');
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'event_id' => 'required|exists:events,id',
            'method' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-\.]+$/u'],
            'account_number' => ['required', 'regex:/^\d+$/', 'max:30'],
            'account_owner' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-\.]+$/u'],
        ]);
    }

    private function assertEventBelongsToOrg(int $eventId): void
    {
        Event::where('organization_id', $this->myOrganization()->id)
            ->where('id', $eventId)
            ->firstOrFail();
    }

    private function findOrganizationPaymentMethod(int $id): PaymentMethod
    {
        return PaymentMethod::whereIn('event_id', $this->organizationEventIds())
            ->findOrFail($id);
    }
}
