<?php

namespace App\Observers;

use App\Models\Absence;

class AbsenceObserver
{
    /**
     * Handle the Absence "created" event.
     */
    public function created(Absence $absence): void
    {
        //
    }

    /**
     * Handle the Absence "creating" event.
     * Runs before the record is saved to database
     */
    public function creating(Absence $absence): void
    {
        $absence->created_by = auth()->id();
    }

    /**
     * Handle the Absence "updated" event.
     */
    public function updated(Absence $absence): void
    {
        //
    }

    /**
     * Handle the Absence "deleted" event.
     */
    public function deleted(Absence $absence): void
    {
        //
    }

    /**
     * Handle the Absence "restored" event.
     */
    public function restored(Absence $absence): void
    {
        //
    }

    /**
     * Handle the Absence "force deleted" event.
     */
    public function forceDeleted(Absence $absence): void
    {
        //
    }
}
