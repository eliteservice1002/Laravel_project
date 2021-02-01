<?php

namespace App\Domains\Tenants\Services;

use App\Domains\Tenants\Events\StoreSet;
use App\Domains\Tenants\Events\StoreUnset;
use App\Domains\Tenants\Models\Store;
use Illuminate\Events\Dispatcher;

class StoreSwitcher
{
    protected Store $current;

    public function __construct(protected Dispatcher $dispatcher)
    {
        $this->switch(new Store());
    }

    public function current(): Store
    {
        return $this->current;
    }

    public function switch(Store $store): void
    {
        $this->current = $store;

        if ($store->exists) {
            $this->dispatcher->dispatch(new StoreSet($store));
        } else {
            $this->dispatcher->dispatch(new StoreUnset());
        }
    }

    public function executeFor(Store $store, callable $callable): void
    {
        $original = $this->current;

        $this->switch($store);

        try {
            $callable($store, $this);
        } catch (\Throwable $exception) {
            throw $exception;
        } finally {
            $this->switch($original);
        }
    }
}
