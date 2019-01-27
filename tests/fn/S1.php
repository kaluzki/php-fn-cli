<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fn;

class S1
{
    public function __invoke(Cli\IO $io, bool $flag = false)
    {
        $flag ? $io->success('true') : $io->error('false');
    }
}
