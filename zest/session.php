<?php
if($_bank_inst->retrieve('config','session','expire')==null) {
	$_bank_inst->add('config',60,'session','expire');
}

	function _session_open( $save_path, $session_name ) {
      global $sess_save_path;
      $sess_save_path = $save_path;

      // Don't need to do anything. Just return TRUE.
      return true;

   }

   function _session_close() {
      return true;
   }

   function _session_read( $id ) {
   	global $_db_inst;
      // Set empty result
      $data = '';
      // Fetch session data from the selected database

      $time = time();

      $newid = mysqli_real_escape_string($_db_inst->conn(),$id);
      $sql = "SELECT `data` FROM `sessions` WHERE `id` = '$newid' AND `expires` > $time";

      $r = $_db_inst->query($sql);                           
      $a = mysqli_num_rows($r);

      if($a > 0) {
        $row = mysqli_fetch_assoc($r);
        $data = $row['data'];
      }
      return $data;
   }

   function _session_write( $id, $data ) {
   	global $_db_inst,$_bank_inst;
      // Build query                
      $time = time() + $_bank_inst->retrieve('config','session','expire');

      $newid = mysqli_real_escape_string($_db_inst->conn(),$id);
      $newdata = mysqli_real_escape_string($_db_inst->conn(),$data);

      $sql = "REPLACE `sessions` (`id`,`data`,`expires`) VALUES('$newid','$newdata', $time)";
      $rs = $_db_inst->query($sql);
      return TRUE;

   }

   function _session_destroy( $id ) {
   	global $_db_inst;
      // Build query
      $newid = mysqli_real_escape_string($_db_inst->conn(),$id);
      $sql = "DELETE FROM `sessions` WHERE `id` = '$newid'";
      $_db_inst->query($sql);
      return TRUE;
   }

   function _session_gc() {
   	global $_db_inst;
      // Garbage Collection
      // Build DELETE query.  Delete all records who have passed the expiration time
      $sql = 'DELETE FROM `sessions` WHERE `expires` < UNIX_TIMESTAMP();';
      $_db_inst->query($sql);

      // Always return TRUE
      return true;

   }
?>