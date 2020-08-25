<?php
class TeamSubGroupListGetRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.team.sub.group.list.get";
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
    private  $get_sub_group_list_parameter;

    public function setGet_sub_group_list_parameter($get_sub_group_list_parameter){
        $this->apiParas['get_sub_group_list_parameter'] = $get_sub_group_list_parameter;
    }
    public function getGet_sub_group_list_parameter(){
        return $this->apiParas['get_sub_group_list_parameter'];
    }
}

?>