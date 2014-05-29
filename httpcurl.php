<?php
class HttpCurl {
	
	var $url;
	var $is_use_buffer;
	var $connect_timeout = 30;
	var $request_timeout = 120;
	
	public function __construct($url, $is_use_buffer = false) {
		$this->url = $url;
		$this->is_use_buffer = $is_use_buffer;
	}
	
	public function post($post_data) {
		$ch = curl_init();
		
		$post_params = $this->buildParams($post_data);
		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->request_timeout);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
		
		if($this->is_use_buffer)
			ob_start();
		
		$rs = curl_exec($ch);
		curl_close($ch);
		
		if($this->is_use_buffer) {
			$rs = ob_get_contents();
		}
		
		ob_clean();

		return $rs;
	} 
	
	public function get($params) {
		$get_params = $this->buildParams($params);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url.'?'.$get_params);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->request_timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$r = curl_exec($ch);
		curl_close($ch);
		
		return $r;
	}

	private function buildParams($params) {
		$params_arr = array();
	
		foreach($params  as $key => $param) {
			$params_arr[] = $key.'='.$param;
		}
	
		return implode('&', $params_arr);
	}
	
}