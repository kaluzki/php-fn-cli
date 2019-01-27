<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @param \fn\Cli\IO $io
     * @param bool       $flag
     */
    public function __invoke(Cli\IO $io, bool $flag = false)
    {
        $flag ? $io->success('true') : $io->error('false');
    }
}
