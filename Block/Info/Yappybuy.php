<?php

namespace YappyBuy\Checkout\Block\Info;

class Yappybuy extends \Magento\Payment\Block\Info
{
    /**
     * @var string
     */
    protected $_usedPaymentMethod;

    /**
     * @var string
     */
    protected $_paymentId;

    /**
     * @var string
     */
    protected $_template = 'YappyBuy_Checkout::info/yappybuy.phtml';

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getUsedPaymentMethod()
    {
        if ($this->_usedPaymentMethod === null) {
            $this->_convertAdditionalData();
        }
        return $this->_usedPaymentMethod;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getPaymentId()
    {
        if ($this->_paymentId === null) {
            $this->_convertAdditionalData();
        }
        return $this->_paymentId;
    }

    /**
     * @deprecated 100.1.1
     * @return $this
     */
    protected function _convertAdditionalData()
    {
        $this->_paymentId  = $this->getInfo()->getAdditionalInformation('paymentId');
        $this->_usedPaymentMethod = $this->getInfo()->getAdditionalInformation('usedPaymentMethod');
        return $this;
    }

    /**
     * @return string
     */
    public function toPdf()
    {
        $this->setTemplate('YappyBuy_Checkout::info/pdf/yappybuy.phtml');
        return $this->toHtml();
    }
}
