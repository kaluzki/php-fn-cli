<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fn\Cli;

use ReflectionParameter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 */
class Parameter
{
    /**
     * @var ReflectionParameter
     */
    private $ref;

    /**
     * @param ReflectionParameter $ref
     */
    public function __construct(ReflectionParameter $ref)
    {
        $this->ref = $ref;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->ref->getName();
    }

    /**
     * @param bool        $asArg
     * @param string|null $desc
     *
     * @return InputOption|InputArgument
     */
    public function input(bool $asArg = false, string $desc = null)
    {
        return $asArg ? $this->arg($desc) : $this->opt($desc);
    }

    /**
     * @param string|null $desc
     *
     * @return InputArgument
     */
    private function arg(string $desc = null): InputArgument
    {
        $mode = $this->ref->isOptional() ? InputArgument::OPTIONAL : InputArgument::REQUIRED;
        return new InputArgument($this->getName(), $mode, $desc);
    }

    /**
     * @param string|null $desc
     *
     * @return InputOption
     */
    private function opt(string $desc = null): InputOption
    {
        $mode = $this->ref->isOptional() ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED;
        return new InputOption($this->getName(), null, $mode, $desc);
    }
}
