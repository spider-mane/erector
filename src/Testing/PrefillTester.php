<?php

namespace WebTheory\Erector\Testing;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PrefillTester
{
    public function __construct(
        protected string $root,
        protected string $mirror,
        protected string $prefill,
        protected array $commands = [],
    ) {
    }

    public function cloneRoot(): static
    {
        $filesystem = new Filesystem();
        $files = Finder::create()
            ->in($this->root)
            ->files()
            ->ignoreDotFiles(false)
            ->ignoreVCS(false)
            ->ignoreVCSIgnored(true);

        $filesystem->remove($this->mirror);
        $filesystem->mirror($this->root, $this->mirror, $files);

        return $this;
    }

    public function runPrefill(array $answers): static
    {
        chdir($this->mirror);

        if ($this->commands) {
            shell_exec(implode(" && ", $this->commands));
        }

        (static function ($__prefill__, $__answers__) {
            extract($__answers__);
            require $__prefill__;
        })($this->prefill, $answers);

        return $this;
    }

    public static function create(string $root, string $mirror, string $prefill, array $commands): static
    {
        return new static($root, $mirror, $prefill, $commands);
    }
}
