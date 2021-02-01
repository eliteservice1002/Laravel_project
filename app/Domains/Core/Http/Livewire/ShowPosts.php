<?php

namespace App\Domains\Core\Http\Livewire;

use Livewire\Component;

class ShowPosts extends Component
{
    public string $name = 'rere';

    public array $greeting = [];

    public bool $loud = false;

    public function render()
    {
        return view('livewire.show-posts');
    }
}
