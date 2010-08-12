<?php
if(!isset($config['db'])) {
  die('Sessions are configured to be stored in a database which is not configured!');
}
if(!isset($config['session']['expire'])) {
  $config['session']['expire'] = 60;
}

	function _session_open( $save_path, $session_name ) {
      global $sess_save_path;
      $sess_save_path = $save_path;
      return true;

   }

   function _session_close() {
      return true;
   }

   function _session_read( $id ) {
      global $_dbconn;
      // Set empty result
      $data = '';
      // Fetch session data from the selected database

      $time = time();

      $newid = mysqli_real_escape_string($_dbconn,$id);
      $sql = "SELECT `data` FROM `sessions` WHERE `id` = '$newid' AND `expires` > $time";

      $r = db_query($sql);                           
      $a = mysqli_num_rows($r);

      if($a > 0) {
        $row = mysqli_fetch_assoc($r);
        $data = $row['data'];
      }
      return $data;
   }

  
   function _session_write( $id, $data ) {
    global $_dbconn,$config;
      // Build query                
      $time = time() + ($config['session']['expire'] * 60);

      $newid = mysqli_real_escape_string($_dbconn,$id);
      $newdata = mysqli_real_escape_string($_dbconn,$data);

      $sql = "REPLACE `sessions` (`id`,`data`,`expires`) VALUES('$newid','$newdata', $time)";
      $rs = db_query($sql);
      return TRUE;
   }

   function _session_destroy( $id ) {
      global $_dbconn;
      // Build query
      $newid = mysqli_real_escape_string($_dbconn,$id);
      $sql = "DELETE FROM `sessions` WHERE `id` = '$newid'";
      db_query($sql);
      return true;
   }

   function _session_gc() {
      global $config;
      // Garbage Collection
      $time = time() - ($config['session']['expire'] * 60);
      // Build DELETE query.  Delete all records who have passed the expiration time
      $sql = "DELETE FROM `sessions` WHERE `expires` < '$time'";
      db_query($sql);

      return true;
   }

session_name($config['session']['name']);
session_set_save_handler("_session_open", "_session_close", "_session_read", "_session_write", "_session_destroy", "_session_gc");
session_start();
?>