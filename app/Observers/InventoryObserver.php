<?php

namespace App\Observers;

use App\AlphaERP; // Nota: usa App\AlphaERP en lugar de App\Models\AlphaERP

class AlphaERPObserver
{
    // Métodos del observer aquí...
    public function created(AlphaERP $AlphaERP)
    {
        // Lógica cuando se crea
    }

    public function updated(AlphaERP $AlphaERP)
    {
        // Lógica cuando se actualiza
    }

    /**
     * Handle the AlphaERP "deleted" event.
     */
    public function deleted(AlphaERP $AlphaERP): void
    {
        //
    }

    /**
     * Handle the AlphaERP "restored" event.
     */
    public function restored(AlphaERP $AlphaERP): void
    {
        //
    }

    /**
     * Handle the AlphaERP "force deleted" event.
     */
    public function forceDeleted(AlphaERP $AlphaERP): void
    {
        //
    }
}