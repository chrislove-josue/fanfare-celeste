<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Gallery;
use App\Models\GalleryMedia;
use App\Models\Testimonial;
use App\Models\CommitteeMember;
use App\Models\Member;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Carousel; // Ajout du modèle Carousel
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
        // Récupérer les carousels actifs pour la hero section
        $carousels = Carousel::active()->ordered()->get();

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
            
        $recentGalleries = Gallery::with(['media' => function($query) {
            $query->where('is_published', true)->orderBy('order');
        }])->where('is_published', true)->latest()->take(8)->get();
        
        $recentMedia = GalleryMedia::where('is_published', true)
            ->with('gallery')
            ->latest()
            ->take(8)
            ->get();
        
        $testimonials = Testimonial::where('is_approved', true)->latest()->take(3)->get();
        $committee = CommitteeMember::with('member')
            ->where('is_current', true)
            ->orderBy('position_order')
            ->take(6)
            ->get();
        $memberCount = Member::active()->count();
        $eventCount = Event::where('status', 'completed')->count();
        $achievements = 15;
        
        // Récupérer les partenaires actifs
        $partners = Partner::active()->ordered()->get();
        $partnerCategories = Partner::getCategories();
        
        // Récupérer les pages personnalisées pour la home si elles existent
        $homePage = Page::where('slug', 'accueil')->where('is_published', true)->first();
        
        return view('home', compact(
            'carousels', // Ajout des carousels
            'upcomingEvents', 
            'latestNews', 
            'recentGalleries',
            'recentMedia',
            'testimonials', 
            'committee', 
            'memberCount', 
            'eventCount', 
            'achievements',
            'homePage',
            'partners',
            'partnerCategories'
        ));
    }

    /**
     * Page À propos - regroupe toutes les sous-pages
     */
    public function about()
    {
        // Historique
        $historyPage = Page::where('slug', 'histoire')->where('is_published', true)->first();
        
        // Mission & Vision
        $missionPage = Page::where('slug', 'mission')->where('is_published', true)->first();
        $visionPage = Page::where('slug', 'vision')->where('is_published', true)->first();
        
        // Bureau exécutif
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
        
        // Sections
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
        
        // Partenaires
        $partners = Partner::active()->ordered()->get();
        $partnerCategories = Partner::getCategories();
        
        // Page principale "À propos"
        $aboutPage = Page::where('slug', 'a-propos')->where('is_published', true)->first();
        
        return view('about', compact(
            'historyPage',
            'missionPage',
            'visionPage',
            'committeeMembers',
            'positions',
            'sections',
            'partners',
            'partnerCategories',
            'aboutPage'
        ));
    }

    /**
     * Page historique - Redirige vers about
     */
    public function history()
    {
        return redirect()->route('about');
    }

    /**
     * Page mission - Redirige vers about
     */
    public function mission()
    {
        return redirect()->route('about');
    }

    /**
     * Page vision - Redirige vers about
     */
    public function vision()
    {
        return redirect()->route('about');
    }

    /**
     * Page bureau exécutif - Redirige vers about
     */
    public function committee()
    {
        return redirect()->route('about');
    }

    /**
     * Page sections - Redirige vers about
     */
    public function sections()
    {
        return redirect()->route('about');
    }

    /**
     * Envoi du formulaire de contact (déprécié - utiliser ContactController)
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