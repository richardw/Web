<?php
$config = array();
require_once('app/protected/config.php');

//THIS IS TEMPORARY!!
//$_SERVER['QUERY_STRING'] = $_SERVER['REQUEST_URI'];

function _exitApp() {
    global $config;
    if($config['session']['enable']==true) {
        session_write_close();
    }
    if(isset($config['db'])) {
        global $_dbconn;
        if($_dbconn!=null)
            mysqli_close($_dbconn);
    }
}

register_shutdown_function('_exitApp');

function _get_uri() {
    // If the URL has a question mark then it's simplest to just
    // build the URI string from the zero index of the $_GET array.
    // This avoids having to deal with $_SERVER variables, which
    // can be unreliable in some environments
    if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '') {
        return key($_GET);
    }

    // Is there a PATH_INFO variable?
    // Note: some servers seem to have trouble with getenv() so we'll test it two ways
    $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
    if (trim($path, '/') != '' && $path != "/index.php"){
        return $path;
    }

    // No PATH_INFO?... What about QUERY_STRING?
    $path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
    if (trim($path, '/') != '') {
        return $path;
    }

    // No QUERY_STRING?... Maybe the ORIG_PATH_INFO variable exists?
    $path = str_replace($_SERVER['SCRIPT_NAME'], '', (isset($_SERVER['ORIG_PATH_INFO'])) ? $_SERVER['ORIG_PATH_INFO'] : @getenv('ORIG_PATH_INFO'));
    if (trim($path, '/') != '' && $path != "/".SELF){
        // remove path and script information so we have good URI data
        return $path;
    }

    // We've exhausted all our options...
    return '';
}


$_path = '';
$_page = '';
$_params = array();
$_uri = _get_uri();
if(substr($_uri,-1)=='/')
    $_uri = substr($_uri,0,-1);
$_uri_segments = explode('/',$_uri);

array_shift($_uri_segments);

//SORT OUT THE ROUTES
$_routes = (!isset($route) OR !is_array($route)) ? array() : $route;
unset($route);

//$_path_default = $_bank_inst->retrieve('route','default_controller');
$_path_default = 'home';
$_path_default = (($_path_default==null) OR ($_path_default == '')) ? FALSE : strtolower($_path_default);

// Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
if ($_uri == '') {
    if ($_path_default === FALSE) {
        echo "Unable to determine what should be displayed. A default controller has not been specified in the config file.";
        exit();
    }
    if (strpos($_path_default, '/') !== FALSE) {
        $x = explode('/', $_path_default);
        $_path = $_path_path = end($x).'/';
        $_page = 'index';
    }
    else {
        $_path = $_path_default.'/';
        $_page = 'index';
    }
}
else {
    require_once('app/protected/routes.php');
    if(_routes()==false) {
        if(is_dir('app/protected/pages/'.$_uri_segments[0])) { //first segment is a directory
            if(isset($_uri_segments[1])) {
                $_path = $_uri_segments[0].'/'; //set controller
                $_page = $_uri_segments[1];
            }
            else {
                $_path = $_uri_segments[0].'/'; //set controller
                $_page = 'index';
            }
        }
        else {
            require_once('app/protected/redirects.php');
            require_once('app/protected/pages/404.php');
            exit();
        }
    }
}

unset($_path_default);
unset($_uri);
if(count($_params)==0) {
    $_params = array_slice($_uri_segments, 2);
}

$_pageinfo = array();
if(file_exists('app/protected/pages/'.$_path.$_page.'.inf.php')) {
    require_once('app/protected/pages/'.$_path.$_page.'.inf.php');
}

$_cachedpagerenew = false;

function _loadCachedPage() {
    global $_path,$_page,$_pageinfo,$_cachedpagerenew;
    if(!isset($_pageinfo['cacheTime']))
        $_pageinfo['cacheTime'] = 30;
    
    if(file_exists('app/protected/cache/pages/'.$_path.$_page.'.tmp')) { //cached page exists
        $fh = @fopen('app/protected/cache/pages/'.$_path.$_page.'.tmp', 'r') or die("can't read cache file.. is the cache directory readable?");
        if ($fh) {
            $contents = fread($fh, filesize('app/protected/cache/pages/'.$_path.$_page.'.tmp'));
        }
        fclose($fh);
        if((int)substr($contents,0,10)<time()) { //cached page has expired
            $_cachedpagerenew = true;
            return;
        }
        else {
            echo substr($contents,11);
            echo "\n<!-- Cached page delivered in "; echo round(microtime() - ZEST_START_TIME, 4); echo " seconds using "; echo round(memory_get_peak_usage() / 1024 / 1024, 2); echo " megabytes of memory. Generated on ".date ("Y-m-d H:i:s", filemtime('app/protected/cache/pages/'.$_path.$_page.'.tmp'))." -->";
            exit();
        }
    }
    else {
        if(!is_dir('app/protected/cache/pages/'.$_path))
            mkdir('app/protected/cache/pages/'.$_path);
        $_cachedpagerenew = true;
        return;
    }
}

