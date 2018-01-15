<?php

namespace Freshsales\Module\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

/**
 * Class Handler
 *
 * @package Freshsales\Module\Logger
 * @author  Michał Wejwoda <mwejwoda@pl.sii.eu>
 */
class Handler extends Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/freshsales.log';
}
