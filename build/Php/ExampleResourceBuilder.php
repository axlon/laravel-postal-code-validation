<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Build\Php;

use Axlon\PostalCodeValidation\Build\Http\AddressValidationData;
use PhpParser\BuilderFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Return_;

final readonly class ExampleResourceBuilder implements Builder
{
    /**
     * Build AST from the given country data.
     *
     * @param non-empty-array<\Axlon\PostalCodeValidation\Build\Http\AddressValidationData> $countries
     * @return \PhpParser\Node\Stmt
     */
    public function build(array $countries): Stmt
    {
        usort($countries, static fn (AddressValidationData $a, AddressValidationData $b) => $a->key <=> $b->key);

        $data = [];

        foreach ($countries as $country) {
            if ($country->postalCode !== null) {
                $data[$country->key] = $country->postalCode['examples'][0];
            }
        }

        $expr = (new BuilderFactory())->val($data);
        $expr->setAttribute(PhpPrinter::ATTRIBUTE_MULTILINE_ARRAY, true);

        $return = new Return_($expr);
        $return->setDocComment(new Doc(<<<'PHPDOC'
        /*
        | This file contains data derived from Google's Address Validation Metadata (CC-BY 4.0).
        | For attribution and licensing information, see the README.
        |
        | This file is generated automatically. Please do not edit it directly;
        | pull requests containing changes to this file will not be accepted.
        */
        PHPDOC));

        return $return;
    }
}
