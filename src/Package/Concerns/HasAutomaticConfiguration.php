<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Crate\Package\Concerns;

use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Symfony\Component\Finder\SplFileInfo;

trait HasAutomaticConfiguration
{
    public function configurePackage(Package $package): void
    {
        $package->name($this->getPackageName($package));

        if ($this->hasConfigFile($package)) {
            $package->hasConfigFile();
        }

        if ($this->hasMigrations($package)) {
            $package->hasMigrations($this->getMigrations($package));
        }

        if ($this->hasViews($package)) {
            $package->hasViews();
        }

        if ($this->hasTranslations($package)) {
            $package->hasTranslations();
        }

        if ($this->hasAssets($package)) {
            $package->hasAssets();
        }

        if ($this->hasRoutes($package)) {
            $package->hasRoutes($this->getRoutes($package));
        }

        if ($this->hasCommands($package)) {
            $package->hasCommands($this->getCommands($package));
        }

        $package->hasInstallCommand(function (InstallCommand $installCommand) use ($package): void {
            if ($this->hasConfigFile($package)) {
                $installCommand->publishConfigFile();
            }

            if ($this->hasMigrations($package)) {
                $installCommand->publishMigrations();
            }

            $installCommand->copyAndRegisterServiceProviderInApp();
        });
    }

    protected function hasConfigFile(Package $package): bool
    {
        $files = $this->getFilesFromDirectory($package, 'config');

        if (\count($files) === 0) {
            return false;
        }

        return \in_array($this->getPackageName($package), $files, true);
    }

    protected function hasMigrations(Package $package): bool
    {
        return $this->hasFilesInDirectory($package, 'database/migrations');
    }

    protected function getMigrations(Package $package): array
    {
        return \array_map(
            fn (string $migration): string => \mb_substr($migration, 0, -4),
            $this->getFilesFromDirectory($package, 'database/migrations'),
        );
    }

    protected function hasViews(Package $package): bool
    {
        return $this->hasFilesInDirectory($package, 'resources/views');
    }

    protected function hasTranslations(Package $package): bool
    {
        return $this->hasFilesInDirectory($package, 'resources/lang');
    }

    protected function hasAssets(Package $package): bool
    {
        return $this->hasFilesInDirectory($package, 'resources/dist');
    }

    protected function hasRoutes(Package $package): bool
    {
        return $this->hasFilesInDirectory($package, 'routes');
    }

    protected function getRoutes(Package $package): array
    {
        return $this->getFilesFromDirectory($package, 'routes');
    }

    protected function hasCommands(Package $package): bool
    {
        return $this->hasFilesInDirectory($package, 'src/Commands');
    }

    protected function getCommands(Package $package): array
    {
        $namespace = $this->getPackageNamespace($package);

        return collect($this->getFilesFromDirectory($package, 'src/Commands'))
            ->map(fn (string $className): string => \sprintf('%sCommands\%s', $namespace, $className))
            ->toArray();
    }

    protected function hasFilesInDirectory(Package $package, string $directory): bool
    {
        $directory = $package->basePath('/../'.$directory);

        if (File::missing($directory)) {
            return false;
        }

        return \count(File::allFiles($directory)) > 0;
    }

    protected function getFilesFromDirectory(Package $package, string $directory): array
    {
        $directory = $package->basePath('/../'.$directory);

        if (File::missing($directory)) {
            return [];
        }

        return collect(File::files($directory))
            ->map(fn (SplFileInfo $file): string => $file->getFilenameWithoutExtension())
            ->toArray();
    }
}
