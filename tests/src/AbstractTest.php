<?php

namespace ByTIC\DataTransferObject\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTest
 */
abstract class AbstractTest extends TestCase
{

    protected function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }
}
