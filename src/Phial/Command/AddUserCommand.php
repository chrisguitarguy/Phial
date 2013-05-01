<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Email as AssertEmail;

class AddUserCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('user:add')
            ->setDescription('Add a new user')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                "The user's email"
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                "The user's password"
            )
            ->addArgument(
                'role',
                InputArgument::OPTIONAL,
                "The user's role, defaults to 'admin'",
                'admin'
            );
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $email = $in->getArgument('email');

        $errors = $this->app['validator']->validateValue($email, new AssertEmail());

        if (count($errors) > 0) {
            $out->writeln('<error>Invalid email.</error>');
            return 1;
        }

        $pass = $in->getArgument('password');
        $role = $in->getArgument('role');

        $user = new \Phial\Entity\User(array(
            'user_email'    => $email,
            'user_role'     => $role,
        ));

        $user['user_pass'] = $pass;

        try {
            $res = $this->app['users']->create($user);
        } catch (\Exception $e) {
            $out->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }

        if ($res) {
            $out->writeln('<info>User created.</info>');
            return 0;
        } else {
            $out->writeln('<warning>User was not created.</warning>');
            return 1;
        }
    }
}
