# TODO
- [x] Analyse seeders et exécution `php artisan db:seed --class=Database\Seeders\DatabaseSeeder --no-interaction`
- [ ] Ajouter l’accès utilisateur connecté à ses infos personnelles
  - [ ] Créer `app/Http/Controllers/MyProfileController.php`
  - [ ] Ajouter routes: `/my/profile`, `/my/contributions`, `/my/transactions`, `/my/events` (middleware `auth`)
  - [ ] Créer views:
    - [ ] `resources/views/my/profile.blade.php`
    - [ ] (éventuellement) pages dédiées pour contributions/transactions/events
  - [ ] Vérifier relations Eloquent: `auth()->user()->member`, `member->contributions`, `member->transactions`, `member->eventParticipations`