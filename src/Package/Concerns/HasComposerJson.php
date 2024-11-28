<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Crate\Package\Concerns;

use Spatie\LaravelPackageTools\Package;

trait HasComposerJson
{
    protected function getPackageManifest(Package $package): array
    {
        $composerJsonPath = \realpath($package->basePath('/../composer.json'));

        if ($composerJsonPath === false) {
            throw new \RuntimeException('This package does not have a composer.json file.');
        }

        $contents = \file_get_contents($composerJsonPath);

        if ($contents === false) {
            throw new \RuntimeException('Unable to read composer.json file.');
        }

        $decoded = \json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        if (!\is_array($decoded)) {
            throw new \RuntimeException('Unable to decode composer.json file.');
        }

        return $decoded;
    }

    protected function getPackageName(Package $package): string
    {
        return \explode('/', (string) $this->getPackageManifest($package)['name'])[1];
    }

    protected function getPackageNamespace(Package $package): string
    {
        $namespace = $this->getPackageManifest($package)['autoload']['psr-4'];

        if (\is_array($namespace)) {
            return (string) \array_key_first($namespace);
        }

        throw new \RuntimeException('This package does not have a namespace.');
    }
}
