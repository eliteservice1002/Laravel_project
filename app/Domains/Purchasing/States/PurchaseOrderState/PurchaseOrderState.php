<?php

namespace App\Domains\Purchasing\States\PurchaseOrderState;

use App\Domains\Purchasing\States\PurchaseOrderState\Transitions\DraftToIssued;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

class PurchaseOrderState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, Issued::class, DraftToIssued::class)
            ->allowTransition(Issued::class, Closed::class);
    }
}
