<?php

namespace App\Command;

use App\Service\JsonUserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class UserCommand extends AbstractServiceCommand
{

    private const ARG_ACTION = 'action';

    private const ACTION_ADD = 'add';
    private const ACTION_GET = 'get';
    private const ACTION_REMOVE = 'remove';

    private const OPT_ID = 'id';
    private const OPT_USERNAME = 'username';
    private const OPT_PASSWORD = 'password';
    private const OPT_FIRSTNAME = 'firstname';
    private const OPT_LASTNAME = 'lastname';
    private const OPT_DESCRIPTION = 'description';
    private const OPT_ROLES = 'roles';

    private JsonUserService $userService;

    /**
     * Constructor
     *
     * @param JsonUserService $userService
     * @param string|null $name
     */
    public function __construct(JsonUserService $userService, string $name = null)
    {
        parent::__construct($name);

        $this->userService = $userService;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->addArgument(self::ARG_ACTION, InputArgument::REQUIRED, 'Action to perform')
            ->addOption(self::OPT_ID, 'id', InputOption::VALUE_OPTIONAL)
            ->addOption(self::OPT_USERNAME, 'usnm', InputOption::VALUE_OPTIONAL)
            ->addOption(self::OPT_PASSWORD, 'pwd', InputOption::VALUE_OPTIONAL)
            ->addOption(self::OPT_FIRSTNAME, 'fnm', InputOption::VALUE_OPTIONAL)
            ->addOption(self::OPT_LASTNAME, 'lnm', InputOption::VALUE_OPTIONAL)
            ->addOption(self::OPT_DESCRIPTION, 'desc', InputOption::VALUE_OPTIONAL)
            ->addOption(self::OPT_ROLES, 'rol', InputOption::VALUE_OPTIONAL)
            ->setName('user')
            ->setDescription('Operations around User');

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
            case self::ACTION_ADD:
                $this->requiredOptions = [self::OPT_USERNAME, self::OPT_PASSWORD, self::OPT_FIRSTNAME, self::OPT_LASTNAME, self::OPT_DESCRIPTION, self::OPT_ROLES];
                $callback = 'addUser';
                break;

            case self::ACTION_GET:
                $this->requiredOptions = [self::OPT_ID];
                $callback = 'getUser';
                break;

            case self::ACTION_REMOVE:
                $this->requiredOptions = [self::OPT_ID];
                $callback = 'removeUser';
                break;

            default:
                $this->statusMessage = sprintf('Unknown action "%s"', $action);
                break;
        }

        return $this->invokeCallback($callback);
    }

    /**
     * php .\bin\console user add --username= --password= --firstname= --lastname= --description= --roles=
     *
     * Example : php .\bin\console user add --username=test --password=test --firstname=John --lastname=Doe --description="Test user" --roles="ROLE_APP,ROLE_API"
     *
     * @return int
     */
    protected function addUser(): int
    {
        if (!$this->userService->isFileExists()) {
            $this->userService->createFile();
        }

        $data = new \stdClass();
        $data->username = $this->input->getOption(self::OPT_USERNAME);
        $data->password = $this->input->getOption(self::OPT_PASSWORD);
        $data->firstName = $this->input->getOption(self::OPT_FIRSTNAME);
        $data->lastName = $this->input->getOption(self::OPT_LASTNAME);
        $data->description = $this->input->getOption(self::OPT_DESCRIPTION);
        $data->roles = explode(',', $this->input->getOption(self::OPT_ROLES));
        $data->enabled = true;

        $user = $this->userService->addUser($data);

        if ($user !== null) {
            $status = Command::SUCCESS;
            $message = sprintf('User %s has been created', $user->getUserIdentifier());
        } else {
            $status = Command::FAILURE;
            $message = 'Unable to add user';

            $this->statusMessage = $message;
        }

        $this->output($message);

        return $status;
    }

    /**
     * php .\bin\console user get --id=
     *
     * @return int
     */
    protected function getUser(): int
    {
        $id = $this->input->getOption(self::OPT_ID);
        $userData = $this->userService->getUser($id);

        if ($userData !== null) {
            $status = Command::SUCCESS;
            $message = sprintf('id: %s, username: %s, enabled: %s', $id, $userData->username, $userData->enabled);
        } else {
            $status = Command::FAILURE;
            $message = sprintf('User %s not found', $id);

            $this->statusMessage = $message;
        }

        $this->output($message);

        return $status;
    }

    /**
     * php .\bin\console user remove --id=
     *
     * @return int
     */
    protected function removeUser(): int
    {
        $id = $this->input->getOption(self::OPT_ID);
        $result = $this->userService->removeUser($id);

        if ($result) {
            $status = Command::SUCCESS;
            $message = sprintf('User %s has been removed', $id);
        } else {
            $status = Command::FAILURE;
            $message = sprintf('Unable to remove user %s', $id);

            $this->statusMessage = $message;
        }

        $this->output($message);

        return $status;
    }
}
