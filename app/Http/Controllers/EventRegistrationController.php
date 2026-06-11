<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Support\StorageImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class EventRegistrationController extends Controller
{
    public function showForm($eventId)
    {
        $event = Event::with([
            'organization', 'category', 'eventType',
        ])->findOrFail($eventId);

        if (! $event->isOpen()) {
            return redirect()->route('event.detail', $event->id)
                ->with('error', $event->registrationClosedReason());
        }

        $userParticipants = auth()->user()->participants()->get();

        $paymentMethods = $event->isFree()
            ? collect()
            : PaymentMethod::forEvent($event->id)->get();

        return view('events.register_form', compact(
            'event',
            'userParticipants',
            'paymentMethods'
        ));
    }

    public function register(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-\.]+$/u'],
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^\d{10,13}$/'],
            'age' => 'required|integer|min:15|max:70',
            'location' => ['required', 'regex:/^[\pL\s\-\.]+,\s*[\pL\s\-\.]+$/u'],
            'payment_method_id' => $event->isFree()
                ? 'nullable'
                : ['required', Rule::exists('payment_methods', 'id')->where('event_id', $event->id)],
            'proof_image' => $event->isFree() ? 'nullable' : 'required|image|mimes:png,jpeg,jpg|max:5120',
        ], [
            'required' => 'Required.',
            'email.email' => 'Format email tidak valid.',
            'phone.regex' => 'Nomor HP harus 10–13 digit angka.',
            'age.integer' => 'Usia harus berupa angka.',
            'age.min' => 'Usia minimal 15 tahun.',
            'age.max' => 'Usia maksimal 70 tahun.',
            'location.regex' => 'Format: Kota, Negara (contoh: Jakarta, Indonesia).',
        ]);

        if (! $event->isOpen()) {
            throw ValidationException::withMessages([
                'registration' => [$event->registrationClosedReason()],
            ]);
        }

        try {
            DB::transaction(function () use ($request, $event, $validated) {
                $participant = auth()->user()->participants()
                    ->where('email', $validated['email'])
                    ->first();

                if (! $participant) {
                    $participant = Participant::create([
                        'user_id' => auth()->id(),
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'],
                        'age' => $validated['age'],
                        'location' => $validated['location'],
                    ]);
                } else {
                    $participant->update([
                        'name' => $validated['name'],
                        'phone' => $validated['phone'],
                        'age' => $validated['age'],
                        'location' => $validated['location'],
                    ]);
                }

                if ($participant->isRegisteredTo($event->id)) {
                    throw new RuntimeException('duplicate');
                }

                $registration = EventRegistration::create([
                    'event_id' => $event->id,
                    'participant_id' => $participant->id,
                    'status' => $event->isFree() ? 'approved' : 'pending',
                ]);

                if (! $event->isFree()) {
                    $storedPath = StorageImage::storeUploadedFile(
                        $request->file('proof_image'),
                        'payment_proofs'
                    );

                    Payment::create([
                        'event_registration_id' => $registration->id,
                        'payment_method_id' => $validated['payment_method_id'],
                        'amount' => $event->price,
                        'status' => 'pending',
                        'proof_image' => $storedPath,
                        'paid_at' => now(),
                    ]);
                }
            });
        } catch (RuntimeException $e) {
            if ($e->getMessage() === 'duplicate') {
                return back()->withInput()->with('error', 'Identitas ini sudah terdaftar di event ini.');
            }

            return back()->withInput()->with('error', 'Gagal menyimpan bukti pembayaran.');
        }

        $message = $event->isFree()
            ? 'Pendaftaran berhasil! Anda sudah terdaftar.'
            : 'Pendaftaran berhasil! Menunggu verifikasi pembayaran.';

        return redirect()->route('user.activities.status')->with('success', $message);
    }

    public function myRegistrations()
    {
        $registrations = EventRegistration::with([
            'event.organization',
            'event.category',
            'event.eventType',
            'participant',
            'payment.paymentMethod',
            'refund',
        ])
            ->whereIn(
                'participant_id',
                auth()->user()->participants()->pluck('id')
            )
            ->latest()
            ->get();

        return view('user.user_activites_status', compact('registrations'));
    }
}
