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

class DeleteUserCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('user:delete')
            ->setDescription('Delete a user.')
            ->addArgument(
                'user_id',
                InputArgument::REQUIRED,
                'The ID of the user you wish to delete.'
            );
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $user_id = $in->getArgument('user_id');

        try {
            $user = $this->app['users']->getBy('user_id', $user_id);
        } catch (\Phial\Exception\UserNotFoundException $e) {
            $out->writeln('<error>Invalid user ID.</error>');
            return 1;
        } catch (\Exception $e) {
            $out->writeln('<error>Caught unexpected exception:</error> ' . $e->getMessage());
            return 1;
        }

        try {
            $res = $this->app['users']->delete($user);
        } catch (\Exception $e) {
            $out->writeln('<error>Caught exception deleting user:</error> ' . $e->getMessage());
            return 1;
        }

        if ($res) {
            $out->writeln('<info>User deleted.</info>');
            return 0;
        } else {
            $out->writeln('<error>No users deleted.</error>');
            return 1;
        }
    }
}
