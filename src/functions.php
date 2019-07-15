<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

namespace fn {

    use DI\Definition\Source\DefinitionSource;

    /**
     * Create a console app from the given di definitions.
     *
     * @param $package, ...$args
     * @param string|array|DefinitionSource|callable|true ...$args
     *
     * @return Cli
     */
    function cli($package, ...$args): Cli
    {
        return Cli::fromPackage(...func_get_args());
    }

    /**
     * @param mixed $content
     * @param int $type
     *
     * @return Cli\Renderable
     */
    function io($content, $type = Cli\IO::OUTPUT_NORMAL): Cli\Renderable
    {
        return new Cli\Renderable($content, $type);
    }

    /**
     * @param string $question
     * @param bool|array|null|callable ...$args
     *
     * @return Cli\Renderable
     */
    function ask($question, ...$args): Cli\Renderable
    {
        return Cli\Renderable::ask($question, ...$args);
    }
}
