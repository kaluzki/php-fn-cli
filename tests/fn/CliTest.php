<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
