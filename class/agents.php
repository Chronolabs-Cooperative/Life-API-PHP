<?php

/**
 * Class lifeAgents
 */
class lifeAgents
{

    /**
     * 
     * @return Ambigous <multitype:, multitype:unknown >
     */
    static function saveUserAgent()
    {
        if (!$agents = APICache::read('life_user_agents_'.sha1($_SERVER["HTTP_HOST"])))
        	$agents = array();
        $agents[sha1($_SERVER['HTTP_USER_AGENT'])] = $_SERVER['HTTP_USER_AGENT'];
   		APICache::write('life_user_agents_'.sha1($_SERVER["HTTP_HOST"]), $agents, 3600 * 24 * 7 * 4 * 72);
   		return $_SERVER['HTTP_USER_AGENT'];
    }
    

    /**
     *
     * @return Ambigous <multitype:, multitype:unknown >
     */
    static function anyUserAgent()
    {
    	if (!$agents = APICache::read('life_user_agents_'.sha1($_SERVER["HTTP_HOST"])))
    		return $_SERVER['HTTP_USER_AGENT'];
    	$keys = array_keys($agents);
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	mt_srand(mt_rand(-microtime(true), microtime(true)));
    	return $agents[$keys[mt_rand(0, count($keys) - 1)]];
    }
}
