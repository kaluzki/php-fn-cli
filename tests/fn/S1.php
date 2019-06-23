<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

namespace fn;

/**
 * Class S1
 *
 * @package fn
 */
class S1
{
    /**
     * Command S1::__invoke
     *
     * @param Cli\IO $io
     * @param bool $flag
     */
    public function __invoke(Cli\IO $io, bool $flag = false)
    {
        $flag ? $io->success('true') : $io->error('false');
    }
}
