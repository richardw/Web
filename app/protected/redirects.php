<?php
//urls from the old site
$old = array(
    '/services.html',
    '/technology.html',
    '/barcode.html'
);

//urls from the new site
$new = array(
    'services',
    'technologies',
    'technologies/barcode'
);

if(isset($_uri)) {
    $key = array_search($_uri,$old);
    if($key===false) {}
    else {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: /".$new[$key]);
    }
}
?>
