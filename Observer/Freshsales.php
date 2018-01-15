<?php

namespace Freshsales\Module\Observer;

use \Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Freshsales\Module\Helper\Data;
use Freshsales\Module\Logger\Logger;

/**
 * Class Freshsales
 *
 * @package Freshsales\Module\Observer
 * @author  Michał Wejwoda <mwejwoda@pl.sii.eu>
 */
class Freshsales implements ObserverInterface
{
    /**
     * @var Data
     */
    public $freshsalesHelper;

    /**
     * @var Logger
     */
    public $logger;

    /**
     * Freshsales constructor.
     *
     * @param Data   $freshsalesHelper
     * @param Logger $logger
     */
    public function __construct(Data $freshsalesHelper, Logger $logger)
    {
        $this->freshsalesHelper = $freshsalesHelper;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->freshsalesHelper->getGeneralConfig('enable')) {
            $customer = $observer->getData('customer');
            $this->freshsalesHelper->createFreshsalesAccount($customer);
        }
    }
}
