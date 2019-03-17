<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

namespace fn;

use fn\test\assert;
use Symfony\Component\Console\Command\Command;

class CliTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Cli::command
     *
     * @todo complete
     */
    public function testCommand()
    {
        $cli = new Cli(di());
        assert\type(Command::class, $cli->command('cmd', function() {}));
    }
}
