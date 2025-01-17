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
        return $user->canViewReports();
    }

    public function view(User $user, Report $report): bool
    {
        return $user->canViewReports();
    }

    public function create(User $user): bool
    {
        return $user->canCreateReports();
    }

    public function update(User $user, Report $report): bool
    {
        return $user->canEditReports();
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->canDeleteReports();
    }

    public function export(User $user): bool
    {
        return $user->canExportReports();
    }
}
