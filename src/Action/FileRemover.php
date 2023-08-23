<?php

namespace WebTheory\Erector\Action;

use WebTheory\Erector\Abstracts\GeneratesFilenamesTrait;
use WebTheory\Erector\Abstracts\GivesCommandsTrait;
use WebTheory\Erector\Interfaces\BuildActionInterface;

class FileRemover implements BuildActionInterface
{
    use GeneratesFilenamesTrait;
    use GivesCommandsTrait;

    protected array $files;

    public function __construct(string ...$files)
    {
        $this->files = $files;
    }

    public function act(string $root, array $values): void
    {
        foreach ($this->files as $file) {
            $path = $this->cleanpath($root, $file);

            $this->runCommand("rm -rf {$path}");
        }
    }
}
