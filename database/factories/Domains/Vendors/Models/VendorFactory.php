<?php

namespace Database\Factories\Domains\Vendors\Models;

use App\Domains\Vendors\Models\Vendor;
use Database\Factories\BaseFactory;

class VendorFactory extends BaseFactory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'name' => [
                'ar' => $this->faker_ar->company,
                'en' => $this->faker->company,
            ],
        ];
    }
}