//setup the session
if($config['session']['enable']==true) {
    if(isset($config['db'])) {
        require_once('db.php');
    }
	
    if(!isset($config['session']['expire'])) {
        $config['session']['expire'] = 60;
    }
	
    //should the session be in the database or the filesystem
    if($config['session']['store']=='filesystem')
        require_once('session_fs.php');
    else
        require_once('session_db.php');

    //is the user logged in
    if(isset($_SESSION['user']['id'])) {
        if(isset($_pageinfo['cacheUser'])) { //should the page be cached?
            if($_pageinfo['cacheUser']==true) {
                _loadCachedPage();
            }
        }
    }
    else {
        if(isset($_pageinfo['cacheVisitor'])) { //should the page be cached?
            if($_pageinfo['cacheVisitor']==true) {
                _loadCachedPage();
            }
        }
    }
    //check the permissions for this page
}
else { //no session, treat user as a visitor
    if(isset($_pageinfo['cacheVisitor'])) { //should the page be cached?
        if($_pageinfo['cacheVisitor']==true) {
            _loadCachedPage();
        }
    }
}

//if no page caching, continue..
$_viewflag = false;


function view($name,$vars=NULL,$return=false,$cache=0) {
    global $_viewflag;
    $_cached_vars = array();
    $_ob_level = 1; //TMP
    if(!file_exists('app/protected/views/'.$name.'.php')) {
        echo 'View ('.$name.') not found';
        return false;
    }

    if (is_array($vars)) {
        $_cached_vars = array_merge($_cached_vars, $vars);
    }
    extract($_cached_vars);

    if ($return == true) {
        ob_start();
    }

    if($cache!=0) {
        if(file_exists('app/protected/cache/views/'.$name.'.tmp')) { //there is a cache file for this view
            $fh = @fopen('app/protected/cache/views/'.$name.'.tmp', 'r') or die("can't read cache file.. is the cache directory readable?");
            if ($fh) {
                $contents = fread($fh, filesize('app/protected/cache/views/'.$name.'.tmp'));
            }
            fclose($fh);
            if((int)substr($contents,0,10)<time()) {
                if ($return == false) {
                    ob_start();
                }
                require('app/protected/views/'.$name.'.php');
                $sec = time() + ($cache * 60);
                $cache_buffer = $sec.'|'.ob_get_contents();
                $fh = @fopen('app/protected/cache/views/'.$name.'.tmp', 'w') or die("can't write to cache file.. is the cache directory writeable?");
                fwrite($fh, $cache_buffer);
                fclose($fh);
                if ($return == false) {
                    $buf = ob_get_contents();
                    ob_end_clean();
                    echo $buf;
                }
            }
            else {
                echo substr($contents,11);
            }
        }
        else {
            if ($return == false) {
                ob_start();
            }
            require('app/protected/views/'.$name.'.php');
            $sec = time() + ($cache * 60);
            $cache_buffer = $sec.'|'.ob_get_contents();
            $fh = @fopen('app/protected/cache/views/'.$name.'.tmp', 'w') or die("can't write to cache file.. is the cache directory writeable?");
            fwrite($fh, $cache_buffer);
            fclose($fh);
            if ($return == false) {
                $buf = ob_get_contents();
                ob_end_clean();
                echo $buf;
            }
        }
    }
    else
        require('app/protected/views/'.$name.'.php');

    // Return the file data if requested
    if ($return == true) {
        $out = ob_get_contents();
        ob_end_clean();
        return $out;
    }
}
if($_cachedpagerenew==true) {
    ob_start();
}


function getParent($id,$ids) {
    $query = "SELECT `parentid` FROM `pages` WHERE `id`='$id'";
    $r = db_query($query);
    $c = mysqli_fetch_assoc($r);
    array_unshift($ids,$c['parentid']);
    if($c['parentid']!=0)
        $ids = getParent($c['parentid'],$ids);
    return $ids;
}

if(file_exists('app/protected/pages/'.$_path.$_page.'.php')) {
    require_once('app/protected/pages/'.$_path.$_page.'.php');
}
else {
    require_once('app/protected/redirects.php');
    require_once('app/protected/pages/404.php');
    exit();
}

if($_cachedpagerenew==true) {
    $sec = time() + ($_pageinfo['cacheTime'] * 60);
    $buf = ob_get_contents();
    $cache_buffer = $sec.'|'.$buf;
    ob_end_clean();
    echo $buf;
    unset($buf);
    $fh = @fopen('app/protected/cache/pages/'.$_path.$_page.'.tmp', 'w') or die("can't write to cache file.. is the cache directory writeable?");
    fwrite($fh, $cache_buffer);
    fclose($fh);
}

echo "\n<!-- Dynamic page generated in "; echo round(microtime() - ZEST_START_TIME, 4); echo " seconds, generated on ".date('Y-m-d H:i:s')." using "; echo round(memory_get_peak_usage() / 1024 / 1024, 2); echo " megabytes of memory. -->";
?>