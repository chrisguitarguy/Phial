<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial;

/**
 * Phial CLI application.
 *
 * @since   0.1
 */
class PhialConsole extends \Symfony\Component\Console\Application
{
    protected $app;

    public function __construct(\Silex\Application $app, $name='Phial', $version='0.1')
    {
        $this->app = $app;
        parent::__construct($name, $version);
    }

    protected function getDefaultCommands()
    {
        $cmds = parent::getDefaultCommands();

        $cmds[] = new Command\InstallCommand($this->app);
        $cmds[] = new Command\UpgradeCommand($this->app);

        return $cmds;
    }
}
