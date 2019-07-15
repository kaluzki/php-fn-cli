<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

namespace fn;

use fn\test\assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;

class CliTest extends TestCase
{
    /**
     * @covers \fn\Cli::command
     *
     * @todo complete
     */
    public function testCommand(): void
    {
        $cli = new Cli(di());
        assert\type(Command::class, $cli->command('cmd', static function () {}));
    }
}
