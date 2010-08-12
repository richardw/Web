<?php
function _routes() {
    global $_uri_segments,$_path,$_page,$_params;
    
    if($_uri_segments[0]=='products') {

        $t = count($_uri_segments);
        if($t==1) {
            $_path = 'products/';
            $_page = 'index';
            return true;
        }
        else {
            $_path = 'products/';
            $_page = 'index';
            $_params = $_uri_segments;
            //unset($_params[0]);
            //$_params = array_values($_params);
            //print_r($_params);
            return true;
        }
    }
    return false;
}
?>