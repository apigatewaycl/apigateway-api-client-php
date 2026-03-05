<?php

declare(strict_types=1);

namespace Tests\Helpers;

use PHPUnit\Framework\SkippedTestSuiteError;

trait RequiresEnvironment
{
    protected static function requireEnv(string $str_var): void
    {
        $value =
            $_ENV[$str_var]
            ?? $_SERVER[$str_var]
            ?? getenv($str_var);

        if ($value == false || $value == null || $value == '') {
            throw new SkippedTestSuiteError(
                sprintf($str_var . ' no está definido.')
            );
        }
    }
}
