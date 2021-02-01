<?php

namespace App\Domains\Inventory\States\TransferOrderState;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class TransferOrderState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, Issued::class)
            ->allowTransition(Issued::class, Closed::class);
    }
}
