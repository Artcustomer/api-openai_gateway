<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author David
 */
abstract class AbstractServiceCommand extends Command
{

    private const CMD_PREFIX = '[cli]';

    public const OPT_DEBUG = 'debug';

    private float $startTime = 0;
    private string $commandId = '';

    protected string $commandName;
    protected string $commandOptions;

    protected string $statusMessage = '';
    protected array $requiredOptions = [];

    protected InputInterface $input;
    protected OutputInterface $output;

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->addOption(self::OPT_DEBUG, 'dbg', InputOption::VALUE_OPTIONAL, '', '');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->extractCommandInfos();
        $this->start();
        $this->preExecute();

        $status = $this->interpret();

        $this->postExecute();
        $this->end();

        if ($status !== Command::SUCCESS) {
            throw new \Exception(sprintf('The command "%s" failed to execute. %s', $this->commandName, $this->statusMessage));
        }

        return $status;
    }

    /**
     * @return void
     */
    protected function preExecute(): void
    {
        // Override it if needed
    }

    /**
     * @return void
     */
    protected function postExecute(): void
    {
        // Override it if needed
    }

    /**
     * @return int
     */
    protected function interpret(): int
    {
        return Command::SUCCESS;
    }

    /**
     * @param string|null $callback
     * @param array $arguments
     * @return int
     */
    protected function invokeCallback(string $callback = null, array $arguments = []): int
    {
        $status = Command::FAILURE;
        $checkedOptions = $this->checkRequiredOptions();

        if (!empty($checkedOptions)) {
            $this->statusMessage = sprintf('Some parameters are missing : %s', implode(',', $checkedOptions));
        } else {
            if (method_exists($this, $callback)) {
                $status = call_user_func_array([$this, $callback], $arguments);
            } else {
                $this->statusMessage = 'Wrong callback';
            }
        }

        return $status;
    }

    /**
     * Check required options
     *
     * @return array
     */
    protected function checkRequiredOptions(): array
    {
        $options = [];

        foreach ($this->requiredOptions as $requiredOption) {
            $value = $this->input->getOption($requiredOption);

            if ($value === null) {
                $options[] = $requiredOption;
            }
        }

        return $options;
    }

    /**
     * Output messages
     *
     * @param string|iterable $messages
     * @return void
     */
    protected function output(string|iterable $messages): void
    {
        $this->output->writeln($messages);
    }

    /**
     * @return void
     */
    private function extractCommandInfos(): void
    {
        $options = $this->input->getOptions();
        $commandOptions = '';

        array_walk(
            $options,
            function ($value, $key) use (&$commandOptions) {
                $commandOptions .= sprintf('--%s=%s ', $key, $value);
            }
        );

        $this->commandName = implode(' ', $this->input->getArguments());
        $this->commandOptions = $commandOptions;
        $this->commandId = uniqid('cmd-');
    }

    /**
     * @return void
     */
    private function start(): void
    {
        $this->startTime = microtime(true);
        $message = sprintf('%s %s START "%s %s"', self::CMD_PREFIX, $this->commandId, $this->commandName, $this->commandOptions);

        $this->output($message);
    }

    /**
     * @return void
     */
    private function end(): void
    {
        $time = microtime(true) - $this->startTime;
        $message = sprintf('%s %s END "%s %s" in %s s', self::CMD_PREFIX, $this->commandId, $this->commandName, $this->commandOptions, $time);

        $this->output($message);
    }

    /**
     * @return string
     */
    public function getCommandId(): string
    {
        return $this->commandId;
    }
}
