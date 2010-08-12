<?php
error_reporting(E_ALL); 
set_time_limit(20);
$config = array(
  'clean' => 'yes',
  'indent' => false,
  'output-xhtml' => true,
  'wrap'=>999,
);

$xml = simplexml_load_file('map.xml');
foreach($xml as $x) {
   $url = str_replace("\n",'',$x[0]);
   $file = basename($url);
   if(!file_exists('cache/'.$file)) {
       $tidy = file_get_contents($url);

       $tidy = tidy_repair_string($tidy, $config, 'utf8');

       $tidy = str_replace('&nbsp','',$tidy);
       $tidy = preg_replace('~^[ t]+~m', '', $tidy);
       $tidy = preg_replace('~[ t]+$~m', '', $tidy);

       echo $file;
       $fh = fopen('cache/'.$file, 'w') or die("can't open file");
       fwrite($fh, $tidy);
       fclose($fh);
       sleep(3);
    }
    //exit();
}
echo 'FINISHED!';
?>
