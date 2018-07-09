<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'agents.php';

/**
 * Class lifeRadio
 */
class lifeVision
{

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
    		foreach(file(dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . 'genres-vision.diz') as $genre)
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
     * @param string $mode
     * @param string $basis
     * @return Ambigous <mixed, NULL, boolean, unknown>
     */
    static function getChannelsFromAPI($mode = 'top500', $basis = '')
    {
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	
    	switch($mode){
    		case 'genre':
    			if (!$channels = APICache::read('life_vision_genre_'.sha1($basis))) {
   					$channels = self::cleanChannels(json_decode(self::getExternal("http://www.shoutcast.com/Home/BrowseByGenre", array('genrename' => self::formatGenre($basis, true)), true)));
   					APICache::write('life_vision_genre_'.sha1($basis), $channels, 60 * mt_rand(5, 11));
    			}
   				break;
   			case 'random':
   				if (!$channels = APICache::read('life_vision_random_'.md5(whitelistGetIP(true)))) {
   					$channels = self::cleanChannel(json_decode(self::getExternal("http://www.shoutcast.com/Home/GetRandomChannel", array('query' => '')), true));
   					APICache::write('life_vision_random_'.md5(whitelistGetIP(true)), $channels, 60 * mt_rand(0.11119, 0.78889));
   				}
   				break;
   			case 'search':
   				if (!$channels = APICache::read('life_vision_search_'.sha1($basis))) {
   					$channels = self::cleanChannels(json_decode(self::getExternal("http://www.shoutcast.com/Search/UpdateSearch", array('query' => $basis)), true));
   					APICache::write('life_vision_search_'.sha1($basis), $channels, mt_rand(24, 89) * mt_rand(120, 720));
   				}
   				break;
   			default:
    		case 'top500':
    			if (!$channels = APICache::read('life_vision_top500')) {
    				$channels = self::cleanChannels(json_decode(self::getExternal("http://www.shoutcast.com/Home/Top", array('query' => '')), true));
    				APICache::write('life_vision_top500', $channels, 60 * mt_rand(5, mt_rand(7,13)));
    			}
    			break;
    	}
    	return $channels;
    
    }

    
    /**
     * 
     * @param number $channelid
     * @param unknown $mimetypes
     * @return Ambigous <multitype:, multitype:Ambigous <unknown> >
     */
    static function getHTMLFromChannelKey($channelkey = '')
    {
    	if (!$keys = APICache::read('life_vision_identity_keys'))
    		return array();
    	if (!isset($keys[$channelkey]))
    		return array();
    	return array('html' => self::parsePlaylist(sprintf(PLSURL, $keys[$channelkey])));
    }
 
}
