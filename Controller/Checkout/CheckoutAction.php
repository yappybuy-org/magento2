<?php


namespace YappyBuy\Checkout\Controller\Checkout;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

/**
 * 
 */
class CheckoutAction extends Action 

{
    
	protected $logger;
	protected $helper;
	protected $config;
	protected $redirect;
	protected $json;
	
	
	public function __construct(
	\YappyBuy\Checkout\Logger\Logger $logger,
	\YappyBuy\Checkout\Helper\Data $helper,
	\YappyBuy\Checkout\Helper\Config $config,
	\Magento\Framework\Json\Helper\Data $json, 
	\Magento\Framework\App\Response\RedirectInterface $redirect,
			Context $context
	) {
		parent::__construct($context);
		$this->logger = $logger;
		$this->json=$json;
		$this->helper = $helper;
		$this->config=$config;
		$this->redirect = $redirect;
		
  }

	public function execute()
	{     

		$mode = $this->getRequest()->getParam('mode');
		
$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/mode.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);
$logger->info($mode);		
		
		$refererUrl= trim(strtok($this->redirect->getRefererUrl(),"?#"),"/");
		$result=$this->helper->registerCart($mode=='iframe'?$refererUrl:null);
		
		$res=array();
		if(isset($result['uri']) && $result['uri']){
			//$res['url']=$this->config->getWebUrl().'cart/'.$result['code'];
			$res['url']=$result['uri'];
			$res['status']='success';
		}else if(isset($result['status']) && $result['status']==400){
			$res['message']=$result['title'];
			$res['status']='error';
		}else{
			$res['message']='Checkot can not be started. Contact support.';
			$res['status']='error';
		}
		
		
		
		$this->logger->info(print_r('$refererUrl='.$refererUrl,true));	
		$this->logger->info(print_r($result,true));	
		$this->logger->info(print_r($res,true));	
		
		
		
		$this->getResponse()->representJson(
			$this->json->jsonEncode($res) 
    );
		
            
  }
}
