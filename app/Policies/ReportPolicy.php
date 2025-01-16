<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Beide Rollen können Reports sehen
    }

    public function view(User $user, Report $report): bool
    {
        return true; // Beide Rollen können Reports im Detail sehen
    }

    public function create(User $user): bool
    {
        return $user->role === 'lawyer'; // Nur Anwälte können Reports erstellen
    }

    public function update(User $user, Report $report): bool
    {
        if ($user->role === 'lawyer') {
            return true; // Anwälte können alle Reports bearbeiten
        }

        // Sachbearbeiter können nur bestimmte Felder bearbeiten
        return $user->role === 'assistant';
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->role === 'lawyer'; // Nur Anwälte können Reports löschen
    }
}
