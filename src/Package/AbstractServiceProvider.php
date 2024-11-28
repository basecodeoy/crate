<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Crate\Package;

use BaseCodeOy\Crate\Package\Concerns\HasAutomaticConfiguration;
use BaseCodeOy\Crate\Package\Concerns\HasBootableTraits;
use BaseCodeOy\Crate\Package\Concerns\HasComposerJson;
use Spatie\LaravelPackageTools\PackageServiceProvider;

abstract class AbstractServiceProvider extends PackageServiceProvider
{
    use HasAutomaticConfiguration;
    use HasBootableTraits;
    use HasComposerJson;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->bootTraits();
    }

    protected function getPackageBaseDirectory(): string
    {
        $fileName = (new \ReflectionClass(static::class))->getFileName();

        if (\is_string($fileName)) {
            return \dirname($fileName);
        }

        throw new \RuntimeException('Could not determine the base directory of the package.');
    }
}
