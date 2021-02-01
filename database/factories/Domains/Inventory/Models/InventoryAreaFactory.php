<?php

namespace Database\Factories\Domains\Inventory\Models;

use Database\Factories\BaseFactory;

class InventoryAreaFactory extends BaseFactory
{
    protected $model = \App\Domains\Inventory\Models\InventoryLocation::class;

    public function definition(): array
    {
        return [
            'name' => [
                'ar' => $this->faker_ar->words(3, true),
                'en' => $this->faker->words(3, true),
            ],
        ];
    }
}
