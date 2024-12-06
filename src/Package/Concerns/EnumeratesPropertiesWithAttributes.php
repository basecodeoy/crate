<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Crate\Package\Concerns;

trait EnumeratesPropertiesWithAttributes
{
    protected function enumeratePropertiesWithAttributes(): array
    {
        $reflectionClass = new \ReflectionClass(static::class);

        $result = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getAttributes() !== []) {
                $result[$reflectionProperty->getName()] = $reflectionProperty->getAttributes();
            }
        }

        return $result;
    }
}
