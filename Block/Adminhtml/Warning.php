<?php
declare(strict_types=1);

namespace YappyBuy\Checkout\Block\Adminhtml;

class Warning extends \Magento\Config\Block\System\Config\Form\Field
{

	
	protected $urlInterface;

    public function __construct(

        \Magento\Backend\Block\Template\Context $context,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\UrlInterface $urlInterface,
        array $data = [],
        $metadata = null
    ) {
		
		$this->scopeConfig = $scopeConfig;
		$this->urlInterface = $urlInterface;
        parent::__construct($context, $data);

    }


    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
		$html = '';
    
		if($this->scopeConfig->getValue("customer/address/telephone_show", $element['scope'], $element['scope_id'])=='req'){
			$html = '<div style="color:red;padding:10px;background-color:#f8f8f8;border:1px solid #ddd;margin-bottom:7px;">
						Customer phone is required. For YappyBuy checkout module phone must be disabled or optional. You can change this setting <a href="'.
						$this->urlInterface->getUrl('adminhtml/system_config/edit/section/customer',($element['scope_id']?array(($element['scope']=='websites'?'website':$element['scope']) => $element['scope_id']):[])).'#customer_address-link">here</a>
					</div>';
			
		}
        return $html;
    }
}
