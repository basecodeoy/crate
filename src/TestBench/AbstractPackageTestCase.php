<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Crate\TestBench;

/**
 * @internal
 */
abstract class AbstractPackageTestCase extends AbstractTestCase
{
    #[\Override()]
    protected function getEnvironmentSetUp($app): void
    {
        $app->config->set('app.key', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');

        $app->config->set('cache.driver', 'array');

        $app->config->set('database.default', 'sqlite');
        $app->config->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app->config->set('mail.driver', 'log');

        $app->config->set('session.driver', 'array');
    }

    #[\Override()]
    protected function getPackageProviders($app): array
    {
        $provider = static::getServiceProviderClass($app);

        if ($provider !== '' && $provider !== '0') {
            return \array_merge($this->getRequiredServiceProviders(), [$provider]);
        }

        return $this->getRequiredServiceProviders();
    }

    protected function getRequiredServiceProviders(): array
    {
        return [];
    }

    abstract protected function getServiceProviderClass(): string;
}
