<?php

namespace Database\Factories\Domains\Tenants\Models;

use App\Domains\Tenants\Models\Tenant;
use Database\Factories\BaseFactory;

class TenantFactory extends BaseFactory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => [
                'ar' => 'شركة تجربة #'.$this->faker->unique()->numberBetween(),
                'en' => $this->faker->company,
            ],
        ];
    }
}
