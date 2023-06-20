<?php

namespace App\Command;

use App\Service\JsonApiTokenService;
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

    private JsonApiTokenService $apiTokenService;

    /**
     * Constructor
     *
     * @param JsonApiTokenService $apiTokenService
     * @param string|null $name
     */
    public function __construct(JsonApiTokenService $apiTokenService, string $name = null)
    {
        parent::__construct($name);

        $this->apiTokenService = $apiTokenService;
    }

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
     * @throws \Exception
     */
    protected function create(): int
    {
        $username = $this->input->getOption(self::OPT_USERNAME);
        $apiToken = $this->apiTokenService->createToken($username);

        if ($apiToken !== null) {
            $status = Command::SUCCESS;
            $message = sprintf('Api Token has been created for user %s', $apiToken->getUsername());
        } else {
            $status = Command::FAILURE;
            $message = sprintf('Unable to create Api Token for user %s', $username);

            $this->statusMessage = $message;
        }

        $this->output($message);

        return $status;
    }

    /**
     * php .\bin\console api-token get --username=
     *
     * @return int
     */
    protected function get(): int
    {
        $username = $this->input->getOption(self::OPT_USERNAME);
        $apiToken = $this->apiTokenService->getToken($username);

        if ($apiToken !== null) {
            $status = Command::SUCCESS;
            $message = sprintf('token: %s, username: %s, enabled: %s', $apiToken->token, $apiToken->username, $apiToken->enabled);
        } else {
            $status = Command::FAILURE;
            $message = sprintf('Api token for user %s not found', $username);

            $this->statusMessage = $message;
        }

        $this->output($message);

        return $status;
    }

    /**
     * php .\bin\console api-token revoke --username=
     *
     * @return int
     */
    protected function revoke(): int
    {
        $username = $this->input->getOption(self::OPT_USERNAME);
        $result = $this->apiTokenService->revokeToken($username);

        if ($result) {
            $status = Command::SUCCESS;
            $message = sprintf('Api token has been removed for user %s', $username);
        } else {
            $status = Command::FAILURE;
            $message = sprintf('Unable to remove Api token for user %s', $username);

            $this->statusMessage = $message;
        }

        $this->output($message);

        return $status;
    }
}
