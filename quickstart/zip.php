<?php
ini_set('display_errors',0);
ini_set('log_errors',1);
ini_set('log_errors_max_len',0);
ini_set('error_log','error_log.txt');

function create_zip($files = array(),$destination = '',$overwrite = true) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}


/* Populates files with project data, then creates a compressed zip file of the base classes, includes and file structure*/
if (isset($_GET['project'])){

//Pull JSON data from project file:
writeFiles($_GET['project']);
}

function writeFiles($project){
if (isset($project)){
//Pull JSON data from project file:

	$project = 'projects/'.$project;
	$project = file_get_contents($project);
	$json_project_settings=json_decode($project,true);

// Write to these files the project specific variables:
// To do: Pull vendor specific files depending on the template type:
// $json_project_settings['output_type']


//*********************************************************************************
// config_app.php:
//********************************************************************************
// 
$out=  <<<'EOD'
<?php
// Application Configuration File
// set application variables

$appConfig['app_title'] = 
EOD;
$out .= '"' . $json_project_settings['settings_application_name'] . '"; ';
$out .= <<<'EOD'


error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('log_errors_max_len',0);
ini_set('error_log','./error_log.txt');

// Discover which environment we are in:
	
if($_SERVER['HTTP_HOST'] == 'localhost'):
 	error_reporting(E_ALL);
	$appConfig["environment"] = "local_development";
EOD;
if ($json_project_settings['dev_use']==1){
$out .= <<<'EOD'


elseif ($_SERVER['HTTP_HOST'] == 
EOD;
$out .= '"' . $json_project_settings['dev_url'] . '"):
';
$out .= <<<'EOD'
 	error_reporting(E_ALL);
	$appConfig["environment"] = "development";
	
EOD;
}
$out .= <<<'EOD'

else:
	$appConfig["environment"] = "production";
	ini_set('display_errors', 'off');
	error_reporting(E_ERROR | E_WARNING  | E_PARSE);
endif;
EOD;

$file_name = "base/includes/config_app.php";
$fp = fopen($file_name,'w+'); 
fwrite($fp, $out); 
fclose($fp);


//*********************************************************************************
// config_mail.php:
//********************************************************************************
// 
$out = '
<?php
//*********************************************************************************
// Mail Configuration File
//*********************************************************************************
	$mailConfig["mail_server"] = "' . $json_project_settings['mail_server'] . '";
	$mailConfig["mail_mailer"] = "smtp";
	$mailConfig["mail_smtpauth"] = true;
	$mailConfig["mail_port"] = "' . $json_project_settings['mail_port'] . '";
	$mailConfig["mail_username"] = "' . $json_project_settings['mail_username'] . '";
	$mailConfig["mail_password"] = "' . $json_project_settings['mail_password'] . '";
	
	$mailConfig["mail_from"] = "' . $json_project_settings['mail_username'] . '";
	$mailConfig["mail_fromname"] = "' . $json_project_settings['mail_username'] . '";
	$mailConfig["mail_sender"] = "' . $json_project_settings['mail_username'] . '";
	$mailConfig["mail_admin"] = "' . $json_project_settings['settings_email'] . '";

?>';

$file_name = "base/includes/config_mail.php";
$fp = fopen($file_name,'w+'); 
fwrite($fp, $out); 
fclose($fp);


//*********************************************************************************
// config_db.php:
//********************************************************************************


$out=  <<<'EOD'
<?php
// Database Configuration File

	require('config_app.php');
	
	if ($appConfig["environment"] == 'development'){
		$dbConfig['dbhost'] = 
EOD;

$out .= '"' . $json_project_settings['dev_dbhost'] . '";';

$out .= <<<'EOD'
		
		$dbConfig['dbuser'] = 
EOD;
$out .= '"' . $json_project_settings['dev_dbuser'] . '";';
$out .= <<<'EOD'

		$dbConfig['dbpass'] = 
EOD;

$out .= '"' . $json_project_settings['dev_dbpass'] . '";';

$out .= <<<'EOD'
		
		$dbConfig['dbname'] = 
EOD;
$out .= '"' . $json_project_settings['dev_dbname'] . '";';

$out .= <<<'EOD'
		
	}elseif ($appConfig["environment"] == 'local_development'){
		$dbConfig['dbhost'] = 
EOD;

$out .= '"' . $json_project_settings['local_dbhost'] . '";';

$out .= <<<'EOD'
		
		$dbConfig['dbuser'] = 
EOD;
$out .= '"' . $json_project_settings['local_dbuser'] . '";';
$out .= <<<'EOD'

		$dbConfig['dbpass'] = 
EOD;

$out .= '"' . $json_project_settings['local_dbpass'] . '";';

$out .= <<<'EOD'
		
		$dbConfig['dbname'] = 
EOD;
$out .= '"' . $json_project_settings['local_dbname'] . '";';

$out .= <<<'EOD'

	}else{
		// Production		
		$dbConfig['dbhost'] = 
EOD;

$out .= '"' . $json_project_settings['pro_dbhost'] . '";';

$out .= <<<'EOD'
		
		$dbConfig['dbuser'] = 
EOD;
$out .= '"' . $json_project_settings['pro_dbuser'] . '";';
$out .= <<<'EOD'

		$dbConfig['dbpass'] = 
EOD;

$out .= '"' . $json_project_settings['pro_dbpass'] . '";';

$out .= <<<'EOD'
		
		$dbConfig['dbname'] = 
EOD;
$out .= '"' . $json_project_settings['pro_dbname'] . '";';

$out .= <<<'EOD'

	}
EOD;
$file_name = "base/includes/config_db.php";
$fp = fopen($file_name,'w+'); 
fwrite($fp, $out); 
fclose($fp);


//*********************************************************************************
// init.php:
//********************************************************************************


$out=  <<<'EOD'
<?php
	ob_start();
	session_start();
	date_default_timezone_set(
EOD;

$out .= '"' . $json_project_settings['settings_timezone'] . '");';

$out .= <<<'EOD'

// Sets the paths for includes for these folders. To access the URL itself, use $json_project_settings array
	define("BASE",$_SERVER['DOCUMENT_ROOT']
EOD;

if (isset($json_project_settings['base_url'])){
	$out .= ' . "/' . $json_project_settings['base_url'] . '");';
} else {
	$out .= ' $_SERVER[\'DOCUMENT_ROOT\'];';
}

$out .= <<<'EOD'

	define("INCLUDES", BASE
EOD;

$out .= ' . "/' . $json_project_settings['includes_url'] . '/");';

$out .= <<<'EOD'

	define("CLASSES", BASE
EOD;

$out .= ' . "/' . $json_project_settings['class_url'] . '/");';

$out .= <<<'EOD'

	define("CSS", BASE
EOD;

$out .= ' . "/' . $json_project_settings['css_url'] . '/");';

$out .= <<<'EOD'

	define("SCRIPTS", BASE
EOD;

$out .= ' . "/' . $json_project_settings['scripts_url'] . '/");';

$out .= <<<'EOD'

	define("ACTIONS", BASE
EOD;

$out .= ' . "/' . $json_project_settings['actions_url'] . '/");

';

if (isset($json_project_settings['base_url'])){	
	$out .= 'define("BASE_URL", "/' .$json_project_settings['base_url'] .'");
	';
} else {
	$out .= 'define("BASE_URL", "/");
	';
}
	$out .= 'define("INCLUDES_URL", BASE_URL . "/' .$json_project_settings['includes_url'] .'");
	';
	$out .= 'define("CLASSES_URL", BASE_URL . "/' .$json_project_settings['class_url'] .'/");
	';
	$out .= 'define("ACTIONS_URL", BASE_URL . "/' .$json_project_settings['actions_url'] .'/");
	';

$out .= <<<'EOD'

	require_once(INCLUDES . '/config_app.php');
	require_once(CLASSES . '/class_session_manager.php');
	require_once(CLASSES . '/class_functions.php');
	require_once(CLASSES . '/class_data_manager.php');
	include_once(CLASSES . '/class_drop_downs.php');	

	define("HEAD", INCLUDES . "head.php");	
	define("INCLUDES_LIST", INCLUDES . "includes.php");
		
	// Get settings:
	//Show if you have a settings table and want to write the settings as constants:
	$dm = new DataManager(); 
	$strSQL = "SELECT * FROM settings";						

	$result = $dm->queryRecords($strSQL);	
	if($result):
		while($row = mysqli_fetch_assoc($result)):
			$const_name = strtoupper(str_replace(" ","_",$row['settings_name']));
			define($const_name,$row['settings_value']);
		endwhile;
	endif;
		
	$session = new SessionManager();
		
	$alert_msg = $session->getAlertMessage();
	$alert_color = $session->getAlertColor();
	
	$admin_email = 
EOD;

$out .= '"' . $json_project_settings['settings_email'] . '";';
$out .= '
// ****************************************** USER NOT LOGGED IN **********************************	
if($session->get_user_id() == "" && $public != true):

// ************************************** LIST OF PUBLIC ACCESS PAGES *****************************
	// Add any public access pages to the array:
	$publicPageArray = array(
	BASE_URL . "/index.php",
	BASE_URL . "/actions/action_login_user.php",
	BASE_URL . "/forgot_password.php",
	BASE_URL . "/actions/action_forgot_password_admin.php"
	);
	
// Additional public pages may be added to the list, or simply set the $public variable to true in your file before calling init.php	
	';

$out .= <<<'EOD'
	
	if (!in_array($_SERVER['PHP_SELF'], $publicPageArray)):
	
		$current_adr = str_replace("?","*",$_SERVER["REQUEST_URI"]);
		$current_adr = str_replace("&","~",$current_adr);
		
// ****************************************** SET TO YOUR LOGIN PAGE **********************************		
		header("location: " . BASE_URL . "/index.php?redirect=".$current_adr );
		exit;
	endif;
endif;
EOD;

$file_name = "base/includes/init.php";
$fp = fopen($file_name,'w+'); 
fwrite($fp, $out); 
fclose($fp);	


//*********************************************************************************
// head.php:
//********************************************************************************
// 
$head_template = file_get_contents("templates/".$json_project_settings['output_type']."/head.tpl");
$out=$head_template;

$file_name = "base/includes/head.php";
$fp = fopen($file_name,'w+'); 
fwrite($fp, $out); 
fclose($fp);

//*********************************************************************************// setup.sql://********************************************************************************
// Sets up users, log tables
$out=  <<<'EOD'

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_first_name` varchar(50) NOT NULL DEFAULT '',
  `user_last_name` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_tel` varchar(20) NOT NULL DEFAULT '0',
  `user_password` varchar(100) NOT NULL DEFAULT '',
  `user_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_role` smallint(11) NOT NULL DEFAULT '0',
  `user_date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_last_updated_user` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `user` (`user_id`, `user_first_name`, `user_last_name`, `user_email`, `user_tel`, `user_password`, `user_login`, `user_role`, `user_date_created`, `user_last_updated`, `user_last_updated_user`) VALUES
(1, 'Quickstart', 'User',
EOD;

$out .= "'" . $json_project_settings['settings_email'] . "',";
$out .=  <<<'EOD'
'0', '3eadac27e1ccff186ad15f836bab3c1dcac6405ecc7dd347258fe0d17b615c64', '2015-05-09 19:13:12', 1, '0000-00-00 00:00:00', '2014-12-14 22:27:54', '0');


CREATE TABLE IF NOT EXISTS `log` (
  `log_id` int(8) NOT NULL AUTO_INCREMENT,
  `log_user` int(3) NOT NULL,
  `log_val` longtext NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;


CREATE TABLE IF NOT EXISTS `aclpagerecords` (
  `aclPageRecords_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `aclPageRecords_user_role` int(10) NOT NULL,
  `aclPageRecords_page_id` varchar(200) NOT NULL,
  `aclPageRecords_page_view` int(3) NOT NULL DEFAULT '0',
  `aclPageRecords_page_edit` int(3) NOT NULL DEFAULT '0',
  `aclPageRecords_page_delete` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aclPageRecords_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `settings` (
  `settings_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `settings_name` varchar(200) NOT NULL,
  `settings_value` text NOT NULL,
  `settings_session_load` int(1) NOT NULL,
  `settings_date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `settings_last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `settings_last_updated_user` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

INSERT INTO `settings` (`settings_id`, `settings_name`, `settings_value`, `settings_session_load`, `settings_date_created`, `settings_last_updated`, `settings_last_updated_user`) VALUES
(1, 'logo', 'website-logo.png', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'),
(2, 'default_tax_rate', '5', 1, '2014-12-30 15:33:20', '2015-02-26 18:46:33', ''),
(3, 'mail_server', 'chocobo.asmallorange.com', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0'),
(4, 'mail_account_type', 'POP3', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0'),
(5, 'mail_username', 'website@myproject.ca', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0'),
(6, 'mail_password', '', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0');
(7, 'alt-logo', 'website-logo-footer.png', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0');

CREATE TABLE IF NOT EXISTS `userrole` (
`userrole_id` bigint(20) unsigned NOT NULL,
  `userrole_title` varchar(200) NOT NULL,
  `userrole_group` varchar(200) NOT NULL,
  `is_active` varchar(1) NOT NULL DEFAULT 'Y',
  `userrole_date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userrole_last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userrole_last_updated_user` varchar(200) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `userrole` (`userrole_id`, `userrole_title`, `userrole_group`, `is_active`, `userrole_date_created`, `userrole_last_updated`, `userrole_last_updated_user`) VALUES
(1, 'Superuser', 'admin', 'Y', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'),
(2, 'Admin', 'admin', 'Y', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0'),
(3, 'User', 'user', 'Y', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0');

ALTER TABLE `userrole`
 ADD PRIMARY KEY (`userrole_id`);

ALTER TABLE `userrole`
MODIFY `userrole_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

EOD;

$file_name = "base/setup.sql";
$fp = fopen($file_name,'w+'); 
fwrite($fp, $out); 
fclose($fp);	
}
// End function:
}

// Make this dynamic at some point (pull everything in the "Base" folder):

$files_to_zip = array(
	'base/actions/action_add_acl.php',
	'base/actions/action_forgot_password_admin.php',
	'base/actions/action_login_user.php',    	
	'base/actions/action_logout.php',
	'base/actions/action_user_edit.php',
	'base/actions/index.html',	    	
	'base/ajax/index.html',
	'base/classes/class_data_manager.php',
	'base/classes/class_drop_downs.php',	
	'base/classes/class_error_handler.php',
	'base/classes/class_functions.php',
	'base/classes/class_phpmailer.php',
	'base/classes/class_record_pager.php',
	'base/classes/class_session_manager.php',
	'base/classes/class_user.php',
	'base/css/font-awesome.css',	
	'base/css/styles.css',
	'base/css/print.css',
	'base/css/styles.css',	
	'base/fonts/FontAwesome.otf',
	'base/fonts/fontawesome-webfont.eot',
	'base/fonts/fontawesome-webfont.svg',
	'base/fonts/fontawesome-webfont.ttf',
	'base/fonts/fontawesome-webfont.woff',
	'base/includes/acl_module.php',									
	'base/includes/config_app.php',
	'base/includes/config_db.php',
	'base/includes/config_mail.php',
	'base/includes/config_secure.php',	
	'base/includes/init.php',
	'base/includes/system_messaging.php',
	'base/includes/navbar.php',	
	'base/includes/head.php',
	'base/includes/includes.php',
	'base/includes/footer.php',		
	'base/images/asc.gif',	
	'base/images/carets.gif',	
	'base/images/darken.gif',	
	'base/images/desc.gif',	
	'base/images/document.png',	
	'base/images/lighten.png',	
	'base/images/view.png',	
	'base/page_templates/index.html',
	'base/setup.sql',
	'base/error.php',		
	'base/index.php',
	'base/dashboard.php',
	'base/debug_log.php',		
	'base/forgot_password.php'	
);
//if true, good; if false, zip creation failed
$result = create_zip($files_to_zip,'quickstart_base.zip');

$file = 'quickstart_base.zip';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}
?>