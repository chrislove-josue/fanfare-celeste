<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
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

