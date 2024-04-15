<?php


namespace YappyBuy\Checkout\Model\Config\Source;



class Mode implements \Magento\Framework\Option\ArrayInterface
{


    public function toOptionArray()
    {
        return [
			['value' => 0, 'label' => __('Same Tab')], 
			['value' => 'popup', 'label' => __('PopUp')],
			['value' => 'newtab', 'label' => __('New Tab')]
			];
    }


    public function toArray()
    {
        return [0 => __('Same Tab'), 'popup' => __('PopUp'), 'newtab'=> __('New Tab')];
    }
}
