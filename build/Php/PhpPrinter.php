<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Build\Php;

use PhpParser\Node\Expr;
use PhpParser\PrettyPrinter\Standard;

final class PhpPrinter extends Standard
{
    /**
     * Indicates whether an array expression should be printed as multiline.
     */
    public const ATTRIBUTE_MULTILINE_ARRAY = 'is_multiline_array';

    /**
     * Print the given array expression.
     *
     * @param \PhpParser\Node\Expr\Array_ $node
     * @return string
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    protected function pExpr_Array(Expr\Array_ $node): string
    {
        if ($node->getAttribute(self::ATTRIBUTE_MULTILINE_ARRAY) === true) {
            return '[' . $this->pCommaSeparatedMultiline($node->items, trailingComma: true) . $this->nl . ']';
        }

        return parent::pExpr_Array($node);
    }
}
