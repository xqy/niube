<?php
class KeplerTradeGetstockRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jd.kepler.trade.getstock";
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
                                    	                   			private $skus;
    	                        
	public function setSkus($skus){
		$this->skus = $skus;
         $this->apiParas["skus"] = $skus;
	}

	public function getSkus(){
	  return $this->skus;
	}

                        	                   			private $area;
    	                        
	public function setArea($area){
		$this->area = $area;
         $this->apiParas["area"] = $area;
	}

	public function getArea(){
	  return $this->area;
	}

                        	                   			private $category;
    	                        
	public function setCategory($category){
		$this->category = $category;
         $this->apiParas["category"] = $category;
	}

	public function getCategory(){
	  return $this->category;
	}

                        	                   			private $venderId;
    	                        
	public function setVenderId($venderId){
		$this->venderId = $venderId;
         $this->apiParas["venderId"] = $venderId;
	}

	public function getVenderId(){
	  return $this->venderId;
	}

}





        
 

