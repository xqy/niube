<?php
class KeplerXuanpinSkuPromotionBatchRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jd.kepler.xuanpin.sku.promotion.batch";
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
    private  $skuIds;

    public function setSkuIds($skuIds){
        $this->apiParas['skuIds'] = $skuIds;
    }
    public function getSkuIds(){
        return $this->apiParas['skuIds'];
    }
}

?>