<?php
declare(strict_types=1);

/**
 * @author Youlead <service-client@youlead.fr>
 * @copyright 2023 Youlead
 * @license Apache-2.0
 */

namespace Youleadbow\Twig\Parser;

use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Node\Nodes;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Youleadbow\Twig\Node\SwitchNode;

/**
 * Based on rejected Twig pull request: https://github.com/twigphp/Twig/pull/185
 */
class SwitchTokenParser extends AbstractTokenParser
{
    public function getTag(): string
    {
        return 'switch';
    }

    public function parse(Token $token): SwitchNode
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $nodes = [
            'value' => $this->parser->parseExpression(),
        ];

        $stream->expect(Token::BLOCK_END_TYPE);

        // There can be some whitespace between the {% switch %} and first {% case %} tag.
        while ($stream->getCurrent()->toEnglish() === 'text' &&
            trim($stream->getCurrent()->getValue()) === ''
        ) {
            $stream->next();
        }

        $stream->expect(Token::BLOCK_START_TYPE);

        $cases = [];
        $end = false;

        while (! $end) {
            $next = $stream->next();

            switch ($next->getValue()) {
                case 'case':
                    $values = [];
                    while (true) {
                        $values[] = $this->parser->parseExpression();
                        // Multiple allowed values?
                        if ($stream->test(Token::OPERATOR_TYPE, 'or')) {
                            $stream->next();
                        } else {
                            break;
                        }
                    }
                    $stream->expect(Token::BLOCK_END_TYPE);
                    $body = $this->parser->subparse([$this, 'decideIfFork']);
                    $cases[] = new Nodes([
                        'values' => new Nodes($values),
                        'body' => $body
                    ]);
                    break;
                case 'default':
                    $stream->expect(Token::BLOCK_END_TYPE);
                    $nodes['default'] = $this->parser->subparse([$this, 'decideIfEnd']);
                    break;
                case 'endswitch':
                    $end = true;
                    break;
                default:
                    throw new SyntaxError(
                        sprintf(
                            'Unexpected end of template. Twig was looking for the following tags "case", "default", or "endswitch" to close the "switch" block started at line %d)',
                            $lineno
                        ),
                        -1
                    );
            }
        }

        $nodes['cases'] = new Nodes($cases);

        $stream->expect(Token::BLOCK_END_TYPE);

        return new SwitchNode($nodes, [], $lineno);
    }

    public function decideIfFork(Token $token): bool
    {
        return $token->test(['case', 'default', 'endswitch']);
    }

    public function decideIfEnd(Token $token): bool
    {
        return $token->test(['endswitch']);
    }
}
