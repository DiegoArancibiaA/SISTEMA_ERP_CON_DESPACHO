<?php

namespace App\Observers;

use App\Inventory; // Nota: usa App\Inventory en lugar de App\Models\Inventory

class InventoryObserver
{
    // Métodos del observer aquí...
    public function created(Inventory $inventory)
    {
        // Lógica cuando se crea
    }

    public function updated(Inventory $inventory)
    {
        // Lógica cuando se actualiza
    }

    /**
     * Handle the Inventory "deleted" event.
     */
    public function deleted(Inventory $inventory): void
    {
        //
    }

    /**
     * Handle the Inventory "restored" event.
     */
    public function restored(Inventory $inventory): void
    {
        //
    }

    /**
     * Handle the Inventory "force deleted" event.
     */
    public function forceDeleted(Inventory $inventory): void
    {
        //
    }
}