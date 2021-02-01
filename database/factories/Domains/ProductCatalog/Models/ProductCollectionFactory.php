<?php

namespace Database\Factories\Domains\ProductCatalog\Models;

use Database\Factories\BaseFactory;

class ProductCollectionFactory extends BaseFactory
{
    protected $model = \App\Domains\ProductCatalog\Models\ProductCollection::class;

    public function definition(): array
    {
        return [
            'name' => [
                'ar' => "[AR] {$this->faker->word}",
                'en' => "[EN] {$this->faker->word}",
            ],
            'description' => [
                'ar' => "[AR] {$this->faker->word}",
                'en' => "[EN] {$this->faker->word}",
            ],
            'slug' => [
                'ar' => 'ar-slug',
                'en' => 'en-slug',
            ],
        ];
    }
}
