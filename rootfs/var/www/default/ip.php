<?php
function funct_get_ip( )
{
	$client  = @$_SERVER[ "HTTP_CLIENT_IP" ];
	$forward = @$_SERVER[ "HTTP_X_FORWARDED_FOR" ];
	$remote  = $_SERVER[ "REMOTE_ADDR" ];
	if ( filter_var( $client, FILTER_VALIDATE_IP ) )
		$ip = $client;
	elseif ( !empty( $forward ) )
		$ip = $forward;
	else
		$ip = $remote;
	return $ip;
}
$user_ip = funct_get_ip();
echo $user_ip;
$cookie_name = "IP";
$cookie_value = $user_ip;
if (!isset($_COOKIE["IP"]))
	setcookie($cookie_name, $cookie_value, null , "/", null, 0, 1);
?>
