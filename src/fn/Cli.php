<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fn;

use DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class Cli extends \Silly\Application
{
    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $c)
    {
        parent::__construct(self::value($c, 'cli.name'), self::value($c, 'cli.version'));
        $resolveBy = (array)self::value($c, 'cli.resolveBy') + ['typeHint' => true, 'parameterName' => true];
        $this->useContainer($c, $resolveBy['typeHint'], $resolveBy['parameterName']);
    }

    /**
     * @inheritdoc
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        $container = $this->getContainer();
        if ($container instanceof Container) {
            $container->set(InputInterface::class, $input = $input ?: new ArgvInput);
            $container->set(OutputInterface::class, $output = $output ?: new ConsoleOutput);
        }

        return parent::run($input, $output);
    }

    /**
     * @param ContainerInterface $c
     * @param string             $id
     * @param mixed              $default
     *
     * @return mixed
     */
    private static function value(ContainerInterface $c, string $id, $default = null)
    {
        return $c->has($id) ? $c->get($id) : $default;
    }
}
