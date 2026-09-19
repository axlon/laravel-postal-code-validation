<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Build\Php;

use PhpParser\BuilderFactory;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Return_;

/**
 * @see \Tests\DataProvider
 */
final class CountryDataProviderBuilder implements Builder
{
    /**
     * Create a new AST builder.
     *
     * @param \PhpParser\Node\Name $className
     * @return void
     */
    public function __construct(
        private readonly Name $className,
    ) {
    }

    /**
     * Build AST from the given country data.
     *
     * @param non-empty-array<\Axlon\PostalCodeValidation\Build\Http\AddressValidationData> $countries
     * @return \PhpParser\Node\Stmt
     */
    public function build(array $countries): Stmt
    {
        $className = $this->className->getLast();
        $namespace = $this->className->slice(0, -1);

        $factory = new BuilderFactory();
        $examples = [];

        foreach ($countries as $country) {
            if ($country->postalCode !== null) {
                $examples[$country->key] = [$country->key, $country->postalCode['examples'][0]];
            }
        }

        ksort($examples);

        $expr = $factory->val($examples);
        $expr->setAttribute(PhpPrinter::ATTRIBUTE_MULTILINE_ARRAY, true);

        return $factory->namespace($namespace)
            ->addStmt(
                $factory->class($className)
                    ->makeFinal()
                    ->setDocComment(PhpDoc::GENERATED_CLASS)
                    ->addStmt(
                        $factory->method('examples')
                            ->makePublic()
                            ->makeStatic()
                            ->setDocComment(<<<PHPDOC
                            /**
                             * @return array<string, array{string, string}>
                             */
                            PHPDOC)
                            ->setReturnType('array')
                            ->addStmt(new Return_($expr)),
                    ),
            )
            ->getNode();
    }
}
