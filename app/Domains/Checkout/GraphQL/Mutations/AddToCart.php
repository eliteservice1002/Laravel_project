<?php

namespace App\Domains\Checkout\GraphQL\Mutations;

class AddToCart
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     *
     * @return mixed
     */
    public function __invoke($_, array $args)
    {
        return $args['variationId'];
    }
}
