<?php

namespace Freshsales\Module\Observer;

use Magento\Framework\Event\ObserverInterface;
use Freshsales\Module\Helper\Data;

class Freshsales implements ObserverInterface
{
    public $freshsalesHelper;

    public function __construct(Data $freshsalesHelper)
    {
        $this->freshsalesHelper = $freshsalesHelper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if($this->freshsalesHelper->getGeneralConfig('enable')) {
            $customer = $observer->getData('customer');
            $this->freshsalesHelper->createFreshsalesAccount();
        }
    }
}
