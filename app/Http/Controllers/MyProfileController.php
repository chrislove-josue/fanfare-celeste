<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MyProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $member = $user->member()->first();

        if (! $member) {
            abort(404, 'Member introuvable pour cet utilisateur.');
        }

        $currentCommitteePosition = $member->currentCommitteePosition()->first();

        return view('my.profile', [
            'member' => $member,
            'currentCommitteePosition' => $currentCommitteePosition,
        ]);
    }

    public function contributions(Request $request)
    {
        $user = $request->user();
        $member = $user->member()->first();

        if (! $member) {
            abort(404, 'Member introuvable pour cet utilisateur.');
        }

        $contributions = $member->contributions()->latest()->paginate(20);

        return view('my.contributions', [
            'member' => $member,
            'contributions' => $contributions,
        ]);
    }

    public function transactions(Request $request)
    {
        $user = $request->user();
        $member = $user->member()->first();

        if (! $member) {
            abort(404, 'Member introuvable pour cet utilisateur.');
        }

        $transactions = $member->transactions()->latest()->paginate(20);

        return view('my.transactions', [
            'member' => $member,
            'transactions' => $transactions,
        ]);
    }

    public function events(Request $request)
    {
        $user = $request->user();
        $member = $user->member()->first();

        if (! $member) {
            abort(404, 'Member introuvable pour cet utilisateur.');
        }

        $events = $member->eventParticipations()
            ->with('event')
            ->latest()
            ->paginate(20);

        return view('my.events', [
            'member' => $member,
            'events' => $events,
        ]);
    }
}

