<?php

namespace Freshsales\Module\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const XML_PATH = 'freshsales/general/';

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

    public function createFreshsalesAccount() {
        $userData = array("first_name" => "MW", "last_name" => "Miroslaw");
        $ch = curl_init($this->getGeneralConfig('api_url'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Token token=".$this->getGeneralConfig('api_key'), "Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));

        $result = curl_exec($ch);
        //var_dump($result);
    }

}
