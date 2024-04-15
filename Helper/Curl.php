<?php

namespace YappyBuy\Checkout\Helper;

class Curl extends \Magento\Framework\App\Helper\AbstractHelper
{

			
		private $config;
    private $lastRequest;
    private $lastError;
    private $logger;
	
    /**
     * Helper constructor.
     *
     * @param \Magento\Directory\Helper\Config                 $config
     * 
     */
  public function __construct(
    Config  $config,
    \YappyBuy\Checkout\Logger\Logger $logger

    ) {
			$this->config = $config;		
      $this->lastRequest = array();
      $this->logger = $logger;
    }
 
		
	
  public function authenticate()
  {
		if(! ($authKey=$this->config->getResultFromCache())){
        
//			$formattedResponse= null;
			// $endpoint='api/v1/authenticate';		
			// $ch = curl_init();
			// curl_setopt($ch, CURLOPT_URL, $this->config->getApiUrl().$endpoint);			
			
			// curl_setopt($ch, CURLOPT_HTTPHEADER, [
			// 	'accept: */*',
			// 	'Content-Type: application/json;charset=UTF-8'
			// ]);		
			
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_VERBOSE, 1);
			// curl_setopt($ch, CURLOPT_HEADER, 1);
			// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			// curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			// curl_setopt($ch, CURLOPT_ENCODING, '');
			// curl_setopt($ch, CURLINFO_HEADER_OUT, 0);		
			// curl_setopt($ch, CURLOPT_POST, 0);
			
			
			// $this->attachRequestBody($ch, array(
			// 		'username'=>$this->config->getMerchantLogin(),
			// 		'password'=>$this->config->getMerchantKey(),
			// 		'rememberMe'=>1
			// ));
			
			// $responseContent = curl_exec($ch);
			// $response['headers'] = curl_getinfo($ch);     
			// $response = $this->setResponseState($response, $responseContent, $ch);
		  			
			// $err      = curl_error($ch);
					   
			// $formattedResponse = $this->formatResponse($response);
			// curl_close($ch);
     
      
			//if($formattedResponse && isset($formattedResponse['id_token'])){
			//	$this->config->storeResultInCache($formattedResponse['id_token']);
				return $this->config->getMerchantKey() ;//$formattedResponse['id_token'];				
			//}
		}else{	
			return $authKey;
		}
    $this->logger->info('YappyBuy\Checkout\Helper\Curl:  authenticate error');	
		return false;
  }		


  public function curlRequest($type,$endpoint,$data=null)
  {
$this->logger->info(print_r($type,true));	
$this->logger->info(print_r($endpoint,true));	
$this->logger->info(json_encode($data));	

		if($authKey=$this->authenticate()){			
			$formattedResponse= null;
$this->logger->info('$authKey='.$authKey);			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->config->getApiUrl().$endpoint);						
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'accept: */*',
				'Content-Type: application/json;charset=UTF-8',
				'Authorization: Bearer '.$authKey
			]);		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLINFO_HEADER_OUT, 0);		
			
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
			
			if($type=='POST'){
				curl_setopt($ch, CURLOPT_POST, 1);
				$this->attachRequestBody($ch, $data);
			}
			
			if($type=='PUT'){
			
				$this->attachRequestBody($ch, $data);
			}			
			$responseContent = curl_exec($ch);
			$err      = curl_error($ch);			
      if($err){
        $this->logger->info('YappyBuy\Checkout\Helper\Curl:  curlRequest error');
        $this->logger->info(print_r($err,true));	
      }
			$response['headers'] = curl_getinfo($ch);
			$response = $this->setResponseState($response, $responseContent, $ch);	   
		   
			$formattedResponse = $this->formatResponse($response);
$this->logger->info(print_r($response,true));	  			
			curl_close($ch);
$this->logger->info(print_r($formattedResponse,true));	      
			return $formattedResponse;		
		}
		return false;
  }	



  private function setResponseState($response, $responseContent, $ch)
  {
    if ($responseContent === false) {
        $this->lastError = curl_error($ch);
    } else {
        $headerSize = $response['headers']['header_size'];
        $response['httpHeaders'] = $this->getHeadersAsArray(substr($responseContent, 0, $headerSize));
        $response['body'] = substr($responseContent, $headerSize);
        if (isset($response['headers']['request_header'])) {
            $this->lastRequest['headers'] = $response['headers']['request_header'];
        }
    }

    return $response;
  } 

  private function getHeadersAsArray($headerString)
  {
    $headers = [];
    foreach (explode("\r\n", $headerString) as $i => $line) {
      if ($i === 0) {
          continue;
      }
      $line = trim($line);
      if (empty($line) ) {
          continue;
      }		
      $arr = explode(': ', $line);
			if(isset($arr[1])){
				$headers[$arr[0]] = $arr[1];
			}
    }
    return $headers;
  }

  private function attachRequestBody(&$ch, $data)
  {	
		if($data){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}
    }

    private function formatResponse($response)
    {        
        if (!empty($response['body'])) {
            return json_decode($response['body'],true);
        }
        return false;
  }		
    
}
