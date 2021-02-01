<?php

namespace App\Domains\Tenants\Observers;

use App\Domains\Core\Models\BaseTenantUser;
use App\Domains\Tenants\Models\InternalComment;
use Illuminate\Support\Facades\Auth;

class InternalCommentObserver
{
    public function creating(InternalComment $comment): void
    {
        if (Auth::check()) {
            /** @var BaseTenantUser $user */
            $user = Auth::user();

            $comment->author()->associate($user);
        }
    }
}
