<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

namespace fn;

use fn\Cli\Parameter;
use fn\Cli\IO;
use fn\DI;
use Invoker\ParameterResolver;

use Psr\Container\ContainerInterface;
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
        $command->setDefinition($this->input($callable, $args, $desc)->traverse);
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
        $params = $this->params($callable);
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
     * @param callable $callable
     * @param string[] $args
     * @param string[] $desc
     *
     * @return Map
     */
    private function input($callable, array $args = [], array $desc = []): Map
    {
        return $this->params($callable)->then(function(Parameter $param) use($args, $desc) {
            return $param->input(hasValue($param->getName(), $args), at($param->getName(), $desc, null));
        });
    }

    /**
     * @param callable $callable
     *
     * @return Map|Parameter[]
     */
    private function params($callable): Map
    {
        return map($this->invoker->reflect($callable)->getParameters(), function(ReflectionParameter $ref, &$key) {
            if ($ref->getClass()) {
                return null;
            }
            $param = new Parameter($ref);
            $key = $param->getName('-');
            return $param;
        });
    }
}
