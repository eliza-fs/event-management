<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Support\StorageImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserDashboardController extends Controller
{
    public function pastEvents()
    {
        $registrations = EventRegistration::with([
            'event.organization',
            'event.category',
            'event.eventType',
            'participant',
        ])
            ->where('status', 'approved')
            ->whereHas('event', fn ($q) => $q->where('end_date', '<', now()))
            ->whereIn(
                'participant_id',
                auth()->user()->participants()->pluck('id')
            )
            ->latest()
            ->get();

        return view('user.past_events', compact('registrations'));
    }

    public function profile()
    {
        $user = auth()->user();
        $participantIds = $user->participants()->pluck('id');

        $stats = [
            'joined_events' => EventRegistration::whereIn('participant_id', $participantIds)
                ->where('status', 'approved')
                ->count(),
            'registration_count' => EventRegistration::whereIn('participant_id', $participantIds)->count(),
        ];

        return view('user.profile', compact('user', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-\.]+$/u'],
            'nickname' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'regex:/^\d{10,13}$/'],
            'volunteer_status' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:png,jpeg,jpg|max:5120',
        ], ['required' => 'Required.']);

        if ($request->hasFile('profile_image')) {
            try {
                $validated['profile_image'] = StorageImage::storeUploadedFile(
                    $request->file('profile_image'),
                    'profile_images'
                );
                StorageImage::delete($user->profile_image);
            } catch (\RuntimeException) {
                return back()->withInput()->with('error', 'Gagal menyimpan foto profil.');
            }
        } else {
            unset($validated['profile_image']);
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
