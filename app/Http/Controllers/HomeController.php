<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Testimonial;
use App\Models\CommitteeMember;
use App\Models\Member;
use App\Models\Page; // Ajout du modèle Page
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil
     */
    public function index()
    {
        $upcomingEvents = Event::upcoming()->take(3)->get();

        $membersTotal = Member::query()->count();
        $publicRegistrations = \App\Models\EventPublicRegistration::query()
            ->whereIn('event_id', $upcomingEvents->pluck('id'))
            ->selectRaw('event_id, COUNT(*) as total')
            ->groupBy('event_id')
            ->pluck('total', 'event_id');

        $upcomingEvents->transform(function (Event $event) use ($membersTotal, $publicRegistrations) {
            $event->publicRegistrationsCount = (int) ($publicRegistrations[$event->id] ?? 0);
            $event->totalParticipants = $membersTotal + $event->publicRegistrationsCount;
            return $event;
        });
        
        $latestNews = News::where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();
            
        $recentGalleries = Gallery::latest()->take(6)->get();
        $testimonials = Testimonial::where('is_approved', true)->latest()->take(3)->get();
        $committee = CommitteeMember::with('member')
            ->where('is_current', true)
            ->orderBy('position_order')
            ->take(6)
            ->get();
        $memberCount = Member::active()->count();
        $eventCount = Event::where('status', 'completed')->count();
        $achievements = 15;
        
        // Récupérer les pages personnalisées pour la home si elles existent
        $homePage = Page::where('slug', 'accueil')->where('is_published', true)->first();
        
        return view('home', compact(
            'upcomingEvents', 'latestNews', 'recentGalleries',
            'testimonials', 'committee', 'memberCount', 'eventCount', 'achievements',
            'homePage'
        ));
    }

    /**
     * Page historique - Utilise le contenu dynamique ou la vue statique
     */
    public function history()
    {
        $page = Page::where('slug', 'histoire')->where('is_published', true)->first();
        return view('about.history', compact('page'));
    }

    /**
     * Page mission
     */
    public function mission()
    {
        $page = Page::where('slug', 'mission')->where('is_published', true)->first();
        return view('about.mission', compact('page'));
    }

    /**
     * Page vision
     */
    public function vision()
    {
        $page = Page::where('slug', 'vision')->where('is_published', true)->first();
        return view('about.vision', compact('page'));
    }

    /**
     * Page bureau exécutif
     */
    public function committee()
    {
        $committeeMembers = CommitteeMember::with('member')
            ->where('is_current', true)
            ->orderBy('position_order')
            ->get();
            
        $positions = [
            'president' => 'Président',
            'vice_president' => 'Vice-président',
            'general_secretary' => 'Secrétaire général',
            'deputy_secretary' => 'Secrétaire adjoint',
            'treasurer' => 'Trésorier',
            'deputy_treasurer' => 'Trésorier adjoint',
            'conductor' => 'Chef de fanfare',
            'deputy_conductor' => 'Chef adjoint',
            'section_leader_trumpet' => 'Responsable Trompettes',
            'section_leader_trombone' => 'Responsable Trombones',
            'section_leader_saxophone' => 'Responsable Saxophones',
            'section_leader_percussion' => 'Responsable Percussions',
        ];
        
        $page = Page::where('slug', 'bureau-executif')->where('is_published', true)->first();
        
        return view('about.committee', compact('committeeMembers', 'positions', 'page'));
    }

    /**
     * Page sections
     */
    public function sections()
    {
        $sections = [
            'trumpet' => [
                'name' => 'Trompettes',
                'description' => 'La section des trompettes apporte éclat et puissance à notre musique.',
                'instruments' => ['Trompette Sib', 'Trompette piccolo', 'Cornet'],
                'members_count' => 25,
                'leader' => 'Frère Jean Mendy',
                'icon' => 'fa-trumpet'
            ],
            'trombone' => [
                'name' => 'Trombones',
                'description' => 'Les trombones ajoutent de la profondeur et de la richesse à notre sonorité.',
                'instruments' => ['Trombone ténor', 'Trombone basse'],
                'members_count' => 15,
                'leader' => 'Frère Paul Dossou',
                'icon' => 'fa-music'
            ],
            'saxophone' => [
                'name' => 'Saxophones',
                'description' => 'La chaleur des saxophones apporte une touche mélodique unique.',
                'instruments' => ['Saxophone alto', 'Saxophone ténor', 'Saxophone baryton'],
                'members_count' => 18,
                'leader' => 'Frère Marc Zinsou',
                'icon' => 'fa-saxophone'
            ],
            'percussion' => [
                'name' => 'Percussions',
                'description' => 'Le rythme et l\'énergie de notre fanfare viennent des percussions.',
                'instruments' => ['Batterie', 'Caisse claire', 'Grosse caisse', 'Cymbales'],
                'members_count' => 20,
                'leader' => 'Frère David Agossou',
                'icon' => 'fa-drum'
            ]
        ];
        
        $page = Page::where('slug', 'sections')->where('is_published', true)->first();
        
        return view('about.sections', compact('sections', 'page'));
    }

    /**
     * Page contact
     */
    public function contact()
    {
        $page = Page::where('slug', 'contact')->where('is_published', true)->first();
        return view('contact', compact('page'));
    }

    /**
     * Envoi du formulaire de contact
     */
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        try {
            Mail::send('emails.contact', $validated, function ($mail) use ($validated) {
                $mail->to('contact@fanfare-celeste.com')
                    ->subject($validated['subject'])
                    ->replyTo($validated['email'], $validated['name']);
            });

            return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur envoi email: ' . $e->getMessage());
            return redirect()->route('contact')->with('error', 'Une erreur est survenue.');
        }
    }

    /**
     * Inscription à la newsletter
     */
    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return redirect()->route('home')->with('success', 'Inscription à la newsletter réussie !');
    }
    
    /**
     * Affichage d'une page dynamique par slug
     */
    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        return view('pages.show', compact('page'));
    }
}