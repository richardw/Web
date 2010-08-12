<?php
class Clean {
	
	public function __construct() {
		$this->_clean_init();
	}
	
	private function _clean_init() {
		$classes = array(
			'bank' => 'Bank'
		);
		
		foreach ($classes as $var => $class) {
			$this->$var =& loadClass($class);
		}
	}
	
	public function input($value) {
		$value = htmlspecialchars(strip_tags(trim($value),$tags));
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		if (!is_numeric($value)) {
			if(!isset($this->db)) {
				if($_bank_inst->retrieve('db')) {
					$this->db =& loadClass('db');
					$value = mysqli_real_escape_string($this->db->conn,$value);
				}			
			}
			else {
				$value = mysqli_real_escape_string($this->db->conn,$value);
			}
		}
		return $value;
	}
	
	public function output($string) {
		$string = htmlentities(stripslashes($string));
		return $string;
	}
}
?>