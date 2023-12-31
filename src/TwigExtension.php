<?php
declare(strict_types=1);

/**
 * @author Youlead <service-client@youlead.fr>
 * @copyright 2023 Youlead
 * @license Apache-2.0
 */

namespace Youleadbow\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Youleadbow\Twig\Parser\IncludeDirTokenParser;
use Youleadbow\Twig\Parser\SwitchTokenParser;
use Youleadbow\Twig\Parser\UseDirTokenParser;

class TwigExtension extends AbstractExtension
{
    public function __construct(private readonly Environment $environment)
    {
    }

    public function getTokenParsers(): array
    {
        return [
            new IncludeDirTokenParser(),
            new UseDirTokenParser($this->environment),
            new SwitchTokenParser()
        ];
    }
}
