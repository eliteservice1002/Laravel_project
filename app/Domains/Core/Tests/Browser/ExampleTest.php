<?php

namespace App\Domains\Core\Tests\Browser;

// use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Domains\Core\Tests\DuskTestCase;
use Laravel\Dusk\Browser;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Laravel');
        });
    }
}
