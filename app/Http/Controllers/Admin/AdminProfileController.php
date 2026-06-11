<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ScopesOrganizationEvents;
use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Support\StorageImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    use ScopesOrganizationEvents;

    public function show()
    {
        $org = $this->myOrganization()->load('category', 'user');
        $eventIds = $this->organizationEventIds();

        $stats = [
            'managed_activities' => $org->events()->count(),
            'processed_volunteers' => EventRegistration::whereIn('event_id', $eventIds)
                ->where('status', 'approved')
                ->distinct('participant_id')
                ->count('participant_id'),
            'pending_registrations' => EventRegistration::whereIn('event_id', $eventIds)
                ->where('status', 'pending')
                ->count(),
        ];

        return view('admin.profile', compact('org', 'stats'));
    }

    public function update(Request $request)
    {
        $org = $this->myOrganization();

        $validated = $request->validate([
            'org_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpeg,jpg|max:5120',
        ], ['required' => 'Required.']);

        if ($request->hasFile('image')) {
            try {
                $validated['image'] = StorageImage::storeUploadedFile($request->file('image'), 'organizations');
                StorageImage::delete($org->image);
            } catch (\RuntimeException) {
                return back()->withInput()->with('error', 'Gagal menyimpan gambar organisasi.');
            }
        } else {
            unset($validated['image']);
        }

        $org->update($validated);

        if ($request->filled('name')) {
            $org->user->update(['name' => $request->name]);
        }

        return back()->with('success', 'Profil organisasi berhasil diperbarui.');
    }
}
