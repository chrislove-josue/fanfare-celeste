<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view events')->only(['index', 'show', 'calendar']);
        $this->middleware('permission:create events')->only(['create', 'store']);
        $this->middleware('permission:edit events')->only(['edit', 'update']);
        $this->middleware('permission:delete events')->only(['destroy']);
        $this->middleware('permission:manage participations')->only(['addParticipant', 'updateParticipation']);
    }

    public function index()
    {
        $upcomingEvents = Event::upcoming()
            ->orderBy('start_date')
            ->paginate(10);
            
        $pastEvents = Event::where('start_date', '<', now())
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);
        
        $ongoingEvents = Event::ongoing()->get();
        
        return view('admin.events.index', compact('upcomingEvents', 'pastEvents', 'ongoingEvents'));
    }

    public function create()
    {
        $users = \App\Models\User::role(['president', 'conductor', 'super-admin'])->get();
        $types = Event::getTypes();
        
        return view('admin.events.create', compact('users', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string',
            'location_url' => 'nullable|url',
            'responsible_id' => 'required|exists:users,id',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'poster' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster_path'] = $request->file('poster')->store('events/posters', 'public');
        }

        $validated['status'] = 'planned';

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement créé avec succès');
    }

    public function show(Event $event)
    {
        $participants = $event->participations()->with('member')->get();
        $availableMembers = Member::where('status', 'active')->get();

        // Tous les membres du club sont considérés comme participants
        // => Total participants = membres du club + inscriptions publiques
        $membersTotal = Member::query()->count();

        $publicRegistrationsCount = \App\Models\EventPublicRegistration::query()
            ->where('event_id', $event->id)
            ->count();

        $totalParticipants = $membersTotal + $publicRegistrationsCount;

        $presentCount = $participants->where('attendance_status', 'present')->count();
        $absentCount = $participants->where('attendance_status', 'absent')->count();
        $lateCount = $participants->where('attendance_status', 'late')->count();

        return view('admin.events.show', compact('event', 'participants', 'availableMembers', 'totalParticipants', 'presentCount', 'absentCount', 'lateCount'));
    }

    public function edit(Event $event)
    {
        $users = \App\Models\User::role(['president', 'conductor', 'super-admin'])->get();
        $types = Event::getTypes();
        $statuses = Event::getStatuses();
        
        return view('admin.events.edit', compact('event', 'users', 'types', 'statuses'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string',
            'location_url' => 'nullable|url',
            'responsible_id' => 'required|exists:users,id',
            'status' => 'required|in:planned,upcoming,ongoing,completed,cancelled',
            'budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                \Storage::disk('public')->delete($event->poster_path);
            }
            $validated['poster_path'] = $request->file('poster')->store('events/posters', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Événement mis à jour');
    }

    public function destroy(Event $event)
    {
        if ($event->poster_path) {
            \Storage::disk('public')->delete($event->poster_path);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Événement supprimé');
    }

    public function addParticipant(Request $request, Event $event)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $existing = EventParticipation::where('event_id', $event->id)
            ->where('member_id', $request->member_id)
            ->first();

        if ($existing) {
            return back()->with('warning', 'Ce membre est déjà inscrit à cet événement');
        }

        EventParticipation::create([
            'event_id' => $event->id,
            'member_id' => $request->member_id,
            'attendance_status' => 'confirmed'
        ]);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Participant ajouté avec succès');
    }

    public function updateParticipation(Request $request, Event $event, EventParticipation $participation)
    {
        $request->validate([
            'attendance_status' => 'required|in:confirmed,present,absent,late,excused',
            'notes' => 'nullable|string'
        ]);

        $participation->update([
            'attendance_status' => $request->attendance_status,
            'notes' => $request->notes,
            'arrival_time' => $request->attendance_status == 'present' ? now() : $participation->arrival_time
        ]);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Statut de participation mis à jour');
    }

    public function calendar()
    {
        $events = Event::where('start_date', '>=', now()->startOfMonth())
            ->where('start_date', '<=', now()->endOfMonth()->addMonths(2))
            ->get();
        
        $eventsByDate = [];
        foreach ($events as $event) {
            $date = $event->start_date->format('Y-m-d');
            if (!isset($eventsByDate[$date])) {
                $eventsByDate[$date] = [];
            }
            $eventsByDate[$date][] = $event;
        }
        
        return view('admin.events.calendar', compact('eventsByDate'));
    }

    public function report(Event $event)
    {
        $participations = $event->participations()->with('member')->get();
        
        $statistics = [
            'total' => $participations->count(),
            'present' => $participations->where('attendance_status', 'present')->count(),
            'absent' => $participations->where('attendance_status', 'absent')->count(),
            'late' => $participations->where('attendance_status', 'late')->count(),
            'excused' => $participations->where('attendance_status', 'excused')->count(),
        ];
        
        $statistics['rate'] = $statistics['total'] > 0 
            ? round(($statistics['present'] / $statistics['total']) * 100, 2) 
            : 0;
        
        return view('admin.events.report', compact('event', 'participations', 'statistics'));
    }
}