<?php

namespace YappyBuy\Checkout\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use YappyBuy\Checkout\Helper\Logger;

class Button extends Template
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;



    protected $yappyHelper;

    /**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;

    /**
     * @var \Magento\Tax\Helper\Data
     */
    protected $taxHelper;

    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    protected $localeResolver;


    public function __construct(
        Template\Context $context,
        Registry $registry,
        PriceCurrencyInterface $priceCurrency,
        \YappyBuy\Checkout\Helper\Data $yappyHelper,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Framework\Locale\Resolver $localeResolver,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->registry = $registry;
        $this->priceCurrency = $priceCurrency;
        $this->yappyHelper = $yappyHelper;
        $this->urlBuilder = $context->getUrlBuilder();
        $this->checkoutHelper = $checkoutHelper;
        $this->taxHelper = $taxHelper;
        $this->localeResolver = $localeResolver;        
    }

    /**
     * Check Is Block enabled
     * @return bool
     */
    public function isEnabled()
    {
        return $this->yappyHelper->isEnabled();
    }

    

    /**
     * Get Quote
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        $quote = $this->checkoutHelper->getCheckout()->getQuote();
        if (!$quote->getId()) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $quote = $objectManager->create('Magento\Checkout\Model\Session')->getQuote();
        }

        return $quote;
    }




}
