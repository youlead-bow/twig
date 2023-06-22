<?php

declare(strict_types=1);


namespace Youleadbow\Twig;


use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

class Util
{
    /**
     * @throws LoaderError
     */
    public static function getFileList(LoaderInterface $loader, string $directory, bool $recursive): array
    {
        if (!$loader instanceof FilesystemLoader) {
            throw new LoaderError('IncludeDir is only supported for filesystem loader!');
        }

        $includePath = '';
        $loaderPath  = '';

        foreach ($loader->getPaths() as $path) {
            if (is_dir($path . DIRECTORY_SEPARATOR . $directory)) {
                $includePath = $path . DIRECTORY_SEPARATOR . $directory;
                $loaderPath  = $path;
            }
        }

        if (empty($includePath)) {
            throw new LoaderError(
                sprintf(
                    'Unable to find template "%s" (looked into: %s).',
                    $directory,
                    implode(', ', $loader->getPaths())
                )
            );
        }

        if ($recursive) {
            $directoryIterator = new RecursiveDirectoryIterator($includePath);
            $iterator = new RecursiveIteratorIterator($directoryIterator);
            $foundFiles = new RegexIterator($iterator, '/^.+\.twig$/i', RegexIterator::GET_MATCH);

            $files = [];
            foreach ($foundFiles as $file) {
                $files[] = $file[0];
            }
        } else {
            $files = glob($includePath . '/*.twig');
        }

        sort($files);

        return [$loaderPath, $files];
    }
}