<?php

namespace App\Domains\Marketing\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends TenantModel
{
    use HasUlid;

    public function rules(): HasMany
    {
        return $this->hasMany(PromotionRule::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(PromotionAction::class);
    }
}
