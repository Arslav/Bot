<?php

namespace Tests\Unit\Helpers;

use Codeception\Test\Unit;
use Arslav\Bot\Helpers\AnnotationReader;

class AnnotationReaderTest extends Unit
{
    /**
     * @return void
     */
    public function testGetValueNull(): void
    {
        $helper = new AnnotationReader('test123');
        $this->assertSame(null, $helper->getValue());
    }
}

