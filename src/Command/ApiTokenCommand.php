<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ApiTokenCommand extends AbstractServiceCommand
{

    private const ARG_ACTION = 'action';

    private const ACTION_CREATE = 'create';
    private const ACTION_GET = 'get';
    private const ACTION_REVOKE = 'revoke';

    private const OPT_USERNAME = 'username';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->addArgument(self::ARG_ACTION, InputArgument::REQUIRED, 'Action to perform')
            ->addOption(self::OPT_USERNAME, 'usnm', InputOption::VALUE_OPTIONAL)
            ->setName('api-token')
            ->setDescription('Operations around API tokens');

        parent::configure();
    }

    /**
     * @return int
     */
    protected function interpret(): int
    {
        $action = $this->input->getArgument(self::ARG_ACTION);
        $callback = null;

        switch ($action) {
            case self::ACTION_CREATE:
                $this->requiredOptions = [self::OPT_USERNAME];
                $callback = 'create';
                break;

            case self::ACTION_GET:
                $this->requiredOptions = [self::OPT_USERNAME];
                $callback = 'get';
                break;

            case self::ACTION_REVOKE:
                $this->requiredOptions = [self::OPT_USERNAME];
                $callback = 'revoke';
                break;

            default:
                $this->statusMessage = sprintf('Unknown action "%s"', $action);
                break;
        }

        return $this->invokeCallback($callback);
    }

    /**
     * php .\bin\console api-token create --username=
     *
     * @return int
     */
    protected function create(): int
    {
        $status = Command::SUCCESS;
        $token = bin2hex(random_bytes(32));

        return $status;
    }

    /**
     * php .\bin\console api-token get --username=
     *
     * @return int
     */
    protected function get(): int
    {
        $status = Command::SUCCESS;

        return $status;
    }

    /**
     * php .\bin\console api-token revoke --username=
     *
     * @return int
     */
    protected function revoke(): int
    {
        $status = Command::SUCCESS;

        return $status;
    }
}
