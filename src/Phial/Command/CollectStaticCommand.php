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

class CollectStaticCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('static:collect')
            ->setDescription('Write static assets to your web directory.');
    }

    protected function execute(InputInterface $in, OutputInterface $out)
    {
        $this->app['assetic.asset_writer']->writeManagerAssets(
            $this->app['assetic']->getAssetManager()
        );

        $out->writeln('<info>Assets written.</info>');

        return 0;
    }
}
