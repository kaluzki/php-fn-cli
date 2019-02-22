<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fn {

    use DI\Definition\Source\DefinitionSource;

    /**
     * Create a console app from the given di definitions.
     * If the last parameter is a callable  it will be invoked to get the container configuration.
     * If the last parameter is TRUE the container will be auto(by reflections) wired.
     *
     * @param string|array|DefinitionSource|callable|true ...$args
     * @return Cli
     */
    function cli(...$args)
    {
        return new Cli(di(...$args));
    }
}
