<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

namespace php {

    /**
     * Create a console app from the given di definitions.
     *
     * @param Package|string|array|callable ...$args
     *
     * @return Cli
     */
    function cli(...$args): Cli
    {
        return Cli::fromPackage(...$args);
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
}
