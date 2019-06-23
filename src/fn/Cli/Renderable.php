<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

namespace fn\Cli;

use fn;

/**
 * @todo add unit tests
 */
class Renderable
{
    /**
     * @var mixed
     */
    private $content;

    /**
     * @var int
     */
    private $type;

    /**
     * @param mixed $content
     * @param int $type
     */
    public function __construct($content, int $type = IO::OUTPUT_NORMAL)
    {
        $this->content = $content;
        $this->type = $type;
    }

    /**
     * @param IO $io
     *
     * @return int
     */
    public function toCli(IO $io): int
    {
        return static::render($io, $this->type, $this->content);
    }

    /**
     * @param IO $io
     * @param int $type
     * @param $content
     * @return int
     */
    protected static function render(IO $io, int $type, $content): int
    {
        if ((($type & IO::VERBOSITY) ?: IO::VERBOSITY_NORMAL) > $io->getVerbosity()) {
            return 0;
        }

        if ($content instanceof self) {
            return $content->toCli($io);
        }

        if (fn\isCallable($content)) {
            return (int)$content($io, $type);
        }

        if (is_array($content)) {
            $current = current($content);
            if (is_array($current)) {
                $io->table(fn\keys($current), $content);
            } else {
                $io->listing($content);
            }
            return count($content);
        }

        if (is_iterable($content)) {
            $count = 0;
            foreach ($content as $key => $line) {
                is_string($key) && $io->title($key);
                $count += static::render($io, $type, $line);
            }
            return $count;
        }

        if (method_exists($content, '__toString')) {
            $content = (string)$content;
        }

        if (is_object($content) && ($content = json_encode($content, JSON_PRETTY_PRINT)) === false) {
            if ($io->getVerbosity() >= IO::VERBOSITY_DEBUG) {
                $io->error(json_last_error_msg());
            }
            return 0;
        }
        $io->writeln($content, $type);
        return 1;
    }

    /**
     * @param string $question
     * @param bool|array|null|callable ...$args
     *
     * @return static
     */
    public static function ask($question, ...$args): self
    {
        return new static(function(IO $io) use ($question, $args) {
            $default = $args[0] ?? null;
            if (is_bool($default)) {
                return $io->confirm($question, $default);
            }
            $choices = $args[1] ?? null;
            if (is_array($default)) {
                return $io->choice($question, $default, ...(array)$choices);
            }
            if (is_array($choices)) {
                return $io->choice($question, $choices, $default);
            }
            return $io->ask($question, ...$args);
        });
    }
}
