<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Exception;

class NoUsersFoundException
    extends \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
    implements PhialException
{
    // empty
}
