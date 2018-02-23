<?php

namespace SimpleUser\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use SimpleUser\Service\UserManager;


class RemoveRoleFromUserCommand extends Command
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
            ->setName('simple-user:remove-role')
            ->addArgument('email', InputArgument::REQUIRED, 'The username of the user.')
            ->addArgument(
                'roles',
                InputArgument::OPTIONAL,
                'The roles of the user. Split by comma: ROLE_USER, ROLE_SONATA_ADMIN'
            )
            ->setDescription('Create a new user.')
            ->setHelp('This command allows you to create a user')
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
            'User Add Role',
            '============',
            '',
        ]);

        $roles = explode(',', $input->getArgument('roles'));
        $email = $input->getArgument('email');

        $result = $this->getUserManager()->removeRolesFromUser($email, $roles);

        if ($result) {
            $output->writeln('Role removed from user!');
        } else {
            $output->writeln('Role not removed from user!');
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