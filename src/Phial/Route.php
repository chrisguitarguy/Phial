<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Route extends \Silex\Route
{
    public function secure($role)
    {
        $this->before(function(Request $r, \Silex\Application $app) use ($role) {
            if (!$app['current_user']->hasRole($role)) {
                throw new AccessDeniedHttpException('You do not have permission to access this page');
            }
        });
    }
}
