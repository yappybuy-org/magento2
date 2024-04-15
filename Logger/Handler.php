<?php

namespace YappyBuy\Checkout\Logger;

use Monolog\Logger;
class Handler extends \Magento\Framework\Logger\Handler\Base
{

    protected $loggerType = Logger::INFO;
  

    protected $fileName = '/var/log/yappyBuy.log';
}