<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{

    public function __construct()
    {

    }

    protected function configure(): void
    {
        $this
            ->setName('user')
            ->setDescription('Operations around User')
            ->addArgument('action', InputArgument::REQUIRED, 'Action to perform');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}