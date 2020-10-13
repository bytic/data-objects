<?php

namespace ByTIC\DataObjects\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTest
 * @package ByTIC\DataObjects\Tests
 */
abstract class AbstractTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }
}
