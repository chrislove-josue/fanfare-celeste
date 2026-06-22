<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\EventPublicRegistration;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function participateForm(Event $event)
    {
        $profile = auth()->check() ? auth()->user() : null;
        $avatarUrl = null;
        if ($profile && !empty($profile->photo)) {
            $avatarUrl = asset('storage/' . $profile->photo);
        } elseif ($profile && property_exists($profile, 'avatar_url') && $profile->avatar_url) {
            $avatarUrl = $profile->avatar_url;
        }

        return view('events.public.participate', [
            'event' => $event,
            'profile' => $profile,
            'avatarUrl' => $avatarUrl ?: asset('https://www.gravatar.com/avatar/?d=mp&f=y'),
        ]);
    }

    public function participate(Request $request, Event $event)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
        ]);

        // Une soumission = une nouvelle inscription publique (auto-incrément id)
        \App\Models\EventPublicRegistration::create([
            'event_id' => $event->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'whatsapp_number' => $validated['phone'],
            'phone' => $validated['phone'],
            'status' => 'confirmed',
        ]);

        return redirect()->route('public.events.show', $event)
            ->with('success', 'Votre demande d’inscription a été envoyée. Merci !');
    }

    public function index()
    {
        $events = Event::query()
            ->whereIn('status', ['planned', 'upcoming', 'ongoing', 'completed'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('events.public.index', compact('events'));
    }

    public function show(Event $event)
    {
        // Tous les membres du club sont considérés comme participants
        // (inscriptions internes gérées par admin + membres du club)
        $membersTotal = \App\Models\Member::query()->count();

        // Liste des participants internes (ceux ajoutés par admin)
        $participants = $event->participants()
            ->orderByRaw("CONCAT(members.first_name, ' ', members.last_name) ASC")
            ->get();

        $publicRegistrationsCount = \App\Models\EventPublicRegistration::query()
            ->where('event_id', $event->id)
            ->count();

        $totalParticipants = $membersTotal + $publicRegistrationsCount;

        return view('events.public.show', compact('event', 'participants', 'publicRegistrationsCount', 'totalParticipants', 'membersTotal'));
    }
}

