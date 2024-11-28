<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Crate\Package\Concerns;

trait HasBootableTraitsWithStatic
{
    private static array $traitInitializers = [];

    private static function bootTraits(): void
    {
        $class = static::class;

        $booted = [];

        static::$traitInitializers[$class] = [];

        foreach (class_uses_recursive($class) as $trait) {
            $method = 'boot'.class_basename($trait);

            if (\method_exists($class, $method) && !\in_array($method, $booted, true)) {
                \forward_static_call([$class, $method]);

                $booted[] = $method;
            }

            if (\method_exists($class, $method = 'initialize'.class_basename($trait))) {
                static::$traitInitializers[$class][] = $method;

                static::$traitInitializers[$class] = \array_unique(
                    static::$traitInitializers[$class],
                );
            }
        }
    }
}
