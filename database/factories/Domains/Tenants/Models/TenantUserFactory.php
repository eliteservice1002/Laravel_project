<?php

namespace Database\Factories\Domains\Tenants\Models;

use App\Domains\Tenants\Models\TenantUser;
use Database\Factories\BaseFactory;
use Illuminate\Support\Str;

class TenantUserFactory extends BaseFactory
{
    protected $model = TenantUser::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
