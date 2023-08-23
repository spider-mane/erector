<?php

namespace WebTheory\Erector\Action;

use WebTheory\Erector\Abstracts\GivesCommandsTrait;
use WebTheory\Erector\Interfaces\BuildActionInterface;

class AutoloadDumper implements BuildActionInterface
{
    use GivesCommandsTrait;

    public function act(string $root, array $values): void
    {
        $this->runCommand("composer dump-autoload -d {$root}");
    }
}
