<?php

namespace App\Domains\Core\Services\ES;

interface Event
{
    public function eventName(): string;
}
