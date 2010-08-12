<?php
class Output {
	private $output = '';
	var $_ob_level;
	
	function __construct() {
		$this->_output_init();
	}
	
	private function _output_init() {
		$this->_ob_level = ob_get_level();
	}
	
	public function append($out) {
		$this->output .= $out;
	}
	
	public function deliver() {
		echo $this->output;
	}
	
	public function format($format) {
		header('Content-type: '.$format);
	}
}
?>