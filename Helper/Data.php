<?php

namespace YappyBuy\Checkout\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

	
	protected $session;
	protected $cartRepositoryInterface;
	protected $customerSession;
	protected $maskedCartId;
	protected $logger;
	protected $curl;
	

	public function __construct(
		\Magento\Checkout\Model\Session $session,
		\Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface $maskedCartId,
		\YappyBuy\Checkout\Logger\Logger $logger,
		Curl $curl		
			
	) {
		
		$this->session = $session;
		$this->cartRepositoryInterface = $cartRepositoryInterface;
		$this->customerSession=$customerSession;
		$this->maskedCartId=$maskedCartId;
		$this->logger = $logger;
		$this->curl=$curl;
		
		
	}
 
		


	public function registerCart($url){
		
		$quote = $this->session->getQuote();
		
		$quote = $this->cartRepositoryInterface->get($quote->getId());
			
		$data=array(
			"reference"=>(!$this->customerSession->isLoggedIn()?$this->maskedCartId->execute($quote->getId()):$quote->getId()),
			"meta"=>array(
				"guest"=> !$this->customerSession->isLoggedIn()
			)			
		);
		

		$result=$this->curl->curlRequest('POST','api/v1/carts',$data); 

		if(isset($result["cartId"]) && $result["cartId"]){
	
		}
		return $result;

	}


    
}
