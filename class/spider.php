<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'agents.php';

/**
 * Class lifeSpider
 */
class lifeSpider
{
	
    /**
     * 
     * @param unknown $url
     * @param unknown $vars
     * @return Ambigous <boolean, string, mixed>
     */
    private function getExternal($url, $vars = array()) {
    	$data = '';
    	if (strlen($data)==0 && function_exists('curl_init')) {
    		$cc = curl_init();
    		curl_setopt($cc, CURLOPT_USERAGENT, lifeAgents::anyUserAgent() );
    		curl_setopt($cc, CURLOPT_POST, (count($vars)>0?true:false));
    		if (count($vars)>0)
    			curl_setopt($cc, CURLOPT_POSTFIELDS, http_build_query($vars) );
    		curl_setopt($cc, CURLOPT_URL, $url);
    		curl_setopt($cc, CURLOPT_HEADER, FALSE);
    		curl_setopt($cc, CURLOPT_FOLLOWLOCATION, TRUE);
    		curl_setopt($cc, CURLOPT_RETURNTRANSFER, TRUE);
    		curl_setopt($cc, CURLOPT_FORBID_REUSE, TRUE);
    		curl_setopt($cc, CURLOPT_VERBOSE, false);
    		curl_setopt($cc, CURLOPT_SSL_VERIFYHOST, false);
    		curl_setopt($cc, CURLOPT_SSL_VERIFYPEER, false);
    		$data = curl_exec($cc);
    		$info = curl_getinfo($cc);
    		$method = 'curl';
    		curl_close($cc);
    	}
    	if (strlen($data)==0 && function_exists('file_get_contents'))
    	{
    		$data = file_get_contents($url);
    		$info = array();
    		$method = 'wget';
    	}
    	return array('result' => $data, 'info' => $info, 'method' => $method);
    }
 
}
