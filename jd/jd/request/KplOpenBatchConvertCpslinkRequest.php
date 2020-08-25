<?php
class KplOpenBatchConvertCpslinkRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jd.kpl.open.batch.convert.cpslink";
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
                                                        		                                    	                   			private $urls;
    	                        
	public function setUrls($urls){
		$this->urls = $urls;
         $this->apiParas["urls"] = $urls;
	}

	public function getUrls(){
	  return $this->urls;
	}

                        	                   			private $type;
    	                        
	public function setType($type){
		$this->type = $type;
         $this->apiParas["type"] = $type;
	}

	public function getType(){
	  return $this->type;
	}

                        	                   			private $appKey;
    	                        
	public function setAppKey($appKey){
		$this->appKey = $appKey;
         $this->apiParas["appKey"] = $appKey;
	}

	public function getAppKey(){
	  return $this->appKey;
	}

                        	                   			private $subUnionId;
    	                        
	public function setSubUnionId($subUnionId){
		$this->subUnionId = $subUnionId;
         $this->apiParas["subUnionId"] = $subUnionId;
	}

	public function getSubUnionId(){
	  return $this->subUnionId;
	}

                        	                   			private $jdShortUrl;
    	                        
	public function setJdShortUrl($jdShortUrl){
		$this->jdShortUrl = $jdShortUrl;
         $this->apiParas["jdShortUrl"] = $jdShortUrl;
	}

	public function getJdShortUrl(){
	  return $this->jdShortUrl;
	}

                            }





        
 

