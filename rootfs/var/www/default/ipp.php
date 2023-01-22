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
function funct_get_page_url()
{
	$page_url = "http";
	if ( @$_SERVER[ "HTTPS" ] == "on" )
		$page_url .= "s";
	elseif ( @$_SERVER[ "HTTP_X_FORWARDED_PROTO" ] == "https" )
		$page_url .= "s";
	$page_url .= "://";
	if ( @$_SERVER[ "SERVER_PORT" ] != "80" )
		$page_url .= $_SERVER[ "SERVER_NAME" ] . ":" . $_SERVER[ "SERVER_PORT" ] . $_SERVER[ "REQUEST_URI" ];
	else
		$page_url .= $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "REQUEST_URI" ];
	return $page_url;
}

// Set Variables
$user_ip       = funct_get_ip();
$user_host     = gethostbyaddr($user_ip);
$user_port     = $_SERVER["REMOTE_PORT"];
$page_url_hex  = funct_get_page_url();
$page_url      = rawurldecode( $page_url_hex );
//$url           = parse_url( $page_url_hex );
$url          = parse_url( $page_url );
$scheme_url    = $url[ "scheme" ] ??= "";
$host_url      = $url[ "host" ] ??= "";
$path_url      = $url[ "path" ] ??= "";
@$query_url    = $url[ "query" ] ??= "";
@$fragment_url = $url[ "fragment" ] ??= "";

// Set Headers
header('Content-Type: application/json');
header( "Scheme: $scheme_url" );
header( "Host: $host_url" );
header( "Path: $path_url" );
if ( !empty( $query_url ) ) {
	header( "Query: $query_url" );
}
if ( !empty( $$fragment_url ) ) {
	header( "Fragment: $fragment_url" );
}

class API implements JsonSerializable {
	public $IP;
	public $Host;
	public $Port;
	public $Server;

	function __construct() {
		$this->IP 	= funct_get_ip();
		$this->Host 	= gethostbyaddr(funct_get_ip());
		$this->Port	= $_SERVER["REMOTE_PORT"];
//		$this->Server	= parse_url(funct_get_page_url());
		$this->Server	= parse_url(rawurldecode(funct_get_page_url()));
	}

	public function jsonSerialize() {
		return (array)$this;
	}
}

$api = new API();
echo json_encode($api, JSON_PRETTY_PRINT) . "\n";
$cookie_name = "IP";
$cookie_value = $user_ip;
if (!isset($_COOKIE["IP"]))
	setcookie($cookie_name, $cookie_value, null , "/", null, 0, 1);
?>
