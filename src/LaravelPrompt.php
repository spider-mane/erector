<?php

namespace WebTheory\Erector;

use Laravel\Prompts\Prompt;
use Laravel\Prompts\TextPrompt;
use WebTheory\Erector\Abstracts\TextInterpolatorTrait;
use WebTheory\Erector\Interfaces\ValueProviderInterface;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\confirm;

class LaravelPrompt implements ValueProviderInterface
{
    use TextInterpolatorTrait;

    /**
     * @param array<string,Prompt|callable> $fields
     */
    public function __construct(protected array $fields, protected string $consent)
    {
        //
    }

    public function getValues(): array
    {
        $values = [];

        foreach ($this->fields as $key => $field) {
            if ($field instanceof Prompt) {
                $value = $this->adjustPrompt($field, $values)->prompt();
            } else {
                $value = $field($values);
            }

            if (is_string($key)) {
                $values[$key] = $value;
            }
        }

        alert("Please check that everything is correct:");

        if (!$this->getConfirmation()) {
            exit;
        }

        return $values;
    }

    protected function adjustPrompt(Prompt $prompt, array $values): Prompt
    {
        $prompt = clone $prompt;

        $fixer = function (callable $update): void {
            /** @var Prompt $this */
            if ($this instanceof TextPrompt) {
                unset($this->listeners['key']);
                $this->trackTypedValue($update($this->value())); // @phpstan-ignore-line
            }
        };

        $fixer->call($prompt, fn ($text) => $this->interpolate($text, $values));

        return $prompt;
    }

    protected function getConfirmation(): bool
    {
        return confirm($this->consent, true);
    }
}
