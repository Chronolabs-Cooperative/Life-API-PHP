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
if (!class_exists('xcp_base'))
{
	/**
	 * 
	 * @author 		Simon Antony Roberts (Aus Passport: M8747409) <wishcraft@users.sourceforge.net>
	 * @package 	checksum
	 * @subpackage 	xcp
	 * @version 	2.0.3
	 * @copyright 	Chronolabs Cooperative Copyright (c) 2015
	 * @category 	forensics
	 * @namespace	xcp
	 * @since		2.0.3
	 * @license			GPL2
	 * @link		https://sourceforge.net/projects/chronolabs
	 * @link		https://sourceforge.net/projects/xortify
	 * @link		https://xortify.com/xcp
	 *
	 */
	class xcp_base extends xcp
	{
	
		var $bin;
		var $range;
		var $pool;
		var $base;
		var $seed;
		var $mode;
		var $roll;
		var $num_evr;
		
		function __construct ($seed = 127)
		{
			$this->bin = array(	"a-z" 	=> 	array(	'begin'	=>	ord("a"),
													'ended'	=>	ord("z")),
								"A-Z" 	=> 	array(	'begin'	=>	ord("A"),
													'ended'	=>	ord("Z")),
								"0-9" 	=> 	array(	'begin'	=>	ord("0"),
													'ended'	=>	ord("9")));				
			$this->range = $this->_set_range();
						
			if ($seed<0)
			{
				$this->seed = 0;
			} elseif ($seed>255) {
				$this->seed = 255;
			} else {
				$this->seed = $seed;
			}
			$this->base = $this->_set_base();
			return $this->get_base();
		}
		

		private function _set_range()
		{
			static $ret = array();
			if (empty($ret))
			{
				foreach($this->bin as $scope => $range)
				{
					for($chr = $range['begin']; $chr<=$range['ended']; $chr++)
						$ret[chr($chr)] = $chr;
				}
			}
			return $ret;
		}
		
		private function _set_base()
		{
			
			if ($this->seed < 65)
			{
				$case=true;
			} else {
				$case=false;
			}
			
			$this->roll = ($this->seed / (3+(1/6)));
			$this->num_evr = floor((34.32 / ($this->roll/$this->seed))/($this->seed*($this->roll/17.8)));
			
			if ($this->roll<16)
			{
				$this->mode = '2';
			} elseif ($this->roll >15 && $this->roll<32) {
				$this->mode = '4';
			} elseif ($this->roll >32 && $this->roll<48) {
				$this->mode = '6';
			} elseif ($this->roll >48 ) {
				$this->mode = '8';
			}
			
			if ($this->num_evr==0)
			{
				$this->num_evr = floor((($this->seed/$this->mode)/($this->mode*3.015)));
			} elseif ($this->num_evr>8) {
				$this->num_evr = $this->num_evr - floor($this->mode*1.35);
			}
				
			
			$pointer = 0;
			$this->base = array();
			for ($qcc=1; $qcc<= $this->mode * 3; $qcc++)
			{
				switch ($this->mode){
				case '2':
					$ii = 0;
					$num = 0;
					$letter = "a";
					for ($qcb=1;$qcb<32;$qcb++)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
	
					for ($qcb=64;$qcb>31;$qcb--)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
					break;
				case '4':
					$ii = 0;
					$num = 0;
					$letter = "a";
					for ($qcb=32;$qcb>0;$qcb--)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
	
					for ($qcb=32;$qcb<65;$qcb++)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
					break;
				case '6':
					$ii = 0;
					$num = 0;
					$letter = "a";
					for ($qcb=1;$qcb<17;$qcb++)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
	
					for ($qcb=64;$qcb>47;$qcb--)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
	
					for ($qcb=32;$qcb>16;$qcb--)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
					
					
					for ($qcb=32;$qcb<48;$qcb++)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
					break;			
				case '8':
					$ii = 0;
					$num = 0;
					$letter = "a";
	
					for ($qcb=17;$qcb>0;$qcb--)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
	
					for ($qcb=17;$qcb<49;$qcb++)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
	
					for ($qcb=64;$qcb>48;$qcb--)
					{
						$ii++;
						$done = false;
						if ($sofar == $this->num_evr)
						{
							if ($num < 9)
							{
								$this->pool[$pointer] .= $num;
								$num++;
								$sofar = 0;
								$done = true;
							}
						} else {
							$sofar++;
						}
						
						if ($done == false)
						{
							if (floor($qcb / ($this->roll/$this->num_evr))>$this->mode)
							{
								switch ($case)
								{
								case true:
									$this->pool[$pointer] .= strtolower($letter);
									$case = false;
									break;
								case false:
									$this->pool[$pointer] .= strtoupper($letter);
									$case = true;
									break;
								}
							} else {
								$this->pool[$pointer] .= strtolower($letter);
							}
							$letter++;
							if (strlen($letter++)>1) { $letter="a"; }
						}
						if (strlen($this->pool[$pointer]) / $this->roll >= $this->mode)
							$pointer++;
					}
					break;			
				}		
			}
			$hasher = implode($this->pool).implode(array_reverse($this->pool));
			if (count($this->base)<=64)
				foreach(array_reverse(array_keys($this->pool)) as $pointer)
					for($y=strlen($this->pool[$pointer])-1; $y>=0; $y--)
						if (count($this->base)<=64)
							$this->base[count($this->base)] = substr($this->pool[$pointer], $y, 1);
			// Adaption to 2.0.1 - Captalised Meters
			$sequen = $this->mode * 6;
			$step = 3;
			foreach($this->base as $idx => $char)
			{
				
				if ($sequen>17)
					$sequen = $this->mode * 5;
				if ($step <= $sequence)
				{
					$step = 2;
					$sequen++;
					$this->base[$idx] = strtoupper($char);
				}
				if ($last == $this->base[$idx])
				{
					$last = $this->base[$idx] = 'aa';
				} else
					$last = $this->base[$idx];
				$step = $step + 3;
			}
			return $this->base;
		}
		
		function get_base()
		{
			return $this->base;
		
		}		
		
		function debug_base()
		{
			$base = array();
			foreach ($this->base as $key => $data)
			{
				$base[$key] = array("char" => $data,
									"ord" => ord($data),
									"bin"  => decbin(ord($data)));
			}
			
			return array("mode" => $this->mode, "roll" => $this->roll,
						 "seed" => $this->seed, "mode" => $this->mode, 
						 "num_evr" => $this->num_evr, "base" => $this->base,
						 "debug" => $base);
		}
	}
}

?>
