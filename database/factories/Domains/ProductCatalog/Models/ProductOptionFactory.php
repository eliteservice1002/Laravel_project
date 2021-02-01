<?php

namespace Database\Factories\Domains\ProductCatalog\Models;

use App\Domains\ProductCatalog\Models\ProductAttribute;
use Database\Factories\BaseFactory;

class ProductOptionFactory extends BaseFactory
{
    protected $model = ProductAttribute::class;

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
