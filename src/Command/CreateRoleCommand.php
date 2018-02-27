<?php

namespace SimpleUser\Command;

use SimpleUser\Interfaces\SimpleUserRoleInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use SimpleUser\Service\UserManager;


class CreateRoleCommand extends Command
{
    /**
     * @var UserManager
     */
    protected $userManager;

    public function __construct(?string $name = null, UserManager $userManager)
    {
        parent::__construct($name);
        $this->userManager = $userManager;
    }

    protected function configure()
    {
        $this
            ->setName('simple-user:create-role')
            ->addArgument('role', InputArgument::REQUIRED, 'The name of the role in security.')
            ->addArgument('description', InputArgument::REQUIRED, 'User friendly name for role.')
            ->setDescription('Create role to with description.')
            ->setHelp('This command allows you to create role.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Create Role',
            '============',
            '',
        ]);

        $roleName =  $input->getArgument('role');
        $roleDescription = $input->getArgument('description');

        $result = $this->getUserManager()->createRole($roleName, $roleDescription);
        if ($result instanceof SimpleUserRoleInterface) {
            $output->writeln(sprintf('Role# created!', $result->getId()));
        } else {
            $output->writeln('Role did not created!');
        }
    }

    /**
     * @return UserManager
     */
    public function getUserManager(): UserManager
    {
        return $this->userManager;
    }
}