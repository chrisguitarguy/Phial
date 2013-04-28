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

class UpgradeCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('db:upgrade')
            ->setDescription('Run the necessary alter table statements to bring the DB inline with the application')
            ->addOption(
                'dry',
                'd',
                \Symfony\Component\Console\Input\InputOption::VALUE_NONE,
                "Do a dry run, don't execute statements, just print them to the screen"
            );
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $dry = $in->getOption('dry');

        $sql = $this->app['schema_manager']->getMigrateSql(
            $this->app['db']->getSchemaManager()->createSchema(),
            $this->app['db']->getDatabasePlatform()
        );

        if (!$sql) {
            $out->writeln('<error>Nothing to do.</error>');
            return 1;
        }

        foreach ($sql as $statement) {
            if ($dry) {
                $out->writeln($statement);
            } else {
                $out->writeln(sprintf('<comment>Executing:</comment> %s', $statement));

                $this->app['db']->exec($statement);

                $out->writeln(sprintf('<comment>Finished:</comment> %s', $statement));
            }
        }
    }
}
