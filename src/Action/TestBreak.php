<?php

namespace WebTheory\Erector\Action;

use WebTheory\Erector\Interfaces\BuildActionInterface;

class TestBreak implements BuildActionInterface
{
    public function __construct(protected bool $dump = true)
    {
        //
    }

    public function act(string $root, array $values): void
    {
        if ($this->dump) {
            dump([
                'root' => $root,
                'values' => $values,
            ]);
        }

        exit;
    }
}
