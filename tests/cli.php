#!/usr/bin/env php
<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

namespace php;

call_user_func(require __DIR__ . '/../vendor/autoload.php', static function () {
    $cli = cli([
        'cli.name' => 'tests/cli',
        'cli.version' => '0.1',
        'cli.commands.default' => false,
        'cli.commands' => [
            S1::class
        ]
    ], DI\WIRING::AUTO);

    $cli->command('s0',

        /**
         * command S0
         *
         * very long
         * description
         *
         * @param Cli\IO $io
         * @param string $NewNASAModule new nasa module
         * @param bool $flag flag description
         * @param mixed ...$args listing
         */
        function (Cli\IO $io, string $NewNASAModule, bool $flag = true, ...$args) {
            $flag ? $io->success($NewNASAModule) : $io->error($NewNASAModule);
            $io->listing($args);
        }
        , ['args'], ['flag' => 'overwritten flag description']
    );

    $cli->command('test:io', static function() {
        yield $listing = [
            'l1',
            'l2',
            'l3',
        ];

        yield $table = [
            ['c1' => 'a1', 'c2' => 'a2', 'c3' => 'a3'],
            ['c1' => 'b1', 'c2' => 'b2', 'c3' => 'b3'],
        ];

        yield from [
            's1',
            's2',
            (object)[null]
        ];

        yield $json = (object)['a' => 'A'];

        yield leaves(['A', ['B', 'd'], 'c'], static function (Map\Path $path) {
            return (string)$path;
        });

        yield map([
            'listing' => $listing,
            'table' => $table,
            'json' => $json,
        ]);

        yield io('io');

        /**
         * @param string $string foo
         */
        yield function (
            string $string,
            array $array,
            bool $yes,
            $arr = [],
            bool $no = false,
            $default = 'def'
        ) {
            return [
                ['name' => 'q', 'value' => $string],
                ['array', (object)$array],
                ['yes', $yes],
                ['arr', (object)$arr],
                ['no', $no],
                ['default', $default],
            ];
        };

//        yield ask('question?');
//        yield ask('question?', 'answer');
//        yield ask('yes?', true);
//        yield ask('no?', false);
//        yield ask('choice?', ['opt1', 'opt2']);
//        yield ask('choice?', 'opt1', ['opt1', 'opt2']);
//        yield ask('choice?', ['opt1', 'opt2'], 'opt2');
    });


    return $cli();
});
