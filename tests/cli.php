#!/usr/bin/env php
<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

\call_user_func(require __DIR__.'/../vendor/autoload.php', function() {
    $cli = new fn\Cli(fn\di([
        'cli.name'     => 'tests/cli',
        'cli.version'  => '0.1',
        'cli.commands' => [
            fn\S1::class
        ]
    ], ['wiring' => fn\DI\ContainerConfigurationFactory::WIRING_REFLECTION]));

    $cli->command('s0',
        /**
         * command S0
         *
         * very long
         * description
         *
         * @param string $NewNASAModule new nasa module
         * @param bool $flag flag description
         */
        function(fn\Cli\IO $io, string $NewNASAModule,  bool $flag = true) {
            $flag ? $io->success('true') : $io->error('false');
        }
    , [], ['flag' => 'overwritten flag description']);

    $cli();
});
