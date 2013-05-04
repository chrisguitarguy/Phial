<?php
/**
 * Phial
 *
 * @package     Phial
 * @since       0.1
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Phial\Test\Entity;

/**
 * Common operations on entities.  Basically everything from
 * Phial\Entity\EntityBase
 *
 * @since   0.1
 * @author  Christopher Davis <http://christopherdavis.me>
 */
class CommonEntityTest extends \PHPUnit_Framework_TestCase
{
    /** Data Providers **********/

    public function entityInstanceProvider()
    {
        return array(
            array(new \Phial\Entity\User()),
            array(new \Phial\Entity\AnonymousUser()),
        );
    }

    /** Tests **********/

    /**
     * @dataProvider entityInstanceProvider
     */
    public function testGetSetStorage($entity)
    {
        $store = array('some_key' => "it's value");

        $this->assertSame($entity, $entity->setStorage($store));
        $this->assertEquals($store, $entity->getStorage());
    }

    /**
     * @dataProvider entityInstanceProvider
     */
    public function testArrayAccess($entity)
    {
        $this->assertFalse(isset($entity['a_key']));

        $this->assertNull($entity['a_key']);

        $entity['a_key'] = 'a value';

        $this->assertTrue(isset($entity['a_key']));

        $this->assertEquals('a value', $entity['a_key']);

        unset($entity['a_key']);
    }

    /**
     * @dataProvider entityInstanceProvider
     */
    public function testDynamicProperties($entity)
    {
        $this->assertFalse(isset($entity->a_key));

        $this->assertNull($entity->a_key);

        $entity->a_key = 'a value';

        $this->assertTrue(isset($entity->a_key));

        $this->assertEquals('a value', $entity->a_key);

        unset($entity->a_key);
    }
}
