<?php

namespace App\Domains\ProductCatalog\Tests\Unit;

use App\Domains\Core\Tests\TestCase;
use App\Domains\ProductCatalog\Services\SkuGeneration\IdentifierGenerator;

/**
 * @internal
 * @coversNothing
 */
class IdentifierGeneratorTest extends TestCase
{
    public function testValidate(): void
    {
        $generator = new IdentifierGenerator();

        $maskAAAA = str_repeat(IdentifierGenerator::MASK_ALPHABETICAL, 4);

        $this->assertTrue($generator->isValid($maskAAAA, 'ABCD'));
        $this->assertFalse($generator->isValid($maskAAAA, 'AA-AA'));
        $this->assertFalse($generator->isValid($maskAAAA, 'AAA'));
        $this->assertFalse($generator->isValid($maskAAAA, 'AAA1'));

        $maskNNNN = str_repeat(IdentifierGenerator::MASK_NUMERIC, 4);

        $this->assertTrue($generator->isValid($maskNNNN, '1111'));
        $this->assertFalse($generator->isValid($maskNNNN, 'ABCD'));
        $this->assertFalse($generator->isValid($maskNNNN, 'AB-CD'));

        $maskNNNN = str_repeat(IdentifierGenerator::MASK_NUMERIC, 4);

        $this->assertTrue($generator->isValid($maskNNNN, '1111'));
        $this->assertFalse($generator->isValid($maskNNNN, 'ABCD'));
        $this->assertFalse($generator->isValid($maskNNNN, 'AB-CD'));
    }

    public function testGenerate(): void
    {
        $generator = new IdentifierGenerator();

        $maskNNNN = str_repeat(IdentifierGenerator::MASK_NUMERIC, 4);
        $this->assertEquals('0000', $generator->generate($maskNNNN));
        $this->assertEquals('0010', $generator->generate($maskNNNN, '0009'));
        $this->assertEquals('0011', $generator->generate($maskNNNN, '0010'));
        $this->assertEquals('9992', $generator->generate($maskNNNN, '9991'));

        $maskAAAA = str_repeat(IdentifierGenerator::MASK_ALPHABETICAL, 4);
        $this->assertEquals('AAAA', $generator->generate($maskAAAA));
        $this->assertEquals('ACCA', $generator->generate($maskAAAA, 'ACBZ'));
        $this->assertEquals('BAAA', $generator->generate($maskAAAA, 'AZZZ'));

        $maskPPPP = str_repeat(IdentifierGenerator::MASK_ALPHANUMERIC, 4);
        $this->assertEquals('0000', $generator->generate($maskPPPP));
        $this->assertEquals('A001', $generator->generate($maskPPPP, 'A000'));
    }
}
