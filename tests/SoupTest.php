<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AlphabetSoupTest extends TestCase
{
    public function testCanBeCreatedFromValidData(): void
    {
        $this->assertInstanceOf(
            AlphabetSoup::class,
            AlphabetSoup::__construct('RecursiveWithLimiter')
        );
    }

    
}


