<?php

require_once( "../../php/util.php" );

if(!isset($HTTP_RAW_POST_DATA))
	$HTTP_RAW_POST_DATA = file_get_contents("php://input");
$ret = array();
if(isset($HTTP_RAW_POST_DATA))
{
	$vars = explode('&', $HTTP_RAW_POST_DATA);
	foreach($vars as $var)
	{
		$parts = explode("=",$var);
		if($parts[0]=="hash")
			$ret[] = $parts[1];
	}
	if(count($ret))
	{
		$fname = FileUtil::getTempDirectory().'rutorrent-prm-'.User::getUser().time();
		file_put_contents( $fname, serialize( $ret ) );
		shell_exec( Utility::getPHP()." -f ".escapeshellarg(dirname( __FILE__)."/batch_check.php")." ".escapeshellarg($fname)." ".escapeshellarg(User::getUser())." > /dev/null 2>&1 &" );
//		shell_exec( Utility::getPHP()." -f ".escapeshellarg(dirname( __FILE__)."/batch_check.php")." ".escapeshellarg($fname)." ".getUser()." > /tmp/1 2>/tmp/2 &" );
	}
}

CachedEcho::send('{}',"application/json");
