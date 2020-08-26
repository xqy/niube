<?php
class KplOpenRegularPlanQueryplancountRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jd.kpl.open.regular.plan.queryplancount";
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
    private  $planParam;

    public function setPlanParam($planParam){
        $this->apiParas['planParam'] = $planParam;
    }
    public function getPlanParam(){
        return $this->apiParas['planParam'];
    }
}

?>