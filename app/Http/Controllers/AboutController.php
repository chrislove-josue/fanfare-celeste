<?php
// app/Http/Controllers/AboutController.php

namespace App\Http\Controllers;

use App\Models\CommitteeMember;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        // Récupérer les membres du comité actifs
        $positionOrder = CommitteeMember::getPositionOrder();
        
        $committeeMembers = CommitteeMember::with('member')
            ->where('is_current', true)
            ->get()
            ->sortBy(function($item) use ($positionOrder) {
                return $positionOrder[$item->position] ?? 99;
            });
        
        $positions = CommitteeMember::getPositions();
        
        return view('pages.about', compact('committeeMembers', 'positions'));
    }
}