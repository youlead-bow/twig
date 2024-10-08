<?php
declare(strict_types=1);

/**
 * @author Youlead <service-client@youlead.fr>
 * @copyright 2023 Youlead
 * @license Apache-2.0
 */

namespace Youleadbow\Twig\Node;

use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\Node;

/**
 * Based on rejected Twig pull request: https://github.com/twigphp/Twig/pull/185
 */
#[YieldReady]
class SwitchNode extends Node
{
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write('switch (')
            ->subcompile($this->getNode('value'))
            ->raw(") {\n")
            ->indent();

        foreach ($this->getNode('cases') as $case) {
            /** @var Node $case */
            // The 'body' node may have been removed by Twig if it was an empty text node in a sub-template,
            // outside any blocks
            if (! $case->hasNode('body')) {
                continue;
            }

            foreach ($case->getNode('values') as $value) {
                $compiler
                    ->write('case ')
                    ->subcompile($value)
                    ->raw(":\n");
            }

            $compiler
                ->write("{\n")
                ->indent()
                ->subcompile($case->getNode('body'))
                ->write("break;\n")
                ->outdent()
                ->write("}\n");
        }

        if ($this->hasNode('default')) {
            $compiler
                ->write("default:\n")
                ->write("{\n")
                ->indent()
                ->subcompile($this->getNode('default'))
                ->outdent()
                ->write("}\n");
        }

        $compiler
            ->outdent()
            ->write("}\n");
    }
}
