<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fn\Cli;

use Invoker;
use Invoker\ParameterResolver\ParameterResolver;
use Silly\Input;

/**
 * Analyze given callable and build console input definition from its parameters
 */
class CallableInputDefinition
{
    /**
     * @var \ReflectionParameter[]
     */
    private $params;

    /**
     * @var ParameterResolver
     */
    private $resolver;

    /**
     * @var array
     */
    private $compiled;

    /** @noinspection PhpDocMissingThrowsInspection */
    /**
     * CallableInputDefinition constructor.
     *
     * @param callable               $callable
     * @param ParameterResolver|null $resolver
     */
    public function __construct($callable, ParameterResolver $resolver = null)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->params = Invoker\Reflection\CallableReflection::create($callable)->getParameters();
        $this->resolver = $resolver;
    }

    private function compile(): array
    {
        if ($this->compiled) {
            return $this->compiled;
        }
        $this->compiled = ['options' => [], 'arguments' => []];
        foreach ($this->params as $param) {
            $param->name;
        }
        return $this->compiled;
    }

    /**
     * @return Input\InputOption[]
     */
    public function getOptions(): array
    {
        return $this->compile()['options'];
    }

    /**
     * @return Input\InputArgument[]
     */
    public function getArguments(): array
    {
        return $this->compile()['arguments'];
    }
}
