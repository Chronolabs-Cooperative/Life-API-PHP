<?php
/**
 * Chronolabs Cooperative Entitisms Repository Services REST API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @package         entities-api
 * @since           2.2.1
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         2.2.8
 * @description		A REST API for the storage and management of entities + persons + beingness collaterated!
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/Emails-API-PHP
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 */
if (!class_exists('xcp_enumerator'))
{
	/**
	 *
	 * @author 		Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @package 	checksum
	 * @subpackage 	xcp
	 * @version 	2.0.0
	 * @copyright 	Chronolabs Cooperative Copyright (c) 2015
	 * @category 	forensics
	 * @namespace	xcp
	 * @since		2.0.0
	 * @license			GPL2
	 * @link		https://sourceforge.net/projects/chronolabs
	 * @link		https://sourceforge.net/projects/xortify
	 * @link		https://xortify.com/xcp
	 *
	 */
	class xcp_enumerator extends xcp
	{
	
		var $elekey;
		var $base;
		var $len;
		
		function __construct ($base, $len)
		{
			@$this->len = $len;
			@$this->setElements($base);
		}
	
		private function setElements($base)
		{
			@$this->base = $base;
			@$this->elekey = array();

			foreach ($base->base as $key => $data)
			{
				if (strlen((string)$data)==1)
				{
					if (strlen(bindec(ord($data)))==5)
					{
						$offset = array("ice" => (int)substr(decbin(ord($data)),5,1),
										"icd" => (int)substr(decbin(ord($data)),4,1),
										"icc" => (int)substr(decbin(ord($data)),3,1),
										"icb" => (int)substr(decbin(ord($data)),2,1),
										"ica" => (int)substr(decbin(ord($data)),1,1));							
						if (substr(decbin(ord($data)),5,1)==1)
						{
							$offset['icf'] = 0;
						} else {
							$offset['icf'] = 1;
						}
					} elseif (strlen(decbin(ord($data)))==6) 
					{
						$offset = array("icf" => (int)substr(decbin(ord($data)),6,1),
										"ice" => (int)substr(decbin(ord($data)),5,1),
										"icd" => (int)substr(decbin(ord($data)),4,1),
										"icc" => (int)substr(decbin(ord($data)),3,1),
										"icb" => (int)substr(decbin(ord($data)),2,1),
										"ica" => (int)substr(decbin(ord($data)),1,1));				
					} elseif (strlen(decbin(ord($data)))==7) 
					{
						$offset = array("ica" => (int)substr(decbin(ord($data)),6,1),
										"icb" => (int)substr(decbin(ord($data)),5,1),
										"icc" => (int)substr(decbin(ord($data)),4,1),
										"icd" => (int)substr(decbin(ord($data)),3,1),
										"ice" => (int)substr(decbin(ord($data)),2,1),
										"icf" => (int)substr(decbin(ord($data)),1,1));
					}			
				} else {
					$offset = array("ica" => (int)substr(decbin(ord(substr($key,strlen($key)-1,1))),6,1),
									"icb" => (int)substr(decbin(ord(substr($key,strlen($key)-1,1))),5,1),
									"icc" => (int)substr(decbin(ord(substr($key,strlen($key)-1,1))),4,1),
									"icd" => (int)substr(decbin(ord(substr($key,strlen($key)-1,1))),2,1),
									"ice" => (int)substr(decbin(ord(substr($key,strlen($key)-1,1))),1,1),
									"icf" => (int)substr(decbin(ord(substr($key,strlen($key)-1,1))),0,1));
				
				}
				
				if (strlen(decbin(ord($data)))==7)
				{
					if (strlen($data)==1)
					{
						$cycle = array("icf", "ice", "icd", "icc", "icb", "ica");
						foreach ($cycle as $element)
						{
							if ($done==false)
							{
								if ($offset[$element]=='0')
								{
									if ($prev_ele!='')
									{
										if ($offset[$prev_ele] == '1')
										{
											$offset[$prev_ele] = '0';
										} else {
											$offset[$prev_ele] = '1';
										}
									}
									$offset[$element]= '1';
									$done=true;
								}
							}
						}
						
					} else {
						$cycle = array("ica", "icb", "icc", "icd", "ice", "icf");
						foreach ($cycle as $element)
						{
							if ($done==false)
							{
								if ($offset[$element]=='0')
								{
									if ($prev_ele!='')
									{
										if ($offset[$prev_ele] == '1')
										{
											$offset[$prev_ele] = '0';
										} else {
											$offset[$prev_ele] = '1';
										}
									}
									$offset[$element]= '1';
									$done=true;
								}
							}
						}
					} 
				}
				$done=false;
				if (strlen($data)==1)
				{
					@$this->elekey[$key] = array("key" => $data,
												 "bin" => decbin(ord($data)),
												 "offset" => $offset,
												 "flip" => 0);
				} else {
					@$this->elekey[$key] = array("key" => $data,
												 "bin" => decbin(ord($data)),
												 "offset" => $offset,
												 "flip" => 1);
				}
			}			
		
		}
	
		private function getBytePos($char)
		{
			return floor((ord($char)+1)/4);
		}
		
		function enum_calc ($char, $enum_calc, $debug=false)
		{
			static $flip;
			
			foreach ($enum_calc as $key => $value)
			{
				${$key} = $value;
			}
			
			static $charnum;
			$charnum++;
			if ($charnum>3)
			{
				$charnum=1;
			}
			
			$nx_key.= $char;
			
			if ($this->len>15)
			{
				if (strlen($nx_key)>$this->len)
				{
					$nx_key = substr($nx_key, strlen($nx_key)/($charnum+1), strlen($nx_key) - (strlen($nx_key)/($charnum+1))).substr($nx_key, 1, strlen($nx_key)-(strlen($nx_key) - (strlen($nx_key)/($charnum+1))));
				}				
			} else {
				if (strlen($nx_key)>32)
				{
					$nx_key = substr($nx_key, strlen($nx_key)/($charnum+1), strlen($nx_key) - (strlen($nx_key)/($charnum+1))).substr($nx_key, 1, strlen($nx_key)-(strlen($nx_key) - (strlen($nx_key)/($charnum+1))));
				}
			}
			
			if ($this->elekey[$this->getBytePos($char)]['flip']==0)
			{
				$ica = $this->elekey[$this->getBytePos($char)]['offset']['ica'];
				$icb = $this->elekey[$this->getBytePos($char)]['offset']['icb'];
				$icc = $this->elekey[$this->getBytePos($char)]['offset']['icc'];
				$icd = $this->elekey[$this->getBytePos($char)]['offset']['icd'];
				$ice = $this->elekey[$this->getBytePos($char)]['offset']['ice'];
				$icf = $this->elekey[$this->getBytePos($char)]['offset']['icf'];
			} else {
				if ($charnum==1)
				{
					$icf = $this->elekey[$this->getBytePos($char)]['offset']['ica'];
					$ice = $this->elekey[$this->getBytePos($char)]['offset']['icb'];
					$icd = $this->elekey[$this->getBytePos($char)]['offset']['icc'];
					$icc = $this->elekey[$this->getBytePos($char)]['offset']['icd'];
					$icb = $this->elekey[$this->getBytePos($char)]['offset']['ice'];
					$ica = $this->elekey[$this->getBytePos($char)]['offset']['icf'];
				} elseif ($charnum==2)
				{
					$icf = $this->elekey[$this->getBytePos($char)]['offset']['ica'];
					$ice = $this->elekey[$this->getBytePos($char)]['offset']['icb'];
					$icd = $this->elekey[$this->getBytePos($char)]['offset']['icc'];
					$icc = $this->elekey[$this->getBytePos($char)]['offset']['icf'];
					$icb = $this->elekey[$this->getBytePos($char)]['offset']['ice'];
					$ica = $this->elekey[$this->getBytePos($char)]['offset']['icd'];
				} else
				{
					$icf = $this->elekey[$this->getBytePos($char)]['offset']['icc'];
					$ice = $this->elekey[$this->getBytePos($char)]['offset']['icb'];
					$icd = $this->elekey[$this->getBytePos($char)]['offset']['ica'];
					$icc = $this->elekey[$this->getBytePos($char)]['offset']['icd'];
					$icb = $this->elekey[$this->getBytePos($char)]['offset']['ice'];
					$ica = $this->elekey[$this->getBytePos($char)]['offset']['icf'];
				}
			}
			for ($icount=1; $icount<65; $icount++)
			{
				if ($this->elekey[$icount]['offset']['ica'] == $icb && $this->elekey[$icount]['offset']['icb'] == $icc && $this->elekey[$icount]['offset']['icc'] == $icd) {            
					$nuclear .=  '10';
					if ($icb = $this->elekey[$icount]['flip']) {                
						$nuclear .=  '0';
					} else {
						$nuclear .=  '1';
					}
					if ($icc = $this->elekey[$icount]['flip']) {                
						$nuclear .=  '0';
					} else {
						$nuclear .=  '1';
					}
					if ($icd = $this->elekey[$icount]['flip']) {                
						$nuclear .=  '0';
					} else {
						$nuclear .=  '1';
					}
				}
				
				if ($this->elekey[$icount]['offset']['ica'] == $icc && $this->elekey[$icount]['offset']['icb'] == $icd && $this->elekey[$icount]['offset']['icc'] == $ice) {           
					$nuclear .=  '01';
					if ($icb = $this->elekey[$icount]['flip']) {                
						$nuclear .=  '0';
					} else {
						$nuclear .=  '1';
					}
					if ($icc = $this->elekey[$icount]['flip']) {                
						$nuclear .=  '0';
					} else {
						$nuclear .=  '1';
					}
					if ($icd = $this->elekey[$icount]['flip']) {                
						$nuclear .=  '0';
					} else {
						$nuclear .=  '1';
					}           		
				}
			}

			// Change in version 1.6.4
			if (strlen($nuclear)>32768)
				$nuclear = substr($nuclear,strlen($nuclear)-32768,32768);

			$result = $result + $ica;               
			$prince = $prince + $icb;               
			$karma = $karma + $icc;                 
			$motivation = $motivation + $icd;       
			$official = $official + $ice;           
			$outsidecause = $outsidecause + $icf;   
			
			if ($ica == '0') {$yang = $yang + 1;} else {$yin = $yin + 1;}
			if ($icb == '0') {$yang = yang + 1;} else {$yin = $yin + 1;}
			if ($icc == '0') {$yang = $yang+ 1;} else {$yin = $yin + 1;}
			if ($icd == '0') {$yang = $yang + 1;} else {$yin = $yin + 1;}
			if ($ice == '0') {$yang = yang + 1;} else {$yin = $yin + 1;}
			if ($icf == '0') {$yang = $yang+ 1;} else {$yin = $yin + 1;}
			
			if ($debug==true)
			{
					
			   $data[sizeof($data)+1] = array("pos" => $this->getBytePos($char),
										 	  "elements" => $this->elekey);
								
			   $result = array("result" => $result,
						 "prince" => $prince,
						 "karma" => $karma,
						 "motivation" => $motivation,
						 "official" => $official,
						 "outsidecause" => $outsidecause,
						 "nuclear" => $nuclear,
						 "yin" => $yin,
						 "yang" => $yang,
						 "nx_key" => $nx_key,
						 "data"=> $data);
			} else {
			   $result = array("result" => $result,
						 "prince" => $prince,
						 "karma" => $karma,
						 "motivation" => $motivation,
						 "official" => $official,
						 "outsidecause" => $outsidecause,
						 "nuclear" => $nuclear,
						 "yin" => $yin,
						 "yang" => $yang,
						 "nx_key" => $nx_key);

			}
			
			return $result;
		}
	}
}
