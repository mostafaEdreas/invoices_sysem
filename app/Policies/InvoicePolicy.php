<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        // 1. Admin can see everything
        if ($user->hasRole('admin')) {
            return true;
        }

        // 2. Employee can only see their own invoices
        return $user->id === $invoice->user_id;
    }




    
}
