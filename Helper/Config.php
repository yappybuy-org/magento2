<?php

namespace YappyBuy\Checkout\Helper;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;


class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
	
		const URL_LIVE				= "https://checkout-api.yappybuy.com/";		
		const URL_TEST				= "https://checkout-api.dev.yappybuy.com/";
		
		const WEB_URL_LIVE			= "https://webapp.yappybuy.app/";
		const WEB_URL_TEST			= "https://webapp.dev.yappybuy.app/";

		
    private $cache;
		protected $scopeConfig;
		protected  $eavConfig;
    protected $storeManager;

    /**
     * Helper constructor.
     *
     * @param ScopeConfigInterface                           $scopeConfig
     * @param StoreManagerInterface                          $storeManager
     * 
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\CacheInterface $cache,
        StoreManagerInterface $storeManager		
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->eavConfig = $eavConfig;
        $this->cache = $cache;
        
    }
 
	public function isEnabled()
	{			
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$scope = "websites";
		return $this->scopeConfig->getValue("yappybuy_checkout/general/enable", $scope, $websiteId);
	}

	public function getLocale()
	{			
		$storeId = $this->storeManager->getStore()->getStoreId();	
		return $localeCode =  $this->scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
	}

	public function getMerchantLogin()
	{			
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$scope = "websites";
		return $this->scopeConfig->getValue("yappybuy_checkout/general/merchantLogin", $scope, $websiteId);
	}
	
	public function getMerchantKey()
	{			
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$scope = "websites";	
		return $this->scopeConfig->getValue("yappybuy_checkout/general/apiKey", $scope, $websiteId);
	}

	public function getMasked()
	{			
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$scope = "websites";	
		return $this->scopeConfig->getValue("yappybuy_checkout/general/masked", $scope, $websiteId);
	}	

	public function isTest()
	{			
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$scope = "websites";	
		return $this->scopeConfig->getValue("yappybuy_checkout/general/environment", $scope, $websiteId);
	}

	public function getApiUrl()
	{			
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$scope = "websites";	
		return $this->isTest()==1?self::URL_TEST:($this->isTest()==2?self::URL_LIVE:$this->scopeConfig->getValue("yappybuy_checkout/general/apiUrl", $scope, $websiteId)) ;
	}

	public function getWebUrl()
	{			
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$scope = "websites";	
		return $this->scopeConfig->getValue("yappybuy_checkout/general/test", $scope, $websiteId)?self::WEB_URL_TEST :self::WEB_URL_LIVE ;
		return $this->isTest()==1?self::WEB_URL_TEST:($this->isTest()==2?self::WEB_URL_LIVE:$this->scopeConfig->getValue("yappybuy_checkout/general/webUrl", $scope, $websiteId)) ;
	}

	public function getButtonIcon()
	{	
		return $this->scopeConfig->getValue("yappybuy_checkout/design/yIcon");
	}

	public function getButtonLabel()
	{	
		return $this->scopeConfig->getValue("yappybuy_checkout/design/label");
	}

	public function getQuickBuyLabel()
	{	
		return $this->scopeConfig->getValue("yappybuy_checkout/design/quicklabel");
	}

	public function getButtonBackground()
	{	
		return str_replace('##','#','#'.$this->scopeConfig->getValue("yappybuy_checkout/design/background"));
	}

	public function getButtonForeground()
	{			
		return str_replace('##','#','#'.$this->scopeConfig->getValue("yappybuy_checkout/design/foreground"));
	}	

	public function getButtonRadius()
	{			
		return $this->scopeConfig->getValue("yappybuy_checkout/design/radius");
	}	
	
	public function getButtonWidth()
	{			
		return $this->scopeConfig->getValue("yappybuy_checkout/design/width");
	}	

	public function getHideMagento()
	{			
		return $this->scopeConfig->getValue("yappybuy_checkout/design/hideMagento");
	}	

	public function getAddToCartButtonSelector()
	{	
		return $this->scopeConfig->getValue("yappybuy_checkout/design/addToCartButtonSelector");
	}	

	public function getShowQuickButton()
	{	
		return $this->scopeConfig->getValue("yappybuy_checkout/design/showQuickButton");
	}	

	public function getCartButtonSelector()
	{			
		return $this->scopeConfig->getValue("yappybuy_checkout/design/cartButtonSelector");
	}	

	public function getMiniCartButtonSelector()
	{			
		return $this->scopeConfig->getValue("yappybuy_checkout/design/miniCartButtonSelector");
	}		

	public function getMode()
	{			
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$scope = "websites";	
		return $this->scopeConfig->getValue("yappybuy_checkout/design/mode", $scope, $websiteId);
	}		

	


	public function storeResultInCache(string $data): bool
    {
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
        return $this->cache->save($data, 'ybAuthString'.'-website-'.$websiteId, ['config'], 86400);
    }
	
    public function getResultFromCache(): string
    {
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
        return $this->cache->load( 'ybAuthString'.'-website-'.$websiteId);
    }
	
  public function getAttributes(){
    $rez=array();
    $raw=preg_split("/\\r\\n|\\r|\\n/", $this->scopeConfig->getValue("yappybuy_checkout/general/attributes") );
    foreach($raw as $row){
      if(trim($row)){
        try {
          $attribute =  $this->eavConfig->getAttribute('catalog_product', trim($row));
          if($attribute && $attribute->getAttributeId()){
            $rez[$row]=$attribute->getStoreLabel();
          }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {

          //  attribute does not exist

        }				
      }
    }
    return $rez;
    
  }

  public function getSettings()
	{
    $width=(int) $this->getButtonWidth()? (int) $this->getButtonWidth():256;
		$bg=$this->getButtonBackground();
		$fg=$this->getButtonForeground();		
		$label=$this->getButtonLabel()?$this->getButtonLabel():'1-Click Order';
		$corner=(int) $this->getButtonRadius()?(int) $this->getButtonRadius():10;
		
		$button=str_replace(array('{{width}}','{{arrow}}','{{corner}}','{{bg}}','{{fg}}','{{label}}'),array($width,$width-35,$corner,$bg, $fg, $label),
		'<svg viewBox="0 0 {{width}} 54" xmlns="http://www.w3.org/2000/svg">
  <defs>
	<style>
		.bg{fill:{{bg}};}
		.icon{fill:#882898;}		
		.iconBg{fill:#ffffff;}
		.arrow{fill:{{fg}};}
		.label{fill:{{fg}};font: 25px sans-serif;}
		
	</style>
  </defs>
  <rect class="bg" x="0" width="{{width}}" height="54" rx="{{corner}}" />  
  '.($this->getButtonIcon()?'<g>
	<rect class="iconBg" x="8.93" y="14.42" width="27.34" height="25.15" rx="4.71"/>
	<g>
		<g>
			<path  class="icon" d="M855.49,537.13c.42-1.36,1-3.8,1.23-4.61s.34-1.62.47-2.4a4.74,4.74,0,0,1,2.28-.55,3.09,3.09,0,0,1,1.67.43,1.66,1.66,0,0,1,.66,1.5,9.82,9.82,0,0,1-.2,1.8c-.14.68-.31,1.41-.54,2.17s-.48,1.56-.77,2.38-.61,1.62-.94,2.4-.66,1.53-1,2.23-.68,1.33-1,1.89a21.59,21.59,0,0,1-1.5,2.3,8.8,8.8,0,0,1-1.33,1.4,4,4,0,0,1-1.26.69,5,5,0,0,1-1.33.18,2.69,2.69,0,0,1-2-.72,3,3,0,0,1-.89-1.87,23.88,23.88,0,0,0,1.84-1.6,6.36,6.36,0,0,0,1.67-1.8A20.89,20.89,0,0,0,855.49,537.13Z" transform="translate(-831.89 -512.25)"/>
		</g>
		<path  class="icon" d="M852.61,532.33l-.23-.78a2.58,2.58,0,0,0-1-1.57,3.12,3.12,0,0,0-1.67-.42,3.76,3.76,0,0,0-2.56,1c.31,1.29.6,2.43.88,3.41s.55,1.87.81,2.64.5,1.44.74,2a22.21,22.21,0,0,0,2.31-4.36C852.12,533.76,852.37,533,852.61,532.33Z" transform="translate(-831.89 -512.25)"/>
		</g>
	</g>':'').'
  <path class="arrow" d="m {{arrow}}.78,18.12 8.89,8.88 -8.89,8.89 -2.07,-2.07 6.81,-6.82 -6.81,-6.81 z" />
  <text  x="'.($this->getButtonIcon()?50:40).'%" y="27"  style="dominant-baseline:central; text-anchor:middle;" class="label">{{label}}</text>
  
</svg>');		

$quickLabel=$this->getQuickBuyLabel()?$this->getQuickBuyLabel():'Quick Buy';

$quickButton=str_replace(array('{{width}}','{{arrow}}','{{corner}}','{{bg}}','{{fg}}','{{label}}'),array($width,$width-35,$corner,$bg, $fg, $quickLabel),
'<svg viewBox="0 0 {{width}} 54" xmlns="http://www.w3.org/2000/svg">
<defs>
<style>
.bg{fill:{{bg}};}
.icon{fill:#882898;}		
.iconBg{fill:#ffffff;}
.arrow{fill:{{fg}};}
.label{fill:{{fg}};font: 25px sans-serif;}

</style>
</defs>
<rect class="bg" x="0" width="{{width}}" height="54" rx="{{corner}}" />  
'.($this->getButtonIcon()?'<g>
<rect class="iconBg" x="8.93" y="14.42" width="27.34" height="25.15" rx="4.71"/>
<g>
<g>
	<path  class="icon" d="M855.49,537.13c.42-1.36,1-3.8,1.23-4.61s.34-1.62.47-2.4a4.74,4.74,0,0,1,2.28-.55,3.09,3.09,0,0,1,1.67.43,1.66,1.66,0,0,1,.66,1.5,9.82,9.82,0,0,1-.2,1.8c-.14.68-.31,1.41-.54,2.17s-.48,1.56-.77,2.38-.61,1.62-.94,2.4-.66,1.53-1,2.23-.68,1.33-1,1.89a21.59,21.59,0,0,1-1.5,2.3,8.8,8.8,0,0,1-1.33,1.4,4,4,0,0,1-1.26.69,5,5,0,0,1-1.33.18,2.69,2.69,0,0,1-2-.72,3,3,0,0,1-.89-1.87,23.88,23.88,0,0,0,1.84-1.6,6.36,6.36,0,0,0,1.67-1.8A20.89,20.89,0,0,0,855.49,537.13Z" transform="translate(-831.89 -512.25)"/>
</g>
<path  class="icon" d="M852.61,532.33l-.23-.78a2.58,2.58,0,0,0-1-1.57,3.12,3.12,0,0,0-1.67-.42,3.76,3.76,0,0,0-2.56,1c.31,1.29.6,2.43.88,3.41s.55,1.87.81,2.64.5,1.44.74,2a22.21,22.21,0,0,0,2.31-4.36C852.12,533.76,852.37,533,852.61,532.33Z" transform="translate(-831.89 -512.25)"/>
</g>
</g>':'').'
<path class="arrow" d="m {{arrow}}.78,18.12 8.89,8.88 -8.89,8.89 -2.07,-2.07 6.81,-6.82 -6.81,-6.81 z" />
<text  x="'.($this->getButtonIcon()?'50':'40').'%" y="27"  style="dominant-baseline:central; text-anchor:middle;" class="label">{{label}}</text>

</svg>');		
		return array(
			'checkStatusDelay' => 1000,
			'button'=>$button,
			'quickButton'=>$quickButton,
			'addToCartButtonSelector' => $this->getAddToCartButtonSelector()? $this->getAddToCartButtonSelector():'#product-addtocart-button',
			'popUpButtonSelector' => $this->getMiniCartButtonSelector()? $this->getMiniCartButtonSelector():'#top-cart-btn-checkout',
			'cartButtonSelector' => $this->getCartButtonSelector()? $this->getCartButtonSelector():'.checkout-cart-index .action.primary.checkout',							
			'disableMagentoButton'=> $this->getHideMagento()?1:0,			
			'activateLabel'=>'Checkout was activated, click here to return to your checkout window.',
			'mode'=>$this->getMode(),
			'showQuickButton'=>$this->getShowQuickButton(),
			'webUrl'=>$this->getWebUrl()
		);
  }
    
}
