<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends TestCase
{
    use m\Adapter\Phpunit\MockeryPHPUnitIntegration;
}
