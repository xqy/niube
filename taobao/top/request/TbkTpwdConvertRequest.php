<?php

class TbkTpwdConvertRequest
{

	private $passwordContent;

	private $adzoneId;

	private $dx;
	
	
	private $apiParas = array();
	
	public function setPasswordContent($passwordContent)
	{
		$this->passwordContent = $passwordContent;
		$this->apiParas["password_content"] = $passwordContent;
	}

	public function getPasswordContent()
	{
		return $this->passwordContent;
    }
    
    public function setAdzoneId($adzoneId)
	{
		$this->adzoneId = $adzoneId;
		$this->apiParas["adzone_id"] = $adzoneId;
	}

	public function getAdzoneId()
	{
		return $this->adzoneId;
    }
    
    public function setDx($dx)
	{
		$this->dx = $dx;
		$this->apiParas["dx"] = $dx;
	}

	public function getDx()
	{
		return $this->dx;
	}


	public function getApiMethodName()
	{
		return "taobao.wireless.share.tpwd.query";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->passwordContent,"text");
		// RequestCheckUtil::checkNotNull($this->adzoneId,"text");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
