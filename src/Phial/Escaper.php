<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial;

class Escaper
{
    public function attr($val)
    {
        return filter_var($val, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    public function html($val)
    {
        return htmlspecialchars($val);
    }

    public function tag($tag)
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9:_-]/', '', $tag));
    }
}
