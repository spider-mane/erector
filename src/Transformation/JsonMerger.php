<?php

namespace WebTheory\Erector\Transformation;

use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;
use WebTheory\Erector\Abstracts\GeneratesFilenamesTrait;
use WebTheory\Erector\Interfaces\FileTransformationInterface;

class JsonMerger implements FileTransformationInterface
{
    use GeneratesFilenamesTrait;

    public function __construct(
        protected string $root,
        protected array $mergers
    ) {
        //
    }

    public function transform(string $file, string $contents, array $values): string
    {
        return ($merger = $this->fetchMerger($file))
            ? $this->merge($merger, $this->getBaseContents($file), $contents)
            : $contents;
    }

    protected function fetchMerger(string $base): callable|false
    {
        return $this->mergers[$base] ?? false;
    }

    protected function merge(callable $merger, string $base, string $stub): string
    {
        $params = $this->getMergerParams($merger);
        $callback = $this->getMergerArgResolver();

        return $this->encodeAndFormatJson(
            $merger(...array_map($callback, [$base, $stub], $params))
        );
    }

    protected function getMergerParams(callable $merger): array
    {
        return (new ReflectionFunction($merger))->getParameters();
    }

    protected function getMergerArgResolver(): callable
    {
        return function (string $json, ReflectionParameter $param): array|object {
            $assoc = $this->parameterTypeIsArray($param) ? true : false;

            return $this->decodeJson($json, $assoc);
        };
    }

    protected function parameterTypeIsArray(ReflectionParameter $param): bool
    {
        $type = $param->getType();

        return $type instanceof ReflectionNamedType
            && 'array' === $type->getName();
    }

    protected function encodeAndFormatJson(array $data): string
    {
        return $this->formatJson($this->encodeJson($data));
    }

    protected function formatJson(string $json): string
    {
        $default = '    ';
        $preferred = '  ';

        return str_replace($default, $preferred, $json) . "\n";
    }

    protected function encodeJson(array $data): string
    {
        $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES;

        return json_encode($data, $flags);
    }

    protected function decodeJson(string $json, bool $associative): array|object
    {
        $json = json_decode($json);

        if ($associative) {
            $this->castNonemptyObjectsToArray($json);
        }

        return $json;
    }

    protected function castNonemptyObjectsToArray(object &$json): void
    {
        $json = get_object_vars($json) ? (array) $json : $json;

        foreach ($json as &$node) {
            if (is_object($node)) {
                $this->castNonemptyObjectsToArray($node);
            }
        }
    }

    protected function getBaseContents(string $file): string
    {
        return file_get_contents($this->cleanpath($this->root, $file));
    }
}
