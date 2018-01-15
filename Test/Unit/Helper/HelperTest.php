<?php

namespace Freshsales\Module\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class HelperTest
 *
 * @package Freshsales\Module\Test\Unit\Model
 * @author  Michał Wejwoda <mwejwoda@pl.sii.eu>
 */
class HelperTest extends TestCase
{
    const API_KEY = '';

    const API_URL = '';

    protected $helper;

    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->helper = $objectManager->getObject('Freshsales\Module\Helper\Data');
    }

    public function testGetMessage()
    {
        $this->assertTrue(is_string("AAA"));
    }
}
