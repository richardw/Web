<?php
class View {
	
	var $_cached_vars = array();
	
	function __construct() {
		$this->_view_init();
	}
	
	private function _view_init() {
		$classes = array(
			'bank' => 'Bank',
			'output'	=> 'Output',
		);
		
		foreach ($classes as $var => $class) {
			$this->$var =& loadClass($class);
		}
	}
	
	public function show($name,$vars=NULL,$return=false,$cache=0) {
		if(!file_exists('app/views/'.$name.'.php')) {
			echo 'View not found';
			return false;
		}
		
		if (is_array($vars)) {
			$this->_cached_vars = array_merge($this->_cached_vars, $vars);
		}
		extract($this->_cached_vars);
		ob_start();

		if($this->cache($name)) { //there is a cache file for this view
			$fh = @fopen('app/cache/views/'.$name.'.tmp', 'r') or die("can't read cache file.. is the cache directory readable?");
			if ($fh) {
        		$contents = fread($fh, filesize('app/cache/views/'.$name.'.tmp'));
        		echo substr($contents,11);
        		fclose($fh);
    		}
		}
		else
			require('app/views/'.$name.'.php');
		
		
		if($cache!=0) {
			$sec = time() + ($cache * 60);
			$cache_buffer = $sec.'|'.ob_get_contents();
			$fh = @fopen('app/cache/views/'.$name.'.tmp', 'w') or die("can't write to cache file.. is the cache directory writeable?");
			fwrite($fh, $cache_buffer);
			fclose($fh);
		}
		
		// Return the file data if requested
		if ($return === TRUE) {		
			@ob_end_clean();
			return ob_get_contents();
		}

		if (ob_get_level() > $this->output->_ob_level + 1) {
			ob_end_flush();
		}
		else {
			$this->output->append(ob_get_contents());
			@ob_end_clean();
		}
		unset($buffer);
	}
	
	public function isCached($name) {
		if(file_exists('app/cache/views/'.$name.'.tmp'))
			return true;
		return false;
	}
	
	public function cache($name,$mins=-1) {
		if(!$this->isCached($name)) {
			return false;
		}
		if($mins==-1) { //return cache time left
			$fh = @fopen('app/cache/views/'.$name.'.tmp', 'r') or die("can't read cache file.. is the cache directory readable?");

			if ($fh) {
        		$buffer = fgets($fh, 11);
    			fclose($fh);
    			$left = time() - $buffer;
    			if($left<0) 
    				return $left/-1;
    			else {
    				$this->cache($name,0);
    				return false;
    			}
    		}
    		return 0;
		}
		else { //set cache time
			if($mins==0) { //delete cache
				@unlink('app/cache/views/'.$name.'.tmp') or die('unable to delete cache file.. is the cache directory writeable?');
			}
			else { //update cache time
				$cachet = time() + ($mins * 60);
				$ini_handle = fopen('app/cache/views/'.$name.'.tmp', "r"); 
   				$ini_contents = fread($ini_handle, filesize('app/cache/views/'.$name.'.tmp')); 
    			fclose($ini_handle); 
    			$handle = fopen($filename, "w+"); 
        		$writestring = $cachet.substr($ini_contents,10); 
        		if (fwrite($handle, $writestring) === false) { 
            		die("can't write to cache file.. is the cache directory writeable?");           
        		} 
   				fclose($handle); 
			}
		}
	}
	
	public function css($path,$types='') {
		$csso = $this->bank->retrieve('config','status');
		if($csso=='development') {
			//if raw css file is newer than cached css
			if (file_exists('app/cache/css/'.$path.'.css')) {
    			$cachet = filemtime('app/cache/css/'.$path.'.css');
    			$csst = filemtime('app/css/'.$path.'.css');
    			if($cachet<=$csst) {
    				$this->generateCSS($path);
    			}
			}
			else
				$this->generateCSS($path);
		}
		echo '<link rel="stylesheet" type="text/css" href="app/cache/css/'.$path.'.css"';
		if($types!='')
			echo ' media="'.$types.'"';
		echo '/>';
		unset($csso);
	}
	
	private function generateCSS($filename) {
		//load the class
		if(!isset($this->css))
			$this->css =& loadClass('css');
		
		//parse the css
		$this->css->chooseFile('app/css/'.$filename.'.css');

		//update the cache
		file_put_contents('app/cache/css/'.$filename.'.css',$this->css->parse()) or die('Unable to write to the cache directory.. is it writeable?');
	}
}
?>