<?php

namespace WebTheory\Erector\Action;

use WebTheory\Erector\Abstracts\GeneratesFilenamesTrait;
use WebTheory\Erector\Interfaces\BuildActionInterface;
use WebTheory\Erector\Interfaces\TextReplacementsInterface;

class FilenameChanger implements BuildActionInterface
{
    use GeneratesFilenamesTrait;

    public function __construct(protected TextReplacementsInterface $renames)
    {
        //
    }

    public function act(string $root, array $values): void
    {
        foreach ($this->renames as $file) {
            $old = $this->cleanpath($root, $file);
            $new = $this->cleanpath(
                $root,
                dirname($file),
                $this->renames->replace($file, $values)
            );

            rename($old, $new);
        }
    }
}
