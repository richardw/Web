<?php
class Controller extends Zest_Base {

	function Controller()
	{	
		parent::Zest_Base();
		$this->_controller_init();
	}
	
	private function _controller_init() {
		
		$classes = array(
			'bank' => 'Bank',
			'output' => 'Output',
			'view' => 'View'
		);
		
		foreach ($classes as $var => $class) {
			$this->$var =& loadClass($class);
		}
		
		$_toload = $this->bank->retrieve('config','functionality');
		if($_toload!=null && is_array($_toload)) {
			foreach($_toload as $_toloado) {
				$this->$_toloado =& loadClass($_toloado);
			}
			unset($_toloado);
		}
		unset($_toload);
		
	}	
}
?>