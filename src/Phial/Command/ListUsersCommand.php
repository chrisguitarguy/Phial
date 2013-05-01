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
use Symfony\Component\Console\Output\OutputInterface;

class ListUsersCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('user:list')
            ->setDescription('Show a list of all users in ID -> Email pairs');
    }

    public function execute(InputInterface $in, OutputInterface $out)
    {
        // xxx only gets the first 1000
        $users = $this->app['users']->all(true, 1, 1000);

        foreach ($users as $user) {
            $out->writeln(sprintf(
                "%d\t\t%s",
                $user['user_id'],
                $user['user_email']
            ));
        }

        return 0;
    }
}
