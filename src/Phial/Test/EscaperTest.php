<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test;

/**
 * Tests for Phial\Escaper
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class EscaperTest extends \PHPUnit_Framework_TestCase
{
    private $esc;

    public function testAttr()
    {
        $this->assertEquals('&#38;', $this->esc->attr('&'));
        $this->assertEquals('&#39;', $this->esc->attr("'"));
        $this->assertEquals('&#34;', $this->esc->attr('"'));
        $this->assertEquals('&#60;', $this->esc->attr('<'));
        $this->assertEquals('&#62;', $this->esc->attr('>'));
    }

    public function testHtml()
    {
        $this->assertEquals('&amp;', $this->esc->html('&'));
        $this->assertEquals("'", $this->esc->html("'"));
        $this->assertEquals('&quot;', $this->esc->html('"'));
        $this->assertEquals('&lt;', $this->esc->html('<'));
        $this->assertEquals('&gt;', $this->esc->html('>'));
    }

    public function testTag()
    {
        $this->assertEquals('tag-one', $this->esc->tag('TaG-one '));
    }

    public function testUrl()
    {
        // todo add more character to check here?
        $this->assertEquals('%26', $this->esc->url('&'));
        $this->assertEquals('%20', $this->esc->url(' '));
    }

    public function testTrailingSlash()
    {
        $this->assertEquals('asdf/', $this->esc->trailingSlash('asdf'));
    }

    public function testLeadingSlash()
    {
        $this->assertEquals('/asdf', $this->esc->leadingSlash('asdf'));
    }

    protected function setUp()
    {
        $this->esc = new \Phial\Escaper();
    }
}
