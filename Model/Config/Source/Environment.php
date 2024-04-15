<?php


namespace YappyBuy\Checkout\Model\Config\Source;



class Environment implements \Magento\Framework\Option\ArrayInterface
{


    public function toOptionArray()
    {
        return [
			['value' => 2, 'label' => __('Live')], 
			['value' => 1, 'label' => __('Test')],
			['value' => 3, 'label' => __('Custom')]
			];
    }


    public function toArray()
    {
        return [2 => __('Live'), 1 => __('Test'), 3=> __('Custom')];
    }
}