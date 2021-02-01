<?php

namespace Database\Factories\Domains\ProductCatalog\Models;

use App\Domains\ProductCatalog\Models\ProductType;
use Database\Factories\BaseFactory;

class ProductTypeFactory extends BaseFactory
{
    protected $model = ProductType::class;

    public function definition(): array
    {
        return [
            'name' => [
                'ar' => "[AR] {$this->faker->word}",
                'en' => "[EN] {$this->faker->word}",
            ],
        ];
    }
}
