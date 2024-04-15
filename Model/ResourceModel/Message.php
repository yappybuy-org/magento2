<?php

namespace YappyBuy\Checkout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Message extends AbstractDb
{
	
	
    public function __construct(
        Context $context,
        $connectionName = null
    )
    {
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('yb_checkout_message', 'id');
    }
}
