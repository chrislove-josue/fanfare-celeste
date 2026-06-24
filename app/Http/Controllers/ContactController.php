<?php
// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Afficher la page de contact
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Envoyer le message de contact
     */
    public function send(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        try {
            // Données pour l'email
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? 'Non renseigné',
                'subject' => $validated['subject'],
                'message' => $validated['message'],
            ];

            // Envoyer l'email
            Mail::send('emails.contact', $data, function ($mail) use ($data) {
                $mail->to('natchougo@technodatasolutions.bj')
                    ->subject('Nouveau message de contact - Fanfare Céleste')
                    ->replyTo($data['email'], $data['name']);
            });

            return redirect()->route('contact')
                ->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');

        } catch (\Exception $e) {
            Log::error('Erreur envoi email de contact: ' . $e->getMessage());
            
            return redirect()->route('contact')
                ->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
        }
    }
}