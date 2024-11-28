<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Crate\TestBench\Concerns;

trait InteractsWithMockery
{
    #[\PHPUnit\Framework\Attributes\After()]
    public function tearDownMockery(): void
    {
        if (\class_exists(\Mockery::class, false)) {
            $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());

            \Mockery::close();
        }
    }
}
