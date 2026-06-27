<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\EventPublicRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicEventController extends Controller
{
    public function participateForm(Event $event)
    {
        $user = Auth::user();
        $prefilledData = [];
        
        if ($user) {
            // Récupérer les informations du membre lié à l'utilisateur
            $member = $user->member;
            
            if ($member) {
                // Pré-remplir avec les données du membre
                $prefilledData = [
                    'first_name' => $member->first_name ?? '',
                    'last_name' => $member->last_name ?? '',
                    'phone' => $member->phone ?? '',
                    'whatsapp' => $member->whatsapp ?? '',
                ];
            } else {
                // Si l'utilisateur n'a pas de membre associé, utiliser les données de l'utilisateur
                $prefilledData = [
                    'first_name' => explode(' ', $user->name)[0] ?? '',
                    'last_name' => explode(' ', $user->name)[1] ?? '',
                    'phone' => '',
                    'whatsapp' => '',
                ];
            }
        }
        
        return view('events.public.participate', [
            'event' => $event,
            'prefilledData' => $prefilledData,
        ]);
    }

    public function participate(Request $request, Event $event)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
        ]);

        // Vérifier si l'utilisateur est déjà inscrit avec ce numéro de téléphone
        $existingRegistration = EventPublicRegistration::where('event_id', $event->id)
            ->where('phone', $validated['phone'])
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'Ce numéro de téléphone est déjà inscrit à cet événement.')
                         ->withInput();
        }

        // Créer l'inscription
        EventPublicRegistration::create([
            'event_id' => $event->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'whatsapp_number' => $validated['whatsapp'] ?? $validated['phone'],
            'status' => 'confirmed',
        ]);

        return redirect()->route('public.events.show', $event)
            ->with('success', 'Votre inscription a été enregistrée avec succès. Merci !');
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
        $membersTotal = Member::query()->count();

        $participants = $event->participants()
            ->orderByRaw("CONCAT(members.first_name, ' ', members.last_name) ASC")
            ->get();

        $publicRegistrationsCount = EventPublicRegistration::query()
            ->where('event_id', $event->id)
            ->count();

        $totalParticipants = $membersTotal + $publicRegistrationsCount;

        return view('events.public.show', compact('event', 'participants', 'publicRegistrationsCount', 'totalParticipants', 'membersTotal'));
    }
}