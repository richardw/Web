<?php
class Bank {
	
	protected static $_bank = array();
	
	public static function retrieve($key,$index=null,$index2=null) {
		if (isset(self::$_bank[$key])) {
			if($index!=null) {
				if($index2!=null) {
					if (isset(self::$_bank[$key][$index][$index2]))
						return self::$_bank[$key][$index][$index2];
					else
						return null;
				}
				else {
					if (isset(self::$_bank[$key][$index]))
						return self::$_bank[$key][$index];
					else
						return null;				
				}
			}
			else
				return self::$_bank[$key];
		}		
		return null;
	}
	
	public static function add($key,$val,$index=null,$index2=null) {
		if($index!=null) {
			if($index2!=null) {
				self::$_bank[$key][$index][$index2] = $val;
			}
			else {
				self::$_bank[$key][$index] = $val;
			}
		}
		else {
			self::$_bank[$key] = $val;
		}		
		return $val;
	}
	
	public static function remove($key,$index=null,$index2=null) {
		if (isset(self::$_bank[$key])) {
			if($index!=null) {
				if($index2!=null) {
					$val = self::$_bank[$key][$index][$index2];
					unset(self::$_bank[$key][$index][$index2]);
				}
				else {
					$val = self::$_bank[$key][$index];
					unset(self::$_bank[$key][$index]);
				}
			}
			else {
				$val = self::$_bank[$key];
				unset(self::$_bank[$key]);
			}
			return $val;
		}		
		return null;
	}
}
?>