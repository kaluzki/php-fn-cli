<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fn\Cli;

use fn\test\assert;

class CallableInputDefinitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CallableInputDefinition::compile
     * @covers CallableInputDefinition::getOptions
     * @covers CallableInputDefinition::getArguments
     */
    public function testCompile()
    {
        $def = new CallableInputDefinition(function() {});
        assert\same([], $def->getArguments());
        assert\same([], $def->getOptions());
    }

}
