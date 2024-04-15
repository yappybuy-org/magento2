<?php

namespace YappyBuy\Checkout\Model\ResourceModel\Message;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected $_idFieldName = 'id';
	
    protected function _construct()
    {
        $this->_init('YappyBuy\Checkout\Model\Message', 'YappyBuy\Checkout\Model\ResourceModel\Message');
    }
	
	
	
}