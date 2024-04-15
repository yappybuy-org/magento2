<?php
 
namespace YappyBuy\Checkout\Model;
 

class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{

    const PAYMENT_METHOD_YAPPYBUY = 'yappybuy';

    
    protected $_canCapture                  = true;
    protected $_canRefund                   = true;
    protected $_canVoid                     = true;
    protected $_canDelete                   = true;
	protected $_canAuthorize              	= true;
	
    protected $_code = self::PAYMENT_METHOD_YAPPYBUY;
	
	protected $_infoBlockType = \YappyBuy\Checkout\Block\Info\Yappybuy::class;
	
	public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
		return $this;
	}
	
	public function cancel(\Magento\Payment\Model\InfoInterface $payment)
    {    
        return $this;
    }
	
	public function void(\Magento\Payment\Model\InfoInterface $payment)
    {
        return $this;
	}
	
	public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        return $this;
	}
	
	public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
		return $this;
	}
	
    public function canCapture()
    {
        if ($this->_canCapture ) {
            return true;
        }
        return false;
    }

    
    public function canRefund()
    {
        if ($this->_canRefund) {
            return true;
        }
        return false;
    }

    public function canVoid()
    {
        if ($this->_canVoid ) {
            return true;
        }

        return false;
    }
	
}