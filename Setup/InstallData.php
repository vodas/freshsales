<?php

namespace Freshsales\Module\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;

/**
 * Class InstallData
 *
 * @package Freshsales\Module\Setup
 * @author  Michał Wejwoda <mwejwoda@pl.sii.eu>
 */
class InstallData implements InstallDataInterface
{

    const ATTRIBUTE_CODE = 'freshsales_id';

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param Config          $eavConfig
     */
    public function __construct(EavSetupFactory $eavSetupFactory, Config $eavConfig)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            Customer::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'varchar',
                'label' => 'Freshsales customer ID',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => false,
                'position' => 999,
                'system' => 0,
            ]
        );
        $sampleAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, self::ATTRIBUTE_CODE);
        $sampleAttribute->setData('used_in_forms', ['adminhtml_customer']);
        $sampleAttribute->save();
    }
}
