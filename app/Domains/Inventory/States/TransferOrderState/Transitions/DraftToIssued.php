<?php

namespace App\Domains\Inventory\States\TransferOrderState\Transitions;

use App\Domains\Inventory\Models\Enums\InventoryAreaType;
use App\Domains\Inventory\Models\InventoryMovement;
use App\Domains\Inventory\Models\TransferOrder;
use App\Domains\Inventory\Models\TransferOrderLineItem;
use App\Domains\Inventory\States\TransferOrderState\Draft;
use App\Domains\Inventory\States\TransferOrderState\Issued;
use App\Domains\Purchasing\Models\PurchaseOrder;
use App\Domains\Purchasing\States\PurchaseOrderState\Issued as PurchaseOrderIssued;
use App\Domains\Vendors\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\ModelStates\Transition;

class DraftToIssued extends Transition
{
    public function __construct(
        protected TransferOrder $transferOrder,
        protected string $issueDate,
    ) {
    }

    public function handle(): TransferOrder
    {
        $this->transferOrder->issue_date = Carbon::parse($this->issueDate);

        if ($this->transferOrder->sourceArea->type->equals(InventoryAreaType::purchase_orders())) {
            $vendorCode = Str::before($this->transferOrder->sourceArea->code, '-PO');
            /** @var Vendor $vendor */
            $vendor = Vendor::query()
                ->where('code', $vendorCode)
                ->firstOrFail();

            $purchaseOrders = $vendor->purchaseOrders()
                ->where('state', PurchaseOrderIssued::class)
                ->get()
                ->mapWithKeys(fn (PurchaseOrder $order) => [$order->id, $order->lineItems]);
        }

        $this->transferOrder->lineItems
            ->each(function (TransferOrderLineItem $lineItem) {
                /** @var InventoryMovement $sourceTransaction */
                $sourceTransaction = $this->transferOrder->sourceArea->inventoryMovements()->make();
                $sourceTransaction->cause()->associate($lineItem);
                $sourceTransaction->product()->associate($lineItem->product);
                $sourceTransaction->quantity = $lineItem->quantity * -1;
                $sourceTransaction->occurred_at = $this->transferOrder->issue_date;
                $sourceTransaction->save();

                /** @var InventoryMovement $targetTransaction */
                $targetTransaction = $this->transferOrder->targetArea->inventoryMovements()->make();
                $targetTransaction->cause()->associate($lineItem);
                $targetTransaction->product()->associate($lineItem->product);
                $targetTransaction->quantity = $lineItem->quantity;
                $targetTransaction->occurred_at = $this->transferOrder->issue_date;
                $targetTransaction->save();
            });

        $this->transferOrder->state = Issued::class;
        $this->transferOrder->setSequentialCode();
        $this->transferOrder->save();

        return $this->transferOrder;
    }

    public function canTransition(): bool
    {
        return $this->transferOrder->state->equals(Draft::class);
    }
}
