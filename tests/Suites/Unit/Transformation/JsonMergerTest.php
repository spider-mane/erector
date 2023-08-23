<?php

namespace Tests\Suites\Unit\Transformation;

use Closure;
use Tests\Support\UnitTestCase;
use WebTheory\Erector\Interfaces\FileTransformationInterface;
use WebTheory\Erector\Transformation\JsonMerger;
use WebTheory\UnitUtils\Partials\HasExpectedTypes;

class JsonMergerTest extends UnitTestCase
{
    use HasExpectedTypes;

    protected JsonMerger $sut;

    protected Closure $merger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->merger = function (array $base, array $stub) {
            return array_merge_recursive($base, $stub);
        };

        $this->sut = new JsonMerger($this->testFiles, [
            'composer.json' => $this->merger,
        ]);
    }

    protected function defineExpectedTypesData(callable $ds): array
    {
        return [
            $ds($i = FileTransformationInterface::class) => [$i],
        ];
    }
}
