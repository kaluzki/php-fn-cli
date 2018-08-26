<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fn\Cli;

use fn\test\assert;
use fn;
use ReflectionFunction;
use ReflectionParameter;

class ParameterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return array
     */
    public function providerGetName(): array
    {
        $ref = new ReflectionFunction(function(
            $NewNASAModule,
            $aBcDeFgH,
            $AbCdEfGh,
            $aBCdEFgH,
            $lower,
            $Upper,
            $under_score,
            $und_Er_sCore,
            $__AB__cd__eF__Gh__,
            $a123B456c789d000
        ) {});

        $expected = [
            'NewNASAModule'      => 'new-nasa-module',
            'aBcDeFgH'           => 'a-bc-de-fg-h',
            'AbCdEfGh'           => 'ab-cd-ef-gh',
            'aBCdEFgH'           => 'a-b-cd-e-fg-h',
            'lower'              => 'lower',
            'Upper'              => 'upper',
            'under_score'        => 'under-score',
            'und_Er_sCore'       => 'und-er-s-core',
            '__AB__cd__eF__Gh__' => 'ab-cd-e-f-gh',
            'a123B456c789d000'   => 'a123-b456-c789-d000',
        ];

        return fn\traverse($ref->getParameters(), function(ReflectionParameter $param, &$key) use($expected) {
            return ['expected' => $expected[$key = $param->getName()], $param];
        });
    }

    /**
     * @dataProvider providerGetName
     * @cover Parameter::getName
     *
     * @param string              $expected
     * @param ReflectionParameter $ref
     */
    public function testGetName($expected, ReflectionParameter $ref)
    {
        $param = new Parameter($ref);
        assert\same($ref->getName(), $param->getName());
        assert\same($expected, $param->getName('-'));
    }
}
