<?php

declare(strict_types = 1);

namespace Youleadbow\Twig\Node;

use Twig\Compiler;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\IncludeNode;
use Twig\Node\Node;
use Twig\Node\NodeOutputInterface;
use Youleadbow\Twig\Util;

/**
 * Class IncludeDirNode
 *
 * @package TwigIncludeDir
 */
class IncludeDirNode extends Node implements NodeOutputInterface
{
    public function __construct(
        AbstractExpression $expr,
        AbstractExpression $variables = null,
        bool $recursive = false,
        bool $only = false,
        int $lineno = 0,
        string $tag = ''
    ) {
        $nodes = ['expr' => $expr];
        if (null !== $variables) {
            $nodes['variables'] = $variables;
        }

        parent::__construct(
            $nodes,
            [
                'recursive' => $recursive,
                'only' => $only
            ],
            $lineno,
            $tag
        );
    }

    /**
     * @param Compiler $compiler
     *
     * @throws LoaderError
     */
    public function compile(Compiler $compiler): void
    {
        $loader = $compiler->getEnvironment()->getLoader();

        list($loaderPath, $files) = Util::getFileList($loader, $this->getNode('expr')->getAttribute('value'), $this->getAttribute('recursive'));
        foreach ($files as $file) {
            $file = str_replace(DIRECTORY_SEPARATOR, '/', str_replace($loaderPath, '', $file));
            $template = new IncludeNode(
                new ConstantExpression($file, $this->lineno),
                $this->hasNode('variables') ? $this->getNode('variables') : null,
                $this->getAttribute('only'),
                false,
                $this->lineno
            );

            $template->compile($compiler);
        }
    }
}
