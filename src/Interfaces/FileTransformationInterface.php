<?php

namespace WebTheory\Erector\Interfaces;

interface FileTransformationInterface
{
    public function transform(string $file, string $contents, array $values): string;
}
