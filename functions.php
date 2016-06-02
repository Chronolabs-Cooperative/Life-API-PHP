<?php
/**
 * Chronolabs IP Lookup's REST API File
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         lookups
 * @since           1.1.2
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @version         $Id: index.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Internet Protocol Address Information API Service REST
 */

define('_PATH_ROOT', dirname(__FILE__));
define('_PATH_CACHE', dirname(_PATH_ROOT) . DIRECTORY_SEPARATOR . 'cache');

require_once _PATH_ROOT  . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . 'lifefile.php';
require_once _PATH_ROOT  . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'lifecache.php';
require_once _PATH_ROOT  . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'simplehtmldom' . DIRECTORY_SEPARATOR . 'simple_html_dom.php';
require_once _PATH_ROOT  . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'radio.php';


/**
 * 
 * @param string $source
 * @param string $mode
 * @param string $basis
 * @return Ambigous <Ambigous, multitype:, unknown, multitype:Ambigous <Ambigous, mixed, NULL, boolean, multitype:unknown > , mixed, NULL, boolean>|multitype:
 */
function getAPIData($source = 'radio', $mode = 'random', $basis = '')
{
	switch ($source)
	{
		case 'radio':
		case 'audio':
		case 'sound':
		default:
			return getRadioAPIData($mode, $basis);
			break;
	}
	return array();
}

/**
 * 
 * @param string $mode
 * @param string $basis
 * @return Ambigous <Ambigous, multitype:, unknown>|Ambigous <Ambigous, multitype:Ambigous <Ambigous, multitype:unknown , mixed, NULL, boolean, unknown> >|Ambigous <Ambigous, mixed, NULL, boolean, unknown>|multitype:
 */
function getRadioAPIData($mode = 'random', $basis = '')
{
	switch ($mode)
	{
		case 'genres':
			switch ($basis)
			{
				case 'primary':
					return lifeRadio::getGenres(true, 'format', '');
					break;
				case 'all':
					return lifeRadio::getGenres(false, 'format', '');
					break;
				default:
					return lifeRadio::getGenres(false, 'format', $basis);
					break;
			}
			break;
		case 'station':
			return lifeRadio::getStreamsFromStationID($basis);
			break;
		default:
			return lifeRadio::getStationsFromAPI($mode, $basis);
			break;
	}
	return array();
}


/**
 *
 * @param string $mode
 * @param string $basis
 * @return Ambigous <Ambigous, multitype:, unknown>|Ambigous <Ambigous, multitype:Ambigous <Ambigous, multitype:unknown , mixed, NULL, boolean, unknown> >|Ambigous <Ambigous, mixed, NULL, boolean, unknown>|multitype:
 */
function getRandomGenre($primary = true)
{
	$list = array();
	foreach(lifeRadio::getGenres($primary, 'format', '') as $primary => $genres)
	{
		if ($primary==false) {
			$list[] = $primary;
			foreach($genres as $genre) {
				$list[] = $genre;
			}
		} else {
			$list[] = $genres;
		}
	}
	return $list[mt_rand(0, count($list))];
}

/**
 * xml_encode()
 * Encodes XML with DOMDocument Objectivity
 *
 * @param mixed $mixed					Mixed Data
 * @param object $domElement			DOM Element
 * @param object $DOMDocument			DOM Document Object
 * @return array
 */
 

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIPAddy()
	 *
	* 	provides an associative array of whitelisted IP Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistGetIPAddy() {
		return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistGetNetBIOSIP")) {

	/* function whitelistGetNetBIOSIP()
	 *
	* 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistGetNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		}
		return $ret;
	}
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIP()
	 *
	* 	get the True IPv4/IPv6 address of the client using the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @param		$asString	boolean		Whether to return an address or network long integer
	*
	* @return 		mixed
	*/
	function whitelistGetIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
		} else
		if (!empty($_SERVER['HTTP_VIA'])) {
			$proxy_ip = $_SERVER['HTTP_VIA'];
		} else
		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
		} else
		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	}
}
 

if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 *
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {

		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 *
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {

			$domElement = is_null($domElement) ? $this : $domElement;

			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {

					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}

					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}

					$this->fromMixed($mixedElement, $node);

				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}

		}
			
	}
}

?>
