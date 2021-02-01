<?php

namespace Database\Factories;

use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

abstract class BaseFactory extends Factory
{
    protected Generator $faker_ar;

    public function __construct(
        $count = null,
        ?Collection $states = null,
        ?Collection $has = null,
        ?Collection $for = null,
        ?Collection $afterMaking = null,
        ?Collection $afterCreating = null,
        $connection = null
    ) {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection);

        $this->faker_ar = Container::getInstance()->make(Generator::class, ['locale' => 'ar_SA']);
    }

    abstract public function definition(): array;
}
