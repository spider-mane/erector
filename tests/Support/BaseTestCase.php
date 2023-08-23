<?php

declare(strict_types=1);

namespace Tests\Support;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use WebTheory\UnitUtils\Concerns\AssertionsTrait;
use WebTheory\UnitUtils\Concerns\FakeGeneratorTrait;
use WebTheory\UnitUtils\Concerns\FormattedDataSetsTrait;
use WebTheory\UnitUtils\Concerns\MockeryTrait;
use WebTheory\UnitUtils\Concerns\ProphecyTrait;
use WebTheory\UnitUtils\Concerns\SystemTrait;

abstract class BaseTestCase extends PHPUnitTestCase
{
    use AssertionsTrait;
    use FakeGeneratorTrait;
    use FormattedDataSetsTrait;
    use MockeryTrait;
    use ProphecyTrait;
    use SystemTrait;

    protected const LEVELS_TO_PROJECT_ROOT = 3;

    protected const LEVELS_TO_SUPPORT_ROOT = 1;

    protected const EXAMPLE_PATHNAME = '/example';

    protected string $testFiles;

    protected function setUp(): void
    {
        parent::setUp();

        $this->init();

        $this->testFiles = $this->getExamplePath();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->closeMockery();
        $this->tearDownProphecy();
    }

    protected function init(): void
    {
        $this->initFaker();
    }

    protected function getPathFromRoot(string $path = ''): string
    {
        return dirname(__FILE__, static::LEVELS_TO_PROJECT_ROOT) . $path;
    }

    protected function getSupportPath(string $path = '')
    {
        return dirname(__FILE__, static::LEVELS_TO_SUPPORT_ROOT) . $path;
    }

    protected function getExamplePath(string $path = ''): string
    {
        return $this->getPathFromRoot(static::EXAMPLE_PATHNAME);
    }
}
