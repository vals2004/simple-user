<?php

namespace SimpleUser\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use SimpleUser\Service\UserManager;


class AddRoleToUserCommand extends ContainerAwareCommand
{
    /**
     * @var UserManager
     */
    protected $userManager;

    protected function configure()
    {
        $this
            ->setName('simple-user:add-role')
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

        $result = $this->getUserManager()->addRolesToUser($email, $roles);
        if ($result) {
            $output->writeln('Role added to user!');
        } else {
            $output->writeln('Role did not add');
        }
    }

    /**
     * @return UserManager
     */
    public function getUserManager(): UserManager
    {
        return $this->getContainer()->get('simple_user.user_manager');
    }
}