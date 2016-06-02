<?php

define('PLSURL', 'http://yp.shoutcast.com/sbin/tunein-station.pls?id=%s');
define('DIRURL', 'http://dir.xiph.org/listen/%s/listen.m3u');

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'agents.php';

/**
 * Class lifeRadio
 */
class lifeRadio
{
	/**
	 * 
	 * @var unknown
	 */
    static $station = array();
    
    /**
     * 
     * @param string $primaryonly
     * @return Ambigous <multitype:, multitype:unknown >
     */
    static function getGenres($primaryonly = false, $format = 'format', $clause = '')
    {
    	static $genres = array();
    	
    	if (empty($genres))
    	{
    		$base = array();
    		foreach(file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'genres-radio.diz') as $genre)
    		{
    			$genre = trim($genre);
    			if (substr($genre, strlen($genre)-1, 1) == '=') {
    				$base['format'] = self::formatGenre($base['none']=substr($genre, 0, strlen($genre)-1), false);
    			} elseif (!empty($base['format'])) {
    				$genres['format'][$base['format']][self::formatGenre($genre)] = self::formatGenre($genre);
    				$genres['none'][$base['none']][$genre] = $genre;
    			}
    		}
    		unset($base);
    	}
    	return ($primaryonly == false) ? (!empty($clause) && isset($genres[$format][$clause]) ? $genres[$format][$clause] : $genres[$format] ) : array_keys($genres[$format]);
    }
    

    /**
     *
     * @param string $primaryonly
     * @return Ambigous <multitype:, multitype:unknown >
     */
    private function formatGenre($genre = '', $undo = false)
    {
    	if ($undo == false)
    	{
    		return str_replace(' ', '-', strtolower($genre));
    	} elseif ($undo == true) {
    		foreach(self::getGenres(false, 'none') as $base => $genres) {
    			if (self::formatGenre($base) == $genre)
    				return $base;
    			foreach($genres as $key => $style) {
    				if (self::formatGenre($style) == $genre)
    					return $style;
    			}
    		}
    	}
    	return $genre;
    }
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
    		curl_setopt($cc, CURLOPT_USERAGENT, lifeAgents::saveUserAgent() );
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
    		curl_close($cc);
    	}
    	if (strlen($data)==0 && function_exists('file_get_contents'))
    	{
    		$data = file_get_contents($url);
    	}
    	return (strlen($data)>0?$data:false);
    }
    
    /**
     *
     * @param string $mode
     * @param string $basis
     * @return Ambigous <mixed, NULL, boolean, unknown>
     */
    static function getDIRXPIHORG($uri = '', $downloaduri = '')
    {
    	$id = $format = $name = $onair = $bitrate = $
    	$ret = array();
    	$html = str_get_html($str = self::getExternal($uri));
    	foreach($html->find('table') as $elementtable)
    	{
	    	switch($elementtable->class)
	    	{
	    		case "servers-list":
			    	$table = str_get_html($str = $elementtable->innertext());
			    	foreach($table->find("tr") as $elementstr)
			    	{
			    		$item = array('bitrate' => "128", 'format' => "audio/ogg", 'ID' =>'', 'name' => '', 'genre' => '', 'currenttrack' => '', 
			    						'listeners' => 0, 'isradionomy' => false, 'iceurl' => '', 'isplaying' => '');
			    		foreach(str_get_html($elementstr->innertext())->find("td") as $elementstd)
			    		{
			    			switch($elementstd->class)
			    			{
			    				case "description":
			    					foreach(str_get_html($elementstd->innertext())->find("span") as $elementsspan)
			    					{
			    						switch($elementsspan->class)
			    						{
			    							case "name":
			    								$item['name'] = $elementsspan->plaintext;
			    								foreach(str_get_html($elementsspan->innertext())->find("a") as $elementsa)
			    								{
			    									if (empty($item['iceurl']))
			    										$item['iceurl'] = $elementsa->href;
			    								}
			    								break;
			    							case "listeners":
			    								$item['listeners'] = str_replace(array("[", "listeners", "listener", "&nbsp;", " ", "]"), "", $elementsspan->plaintext);
			    								break;
			    						}		
			    					}
			    					
			    					foreach(str_get_html($elementstd->innertext())->find("p") as $elementsp)
			    					{
			    						switch($elementsp->class)
			    						{
			    							case "stream-onair":
			    								$item['currenttrack'] = trim(str_replace(array("On", "Air:", "\t"), "", $elementsp->plaintext));
			    								
			    								break;
			    						}
			    					}
			    					foreach(str_get_html($elementstd->innertext())->find("ul.inline-tags") as $elementsul)
			    					{
			    						foreach(str_get_html($elementsul->innertext())->find("a") as $elementsa)
			    						{
				    						if (strpos($elementsa->href, "by_genre"))
				    						{
				    							if (empty($item['genre']))
				    								$item['genre'] = $elementsa->plaintext;
				    						}
			    						}
			    					}
			    					break;
			    				case "tune-in":
			    					foreach(str_get_html($elementstd->innertext())->find("p") as $elementsp)
			    					{
			    						switch($elementsp->class)
			    						{
			    							case "format":
			    								if (!empty($elementsp->title))
			    								{
			    									$item['bitrate'] = intval(str_replace(array("Kbps", "kbps", "Stream"), "", $elementsp->title));
			    									$item['format'] = "audio/".trim(strtolower(str_replace(array("									"," ", "\t", '"', "streams", "stream", "&nbsp;"), "", $elementsp->plaintext)));
			    								}
			    								break;
			    						}
			    					}
			    					foreach(str_get_html($elementstd->innertext())->find("a") as $elementsa)
			    					{
			    					if (strpos($elementsa->href, "listen.m3u"))
			    						{
			    							if (empty($item['ID']))
			    								$item['ID'] = str_replace(array("/listen/","/listen.m3u"), "", $elementsa->href);
			    							//$item['isradionomy'] = !(!strpos(self::getExternal("http://dir.xiph.org/".$elementa->href), "radionomy"));
			    						}
			    					}
			    					break;
			    			}
			    		}
			    		$ret[$item['ID']] = $item;
			    	}
			    	break;
	    	}
    	}
    	foreach($html->find('ul.pager') as $elementul)
    	{
	    	$pager = str_get_html($elementul->innertext());
	    	$next = false;
	    	foreach($pager->find("a") as $elementsa)
	    	{
	    		if ($next == true)
	    		{
	    			$ret = array_merge($ret, self::getDIRXPIHORG("http://dir.xiph.org/".$elementsa->href, $downloaduri));
	    			$next = false;
	    			continue;
	    		}
	    		if ($elementsa->class == 'active')
	    		{
	    			$next = true;
	    		}
	    	}
    	}
    	return $ret;
    }
    
    
    /**
     * 
     * @param string $mode
     * @param string $basis
     * @return Ambigous <mixed, NULL, boolean, unknown>
     */
    static function getStationsFromAPI($mode = 'top500', $basis = '')
    {
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	
    	switch($mode){
    		case 'genre':
    			if (!$stations = lifeCache::read('life_radio_genre_'.sha1($basis))) {
	    			if (!$plstations = lifeCache::read('life_pls_radio_genre_'.sha1($basis))) {
	   					$plstations = self::cleanStations(json_decode(self::getExternal("http://www.shoutcast.com/Home/BrowseByGenre", array('genrename' => self::formatGenre($basis, true))), true), constant("PLSURL"), 'parsePlaylist');
	   					lifeCache::write('life_pls_radio_genre_'.sha1($basis), $plstations, 60 * mt_rand(5, 11));
	    			}
	    			if (!$dirtations = lifeCache::read('life_xpih_radio_genre_'.sha1($basis))) {
	    				$dirtations = self::cleanStations(self::getDIRXPIHORG("http://dir.xiph.org/by_genre/" . str_replace(" ", "", self::formatGenre($basis, true))), constant("DIRURL"), "parseM3UPlaylist");
	    				lifeCache::write('life_xpih_radio_genre_'.sha1($basis), $dirtations, 60 * mt_rand(5, 11));
	    			}
	    			lifeCache::write('life_radio_genre_'.sha1($basis), $stations = array_merge($dirtations, $plstations), mt_rand(4, 19) * mt_rand(31, 90));
    			}
   				break;
   			case 'random':
   				if (!$stations = lifeCache::read('life_radio_random_'.md5($_SERVER["REMOTE_ADDR"]))) {
   					$stations = self::cleanStation(json_decode(self::getExternal("http://www.shoutcast.com/Home/GetRandomStation", array('query' => '')), true), constant("PLSURL"), 'parsePlaylist');
   					lifeCache::write('life_radio_random_'.md5($_SERVER["REMOTE_ADDR"]), $stations, 60 * mt_rand(0.11119, 0.78889));
   				}
   				break;
   			case 'search':
   				if (!$stations = lifeCache::read('life_radio_search_'.sha1($basis))) {
   					if (!$plstations = lifeCache::read('life_pls_radio_search_'.sha1($basis))) {
   						$plstations = self::cleanStations(json_decode(self::getExternal("http://www.shoutcast.com/Search/UpdateSearch", array('query' => $basis)), true), constant("PLSURL"), 'parsePlaylist');
   						lifeCache::write('life_pls_radio_search_'.sha1($basis), $plstations, 60 * mt_rand(5, 11));
   					}
   					if (!$dirtations = lifeCache::read('life_xpih_radio_search_'.sha1($basis))) {
   						$dirtations = self::cleanStations(self::getDIRXPIHORG("http://dir.xiph.org/?search=" . $basis), constant("DIRURL"), "parseM3UPlaylist");
   						lifeCache::write('life_xpih_radio_search_'.sha1($basis), $dirtations, 60 * mt_rand(5, 11));
   					}
   					lifeCache::write('life_radio_search_'.sha1($basis), $stations = array_merge($dirtations, $plstations), mt_rand(4, 19) * mt_rand(31, 90));
   				}
   				break;
   			default:
    		case 'top500':
    			if (!$stations = lifeCache::read('life_radio_top500')) {
    				$stations = self::cleanStations(json_decode(self::getExternal("http://www.shoutcast.com/Home/Top", array('query' => '')), true), constant("PLSURL"), 'parsePlaylist');
    				lifeCache::write('life_radio_top500', $stations, 60 * mt_rand(5, mt_rand(7,13)));
    			}
    			break;
    	}
    	return $stations;
    
    }

    /**
     * 
     * @param unknown $stations
     * @return multitype:NULL
     */
    private function cleanStations($stations = array(), $downloaduri = '', $func = 'parsePlaylist')
    {
    	$ret = array();
    	foreach($stations as $id => $station)
    	{
    		$ret[sha1($station['ID'].$_SERVER["HTTP_HOST"].$station['NAME'].$downloaduri)] = self::cleanStation($station, $downloaduri, $func);
    	}
    	return $ret;
    }

    /**
     *
     * @param unknown $stations
     * @return multitype:NULL
     */
    private function cleanStation($station = array(), $downloaduri = '', $func = 'parsePlaylist')
    {
    	$ret = array();
    	foreach($station as $key => $value)
    	{
    		switch ($key)
    		{
    			case "ID":
    				$ret['key'] = sha1($value.$_SERVER["HTTP_HOST"].$station['NAME'].$downloaduri);
    				$id = $value;
    				break;
    			default:
    				$ret[strtolower($key)] = $value;
    				break;
    			
    		}
    	}
    	if (!$keys = lifeCache::read('life_radio_identity_keys'))
    		$keys = array();
    	$keys[$ret['key']] = array('id' => $id, 'uri' => $downloaduri, 'func' => $func);
    	lifeCache::write('life_radio_identity_keys', $keys, 3600 * 48 * 7 * 4 * 12);
    	return $ret;
    }    
    
    
    /**
     * 
     * @param number $stationid
     * @param unknown $mimetypes
     * @return Ambigous <multitype:, multitype:Ambigous <unknown> >
     */
    static function getStreamsFromStationID($stationkey = '')
    {
    	if (!$keys = lifeCache::read('life_radio_identity_keys'))
    		return array();
    	if (!isset($keys[$stationkey]))
    		return array();
    	$func = $keys[$stationkey]['func'];
    	return array('streams' => self::$func(sprintf($keys[$stationkey]['uri'], $keys[$stationkey]['id'])));
    }

    /**
     * 
     * @param unknown $playlistUrl
     * @return Ambigous <multitype:unknown , mixed, NULL, boolean, unknown>
     */
    private function parsePlaylist($playlistUrl) {
    	if (!$streamUrls = lifeCache::read('life_radio_streams_'.sha1($playlistUrl))) {
	    	$response = self::getExternal($playlistUrl, array());
	    	$playlist = parse_ini_string($response);
	    	$streamUrls = array();
	    	foreach($playlist as $key => $value)
	    	{
	    		if (substr($key, 0, 4) == 'File')
	    			$streamUrls[] = $value;
	    	}
	    	lifeCache::write('life_radio_streams_'.sha1($playlistUrl), $streamUrls, 60 * mt_rand(30, 90) * mt_rand(45, 135));
    	}
    	return $streamUrls;
    }
    
    /**
     *
     * @param unknown $playlistUrl
     * @return Ambigous <multitype:unknown , mixed, NULL, boolean, unknown>
     */
    private function parseM3UPlaylist($playlistUrl) {
    	if (!$streamUrls = lifeCache::read('life_radio_streams_'.sha1($playlistUrl))) {
    		$response = self::getExternal($playlistUrl, array());
    		$streamUrls = explode("\n",str_replace(array("\R\n", "\n\R", "\n\n") ,"\n", $response));
    		foreach($streamUrls as $id => $value)
    			$streamUrls[$id] = trim($value);
    		lifeCache::write('life_radio_streams_'.sha1($playlistUrl), $streamUrls, 60 * mt_rand(30, 90) * mt_rand(45, 135));
    	}
    	return $streamUrls;
    }
    
}
