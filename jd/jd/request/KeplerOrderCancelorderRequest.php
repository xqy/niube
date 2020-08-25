<?php
class KeplerOrderCancelorderRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jd.kepler.order.cancelorder";
	}
	
	public function getApiParas(){
        if(empty($this->apiParas)){
	        return "{}";
	    }
		return json_encode($this->apiParas);
	}
	
	public function check(){
		
	}
	
    public function putOtherTextParam($key, $value){
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}

    private  $version;

    public function setVersion($version){
        $this->version = $version;
    }

    public function getVersion(){
        return $this->version;
    }
    private  $cancelOrderReq;

    public function setCancelOrderReq($cancelOrderReq){
        $this->apiParas['cancelOrderReq'] = $cancelOrderReq;
    }
    public function getCancelOrderReq(){
        return $this->apiParas['cancelOrderReq'];
    }
    private  $client;

    public function setClient($client){
        $this->apiParas['client'] = $client;
    }
    public function getClient(){
        return $this->apiParas['client'];
    }
}

?>