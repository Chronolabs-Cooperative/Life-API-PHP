<?php
/**
 * Chronolabs Digital Signature Generation & API Services
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Software Licence (https://web.labs.coop/public/legal/general-software-license/10,3.html)
 * @package         life
 * @since           1.0.1
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		cache
 * @description		Digital Signature Generation & API Services
 * @link			https://life.labs.coop Digital Signature Generation & API Services
 */

defined('_PATH_ROOT') or die('Restricted access');

/**
 * APC storage engine for cache.
 *
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *                                  1785 E. Sahara Avenue, Suite 490-204
 *                                  Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package cake
 * @subpackage cake.cake.libs.cache
 * @since CakePHP(tm) v 1.2.0.4933
 * @version $Revision: 8066 $
 * @modifiedby $LastChangedBy: beckmi $
 * @lastmodified $Date: 2011-11-06 01:09:33 -0400 (Sun, 06 Nov 2011) $
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * APC storage engine for cache
 *
 * @package cake
 * @subpackage cake.cake.libs.cache
 */
class lifeCacheApc extends lifeCacheEngine
{
    /**
     * Initialize the Cache Engine
     *
     * Called automatically by the cache frontend
     * To reinitialize the settings call Cache::engine('EngineName', [optional] settings = array());
     *
     * @param array $setting array of setting for the engine
     * @return boolean True if the engine has been successfully initialized, false if not
     * @see CacheEngine::__defaults
     * @access public
     */
    function init($settings = array())
    {
        parent::init($settings);
        return function_exists('apc_cache_info');
    }

    /**
     * Write data for key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $value Data to be cached
     * @param integer $duration How long to cache the data, in seconds
     * @return boolean True if the data was succesfully cached, false on failure
     * @access public
     */
    function write($key, &$value, $duration)
    {
        return apc_store($key, $value, $duration);
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed The cached data, or false if the data doesn't exist, has expired, or if there was an error fetching it
     * @access public
     */
    function read($key)
    {
        return apc_fetch($key);
    }

    /**
     * Delete a key from the cache
     *
     * @param string $key Identifier for the data
     * @return boolean True if the value was succesfully deleted, false if it didn't exist or couldn't be removed
     * @access public
     */
    function delete($key)
    {
        return apc_delete($key);
    }

    /**
     * Delete all keys from the cache
     *
     * @return boolean True if the cache was succesfully cleared, false otherwise
     * @access public
     */
    function clear()
    {
        return apc_clear_cache('user');
    }
}

?>