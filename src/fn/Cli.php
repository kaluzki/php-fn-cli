<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

namespace fn;

use fn\Cli\Parameter;
use fn\Cli\IO;
use fn\DI;
use Invoker\ParameterResolver;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlockFactory;
use Psr\Container\ContainerInterface;
use ReflectionFunctionAbstract;
use ReflectionParameter;

use Symfony\Component\Console\{
    Application,
    Command\Command,
    Input\ArgvInput,
    Input\InputInterface,
    Output\ConsoleOutput,
    Output\OutputInterface
};

/**
 * @property-read IO $io
 */
class Cli extends Application
{
    use DI\PropertiesReadOnlyTrait;

    /**
     * @var DI\Container
     */
    private $container;

    /**
     * @var DI\Invoker
     */
    private $invoker;

    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $container)
    {
        if (!$container instanceof DI\Container) {
            $container = new DI\Container(null , null, $container);
        }
        $this->container = $container;
        $this->invoker   = new DI\Invoker(
            new ParameterResolver\VariadicResolver,
            new ParameterResolver\AssociativeArrayResolver,
            new ParameterResolver\TypeHintResolver,
            $container,
            new ParameterResolver\Container\ParameterNameContainerResolver($container),
            new ParameterResolver\DefaultValueResolver
        );
        parent::__construct($this->value('cli.name'), $this->value('cli.version'));

        foreach ($this->value('cli.commands', []) as $name => $command) {
            if (is_numeric($name) && is_string($command)) {
                $name = end($name = explode('\\', $command));
            }
            $this->command(strtolower($name), $command);
        }
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        if (($default = $this->value('cli.commands.default')) !== null) {
            if (isCallable($default, true)) {
                return traverse($commands, $default);
            }
            return $default ? (array) $default : [];
        }
        return $commands;
    }

    /**
     * @param string $id
     * @param mixed  $default
     *
     * @return mixed
     */
    private function value(string $id, $default = null)
    {
        return $this->container->has($id) ? $this->container->get($id) : $default;
    }

    /**
     * @inheritdoc
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        $this->container->set(InputInterface::class, $input = $input ?: new ArgvInput);
        $this->container->set(OutputInterface::class, $output = $output ?: new ConsoleOutput);
        $this->container->set(IO::class, $io = new IO($input, $output));
        $this->container->set('io', $io);

        return parent::run($input, $output);
    }

    public function __invoke()
    {
        return $this->run();
    }

    /**
     * @param string   $name
     * @param callable $callable
     * @param string[] $args
     * @param string[] $desc
     *
     * @return Command
     */
    public function command(string $name, $callable, array $args = [], array $desc = []): Command
    {
        $command = new Command($name);
        $refFn   = $this->invoker->reflect($callable);
        if (class_exists(DocBlockFactory::class) && $comment = $refFn->getDocComment()) {
            $doc = DocBlockFactory::createInstance()->create($comment);
            $command->setDescription($doc->getSummary());
            $desc = merge(traverse($doc->getTagsByName('param'), function(Param $tag) {
                if ($paramDesc = (string)$tag->getDescription()) {
                    return mapKey($tag->getVariableName())->andValue($paramDesc);
                }
                return null;
            }), $desc);
        }

        $command->setDefinition(traverse($this->params($refFn), function(Parameter $param) use($args, $desc) {
            return $param->input(hasValue($param->getName(), $args), at($param->getName(), $desc, null));
        }));

        $command->setCode(function() use($callable) {
            return $this->invoker->call($callable, $this->provided($callable));
        });
        $this->add($command);
        return $command;
    }

    /**
     * @param callable $callable
     *
     * @return array
     */
    private function provided($callable): array
    {
        $params = $this->params($this->invoker->reflect($callable));
        return merge(
            $this->io->getOptions(true),
            $this->io->getArguments(true),
            function($value, &$key) use($params) {
                if (isset($params[$key])) {
                    $key = $params[$key]->getName();
                    return $value;
                }
                return null;
            }
        );
    }

    /**
     * @param ReflectionFunctionAbstract $refFn
     *
     * @return Map|Parameter[]
     */
    private function params(ReflectionFunctionAbstract $refFn): Map
    {
        return map($refFn->getParameters(), function(ReflectionParameter $ref, &$key) {
            if ($ref->getClass() || $ref->isCallable()) {
                return null;
            }
            $param = new Parameter($ref);
            $key = $param->getName('-');
            return $param;
        });
    }
}
