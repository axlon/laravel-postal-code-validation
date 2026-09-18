<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Build\Php;

use PhpParser\Node\Stmt;

interface Builder
{
    /**
     * Build AST from the given country data.
     *
     * @param non-empty-array<\Axlon\PostalCodeValidation\Build\Http\AddressValidationData> $countries
     * @return \PhpParser\Node\Stmt
     */
    public function build(array $countries): Stmt;
}
