<?php

namespace Freshsales\Module\Helper;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Freshsales\Module\Logger\Logger;

/**
 * Class Data
 *
 * @package Freshsales\Module\Helper
 * @author  Michał Wejwoda <mwejwoda@pl.sii.eu>
 */
class Data extends AbstractHelper
{

    /**
     * path to core_config_data
     */
    const XML_PATH = 'freshsales/general/';

    /**
     * freshsales attribute code
     */
    const FRESHSALES_ATTRIBUTE_CODE = 'freshsales_id';

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepositoryInterface;

    /**
     * Data constructor.
     *
     * @param Context                     $context
     * @param Logger                      $logger
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context, Logger $logger,
        CustomerRepositoryInterface $customerRepository
    )
    {
        $this->logger = $logger;
        $this->customerRepositoryInterface = $customerRepository;
        parent::__construct($context);
    }

    /**
     * @param      $field
     * @param null $storeId
     *
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * @param string $code
     * @param null   $storeId
     *
     * @return string
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH . $code, $storeId);
    }

    /**
     * @param object $customer
     */
    public function createFreshsalesAccount($customer)
    {

        $apiUrl = (string)$this->getGeneralConfig('api_url');
        $apiKey = (string)$this->getGeneralConfig('api_key');

        if ($customer && !empty($apiUrl) && !empty($apiKey)) {
            $userData = [
                'first_name' => $customer->getFirstname(),
                'last_name' => $customer->getLastname(),
                'email' => $customer->getEmail(),
            ];

            $result = $this->makeRequestToApi($apiUrl, $apiKey, $userData);
            if (!$result) {
                $this->logger->info('There was no response from API.');
            }
            $result = json_decode($result);
            $this->responseMessage($result, $customer);
        }
    }

    /**
     * @param object $result
     * @param array $customer
     */
    private function responseMessage($result, $customer)
    {
        if (property_exists($result, 'contact')) {
            $customer = $this->customerRepositoryInterface->getById($customer->getId());
            $customer->setCustomAttribute(self::FRESHSALES_ATTRIBUTE_CODE, $result->contact->id);
            $this->customerRepositoryInterface->save($customer);
            return;
        }

        if (property_exists($result, 'errors')) {
            $this->logger->info('Code:' . $result->errors->code . 'Message:' . $result->errors->message);
            return;
        }

        if (property_exists($result, 'login') && property_exists($result, 'message')) {
            $this->logger->info('There was login problem:' . $result->message);
            return;
        }
        $this->logger->info('There was no contact or errors field in response.');
    }

    /**
     * @param string $apiUrl
     * @param string $apiKey
     * @param array  $userData
     *
     * @return mixed
     */
    public function makeRequestToApi($apiUrl, $apiKey, $userData)
    {
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $auth = "Authorization: Token token=" . $apiKey;
        curl_setopt($ch, CURLOPT_HTTPHEADER, [$auth, "Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))]);
        return curl_exec($ch);
    }
}
