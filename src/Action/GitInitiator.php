<?php

namespace WebTheory\Erector\Action;

use WebTheory\Erector\Abstracts\GivesCommandsTrait;
use WebTheory\Erector\Interfaces\BuildActionInterface;

class GitInitiator implements BuildActionInterface
{
    use GivesCommandsTrait;

    public function act(string $root, array $values): void
    {
        $this->runSuccessiveCommands(
            "rm -rf {$root}/.git",
            "git init {$root}"
        );
    }
}
