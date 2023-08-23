<?php

namespace WebTheory\Erector\Interfaces;

interface BaseResolverInterface
{
    public function getBaseFrom(string $file): string;
}
