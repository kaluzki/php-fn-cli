#!/usr/bin/env php
<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

use fn\{Cli, DI};

call_user_func(require __DIR__ . '/../vendor/autoload.php', function() {
    $cli = fn\cli([
        'cli.name'     => 'tests/cli',
        'cli.version'  => '0.1',
        'cli.commands.default' => \DI\value(function($command) {
            return $command;
        }),
        'cli.commands' => [
            fn\S1::class
        ]
    ], DI\WIRING\AUTO);

    $cli->command('s0',
        /**
         * command S0
         *
         * very long
         * description
         *
         * @param Cli\IO     $io
         * @param string     $NewNASAModule new nasa module
         * @param bool       $flag          flag description
         */
        function(Cli\IO $io, string $NewNASAModule,  bool $flag = true) {
            $flag ? $io->success('true') : $io->error('false');
        }
    , [], ['flag' => 'overwritten flag description']);


    $cli();
});
