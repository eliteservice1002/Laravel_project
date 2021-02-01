<?php

namespace App\Domains\Vendors\Models;

use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Tenants\Models\TenantUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int vendor_id
 * @property string $code
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property Vendor $vendor
 */
class VendorUser extends TenantUser
{
    use HasUlid;
    use HasTranslations;

    protected array $translatable = ['name'];

    protected $fillable = [
        'code',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
