<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial;

final class PhialEvents
{
    const GET_TEMPLATE          = 'phial.get_template';

    // users
    const USERS_ALTER_FORM      = 'phial.users.alter_form';
    const USERS_PRE_CREATE      = 'phial.users.pre_create';
    const USERS_POST_CREATE     = 'phial.users.post_create';
    const USERS_PRE_SAVE        = 'phial.users.pre_save';
    const USERS_POST_SAVE       = 'phial.users.post_save';
    const USERS_DELETE          = 'phial.users.delete';
}
