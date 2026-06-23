<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $member = $user->member; // Utiliser la relation définie dans User

        if (!$member) {
            // Créer un membre temporaire ou afficher un message
            return view('my.profile', [
                'member' => null,
                'currentCommitteePosition' => null,
                'user' => $user,
            ]);
        }

        $currentCommitteePosition = $member->currentCommitteePosition;

        return view('my.profile', [
            'member' => $member,
            'currentCommitteePosition' => $currentCommitteePosition,
            'user' => $user,
        ]);
    }

public function updateProfile(Request $request)
{
    $user = $request->user();
    $member = $user->member;

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ]);

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
    ]);

    if ($member) {
        $member->update([
            'phone' => $validated['phone'] ?? $member->phone,
            'address' => $validated['address'] ?? $member->address,
        ]);
    }

    return redirect()->route('my.profile')
        ->with('success', 'Profil mis à jour avec succès.');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = $request->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('my.profile')
        ->with('success', 'Mot de passe mis à jour avec succès.');
}

    public function contributions(Request $request)
    {
        $user = $request->user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('my.profile')
                ->with('warning', 'Vous n\'êtes pas encore associé à un membre.');
        }

        $contributions = $member->contributions()
            ->latest()
            ->paginate(20);
            
        $totalContributions = $member->contributions()->sum('amount');
        $monthlyContributions = $member->contributions()
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        return view('my.contributions', [
            'member' => $member,
            'contributions' => $contributions,
            'totalContributions' => $totalContributions,
            'monthlyContributions' => $monthlyContributions,
            'user' => $user,
        ]);
    }

    public function transactions(Request $request)
    {
        $user = $request->user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('my.profile')
                ->with('warning', 'Vous n\'êtes pas encore associé à un membre.');
        }

        $transactions = $member->transactions()
            ->latest('transaction_date')
            ->paginate(20);
            
        $totalIncome = $member->transactions()
            ->where('type', 'income')
            ->sum('amount');
            
        $totalExpenses = $member->transactions()
            ->where('type', 'expense')
            ->sum('amount');
            
        $balance = $totalIncome - $totalExpenses;

        return view('my.transactions', [
            'member' => $member,
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'balance' => $balance,
            'user' => $user,
        ]);
    }

    public function events(Request $request)
    {
        $user = $request->user();
        $member = $user->member;

        if (!$member) {
            return redirect()->route('my.profile')
                ->with('warning', 'Vous n\'êtes pas encore associé à un membre.');
        }

        $events = $member->eventParticipations()
            ->with('event')
            ->latest()
            ->paginate(20);
            
        $totalEvents = $member->eventParticipations()->count();
        $upcomingEvents = $member->eventParticipations()
            ->whereHas('event', function($query) {
                $query->where('start_date', '>=', now());
            })
            ->count();

        return view('my.events', [
            'member' => $member,
            'events' => $events,
            'totalEvents' => $totalEvents,
            'upcomingEvents' => $upcomingEvents,
            'user' => $user,
        ]);
    }
}