<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Exception;

class UserSaveException
    extends \Symfony\Component\HttpKernel\Exception\HttpException
    implements PhialException
{
    public function __construct($msg=null, \Exception $prev=null, $code=0)
    {
        parent::__construct(400, $msg, $prev, array(), $code);
    }
}
