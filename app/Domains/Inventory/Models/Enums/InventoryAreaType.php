<?php

namespace App\Domains\Inventory\Models\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self initial()
 * @method static self receiving()
 * @method static self inspection()
 * @method static self stock_storage()
 * @method static self purchase_orders()
 */
class InventoryAreaType extends Enum
{
}
