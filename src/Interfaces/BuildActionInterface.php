<?php

namespace WebTheory\Erector\Interfaces;

interface BuildActionInterface
{
    public function act(string $root, array $values): void;
}
