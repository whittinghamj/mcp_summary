<?php

function mysql_current_db() {
    $r = mysql_query("SELECT DATABASE()") or die(mysql_error());
    return mysql_result($r,0);
}
class SessionManager {

   var $life_time;
   var $app_db;

   function SessionManager() {

      // Read the maxlifetime setting from PHP
      // $this->life_time = get_cfg_var("session.gc_maxlifetime");
	  $this->life_time = 60 * 60 * 24 * 7;
      // Register this object as the session handler
      session_set_save_handler( 
        array( &$this, "open" ), 
        array( &$this, "close" ),
        array( &$this, "read" ),
        array( &$this, "write"),
        array( &$this, "destroy"),
        array( &$this, "gc" )
      );

   }

     function open( $save_path, $session_name ) {

        global $sess_save_path;

        $sess_save_path = $save_path;

	$this->app_db =  mysql_current_db();

        return true;

     }

     function close() {
	mysql_select_db($this->app_db);
        return true;

     }

        function read( $id ) {
		mysql_select_db('mcp');

           // Set empty result
           $data = '';

           // Fetch session data from the selected database

           $time = time();

           $newid = mysql_real_escape_string($id);
           $sql = "SELECT `session_data` FROM `sessions` WHERE `session_id` = '$newid' AND `expires` > $time";

           $rs = mysql_query($sql);                           
           $a = mysql_num_rows($rs);

           if($a > 0) {
             $row = mysql_fetch_assoc($rs);
             $data = $row['session_data'];
           }
		mysql_select_db($this->app_db);
			return $data;
		}

      function write( $id, $data ) {
	mysql_select_db('mcp');
         // Build query                
         $time = time() + $this->life_time;
         $newid = mysql_real_escape_string($id);
         $newdata = mysql_real_escape_string($data);
		 $client_ip = $_SERVER['REMOTE_ADDR'];

         $sql = "REPLACE `sessions` (`session_id`,`session_data`,`expires`,`ip`) VALUES('$newid', '$newdata', $time, '$client_ip')";
         $rs = mysql_query($sql);
	mysql_select_db($this->app_db);
         return TRUE;
      }
      function destroy( $id ) {
	mysql_select_db('mcp');
         // Build query
         $newid = mysql_real_escape_string($id);
         $sql = "DELETE FROM `sessions` WHERE `session_id` = '$newid'";
         mysql_query($sql);
	mysql_select_db($this->app_db);
         return TRUE;
      }

      function gc() {
	mysql_select_db('mcp');
         // Garbage Collection       
         $sql = 'DELETE FROM `sessions` WHERE `expires` < UNIX_TIMESTAMP();';
         mysql_query($sql);
	mysql_select_db($this->app_db);
         // Always return TRUE
         return true;
      }
}
?>
