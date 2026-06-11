<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventType;

class EventController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'organization') {
            return redirect()->route('admin.dashboard');
        }

        $events = Event::with([
            'organization',
            'category',
            'eventType',
        ])
            ->withCount([
                'registrations as approved_count' => fn ($q) => $q->where('status', 'approved'),
            ])
            ->where('end_date', '>=', now())
            ->latest('start_date')
            ->get();

        $categories = EventCategory::orderBy('group_type')->orderBy('name')->get();
        $eventTypes = EventType::orderBy('name')->get();

        $filterOptions = [
            'Self Development' => $categories->where('group_type', 'Self Development')->pluck('name')->unique()->values(),
            'Volunteer' => $categories->where('group_type', 'Volunteer')->pluck('name')->unique()->values(),
            'Community' => $categories->where('group_type', 'Community')->pluck('name')->unique()->values(),
        ];

        $eventsData = $events->map(fn ($e) => [
            'id' => $e->id,
            'title' => $e->title,
            'org' => $e->organization->org_name ?? '-',
            'cat' => $e->category->group_type ?? '-',
            'subCat' => $e->category->name ?? '-',
            'type' => $e->eventType->name ?? 'Onsite',
            'price' => $e->isFree() ? 0 : (float) ($e->price ?? 0),
            'quota' => ($e->approved_count ?? 0).'/'.$e->quota,
            'location' => $e->location ?? '-',
            'date' => optional($e->start_date)->format('d M Y'),
            'desc' => $e->description ?? '',
            'img' => $e->image_url,
            'hasImg' => $e->has_stored_image,
            'registrationOpen' => $e->isOpen(),
        ]);

        return view('home', compact(
            'events',
            'eventsData',
            'categories',
            'eventTypes',
            'filterOptions'
        ));
    }

    public function show($id)
    {
        if (auth()->check() && auth()->user()->role === 'organization') {
            return redirect()->route('admin.profile');
        }

        $event = Event::with([
            'organization.category',
            'category',
            'eventType',
        ])
            ->withCount([
                'registrations as approved_count' => fn ($q) => $q->where('status', 'approved'),
            ])
            ->findOrFail($id);

        return view('events.show', compact('event'));
    }
}
