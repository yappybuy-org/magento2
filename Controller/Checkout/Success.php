<?php


namespace YappyBuy\Checkout\Controller\Checkout;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface; 

class Success extends \Magento\Checkout\Controller\Onepage  implements HttpGetActionInterface 
{
  


    public function execute()
    {
        $params = $this->getRequest()->getParams();
		
		$orderId = $this->getRequest()->getParam('order_id',null);		
		$orderRepository=$this->_objectManager->get(\Magento\Sales\Api\OrderRepositoryInterface::class);
		$order = $orderRepository->get($orderId);
		
		$session = $this->getOnepage()->getCheckout(); 


		$session 
			->setLastOrderId($order->getId())
			->setLastSuccessQuoteId($order->getQuoteId())
			->setLastQuoteId($order->getQuoteId())
			->setLastRealOrderId($order->getIncrementId())
			->setLastOrderStatus($order->getStatus());
	


	
        return $this->resultRedirectFactory->create()->setPath('checkout/onepage/success');     
    }



} 