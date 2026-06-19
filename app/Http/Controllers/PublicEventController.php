<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\EventParticipation;
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
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        // Ici : créer/mettre à jour une demande de participation.
        // Comme ton modèle exact n’existe pas clairement côté public,
        // on enregistre au minimum une participation « confirmed » si possible.
        $member = null;
        if (class_exists(\App\Models\Member::class)) {
            $member = \App\Models\Member::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'status' => 'pending',
                ]
            );
        }

        if ($member) {
            \App\Models\EventParticipation::updateOrCreate(
                ['event_id' => $event->id, 'member_id' => $member->id],
                [
                    'attendance_status' => 'confirmed',
                    'notes' => $validated['message'] ?? null,
                ]
            );
        }

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
        // On récupère aussi la liste des participants si nécessaire pour la lecture.
        $participants = $event->participants()
            ->orderByRaw("CONCAT(members.first_name, ' ', members.last_name) ASC")
            ->get();

        return view('events.public.show', compact('event', 'participants'));
    }
}

