<?php


namespace YappyBuy\Checkout\Controller\Checkout;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Controller for processing add to cart action.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Quick extends \Magento\Checkout\Controller\Cart\Add
{
  
	protected $logger;
	protected $helper;
	protected $config;
	protected $redirect;
	

  /**
   * @param \Magento\Framework\App\Action\Context $context
   * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
   * @param \Magento\Checkout\Model\Session $checkoutSession
   * @param \Magento\Store\Model\StoreManagerInterface $storeManager
   * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
   * @param CustomerCart $cart
   * @param ProductRepositoryInterface $productRepository
   * @codeCoverageIgnore
   */  
  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Checkout\Model\Session $checkoutSession,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
    CustomerCart $cart,
    ProductRepositoryInterface $productRepository,
    \Magento\Framework\App\Response\RedirectInterface $redirect,
    \YappyBuy\Checkout\Helper\Data $helper,
    \YappyBuy\Checkout\Helper\Config $config,
    \YappyBuy\Checkout\Logger\Logger $logger
  ) {
      parent::__construct(
          $context,
          $scopeConfig,
          $checkoutSession,
          $storeManager,
          $formKeyValidator,
          $cart,
          $productRepository
      );      
      $this->logger = $logger;      
      $this->helper = $helper;
      $this->config=$config;
      $this->redirect = $redirect;      
  }

  /**
   * Resolve response
   *
   * @param string $backUrl
   * @param \Magento\Catalog\Model\Product $product
   * @return $this|\Magento\Framework\Controller\Result\Redirect
   */
  protected function goBack($backUrl = null, $product = null)
  {

      $result = [];
    
      $messageCollection = $this->messageManager->getMessages(true);
      if ($messageCollection && $messageCollection->getItems()) {
          foreach ($messageCollection->getItems() as $message) {
            if('error' == $message->getType()){
              $result = [
                'status' => 'error',
                'statusText' => $message->getText()
              ];
              $this->getResponse()->representJson(
                $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($result)
              );
              return;
            }
          }
      }
      
      $mode = $this->getRequest()->getParam('mode');
      $refererUrl= trim(strtok($this->redirect->getRefererUrl(),"?#"),"/");
      $res=$this->helper->registerCart($mode=='iframe'?$refererUrl:null);
      
      
      if(isset($res['code']) && $res['code']){        
        $result = [
          'status' => 'success',
          'statusText' => __('OK'),
          'url' => $res['uri']
        ];        
      }else{
        $result = [
          'status' => 'error',
          'statusText' => 'Checkout can not be initiated'
        ];
      }

      $this->getResponse()->representJson(
          $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($result)
      );
  }

    /**
     * Is redirect should be performed after the product was added to cart.
     *
     * @return bool
     */
    private function shouldRedirectToCart()
    {
        return false;
    }

} 