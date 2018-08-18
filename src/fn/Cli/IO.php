<?php
/**
 * (c) php-fn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace fn\Cli;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Merge capabilities of input and output interfaces
 */
class IO extends SymfonyStyle
{
    /**
     * @var InputInterface
     */
    private $inputInterface;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->inputInterface = $input;
        parent::__construct($input, $output);
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->inputInterface;
    }

    /**
     * @see InputInterface::getArgument()
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getArgument(string $name)
    {
        return $this->getInput()->getArgument($name);
    }

    /**
     * @see InputInterface::getOption
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getOption(string $name)
    {
        return $this->getInput()->getOption($name);
    }

    /**
     * @see InputInterface::hasOption()
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasOption(string $name): bool
    {
        return $this->getInput()->hasOption($name);
    }
}
