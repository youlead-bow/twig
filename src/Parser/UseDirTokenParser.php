<?php

declare(strict_types = 1);

namespace Youleadbow\Twig\Parser;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;
use Twig\Node\EmptyNode;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\Node\Nodes;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Youleadbow\Twig\Util;

/**
 * Class UseDirTokenParser
 *
 * @package TwigUseDir
 */
class UseDirTokenParser extends AbstractTokenParser
{
    public function __construct(private readonly Environment $environment)
    {
    }

    /**
     * Parses a token and returns a node.
     *
     * @param Token $token
     *
     * @return Node
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function parse(Token $token): Node
    {
        $expr = $this->parser->parseExpression();
        $stream = $this->parser->getStream();

        if (!$expr instanceof ConstantExpression) {
            throw new SyntaxError('The template references in a "useDir" statement must be a string.', $stream->getCurrent()->getLine());
        }

        $recursive = false;
        if ($stream->nextIf(Token::NAME_TYPE, 'recursive')) {
            $recursive = true;
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        $loader = $this->environment->getLoader();
        list($loaderPath, $files) = Util::getFileList($loader, $expr->getAttribute('value'), $recursive);
        foreach ($files as $file) {
            $file = str_replace(DIRECTORY_SEPARATOR, '/', str_replace($loaderPath, '', $file));
            $template = new ConstantExpression($file, 0);
            $this->parser->addTrait(new Nodes(['template' => $template, 'targets' => new EmptyNode()]));
        }

        return new EmptyNode();
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag(): string
    {
        return 'useDir';
    }
}