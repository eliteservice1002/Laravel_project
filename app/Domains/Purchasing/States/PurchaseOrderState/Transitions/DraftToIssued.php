<?php

namespace App\Domains\Purchasing\States\PurchaseOrderState\Transitions;

use App\Domains\Inventory\Models\InventoryMovement;
use App\Domains\Purchasing\Models\PurchaseOrder;
use App\Domains\Purchasing\Models\PurchaseOrderLineItem;
use App\Domains\Purchasing\States\PurchaseOrderState\Draft;
use App\Domains\Purchasing\States\PurchaseOrderState\Issued;
use Illuminate\Support\Carbon;
use Spatie\ModelStates\Transition;

class DraftToIssued extends Transition
{
    public function __construct(
        protected PurchaseOrder $purchaseOrder,
        protected string $issueDate,
    ) {
    }

    public function handle(): PurchaseOrder
    {
        $this->purchaseOrder->issue_date = Carbon::parse($this->issueDate);

        $vendorPurchasingArea = $this->purchaseOrder->vendor->getPurchaseOrdersArea();

        $this->purchaseOrder->lineItems
            ->each(function (PurchaseOrderLineItem $lineItem) use ($vendorPurchasingArea) {
                /** @var InventoryMovement $transaction */
                $transaction = $vendorPurchasingArea->inventoryMovements()->make();

                $transaction->cause()->associate($lineItem);

                $transaction->product()->associate($lineItem->product);
                $transaction->quantity = $lineItem->quantity;
                $transaction->occurred_at = $this->purchaseOrder->issue_date;

                $transaction->save();
            });

        $this->purchaseOrder->state = Issued::class;
        $this->purchaseOrder->setSequentialCode();
        $this->purchaseOrder->save();

        return $this->purchaseOrder;
    }

    public function canTransition(): bool
    {
        return $this->purchaseOrder->state->equals(Draft::class);
    }
}
