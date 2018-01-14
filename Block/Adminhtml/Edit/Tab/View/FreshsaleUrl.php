<?php

namespace Freshsales\Module\Block\Adminhtml\Edit\Tab\View;

use Freshsales\Module\Helper\Data as Helper;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Block\Adminhtml\Edit\Tab\View\PersonalInfo;
use Magento\Customer\Model\Address\Mapper;

/**
 * Class FreshsaleUrl
 * @package Freshsales\Module\Block\Adminhtml\Edit\Tab\View
 */
class FreshsaleUrl extends PersonalInfo
{

    /**
     * @var Helper
     */
    public $freshsaleHelper;

    public $customerRepository;

    public function __construct(
      \Magento\Backend\Block\Template\Context $context,
      AccountManagementInterface $accountManagement,
      \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
      \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory,
      \Magento\Customer\Helper\Address $addressHelper,
      \Magento\Framework\Stdlib\DateTime $dateTime,
      \Magento\Framework\Registry $registry,
      Mapper $addressMapper,
      \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
      \Magento\Customer\Model\Logger $customerLogger,
      Helper $helper,
      CustomerRepositoryInterface $customerRepository,
      array $data = []
    )
    {
        $this->freshsaleHelper = $helper;
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $accountManagement, $groupRepository, $customerDataFactory, $addressHelper, $dateTime, $registry, $addressMapper, $dataObjectHelper, $customerLogger, $data);
    }

    /**
     * @return bool
     */
    public function moduleIsActive()
    {
        return (boolean)$this->freshsaleHelper->getGeneralConfig('enable');
    }

    /**
     * @return string
     */
    public function getFreshsaleProfileUrl()
    {
        $customer = $this->customerRepository->getById($this->getCustomerId());
        $freshSaleId = $customer->getCustomAttribute(Helper::FRESHSALES_ATTRIBUTE_CODE)->getValue();
        return (string)$this->freshsaleHelper->getGeneralConfig('api_url') . DIRECTORY_SEPARATOR . $freshSaleId;
    }
}
