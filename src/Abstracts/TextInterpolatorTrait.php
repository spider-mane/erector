<?php

namespace WebTheory\Erector\Abstracts;

trait TextInterpolatorTrait
{
    protected function interpolate(string $text, array $values)
    {
        if (!preg_match_all('/\{(\w+)\}/', $text, $m)) {
            return $text;
        }

        foreach ($m[0] as $k => $str) {
            $f = $m[1][$k];
            $text = str_replace($str, $values[$f], $text);
        }

        return $text;
    }
}
