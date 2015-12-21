<?php

//Functions

function consoleLog($val){
	// Don't show anything if IE - Stupid IE breaks
	if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE",0) == 0){
    $numargs = func_num_args();
    
	if ($numargs >= 2) {
		$val = "";
	    $arg_list = func_get_args();
    	for ($i = 0; $i < $numargs; $i++) {
        	$val .= $arg_list[$i] . " / ";
    	}
	}
		print "<script>console.log('" . mysql_real_escape_string($val) . "')</script>";
	}
}

function addToLog($val, $notify=false){
	//require_once('../includes/init.php');
	$dm = new DataManager();
	global $session;
//	if (isset(
	$user_id = $session->get_user_id();
	//$user_id =1;
	// What kind of $val is this? string, array, or object:
	ob_start();
	if (is_object($val)){
		$val = var_dump($val);
	} elseif(is_array($val)){
		$val = var_dump($val);			
	} else {
		// Just a string
		echo mysqli_real_escape_string($dm->connection, $val);
	}
	$result = ob_get_contents();
	ob_end_clean();

	$strSQL = "INSERT INTO log (log_user, log_val) VALUES ('" . $user_id . "', '" . $result . "')";				
	$result = $dm->updateRecords($strSQL);
	
	//If notify var is true, send an email to the techical contact:
	if ($notify && $appConfig["environment"] != "local_development"):
		require(INCLUDES . 'config_mail.php');
		require_once(CLASSES . 'class_phpmailer.php');
		global $admin_email;
		global $appConfig;
		
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		$mail->From = $mailConfig["mail_from"];
		$mail->FromName = $mailConfig["mail_fromname"];
		$mail->Sender = $mailConfig["mail_sender"];
		$mail->AddAddress($admin_email,"");		
		$mail->Subject = "System error - ".$appConfig['app_title'];
			
		$body = '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><body><p>An unexpected error occured.</p>';
		$body .= $val;
		$body .= "<p><br />The " . $appConfig['app_title'] ." Team</p></body></html>";
		$mail->Body = $body;
		
		$mail->Send();
		$mail = null;
	endif;
	
}

function escaped_var_from_post($varname){
	// Return a single cleaned post or get variable/value pair
	$dm = new DataManager();
	if (isset($_REQUEST[$varname])){
		$$varname = mysqli_real_escape_string($dm->connection, $_REQUEST[$varname]);
	}else{
		$$varname = "";
	}
		return $$varname;
}

function extract_and_escape($array){
//$array should be $_POST or $_GET
// Use to get and clean all POST or GET vars:
	$dm = new DataManager();
	
	foreach ($array as $key => $val){
		global $$key;
		$$key = mysqli_real_escape_string($dm->connection, $val);
	}
}

/* backup the db OR just a table */
function backup_tables($tables = '*')
{
	require_once('class_data_manager.php');
	$dm = new DataManager();
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = $dm->queryRecords('SHOW TABLES');
		while($row = mysqli_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = $dm->queryRecords('SELECT * FROM '.$table);
		$num_fields = mysqli_num_fields($result);
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysqli_fetch_row($dm->queryRecords('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysqli_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					//$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	//save file
	$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
	fwrite($handle,$return);
	fclose($handle);
}
