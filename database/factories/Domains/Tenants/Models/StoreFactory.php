<?php

namespace Database\Factories\Domains\Tenants\Models;

use App\Domains\Tenants\Models\Store;
use Database\Factories\BaseFactory;

class StoreFactory extends BaseFactory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'name' => [
                'ar' => $this->faker_ar->name,
                'en' => $this->faker->name,
            ],
        ];
    }
}
