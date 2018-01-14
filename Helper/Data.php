<?php

namespace Freshsales\Module\Helper;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Freshsales\Module\Logger\Logger;


class Data extends AbstractHelper
{

    const XML_PATH = 'freshsales/general/';

    const FRESHSALES_ATTRIBUTE_CODE = 'freshsales_id';

    protected $logger;

    protected $customerRepositoryInterface;

    public function __construct(Context $context, Logger $logger, CustomerRepositoryInterface $customerRepository)
    {
        $this->logger = $logger;
        $this->customerRepositoryInterface = $customerRepository;
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH . $code, $storeId);
    }

    public function createFreshsalesAccount($customer) {

        $apiUrl = (string)$this->getGeneralConfig('api_url');
        $apiKey = (string)$this->getGeneralConfig('api_key');

        if($customer && !empty($apiUrl) && !empty($apiKey)) {
            $userData = [
                'first_name' => $customer->getFirstname(),
                'last_name' => $customer->getLastname(),
                'email' => $customer->getEmail()
            ];
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $auth = "Authorization: Token token=" . $apiKey;
            curl_setopt($ch, CURLOPT_HTTPHEADER, [$auth, "Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))]);

            $result = curl_exec($ch);
            if ($result) {
                $result = json_decode($result);
                if (property_exists($result, 'errors')) {
                    $this->logger->info('Code:' . $result->errors->code .'Message:'. $result->errors->message);
                } elseif(property_exists($result, 'contact')) {
                    $customer = $this->customerRepositoryInterface->getById($customer->getId());
                    $customer->setCustomAttribute(self::FRESHSALES_ATTRIBUTE_CODE, $result->contact->id);
                    $this->customerRepositoryInterface->save($customer);
                } elseif(property_exists($result, 'login') && property_exists($result, 'message')) {
                    $this->logger->info('There was login problem:'. $result->message);
                } else {
                    $this->logger->info('There was no contact or errors field in response.');
                }
            } else {
                $this->logger->info('There was no response from API.');
            }
        }
    }
}
