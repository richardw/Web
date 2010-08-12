<?php
$configo = array(
  'clean' => 'yes',
  'indent' => false,
  'output-xhtml' => true,
  'wrap'=>999,
);

function get_data($url)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

$r = db_query("SELECT `id`,`content` FROM `pages`");
while($c = mysqli_fetch_assoc($r)) {
   $dom = new DOMDocument;
   $dom->loadHTML($c['content']);
   $xpath = new DOMXPath($dom);
   $imgo = $xpath->query("//img");
   $contento = $c['content'];
   foreach($imgo as $t2) {
       $imgo2 = get_data($t2->getAttribute('src'));
       //echo $imgo2;
        $filename = basename($t2->getAttribute('src'));
        $ourFileName = "app/public/img/upload/".$filename;
        $fh = fopen(urldecode($ourFileName), 'w') or die("can't open file");
        fwrite($fh, $imgo2);
        fclose($fh);
        $contento = str_replace($t2->getAttribute('src'),'/'.$ourFileName,$contento);
        
   }
   $query = "UPDATE `pages` SET `content`='".mysqli_real_escape_string($_dbconn,$contento)."' WHERE `id`='{$c['id']}'";
   db_query($query);
   sleep(2);
   //echo $contento;
}
?>