<?php

namespace Freshsales\Module\Block\Adminhtml\Edit\Tab\View;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Block\Adminhtml\Edit\Tab\View\PersonalInfo;
use Magento\Customer\Helper\Address;
use Magento\Customer\Model\Address\Mapper;
use Magento\Customer\Model\Logger;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Freshsales\Module\Helper\Data as Helper;

/**
 * Class FreshsaleUrl
 *
 * @package Freshsales\Module\Block\Adminhtml\Edit\Tab\View
 */
class FreshsaleUrl extends PersonalInfo
{

    /**
     * @var Helper
     */
    public $freshsaleHelper;

    /**
     * @var CustomerRepositoryInterface
     */
    public $customerRepository;

    /**
     * FreshsaleUrl constructor.
     *
     * @param Context                     $context
     * @param AccountManagementInterface  $accountManagement
     * @param GroupRepositoryInterface    $groupRepository
     * @param CustomerInterfaceFactory    $customerDataFactory
     * @param Address                     $addressHelper
     * @param DateTime                    $dateTime
     * @param Registry                    $registry
     * @param Mapper                      $addressMapper
     * @param DataObjectHelper            $dataObjectHelper
     * @param Logger                      $customerLogger
     * @param Helper                      $helper
     * @param CustomerRepositoryInterface $customerRepository
     * @param array                       $data
     */
    public function __construct(
        Context $context,
        AccountManagementInterface $accountManagement,
        GroupRepositoryInterface $groupRepository,
        CustomerInterfaceFactory $customerDataFactory,
        Address $addressHelper,
        DateTime $dateTime,
        Registry $registry,
        Mapper $addressMapper,
        DataObjectHelper $dataObjectHelper,
        Logger $customerLogger,
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
        $freshSaleIdAttribute = $customer->getCustomAttribute(Helper::FRESHSALES_ATTRIBUTE_CODE);
        if ($freshSaleIdAttribute) {
            $freshSaleId = $freshSaleIdAttribute->getValue();
            return (string)$this->freshsaleHelper->getGeneralConfig('api_url') . DIRECTORY_SEPARATOR . $freshSaleId;
        }
        return null;
    }
}
