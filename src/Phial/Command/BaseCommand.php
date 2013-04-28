<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Command;

abstract class BaseCommand extends \Symfony\Component\Console\Command\Command
{
    protected $app;

    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
        parent::__construct();
    }
}
