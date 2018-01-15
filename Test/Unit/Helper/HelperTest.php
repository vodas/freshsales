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
    const API_KEY = 'KJ_vE2J_P64QksCcWLIXXw';

    const API_URL = 'https://myown123.freshsales.io/contacts';

    protected $helper;

    protected $userData = [
        'first_name' => 'Adam',
        'last_name' => 'Nowak',
        'email' => 'adam@nowak.pl',
    ];

    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->helper = $objectManager->getObject('Freshsales\Module\Helper\Data');
    }

    public function testGetMessage()
    {
        $this->assertTrue(is_string($this->helper->makeRequestToApi(self::API_URL, self::API_KEY, $this->userData)));
    }
}
