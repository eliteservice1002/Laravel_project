<?php

namespace App\Domains\Vendors\Models;

use App\Domains\Core\Models\Concerns\HasSequentialCode;
use App\Domains\Core\Models\Concerns\HasUlid;
use App\Domains\Core\Models\TenantModel;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Purchasing\Models\PurchaseInvoice;
use App\Domains\Purchasing\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $company_name
 * @property string $vat_account_number
 * @property Collection|PurchaseOrder $purchaseOrders
 * @property Collection|PurchaseInvoice $purchaseInvoices
 * @property InventoryArea $purchasingArea
 */
class Vendor extends TenantModel
{
    use HasUlid;
    use HasSequentialCode;
    use HasTranslations;

    protected int $sequentialCodePaddingLength = 4;

    protected $fillable = [
        'code',
    ];

    protected array $translatable = ['name', 'company_name'];

    public function vendorUsers(): HasMany
    {
        return $this->hasMany(VendorUser::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function purchaseInvoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    public function getPurchaseOrdersAreaCode(): string
    {
        return $this->code.'-PO';
    }

    public function getPurchaseOrdersArea(): InventoryArea
    {
        return InventoryArea::query()
            ->where('code', $this->getPurchaseOrdersAreaCode())
            ->firstOrFail();
    }
}
