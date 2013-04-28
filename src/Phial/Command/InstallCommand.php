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

class InstallCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('db:install')
            ->setDescription('Install the database tables');
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $sql = $this->app['schema_manager']
            ->getInstallSql($this->app['db']->getDatabasePlatform());

        foreach ($sql as $statement) {
            $out->writeln(sprintf('<comment>Executing:</comment> %s', $statement));

            $this->app['db']->exec($statement);

            $out->writeln(sprintf('<comment>Finished:</comment> %s', $statement));
        }
    }
}
