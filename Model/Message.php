<?php

namespace YappyBuy\Checkout\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

use YappyBuy\Checkout\Model\ResourceModel\Message as MessageResource;
use YappyBuy\Checkout\Model\ResourceModel\Message\Collection;
use YappyBuy\Checkout\Helper\Data;

class Message extends AbstractModel
{
    
    protected $storeManager;
    

    public function __construct(
        Context $context,
        Registry $registry,
        MessageResource $resource = null,
        Collection $resourceCollection = null,
        array $data = []
    )
    {
        
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
		parent::_construct();
        $this->_init('YappyBuy\Checkout\Model\ResourceModel\Message');
    }
   
}
