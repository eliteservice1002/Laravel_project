<?php

namespace App\Domains\Core\Models\Concerns;

use App\Domains\Core\Models\Casts\MoneyCast;
use Illuminate\Database\Schema\Blueprint;

/**
 * @property array $casts
 * @property array $money
 */
trait HasMoney
{
    public function initializeHasMoney(): void
    {
        foreach ($this->getMoneyAttributes() as $attribute) {
            if ( ! isset($this->casts[$attribute])) {
                $this->casts[$attribute] = MoneyCast::class;
            }
        }
    }

    public static function addMoneyColumns(Blueprint $table, string $name = 'ulid'): void
    {
        $table->decimal("{$name}_amount", 15);
        $table->string("{$name}_currency");
    }

    protected function getMoneyAttributes(): array
    {
        return is_array($this->money) ? $this->money : [];
    }
}
