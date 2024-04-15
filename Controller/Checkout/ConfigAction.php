<?php


namespace YappyBuy\Checkout\Controller\Checkout;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

/**
 * 
 */

class ConfigAction extends Action
{
    
	protected $helper;
	protected $jsonHelper;
	
	
    public function __construct(
		\YappyBuy\Checkout\Helper\Config $helper,
		\Magento\Framework\Json\Helper\Data $jsonHelper,
        Context $context
    ) {
      parent::__construct($context);
			$this->helper = $helper;
			$this->jsonHelper = $jsonHelper;    
    }

    public function execute()
    {		
			$this->getResponse()->representJson(
				$this->jsonHelper->jsonEncode($this->helper->getSettings())
      );		
            
    }
}
