<?php

namespace App\Domains\Core\Services\Livewire;

use Illuminate\Support\Collection;

class LivewireComponentsFinder extends \Livewire\LivewireComponentsFinder
{
    public function getClassNames(): Collection
    {
        return collect($this->path);
    }
}
