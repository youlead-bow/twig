<?php
declare(strict_types=1);

/**
 * @author Youlead <service-client@youlead.fr>
 * @copyright 2022 Youlead
 * @license Apache-2.0
 */

namespace youleadbow\twig\Extension;

use Twig\Extension\AbstractExtension;
use youleadbow\twig\Parser\IncludeDirTokenParser;
use youleadbow\twig\Parser\SwitchTokenParser;

class TwigExtension extends AbstractExtension
{
    public function getTokenParsers(): array
    {
        return [
            new IncludeDirTokenParser(),
            new SwitchTokenParser()
        ];
    }
}
