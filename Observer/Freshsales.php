<?php

namespace Freshsales\Module\Observer;

use Magento\Framework\Event\ObserverInterface;
use Freshsales\Module\Helper\Data;
use Freshsales\Module\Logger\Logger;


class Freshsales implements ObserverInterface
{
    public $freshsalesHelper;

    public $logger;

    public function __construct(Data $freshsalesHelper, Logger $logger)
    {
        $this->freshsalesHelper = $freshsalesHelper;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if($this->freshsalesHelper->getGeneralConfig('enable')) {
            $customer = $observer->getData('customer');
            $this->freshsalesHelper->createFreshsalesAccount($customer);
        }
    }
}
