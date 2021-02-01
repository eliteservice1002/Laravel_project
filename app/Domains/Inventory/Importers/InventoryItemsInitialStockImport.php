<?php

namespace App\Domains\Inventory\Importers;

use App\Domains\Inventory\Models\Enums\InventoryAreaType;
use App\Domains\Inventory\Models\InventoryArea;
use App\Domains\Inventory\Models\TransferOrder;
use App\Domains\ProductCatalog\Models\Enums\ProductItemIdentifierType;
use App\Domains\ProductCatalog\Models\ProductItemIdentifier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InventoryItemsInitialStockImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function __construct(
        protected InventoryArea $inventoryArea,
        protected Carbon $issueDate,
    ) {
    }

    public function collection(Collection $collection): void
    {
        /** @var InventoryArea $initialStockArea */
        $initialStockArea = $this->inventoryArea->inventoryLocation->areas()
            ->where('type', InventoryAreaType::initial())
            ->firstOrFail();

        /** @var TransferOrder $to */
        $to = TransferOrder::query()->firstOrCreate([
            'source_area_id' => $initialStockArea->id,
            'target_area_id' => $this->inventoryArea->id,
            'issue_date' => $this->issueDate,
        ]);

        $collection->each(function ($row) use ($to) {
            try {
                /** @var ProductItemIdentifier $pii */
                $pii = ProductItemIdentifier::query()
                    ->where('type', ProductItemIdentifierType::SKU())
                    ->where('value', $row['sku'])
                    ->firstOrFail();

                $to->lineItems()
                    ->updateOrCreate(['product_item_id' => $pii->product_item_id], [
                        'quantity' => $row['quantity'],
                    ]);
            } catch (ModelNotFoundException) {
                Log::info($row['sku']);
            }
        });
    }

    public function rules(): array
    {
        return [
            // 'sku' => [
            //     'required',
            //     Rule::exists('product_item_identifiers', 'value')
            //         ->where('type', ProductItemIdentifierType::SKU()),
            // ],
            'quantity' => [
                'required',
                'numeric',
            ],
        ];
    }
}
